<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN - VIEW FEEDBACK DETAIL
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 */
session_start();
require_once "../config.php";

// Auth check
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}

// Validate ID
$id = intval($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch feedback
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = :id");
$stmt->execute([":id" => $id]);
$fb = $stmt->fetch();

if (!$fb) {
    header("Location: index.php");
    exit();
}

// Helper to display rating as text
function ratingText($val)
{
    switch ($val) {
        case 3:
            return '<span class="text-emerald-400 font-semibold">Excellent</span>';
        case 2:
            return '<span class="text-green-400 font-semibold">Good</span>';
        case 1:
            return '<span class="text-orange-400 font-semibold">Poor</span>';
        default:
            return '<span class="text-white/20">Not Rated</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback #<?= $id ?> - Admin - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine: { 800: '#1B3A2D', 900: '#142B21', 950: '#0A1912' },
                        gold: { 400: '#C9A96E', 500: '#b5893a' },
                    },
                    fontFamily: {
                        script: ['"Great Vibes"', 'cursive'],
                        serif:  ['"Playfair Display"', 'serif'],
                        sans:   ['"Inter"', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { background: #0A1912; }
        .glass-card {
            background: rgba(245,235,224,0.06);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(201,169,110,0.12);
        }
    </style>
</head>
<body class="font-sans text-white min-h-screen">

    <!-- Nav -->
    <nav class="border-b border-white/[0.06] px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="index.php" class="text-white/30 hover:text-white/60 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            <span class="text-white/15 text-[1rem]">Feedback #<?= $id ?></span>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-6 py-8 space-y-6">

        <!-- ═══ GUEST INFO HEADER ═══ -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="font-serif text-[1.75rem] text-white/80 mb-1">
                        <?= !empty($fb["guest_name"])
                            ? htmlspecialchars($fb["guest_name"])
                            : '<span class="italic text-white/30">Anonymous Guest</span>' ?>
                    </h1>
                    <div class="flex flex-wrap items-center gap-3 text-[1rem] text-white/30">
                        <?php if (!empty($fb["email"])): ?>
                            <span><?= htmlspecialchars($fb["email"]) ?></span>
                            <span class="text-white/10">|</span>
                        <?php endif; ?>
                        <?php if (!empty($fb["contact_no"])): ?>
                            <span><?= htmlspecialchars(
                                $fb["contact_no"],
                            ) ?></span>
                            <span class="text-white/10">|</span>
                        <?php endif; ?>
                        <?php if (!empty($fb["address"])): ?>
                            <span><?= htmlspecialchars($fb["address"]) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[2.5rem] font-bold text-gold-400"><?= $fb[
                            "overall_rating"
                        ] ?></span>
                        <span class="text-white/60 text-[1.375rem]">/10</span>
                    </div>
                    <span class="text-[0.85rem] font-bold text-gold-400/80 uppercase tracking-[0.2em]">Overall Rating</span>
                </div>
            </div>

            <!-- Quick info row -->
            <div class="mt-5 pt-5 border-t border-white/[0.06] grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div>
                    <span class="block text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.15em] mb-1">Room</span>
                    <span class="text-[1.125rem] text-white/60 font-semibold"><?= htmlspecialchars(
                        $fb["room_no"],
                    ) ?></span>
                </div>
                <div>
                    <span class="block text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.15em] mb-1">First Stay?</span>
                    <span class="text-[1.125rem] text-white/60"><?= htmlspecialchars(
                        $fb["first_stay"] ?: "—",
                    ) ?></span>
                </div>
                <div>
                    <span class="block text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.15em] mb-1">Nationality</span>
                    <span class="text-[1.125rem] text-white/60"><?= htmlspecialchars(
                        $fb["nationality"] ?: "—",
                    ) ?></span>
                </div>
                <div>
                    <span class="block text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.15em] mb-1">Purpose</span>
                    <span class="text-[1.125rem] text-white/60"><?= htmlspecialchars(
                        $fb["purpose_of_stay"] ?: "—",
                    ) ?></span>
                    <?php if (!empty($fb["other_purpose_text"])): ?>
                        <span class="text-[1rem] text-white/30 block">(<?= htmlspecialchars(
                            $fb["other_purpose_text"],
                        ) ?>)</span>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="block text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.15em] mb-1">Stay Dates</span>
                    <span class="text-[1.125rem] text-white/60">
                        <?php if (
                            !empty($fb["check_in"]) &&
                            !empty($fb["check_out"])
                        ): ?>
                            <?= date(
                                "M d",
                                strtotime($fb["check_in"]),
                            ) ?> — <?= date(
     "M d, Y",
     strtotime($fb["check_out"]),
 ) ?>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        <!-- ═══ FRONT OF HOUSE RATINGS ═══ -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06]">
                <h2 class="font-serif text-white/60 text-[1.25rem] tracking-wider uppercase">Front of House</h2>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-4 gap-x-6">
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Front Desk</span><?= ratingText(
                        $fb["frontdesk"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Reservations</span><?= ratingText(
                        $fb["reservations"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Telephone</span><?= ratingText(
                        $fb["telephone_operator"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Valet</span><?= ratingText(
                        $fb["valet"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Housekeeping</span><?= ratingText(
                        $fb["housekeeping"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Accommodation</span><?= ratingText(
                        $fb["accommodation"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Safety</span><?= ratingText(
                        $fb["safety"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Security</span><?= ratingText(
                        $fb["security"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Overall Service</span><?= ratingText(
                        $fb["overall_service"],
                    ) ?></div>
                </div>
                <?php if (!empty($fb["frontdesk_comments"])): ?>
                    <div class="mt-5 pt-4 border-t border-white/[0.04]">
                        <span class="text-[0.8rem] font-bold text-gold-400/40 uppercase tracking-wider block mb-2">Comments</span>
                        <p class="text-[1.125rem] text-white/50 italic leading-relaxed"><?= nl2br(
                            htmlspecialchars($fb["frontdesk_comments"]),
                        ) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ FOOD & BEVERAGE RATINGS ═══ -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06]">
                <h2 class="font-serif text-white/60 text-[1.25rem] tracking-wider uppercase">Food & Beverage</h2>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-4 gap-x-6">
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Food Quality</span><?= ratingText(
                        $fb["food_quality"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Serving Time</span><?= ratingText(
                        $fb["serving_time"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Wait Staff</span><?= ratingText(
                        $fb["wait_staff"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Grooming</span><?= ratingText(
                        $fb["grooming"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Behavior</span><?= ratingText(
                        $fb["behavior"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Service</span><?= ratingText(
                        $fb["fnb_service"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Bar</span><?= ratingText(
                        $fb["bar"],
                    ) ?></div>
                    <div><span class="text-[0.85rem] font-bold text-white/60 uppercase tracking-wider block mb-1">Bartender</span><?= ratingText(
                        $fb["bartender"],
                    ) ?></div>
                </div>
                <?php if (!empty($fb["fnb_comments"])): ?>
                    <div class="mt-5 pt-4 border-t border-white/[0.04]">
                        <span class="text-[0.8rem] font-bold text-gold-400/40 uppercase tracking-wider block mb-2">Comments</span>
                        <p class="text-[1.125rem] text-white/50 italic leading-relaxed"><?= nl2br(
                            htmlspecialchars($fb["fnb_comments"]),
                        ) ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($fb["helpful_staff_names"])): ?>
                    <div class="mt-4 pt-4 border-t border-white/[0.04]">
                        <span class="text-[0.8rem] font-bold text-gold-400/40 uppercase tracking-wider block mb-2">Especially Helpful Staff</span>
                        <p class="text-[1.125rem] text-gold-400/90 font-medium"><?= htmlspecialchars(
                            $fb["helpful_staff_names"],
                        ) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ ADDITIONAL COMMENTS ═══ -->
        <?php if (
            !empty($fb["suggestions_future"]) ||
            !empty($fb["other_comments"])
        ): ?>
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/[0.06]">
                    <h2 class="font-serif text-white/60 text-[1.25rem] tracking-wider uppercase">Additional Comments</h2>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <?php if (!empty($fb["suggestions_future"])): ?>
                        <div>
                            <span class="text-[0.8rem] font-bold text-gold-400/40 uppercase tracking-wider block mb-2">Suggestions for the Future</span>
                            <p class="text-[1.125rem] text-white/50 italic leading-relaxed"><?= nl2br(
                                htmlspecialchars($fb["suggestions_future"]),
                            ) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($fb["other_comments"])): ?>
                        <div>
                            <span class="text-[0.8rem] font-bold text-gold-400/40 uppercase tracking-wider block mb-2">Other Comments</span>
                            <p class="text-[1.125rem] text-white/50 italic leading-relaxed"><?= nl2br(
                                htmlspecialchars($fb["other_comments"]),
                            ) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Submitted timestamp -->
        <div class="text-center py-4">
            <p class="text-white/15 text-[1rem]">Submitted on <?= date(
                'F d, Y \a\t h:i A',
                strtotime($fb["created_at"]),
            ) ?></p>
        </div>

    </div><!-- /max-w-5xl -->

</body>
</html>