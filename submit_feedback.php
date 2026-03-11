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
        <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap");
        </style>
    </head>
    <body style="margin: 0; padding: 0; background-color: #0A1912; font-family: \'Inter\', Arial, sans-serif;">
        <!-- Outer wrapper with embedded background image -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0A1912;">
            <tr>
                <td align="center" background="cid:forestbg" style="background-image: url(cid:forestbg); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <!-- Dark overlay layer -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: linear-gradient(180deg, rgba(10,25,18,0.6) 0%, rgba(10,25,18,0.4) 50%, rgba(10,25,18,0.7) 100%);">
                        <tr>
                            <td align="center" style="padding: 30px 0;">

                    <!-- Header: Hotel Name + Forest Wing -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="width: 100%; max-width: 600px;">
                        <tr>
                            <td align="center" style="padding: 20px 40px 10px 40px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <h1 style="margin: 0 0 6px 0; font-family: \'Great Vibes\', cursive; font-size: 36px; font-weight: normal; color: rgba(255,255,255,0.8);">
                                    John Hay Hotels
                                </h1>
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="width: 30px; border-top: 1px solid #8D7A55;"></td>
                                        <td style="padding: 0 12px;">
                                            <span style="font-family: \'Inter\', Arial, sans-serif; font-size: 9px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: rgba(201,169,110,0.8);">Forest Wing</span>
                                        </td>
                                        <td style="width: 30px; border-top: 1px solid #8D7A55;"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Main Content Card -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="width: 100%; max-width: 600px;">
                        <tr>
                            <td align="center" style="padding: 40px 40px 20px 40px;">
                                <!-- Gold Checkmark Circle -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #C9A96E, #b5893a);">
                                            <span style="font-size: 36px; color: #ffffff; line-height: 1;">&#10003;</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 0 40px;">
                                <!-- Glassmorphism-style card -->
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: rgba(245,235,224,0.10); border-radius: 16px; border: 1px solid rgba(201,169,110,0.15); box-shadow: 0 8px 32px rgba(0,0,0,0.25);">
                                    <tr>
                                        <td align="center" style="padding: 36px 30px;">
                                            <!-- Greeting -->
                                            <h2 style="margin: 0 0 20px 0; font-family: \'Playfair Display\', Georgia, serif; font-size: 28px; font-weight: 400; color: rgba(255,255,255,0.9);">
                                                Thank You, ' . $displayName . '!
                                            </h2>

                                            <!-- Main message -->
                                            <p style="margin: 0 0 28px 0; font-family: \'Playfair Display\', Georgia, serif; font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.9); font-style: italic; text-align: center;">
                                                Your feedback is invaluable to us. It helps us continue delivering the exceptional experience you deserve at John Hay Hotels.
                                            </p>

                                            <!-- Diamond divider -->
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 28px;">
                                                <tr>
                                                    <td style="width: 40px; border-top: 1px solid rgba(201,169,110,0.3);"></td>
                                                    <td style="padding: 0 12px;">
                                                        <span style="color: rgba(201,169,110,0.4); font-size: 8px; line-height: 1;">&#9670;</span>
                                                    </td>
                                                    <td style="width: 40px; border-top: 1px solid rgba(201,169,110,0.3);"></td>
                                                </tr>
                                            </table>

                                            <!-- Closing message -->
                                            <p style="margin: 0; font-family: \'Playfair Display\', Georgia, serif; font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.9); font-style: italic; text-align: center;">
                                                We look forward to welcoming you again soon.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Footer -->
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="width: 100%; max-width: 600px; margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.06);">
                        <tr>
                            <td align="center" style="padding: 24px 40px;">
                                <p style="margin: 0 0 6px 0; font-family: \'Great Vibes\', cursive; font-size: 28px; color: rgba(201,169,110,0.9);">
                                    John Hay Hotels
                                </p>
                                <p style="margin: 0; font-family: \'Inter\', Arial, sans-serif; font-size: 9px; font-weight: 500; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 4px;">
                                    Forest Wing &ndash; Camp John Hay &ndash; Baguio City, 2600
                                </p>
                            </td>
                        </tr>
                    </table>

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
        // Embed the forest background image
        $bgImagePath = __DIR__ . '/img/forest-bg.jpg';
        if (file_exists($bgImagePath)) {
            $mail->addEmbeddedImage($bgImagePath, 'forestbg', 'forest-bg.jpg');
        }
        $mail->Body = $htmlBody;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

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
    "frontdesk_comments" => htmlspecialchars(
        trim($_POST["frontdesk_comments"] ?? ""),
    ),
    "food_quality" => intval($_POST["food_quality"] ?? 0),
    "serving_time" => intval($_POST["serving_time"] ?? 0),
    "wait_staff" => intval($_POST["wait_staff"] ?? 0),
    "grooming" => intval($_POST["grooming"] ?? 0),
    "behavior" => intval($_POST["behavior"] ?? 0),
    "fnb_service" => intval($_POST["fnb_service"] ?? 0),
    "bar" => intval($_POST["bar"] ?? 0),
    "bartender" => intval($_POST["bartender"] ?? 0),
    "fnb_comments" => htmlspecialchars(trim($_POST["fnb_comments"] ?? "")),
    "helpful_staff_names" => htmlspecialchars(
        trim($_POST["helpful_staff_names"] ?? ""),
    ),
    "overall_rating" => intval($_POST["overall_rating"] ?? 0),
    "suggestions_future" => htmlspecialchars(
        trim($_POST["suggestions_future"] ?? ""),
    ),
    "other_comments" => htmlspecialchars(trim($_POST["other_comments"] ?? "")),
    "first_stay" => htmlspecialchars(trim($_POST["first_stay"] ?? "")),
    "purpose_of_stay" => htmlspecialchars(
        trim($_POST["purpose_of_stay"] ?? ""),
    ),
    "other_purpose_text" => htmlspecialchars(
        trim($_POST["other_purpose_text"] ?? ""),
    ),
    "nationality" => htmlspecialchars(trim($_POST["nationality"] ?? "")),
    "other_nationality_text" => htmlspecialchars(
        trim($_POST["other_nationality_text"] ?? ""),
    ),
    "guest_name" => htmlspecialchars(trim($_POST["guest_name"] ?? "")),
    "email" => htmlspecialchars(trim($_POST["email"] ?? "")),
    "address" => htmlspecialchars(trim($_POST["address"] ?? "")),
    "contact_no" => htmlspecialchars(trim($_POST["contact_no"] ?? "")),
    "room_no" => htmlspecialchars(trim($_POST["room_no"] ?? "")),
    "check_in" => !empty($_POST["check_in"]) ? $_POST["check_in"] : null,
    "check_out" => !empty($_POST["check_out"]) ? $_POST["check_out"] : null,
];

