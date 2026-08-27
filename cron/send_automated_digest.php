<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * AUTOMATED DIGEST & DATABASE BACKUP RUNNER
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 */

// Enable error reporting to log but not pollute output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../email/config.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';
require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/AutomationManager.php';
require_once __DIR__ . '/ReportPDFGenerator.php';
require_once __DIR__ . '/DatabaseBackup.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ─── Security Check ───
$isCLI = (php_sapi_name() === 'cli');
$providedToken = $_GET['cron_token'] ?? $_POST['cron_token'] ?? '';
$definedToken = defined('CRON_SECRET_TOKEN') ? CRON_SECRET_TOKEN : 'jh_secure_cron_token_2026';

// Check if user is logged in as admin
session_start();
$isAdminLoggedIn = (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) ||
                   (isset($_SESSION['superadmin_logged_in']) && $_SESSION['superadmin_logged_in'] === true);

$isAuthorized = $isCLI || $isAdminLoggedIn || ($providedToken === $definedToken);

if (!$isAuthorized) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Forbidden: Invalid or missing cron_token.']);
    exit();
}

$isForce = isset($_GET['force']) || isset($_POST['force']) || (isset($argv) && in_array('--force', $argv));

try {
    $settings = AutomationManager::getSettings();
    $dueCheck = AutomationManager::isDueToRun($settings, $isForce);

    if (!$dueCheck['due']) {
        $result = [
            'status' => 'skipped',
            'message' => $dueCheck['reason'],
            'timestamp' => date('Y-m-d H:i:s'),
            'settings' => [
                'frequency' => $settings['frequency'],
                'weekday' => $settings['weekday'],
                'month_day' => $settings['month_day'],
                'last_dispatched_at' => $settings['last_dispatched_at']
            ]
        ];
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
        exit();
    }

    // Determine Date Range
    $frequency = $settings['frequency'];
    $today = date('Y-m-d');

    if ($frequency === 'daily') {
        $dateFrom = date('Y-m-d', strtotime('-1 day'));
        $dateTo = $today;
        $periodTitle = "Daily Feedback Digest (" . date('M d, Y', strtotime($dateFrom)) . ")";
    } elseif ($frequency === 'monthly') {
        $dateFrom = date('Y-m-d', strtotime('-30 days'));
        $dateTo = $today;
        $periodTitle = "Monthly Feedback Intelligence Report (" . date('M d, Y', strtotime($dateFrom)) . " - " . date('M d, Y', strtotime($dateTo)) . ")";
    } else {
        // Default weekly (7 days)
        $dateFrom = date('Y-m-d', strtotime('-6 days'));
        $dateTo = $today;
        $periodTitle = "Weekly Feedback Intelligence Report (" . date('M d, Y', strtotime($dateFrom)) . " - " . date('M d, Y', strtotime($dateTo)) . ")";
    }

    $tempFiles = [];
    $pdfAttachmentPath = null;
    $sqlAttachmentPath = null;
    $pdfSummary = null;
    $backupSummary = null;

    // 1. Generate PDF Report if enabled
    if ($settings['include_pdf']) {
        $pdfResult = ReportPDFGenerator::generateReport($dateFrom, $dateTo);
        $pdfAttachmentPath = $pdfResult['filepath'];
        $tempFiles[] = $pdfAttachmentPath;
        $pdfSummary = $pdfResult;
    }

    // 2. Generate Database Backup if enabled
    if ($settings['include_backup']) {
        $backupResult = DatabaseBackup::createBackup();
        $sqlAttachmentPath = $backupResult['filepath'];
        $tempFiles[] = $sqlAttachmentPath;
        $backupSummary = $backupResult;
    }

    // 3. Build Rich HTML Email
    $htmlBody = buildExecutiveHtmlDigest($periodTitle, $dateFrom, $dateTo, $pdfSummary, $backupSummary);

    // 4. Dispatch via PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = (SMTP_PORT == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(SMTP_USERNAME, SMTP_FROM_NAME);

    // Parse recipient emails
    $recipientList = explode(',', $settings['recipient_emails']);
    $validRecipientCount = 0;
    foreach ($recipientList as $rawEmail) {
        $cleanEmail = trim($rawEmail);
        if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->addAddress($cleanEmail);
            $validRecipientCount++;
        }
    }

    if ($validRecipientCount === 0) {
        throw new Exception("No valid recipient email address configured in automation settings.");
    }

    $mail->isHTML(true);
    $mail->Subject = "[John Hay Hotels] {$periodTitle} & System Backup";
    $mail->Body = $htmlBody;
    $mail->AltBody = "John Hay Hotels - Forest Wing Executive Feedback Report. Period: {$dateFrom} to {$dateTo}. Please view the attached PDF report and Database backup.";

    // Attach PDF
    if ($pdfAttachmentPath && file_exists($pdfAttachmentPath)) {
        $mail->addAttachment($pdfAttachmentPath, basename($pdfAttachmentPath));
    }

    // Attach SQL
    if ($sqlAttachmentPath && file_exists($sqlAttachmentPath)) {
        $mail->addAttachment($sqlAttachmentPath, basename($sqlAttachmentPath));
    }

    $mail->send();

    // Clean up temporary files
    foreach ($tempFiles as $tFile) {
        if (file_exists($tFile)) {
            @unlink($tFile);
        }
    }

    // Update settings table
    $statusMsg = "Dispatched successfully to {$validRecipientCount} recipient(s) on " . date('M d, Y h:i A');
    AutomationManager::recordDispatchResult($statusMsg);

    $response = [
        'status' => 'success',
        'message' => $statusMsg,
        'period_from' => $dateFrom,
        'period_to' => $dateTo,
        'recipients' => array_map('trim', $recipientList),
        'pdf_attached' => ($pdfAttachmentPath !== null),
        'backup_attached' => ($sqlAttachmentPath !== null),
        'timestamp' => date('Y-m-d H:i:s')
    ];

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $errorMsg = "Dispatch failed: " . $e->getMessage();
    AutomationManager::recordDispatchResult($errorMsg);
    
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $errorMsg]);
}

