<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * FEEDBACK SUBMISSION SCRIPT
 * Processes POST requests from the guest feedback form.
 * Captures ratings, comments, and guest details, then inserts
 * them into the normalized database tables.
 * Sends an automated "Thank You" email via PHPMailer on success.
 * ═══════════════════════════════════════════════════════════════
 */
require_once "config.php";

// ── PHPMailer ────────────────────────────────────────────────
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';
require_once __DIR__ . '/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ═════════════════════════════════════════════════════════════
// GMAIL SMTP CREDENTIALS
// ═════════════════════════════════════════════════════════════
$gmailUsername    = 'noreply.johnhayhotelsforestwing@gmail.com';
$gmailAppPassword = 'ammq zyno kyts cfbf';

function sendThankYouEmail(string $guestName, string $guestEmail, string $smtpUser, string $smtpPass): bool
{
    $displayName = !empty($guestName) ? htmlspecialchars($guestName) : 'Valued Guest';

    $htmlBody = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thank You - John Hay Hotels</title>
    </head>
    <body style="margin:0; padding:0; background-color:#0A1912; font-family: Georgia, serif;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0A1912; padding:40px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#142B21; border-radius:16px; overflow:hidden; border:1px solid rgba(201,169,110,0.2);">
                        <tr><td style="height:4px; background:linear-gradient(90deg, #b5893a, #C9A96E);"></td></tr>
                        <tr>
                            <td align="center" style="padding:40px;">
                                <h1 style="margin:0; font-size:32px; color:rgba(255,255,255,0.85); font-style:italic;">John Hay Hotels</h1>
                                <span style="font-size:10px; font-weight:600; letter-spacing:4px; text-transform:uppercase; color:#C9A96E;">Forest Wing</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding:0 40px;">
                                <h2 style="margin:0; font-size:26px; color:rgba(255,255,255,0.9);">Thank You, ' . $displayName . '!</h2>
                                <p style="margin:20px 0; font-size:16px; line-height:1.7; color:rgba(255,255,255,0.65); font-style:italic;">Your feedback is invaluable to us.</p>
                                <p style="margin:0 0 40px 0; font-size:14px; font-weight:600; color:#C9A96E;">Warm Regards,<br>Forest Wing Team</p>
                            </td>
                        </tr>
                        <tr><td style="height:4px; background:linear-gradient(90deg, #b5893a, #C9A96E);"></td></tr>
                    </table>
                    <table role="presentation" width="600" style="margin-top:20px;">
                        <tr>
                            <td align="center">
                                <p style="margin:0; font-size:11px; color:rgba(255,255,255,0.25);">Camp John Hay, Baguio City, Philippines, 2600</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom($smtpUser, 'John Hay Hotels - Forest Wing');
        $mail->addAddress($guestEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Your Feedback';
        $mail->Body = $htmlBody;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: index.php"); exit(); }
$pdo = getDBConnection();

$data = [
    "frontdesk" => intval($_POST["frontdesk"] ?? 0),
    "reservations" => intval($_POST["reservations"] ?? 0),
    "telephone_operator" => intval($_POST["telephone_operator"] ?? 0),
    "valet" => intval($_POST["valet"] ?? 0),
    "housekeeping" => intval($_POST["housekeeping"] ?? 0),
    "accommodation" => intval($_POST["accommodation"] ?? 0),
    "safety" => intval($_POST["safety"] ?? 0),
    "security" => intval($_POST["security"] ?? 0),
    "overall_service" => intval($_POST["overall_service"] ?? 0),
    "frontdesk_comments" => htmlspecialchars(trim($_POST["frontdesk_comments"] ?? "")),
    "food_quality" => intval($_POST["food_quality"] ?? 0),
    "serving_time" => intval($_POST["serving_time"] ?? 0),
    "wait_staff" => intval($_POST["wait_staff"] ?? 0),
    "grooming" => intval($_POST["grooming"] ?? 0),
    "behavior" => intval($_POST["behavior"] ?? 0),
    "fnb_service" => intval($_POST["fnb_service"] ?? 0),
    "bar" => intval($_POST["bar"] ?? 0),
    "bartender" => intval($_POST["bartender"] ?? 0),
    "fnb_comments" => htmlspecialchars(trim($_POST["fnb_comments"] ?? "")),
    "helpful_staff_names" => htmlspecialchars(trim($_POST["helpful_staff_names"] ?? "")),
    "overall_rating" => intval($_POST["overall_rating"] ?? 0),
    "suggestions_future" => htmlspecialchars(trim($_POST["suggestions_future"] ?? "")),
    "other_comments" => htmlspecialchars(trim($_POST["other_comments"] ?? "")),
    "first_stay" => htmlspecialchars(trim($_POST["first_stay"] ?? "")),
    "purpose_of_stay" => htmlspecialchars(trim($_POST["purpose_of_stay"] ?? "")),
    "other_purpose_text" => htmlspecialchars(trim($_POST["other_purpose_text"] ?? "")),
    "nationality" => htmlspecialchars(trim($_POST["nationality"] ?? "")),
    "other_nationality_text" => htmlspecialchars(trim($_POST["other_nationality_text"] ?? "")),
    "guest_name" => htmlspecialchars(trim($_POST["guest_name"] ?? "")),
    "email" => htmlspecialchars(trim($_POST["email"] ?? "")),
    "address" => htmlspecialchars(trim($_POST["address"] ?? "")),
    "contact_no" => htmlspecialchars(trim($_POST["contact_no"] ?? "")),
    "room_no" => htmlspecialchars(trim($_POST["room_no"] ?? "")),
    "check_in" => !empty($_POST["check_in"]) ? $_POST["check_in"] : null,
    "check_out" => !empty($_POST["check_out"]) ? $_POST["check_out"] : null,
];

try {
    $pdo->beginTransaction();
    $stmtGuest = $pdo->prepare("INSERT INTO guests (guest_name, email, address, contact_no, nationality, other_nationality_text) VALUES (:guest_name, :email, :address, :contact_no, :nationality, :other_nationality_text)");
    $stmtGuest->execute([":guest_name"=>$data["guest_name"], ":email"=>$data["email"], ":address"=>$data["address"], ":contact_no"=>$data["contact_no"], ":nationality"=>$data["nationality"], ":other_nationality_text"=>$data["other_nationality_text"]]);
    $guest_id = $pdo->lastInsertId();

    $stmtStay = $pdo->prepare("INSERT INTO stays (guest_id, room_no, check_in, check_out, first_stay, purpose_of_stay, other_purpose_text) VALUES (:guest_id, :room_no, :check_in, :check_out, :first_stay, :purpose_of_stay, :other_purpose_text)");
    $stmtStay->execute([":guest_id"=>$guest_id, ":room_no"=>$data["room_no"], ":check_in"=>$data["check_in"], ":check_out"=>$data["check_out"], ":first_stay"=>$data["first_stay"], ":purpose_of_stay"=>$data["purpose_of_stay"], ":other_purpose_text"=>$data["other_purpose_text"]]);
    $stay_id = $pdo->lastInsertId();

    $stmtFeedback = $pdo->prepare("INSERT INTO feedbacks (stay_id, overall_rating, suggestions_future, other_comments) VALUES (:stay_id, :overall_rating, :suggestions_future, :other_comments)");
    $stmtFeedback->execute([":stay_id"=>$stay_id, ":overall_rating"=>$data["overall_rating"], ":suggestions_future"=>$data["suggestions_future"], ":other_comments"=>$data["other_comments"]]);
    $feedback_id = $pdo->lastInsertId();

    $stmtFOH = $pdo->prepare("INSERT INTO feedback_foh (feedback_id, frontdesk, reservations, telephone_operator, valet, housekeeping, accommodation, safety, security, overall_service, frontdesk_comments) VALUES (:feedback_id, :frontdesk, :reservations, :telephone_operator, :valet, :housekeeping, :accommodation, :safety, :security, :overall_service, :frontdesk_comments)");
    $stmtFOH->execute([":feedback_id"=>$feedback_id, ":frontdesk"=>$data["frontdesk"], ":reservations"=>$data["reservations"], ":telephone_operator"=>$data["telephone_operator"], ":valet"=>$data["valet"], ":housekeeping"=>$data["housekeeping"], ":accommodation"=>$data["accommodation"], ":safety"=>$data["safety"], ":security"=>$data["security"], ":overall_service"=>$data["overall_service"], ":frontdesk_comments"=>$data["frontdesk_comments"]]);

    $stmtFNB = $pdo->prepare("INSERT INTO feedback_fnb (feedback_id, food_quality, serving_time, wait_staff, grooming, behavior, fnb_service, bar, bartender, fnb_comments) VALUES (:feedback_id, :food_quality, :serving_time, :wait_staff, :grooming, :behavior, :fnb_service, :bar, :bartender, :fnb_comments)");
    $stmtFNB->execute([":feedback_id"=>$feedback_id, ":food_quality"=>$data["food_quality"], ":serving_time"=>$data["serving_time"], ":wait_staff"=>$data["wait_staff"], ":grooming"=>$data["grooming"], ":behavior"=>$data["behavior"], ":fnb_service"=>$data["fnb_service"], ":bar"=>$data["bar"], ":bartender"=>$data["bartender"], ":fnb_comments"=>$data["fnb_comments"]]);

    if (!empty($data["helpful_staff_names"])) {
        $staffNames = array_map('trim', explode(",", $data["helpful_staff_names"]));
        $stmtStaff = $pdo->prepare("INSERT INTO feedback_helpful_staff (feedback_id, staff_name) VALUES (:feedback_id, :staff_name)");
        foreach ($staffNames as $name) { if (!empty($name)) $stmtStaff->execute([":feedback_id"=>$feedback_id, ":staff_name"=>$name]); }
    }
    $pdo->commit();
    $success = true;
    if (!empty($data['email'])) sendThankYouEmail($data['guest_name'], $data['email'], $gmailUsername, $gmailAppPassword);
} catch (PDOException $e) { if ($pdo->inTransaction()) $pdo->rollBack(); error_log($e->getMessage()); $success = false; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Thank You - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display&display=swap" rel="stylesheet">
    <style>
        body{background:#0A1912;color:white;font-family:sans-serif}
        .glass{background:rgba(255,255,255,0.1);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:40px}
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 text-center">
    <header class="mb-12">
        <h1 class="font-['Great_Vibes'] text-6xl mb-2">John Hay Hotels</h1>
        <p class="uppercase tracking-[0.5em] text-xs text-gold-400">Forest Wing</p>
    </header>
    <main class="glass max-w-lg w-full">
        <?php if ($success): ?>
            <h2 class="text-3xl font-['Playfair_Display'] mb-4">Thank You, <?= $data['guest_name'] ?: 'Valued Guest' ?>!</h2>
            <p class="text-white/60 italic">Your feedback is invaluable to us.</p>
            <a href="index.php" class="inline-block mt-8 text-gold-400 uppercase text-xs tracking-widest border-b border-gold-400 pb-1">Back to Home</a>
        <?php else: ?>
            <h2 class="text-3xl mb-4">Error</h2>
            <p>Something went wrong.</p>
        <?php endif; ?>
    </main>
    <footer class="mt-12 text-white/40 text-[0.6rem] uppercase tracking-[0.3em]">
        Forest Wing - Camp John Hay - Baguio City, 2600
    </footer>
</body>
</html>