$success = false;

try {
    $pdo->beginTransaction();

    // 1. Insert into guests table
    $sqlGuest = "INSERT INTO guests (guest_name, email, address, contact_no, nationality, other_nationality_text)
                 VALUES (:guest_name, :email, :address, :contact_no, :nationality, :other_nationality_text)";
    $stmtGuest = $pdo->prepare($sqlGuest);
    $stmtGuest->execute([
        ":guest_name" => $data["guest_name"],
        ":email" => $data["email"],
        ":address" => $data["address"],
        ":contact_no" => $data["contact_no"],
        ":nationality" => $data["nationality"],
        ":other_nationality_text" => $data["other_nationality_text"],
    ]);
    $guest_id = $pdo->lastInsertId();

    // 2. Insert into stays table
    $sqlStay = "INSERT INTO stays (guest_id, room_no, check_in, check_out, first_stay, purpose_of_stay, other_purpose_text)
                VALUES (:guest_id, :room_no, :check_in, :check_out, :first_stay, :purpose_of_stay, :other_purpose_text)";
    $stmtStay = $pdo->prepare($sqlStay);
    $stmtStay->execute([
        ":guest_id" => $guest_id,
        ":room_no" => $data["room_no"],
        ":check_in" => $data["check_in"],
        ":check_out" => $data["check_out"],
        ":first_stay" => $data["first_stay"],
        ":purpose_of_stay" => $data["purpose_of_stay"],
        ":other_purpose_text" => $data["other_purpose_text"],
    ]);
    $stay_id = $pdo->lastInsertId();

    // 3. Insert into feedbacks table
    $sqlFeedback = "INSERT INTO feedbacks (stay_id, overall_rating, suggestions_future, other_comments)
                    VALUES (:stay_id, :overall_rating, :suggestions_future, :other_comments)";
    $stmtFeedback = $pdo->prepare($sqlFeedback);
    $stmtFeedback->execute([
        ":stay_id" => $stay_id,
        ":overall_rating" => $data["overall_rating"],
        ":suggestions_future" => $data["suggestions_future"],
        ":other_comments" => $data["other_comments"],
    ]);
    $feedback_id = $pdo->lastInsertId();

    // 4. Insert into feedback_foh
    $sqlFOH = "INSERT INTO feedback_foh (feedback_id, frontdesk, reservations, telephone_operator, valet, housekeeping, accommodation, safety, security, overall_service, frontdesk_comments)
               VALUES (:feedback_id, :frontdesk, :reservations, :telephone_operator, :valet, :housekeeping, :accommodation, :safety, :security, :overall_service, :frontdesk_comments)";
    $stmtFOH = $pdo->prepare($sqlFOH);
    $stmtFOH->execute([
        ":feedback_id" => $feedback_id,
        ":frontdesk" => $data["frontdesk"],
        ":reservations" => $data["reservations"],
        ":telephone_operator" => $data["telephone_operator"],
        ":valet" => $data["valet"],
        ":housekeeping" => $data["housekeeping"],
        ":accommodation" => $data["accommodation"],
        ":safety" => $data["safety"],
        ":security" => $data["security"],
        ":overall_service" => $data["overall_service"],
        ":frontdesk_comments" => $data["frontdesk_comments"],
    ]);

    // 5. Insert into feedback_fnb
    $sqlFNB = "INSERT INTO feedback_fnb (feedback_id, food_quality, serving_time, wait_staff, grooming, behavior, fnb_service, bar, bartender, fnb_comments)
               VALUES (:feedback_id, :food_quality, :serving_time, :wait_staff, :grooming, :behavior, :fnb_service, :bar, :bartender, :fnb_comments)";
    $stmtFNB = $pdo->prepare($sqlFNB);
    $stmtFNB->execute([
        ":feedback_id" => $feedback_id,
        ":food_quality" => $data["food_quality"],
        ":serving_time" => $data["serving_time"],
        ":wait_staff" => $data["wait_staff"],
        ":grooming" => $data["grooming"],
        ":behavior" => $data["behavior"],
        ":fnb_service" => $data["fnb_service"],
        ":bar" => $data["bar"],
        ":bartender" => $data["bartender"],
        ":fnb_comments" => $data["fnb_comments"],
    ]);

    // 6. Handle helpful staff names (1NF normalization)
    if (!empty($data["helpful_staff_names"])) {
        $staffNames = array_map('trim', explode(",", $data["helpful_staff_names"]));
        $sqlStaff = "INSERT INTO feedback_helpful_staff (feedback_id, staff_name) VALUES (:feedback_id, :staff_name)";
        $stmtStaff = $pdo->prepare($sqlStaff);
        foreach ($staffNames as $name) {
            if (!empty($name)) {
                $stmtStaff->execute([
                    ":feedback_id" => $feedback_id,
                    ":staff_name" => $name,
                ]);
            }
        }
    }

    $pdo->commit();
    $success = true;
    if (!empty($data['email'])) sendThankYouEmail($data['guest_name'], $data['email'], $gmailUsername, $gmailAppPassword);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Failed to insert normalized feedback: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icon.png">
    <title>Thank You - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{colors:{pine:{800:'#1B3A2D',900:'#142B21',950:'#0A1912'},gold:{400:'#C9A96E'}},fontFamily:{script:['"Great Vibes"','cursive'],serif:['"Playfair Display"','serif'],sans:['"Inter"','sans-serif']}}}}</script>
    <style>
        body{background:#0A1912}
        .scene-bg{position:fixed;inset:0;z-index:0;background:url('img/forest-bg.jpg') center/cover no-repeat}
        .scene-bg::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,25,18,0.6) 0%,rgba(10,25,18,0.4) 50%,rgba(10,25,18,0.7) 100%)}
        @keyframes checkDraw{0%{stroke-dashoffset:48}100%{stroke-dashoffset:0}}
        .check-animated{stroke-dasharray:48;stroke-dashoffset:48;animation:checkDraw .6s .4s ease-out forwards}
        @keyframes scaleIn{0%{transform:scale(0)}100%{transform:scale(1)}}
        .scale-animated{animation:scaleIn .4s cubic-bezier(.34,1.56,.64,1) forwards}
        @keyframes fadeUp{0%{opacity:0;transform:translateY(20px)}100%{opacity:1;transform:translateY(0)}}
        .fade-up{opacity:0;animation:fadeUp .6s ease-out forwards}
        .glass-card-warm {
            background: rgba(245,235,224,0.10);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(201,169,110,0.15);
            box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(201,169,110,0.10);
        }
    </style>