/**
 * Builds the responsive, executive HTML email digest
 */
function buildExecutiveHtmlDigest(string $title, string $dateFrom, string $dateTo, ?array $pdfSummary, ?array $backupSummary): string
{
    $formattedFrom = date('M d, Y', strtotime($dateFrom));
    $formattedTo = date('M d, Y', strtotime($dateTo));
    $submissions = $pdfSummary['submissions'] ?? '—';
    $avgScore = $pdfSummary['avg_score'] ?? '—';
    $backupSize = $backupSummary['filesize_formatted'] ?? 'Included';

    return '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($title) . '</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #FCFBFA; font-family: \'Segoe UI\', Helvetica, Arial, sans-serif; color: #142B21;">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FCFBFA; padding: 30px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="width: 100%; max-width: 600px; background-color: #ffffff; border-radius: 12px; border: 1px solid #EAE6DB; overflow: hidden; box-shadow: 0 10px 30px rgba(20,43,33,0.06);">
                        
                        <!-- Top Pine Header -->
                        <tr>
                            <td style="background-color: #142B21; padding: 32px 36px; border-bottom: 3px solid #C9A96E;">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; letter-spacing: 3px; color: #C9A96E; text-transform: uppercase;">
                                                JOHN HAY HOTELS • FOREST WING
                                            </p>
                                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #FFFFFF; letter-spacing: 0.5px;">
                                                Executive Feedback Digest
                                            </h1>
                                            <p style="margin: 6px 0 0 0; font-size: 12px; color: #D5E2DA;">
                                                Period: ' . $formattedFrom . ' – ' . $formattedTo . '
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Body Content -->
                        <tr>
                            <td style="padding: 36px 36px 24px 36px;">
                                <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #4A5568;">
                                    Hello Management Team,<br><br>
                                    Here is your automated guest feedback report and database snapshot for <strong>' . $formattedFrom . ' to ' . $formattedTo . '</strong>.
                                </p>

                                <!-- KPI Metric Cards -->
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 20px 0 30px 0;">
                                    <tr>
                                        <td width="48%" style="background-color: #FAF8F5; border: 1px solid #EAE6DB; border-radius: 8px; padding: 18px; text-align: center;">
                                            <span style="display: block; font-size: 28px; font-weight: 700; color: #142B21;">' . $submissions . '</span>
                                            <span style="font-size: 10px; font-weight: 700; color: #8C827A; letter-spacing: 1.5px; text-transform: uppercase;">Submissions</span>
                                        </td>
                                        <td width="4%"></td>
                                        <td width="48%" style="background-color: #FAF8F5; border: 1px solid #EAE6DB; border-radius: 8px; padding: 18px; text-align: center;">
                                            <span style="display: block; font-size: 28px; font-weight: 700; color: #B5893A;">' . $avgScore . ' <span style="font-size: 14px; color: #8C827A;">/ 5.0</span></span>
                                            <span style="font-size: 10px; font-weight: 700; color: #8C827A; letter-spacing: 1.5px; text-transform: uppercase;">Avg. Guest Satisfaction</span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Attached Files Callout -->
                                <div style="background-color: #F4F7F5; border-left: 4px solid #142B21; border-radius: 4px; padding: 16px 20px; margin-bottom: 25px;">
                                    <h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #142B21; text-transform: uppercase; letter-spacing: 1px;">
                                        📦 Attached in this Dispatch:
                                    </h4>
                                    <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: #2D3748; line-height: 1.8;">
                                        <li><strong>Executive Report (PDF):</strong> Comprehensive departmental ratings, recognized staff, and guest remarks breakdown.</li>
                                        <li><strong>Full Database Backup (.sql):</strong> Complete, importable snapshot of all guest profiles, stays, and feedback records (' . $backupSize . ').</li>
                                    </ul>
                                </div>

                                <p style="font-size: 12px; color: #718096; line-height: 1.5; margin: 0;">
                                    You can adjust the schedule, recipient emails, or trigger immediate reports anytime inside your <strong>Admin Panel &rarr; Reports &rarr; Automation Settings</strong>.
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #FAF8F5; padding: 20px 36px; border-top: 1px solid #EAE6DB; text-align: center;">
                                <p style="margin: 0; font-size: 11px; color: #8C827A; letter-spacing: 0.5px;">
                                    John Hay Hotels - Forest Wing &bull; Automated Intelligence System<br>
                                    Confidential Management Report &bull; ' . date('Y') . '
                                </p>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    ';
}
?>
