<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN AUTOMATION SETTINGS API
 * Handles fetching settings, saving schedule, and triggering test runs.
 * ═══════════════════════════════════════════════════════════════
 */
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../cron/AutomationManager.php';

// Auth check
if (!isset($_SESSION["admin_logged_in"]) && !isset($_SESSION["superadmin_logged_in"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $settings = AutomationManager::getSettings();
        echo json_encode([
            'success' => true,
            'settings' => $settings,
            'cron_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))) . '/cron/send_automated_digest.php?cron_token=jh_secure_cron_token_2026'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true) ?? $_POST;
    $action = $data['action'] ?? 'save';

    if ($action === 'save') {
        try {
            $saved = AutomationManager::saveSettings($data);
            if ($saved) {
                echo json_encode(['success' => true, 'message' => 'Automation settings saved successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to save settings to database.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    if ($action === 'send_now') {
        try {
            // First save any modified settings if passed
            if (!empty($data['recipient_emails'])) {
                AutomationManager::saveSettings($data);
            }

            // Trigger the digest runner directly
            ob_start();
            $_GET['force'] = '1';
            require __DIR__ . '/../../cron/send_automated_digest.php';
            $output = ob_get_clean();

            $decoded = json_decode($output, true);
            if (isset($decoded['error'])) {
                http_response_code(500);
                echo json_encode(['error' => $decoded['error']]);
            } else {
                echo $output;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    http_response_code(400);
    echo json_encode(['error' => 'Invalid action.']);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed.']);
?>