</head>
<body class="font-sans min-h-screen flex flex-col text-white relative">
    <div class="scene-bg"></div>
    <header class="relative z-10 text-center py-6 border-b border-white/[0.05]">
        <h1 class="font-script text-4xl text-white/80 mb-1">John Hay Hotels</h1>
        <div class="flex items-center justify-center gap-3">
            <span class="w-8 h-px bg-gold-400/40"></span>
            <span class="text-gold-400/80 text-[0.65rem] font-semibold tracking-[0.3em] uppercase">Forest Wing</span>
            <span class="w-8 h-px bg-gold-400/40"></span>
        </div>
    </header>
    <main class="flex-1 flex items-center justify-center px-4 py-16 relative z-10">
        <div class="max-w-lg w-full text-center">
        <?php if ($success): ?>
            <div class="glass-card-warm rounded-2xl px-8 py-10 max-w-2xl mx-auto mb-10 fade-up">
                <div class="w-24 h-24 mx-auto mb-8 rounded-full flex items-center justify-center scale-animated" style="background:linear-gradient(135deg,#C9A96E,#b5893a)">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none">
                        <path class="check-animated" d="M5 13l4 4L19 7" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 class="font-serif text-3xl md:text-4xl text-white/90 mb-4 fade-up" style="animation-delay:.3s">Thank You, <?= !empty(
                    $data["guest_name"]
                )
                    ? $data["guest_name"]
                    : "Valued Guest" ?>!</h2>
               <p class="font-serif italic text-white/90 text-lg md:text-xl mb-10 drop-shadow-lg font-medium leading-relaxed" style="animation-delay:.5s">Your feedback is invaluable to us. It helps us continue delivering the exceptional experience you deserve at John Hay Hotels.</p>
                <div class="flex items-center justify-center gap-3 mb-8 fade-up" style="animation-delay:.6s">
                    <span class="w-12 h-px bg-gold-400/30"></span>
                    <span class="w-2 h-2 rotate-45 bg-gold-400/40"></span>
                    <span class="w-12 h-px bg-gold-400/30"></span>
                </div>
                <p class="font-serif italic text-white/90 text-lg md:text-xl mb-6 drop-shadow-lg font-medium leading-relaxed" style="animation-delay:.7s">We look forward to welcoming you again soon.</p>
            </div>
            <a href="index.php" class="inline-flex items-center gap-2 text-sm font-semibold text-gold-400/70 uppercase tracking-wider hover:text-gold-400 transition-colors duration-300 fade-up" style="animation-delay:.8s">
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
                Back to Feedback Form
            </a>
        <?php else: ?>
            <div class="w-24 h-24 mx-auto mb-8 rounded-full bg-red-900/30 flex items-center justify-center scale-animated">
                <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="font-serif text-3xl text-white/80 mb-4">Something Went Wrong</h2>
            <p class="text-white/40 mb-8">We could not process your feedback. Please try again. (Database error)</p>
            <a href="index.php" class="inline-flex items-center gap-2 px-8 py-3 rounded-full font-semibold text-sm uppercase tracking-wider transition-colors" style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912">Try Again</a>
        <?php endif; ?>
        </div>
    </main>
     <footer class="relative z-10 text-center py-12 border-t border-white/10 bg-black/20 backdrop-blur-sm">
    <p class="font-script text-4xl text-gold-400/90 mb-3 drop-shadow-md">
        John Hay Hotels
    </p>
    
    <p class="text-white/60 text-[0.7rem] font-medium uppercase tracking-[0.5em]">
        Forest Wing - Camp John Hay - Baguio City, 2600
    </p>
</footer>
</body>
</html>
