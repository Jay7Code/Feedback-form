<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN DASHBOARD
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

$mysqli = getDBConnection();

// ─── Handle CSV Export ───
if (isset($_GET["export"]) && $_GET["export"] === "csv") {
    $csvQuery = "SELECT 
        f.id AS Feedback_ID, f.created_at AS Date_Submitted,
        g.guest_name AS Guest_Name, g.email AS Email, g.contact_no AS Contact, g.address AS Address, g.nationality AS Nationality,
        s.room_no AS Room, s.check_in AS Check_In, s.check_out AS Check_Out, s.first_stay AS First_Stay, s.purpose_of_stay AS Purpose, s.find_out_about_us AS Find_Out, s.mode_of_reservation AS Mode_Reservation,
        f.overall_rating AS Overall_Rating, f.repeat_visit AS Repeat_Visit,
        foh.reservations AS FOH_Reservations, foh.check_in_rating AS FOH_CheckIn, foh.check_out_rating AS FOH_CheckOut, foh.accommodation AS FOH_Accommodation, foh.telephone_operator AS FOH_Telephone, foh.frontdesk AS FOH_Frontdesk, foh.housekeeping AS FOH_Housekeeping, foh.security AS FOH_Security,
        foh.friendliness AS FOH_Friendliness, foh.attentiveness AS FOH_Attentiveness, foh.courteousness AS FOH_Courteousness,
        foh.safety AS FOH_Safety, foh.valet AS FOH_Valet,
        fg.cleanliness AS Guestroom_Cleanliness, fg.ambiance AS Guestroom_Ambiance, fg.comfort AS Guestroom_Comfort, fg.bathroom AS Guestroom_Bathroom,
        fnb.food_quality AS FNB_Food, fnb.serving_time AS FNB_Serving, fnb.grooming AS FNB_Grooming, fnb.behavior AS FNB_Behavior, fnb.fnb_service AS FNB_Service, fnb.bar AS FNB_Bar,
        f.general_comments AS Comments
    FROM feedbacks f
    JOIN stays s ON f.stay_id = s.id
    JOIN guests g ON s.guest_id = g.id
    LEFT JOIN feedback_foh foh ON f.id = foh.feedback_id
    LEFT JOIN feedback_fnb fnb ON f.id = fnb.feedback_id
    LEFT JOIN feedback_guestroom fg ON f.id = fg.feedback_id
    ORDER BY f.created_at DESC";
    
    $result = $mysqli->query($csvQuery);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    header("Content-Type: text/csv");
    header('Content-Disposition: attachment; filename="feedback_export_' . date("Y-m-d") . '.csv"');

    $output = fopen("php://output", "w");
    if (!empty($rows)) {
        fputcsv($output, array_keys($rows[0]));
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();
}

// ─── Filters ───
$where = [];
$params = [];
$types = "";

if (!empty($_GET["date_from"])) {
    $where[] = "DATE(f.created_at) >= ?";
    $params[] = $_GET["date_from"];
    $types .= "s";
}
if (!empty($_GET["date_to"])) {
    $where[] = "DATE(f.created_at) <= ?";
    $params[] = $_GET["date_to"];
    $types .= "s";
}
if (!empty($_GET["room"])) {
    $where[] = "s.room_no = ?";
    $params[] = $_GET["room"];
    $types .= "s";
}
if (!empty($_GET["search"])) {
    $where[] = "(g.guest_name LIKE ? OR g.email LIKE ? OR s.room_no LIKE ?)";
    $searchKey = "%" . $_GET["search"] . "%";
    $params[] = $searchKey;
    $params[] = $searchKey;
    $params[] = $searchKey;
    $types .= "sss";
}

$whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$joinSQL = "FROM feedbacks f JOIN stays s ON f.stay_id = s.id JOIN guests g ON s.guest_id = g.id";

// ─── Stats ───
$totalStmt = $mysqli->prepare("SELECT COUNT(f.id) $joinSQL $whereSQL");
if (!empty($params)) {
    $totalStmt->bind_param($types, ...$params);
}
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$row = $totalResult->fetch_row();
$totalCount = $row[0];

$avgStmt = $mysqli->prepare(
    "SELECT ROUND(AVG(f.overall_rating), 1) $joinSQL $whereSQL",
);
if (!empty($params)) {
    $avgStmt->bind_param($types, ...$params);
}
$avgStmt->execute();
$avgResult = $avgStmt->get_result();
$row = $avgResult->fetch_row();
$avgRating = $row[0] ?: "—";

$latestStmt = $mysqli->prepare("SELECT MAX(f.created_at) $joinSQL $whereSQL");
if (!empty($params)) {
    $latestStmt->bind_param($types, ...$params);
}
$latestStmt->execute();
$latestResult = $latestStmt->get_result();
$row = $latestResult->fetch_row();
$latestDate = $row[0];
$latestFormatted = $latestDate ? date("M d, Y", strtotime($latestDate)) : "—";

// ─── Pagination ───
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$totalPages = ceil($totalCount / $limit);

// ─── Feedback list ───
$listStmt = $mysqli->prepare(
    "SELECT f.id, g.guest_name, s.room_no, f.overall_rating, s.purpose_of_stay, s.check_in, s.check_out, f.created_at 
     $joinSQL $whereSQL 
     ORDER BY f.created_at DESC LIMIT $offset, $limit",
);
if (!empty($params)) {
    $listStmt->bind_param($types, ...$params);
}
$listStmt->execute();
$listResult = $listStmt->get_result();
$feedbacks = [];
while ($row = $listResult->fetch_assoc()) {
    $feedbacks[] = $row;
}

// Rating label helper
function ratingLabel($val)
{
    if ($val >= 5) {
        return ["Excellent", "text-emerald-400"];
    }
    if ($val >= 4) {
        return ["Good", "text-green-400"];
    }
    if ($val >= 3) {
        return ["Average", "text-yellow-400"];
    }
    if ($val >= 2) {
        return ["Below Avg", "text-orange-400"];
    }
    return ["Poor", "text-red-400"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine: { 700: '#1e5832', 800: '#1B3A2D', 900: '#142B21', 950: '#0A1912' },
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
        .lodge-input {
            padding: 10px 14px; border-radius: 10px;
            border: 1px solid rgba(201,169,110,0.2);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
            font-family: 'Inter', sans-serif; font-size: 0.8rem;
            transition: all 0.3s ease; outline: none;
        }
        .lodge-input:focus { border-color: #C9A96E; background: rgba(255,255,255,0.1); }
        .lodge-input::placeholder { color: rgba(255,255,255,0.3); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(10,25,18,0.5); }
        ::-webkit-scrollbar-thumb { background: rgba(201,169,110,0.3); border-radius: 99px; }
        .nav-link { padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s ease; color: rgba(255,255,255,0.7); }
        .nav-link:hover { background: rgba(201,169,110,0.1); color: #ffffff; }
        .nav-link.active { color: #C9A96E; }
        
        /* Hover transitions matching the official website */
        nav.group:hover .nav-link { color: #1B3A2D; opacity: 0.7; }
        nav.group:hover .nav-link:hover { background: rgba(201,169,110,0.1); opacity: 1; color: #1B3A2D; }
        nav.group:hover .nav-link.active { background: rgba(201,169,110,0.15); opacity: 1; color: #b5893a; }
        
        .logo-img { filter: brightness(0) invert(1); transition: all 0.3s ease; }
        nav.group:hover .logo-img { filter: none; }
    </style>
</head>
<body class="font-sans text-white min-h-screen">
    <?php if (isset($_SESSION['show_welcome_modal']) && $_SESSION['show_welcome_modal'] === true): ?>
    <div id="welcomeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeWelcomeModal()"></div>
        
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl p-8 text-center shadow-2xl transition-all" style="background: rgba(245,235,224,0.08); backdrop-filter: blur(24px); border: 1px solid rgba(201,169,110,0.15); box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gold-400/10 mb-6">
                <svg class="h-8 w-8 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-serif text-[1.75rem] text-white/90 tracking-wide mb-2">Welcome Back!</h3>
            <p class="text-white/60 text-[1rem] leading-relaxed mb-8">
                Hello, <span class="text-gold-400 font-semibold"><?= htmlspecialchars($_SESSION['admin_full_name'] ?? $_SESSION['admin_username'] ?? 'Admin') ?></span>. You have successfully logged into the John Hay Hotels Admin Panel.
            </p>
            <button onclick="closeWelcomeModal()" class="w-full py-3.5 rounded-full font-semibold text-[1.125rem] uppercase tracking-[0.15em] transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #C9A96E, #b5893a); color: #0A1912;">
                Continue to Dashboard
            </button>
        </div>
    </div>
    <script>
        function closeWelcomeModal() {
            var modal = document.getElementById('welcomeModal');
            if(modal) {
                modal.style.opacity = '0';
                setTimeout(function(){ modal.remove(); }, 300);
            }
        }
    </script>
    <?php unset($_SESSION['show_welcome_modal']); endif; ?>

    <!-- ═══ TOP NAV BAR ═══ -->
    <nav class="group bg-transparent hover:bg-white px-6 py-3 no-print relative z-10 border-b border-white/[0.06] hover:border-gold-400/20 transition-all duration-300 ease-in-out hover:shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <img src="../img/logo.png" alt="John Hay Hotels Logo" class="logo-img h-16 sm:h-20 w-auto object-contain">
                <span class="text-[0.8rem] font-bold text-white/70 group-hover:text-pine-900 uppercase tracking-[0.2em] px-3 py-1 rounded-full bg-white/5 group-hover:bg-gold-400/20 border border-white/10 group-hover:border-gold-400/30 hidden sm:inline-block transition-colors duration-300">Admin Panel</span>
            </div>
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="index.php" class="nav-link active">Dashboard</a>
                <a href="analytics.php" class="nav-link">Analytics</a>
                <a href="reports.php" class="nav-link">Reports</a>
                <span class="text-white/20 group-hover:text-pine-900/20 mx-1 sm:mx-2 transition-colors duration-300">|</span>
                <a href="logout.php" class="text-[0.9rem] font-semibold text-white/50 hover:text-red-400 group-hover:text-red-600/80 group-hover:hover:text-red-700 group-hover:hover:bg-red-50 transition-colors flex items-center gap-1.5 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- ═══ STAT CARDS ═══ -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <!-- Total Feedback -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-[0.9rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em]">Total Feedback</span>
                </div>
                <p class="text-[2.125rem] font-bold text-white/80"><?= $totalCount ?></p>
            </div>

            <!-- Average NPS -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <span class="text-[0.9rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em]">Avg. Satisfaction</span>
                </div>
                <p class="text-[2.125rem] font-bold text-white/80"><?= $avgRating ?><span class="text-[1.375rem] text-white/30">/5</span></p>
            </div>

            <!-- Latest Submission -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[0.9rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em]">Latest Submission</span>
                </div>
                <p class="text-[1.5rem] font-bold text-white/80"><?= $latestFormatted ?></p>
            </div>
        </div>

        <!-- ═══ FILTERS & ACTIONS ═══ -->
        <div class="glass-card rounded-xl p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-[0.85rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-1.5">Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars(
                        $_GET["search"] ?? "",
                    ) ?>" placeholder="Name, email, or room..." class="lodge-input w-full">
                </div>
                <div>
                    <label class="block text-[0.85rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-1.5">Date From</label>
                    <input type="date" name="date_from" value="<?= htmlspecialchars(
                        $_GET["date_from"] ?? "",
                    ) ?>" class="lodge-input">
                </div>
                <div>
                    <label class="block text-[0.85rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-1.5">Date To</label>
                    <input type="date" name="date_to" value="<?= htmlspecialchars(
                        $_GET["date_to"] ?? "",
                    ) ?>" class="lodge-input">
                </div>
                <div>
                    <label class="block text-[0.85rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-1.5">Room</label>
                    <input type="text" name="room" value="<?= htmlspecialchars(
                        $_GET["room"] ?? "",
                    ) ?>" placeholder="Room #" class="lodge-input w-24">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-lg font-semibold text-[1rem] uppercase tracking-wider" style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912">
                    Filter
                </button>
                <a href="index.php" class="px-4 py-2.5 rounded-lg text-[1rem] text-white/30 hover:text-white/50 border border-white/10 transition-colors">Clear</a>
                <a href="?export=csv" class="ml-auto px-5 py-2.5 rounded-lg font-semibold text-[1rem] uppercase tracking-wider border border-gold-400/30 text-gold-400/90 hover:text-gold-400 hover:border-gold-400/50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
            </form>
        </div>
        <!-- ═══ FEEDBACK TABLE ═══ -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
                <h2 class="font-serif text-gold-400/100 text-[1.25rem] tracking-wider uppercase">Guest Feedback</h2>
                <span class="text-white/20 text-[1rem]"><?= $totalCount ?> entries</span>
            </div>

            <?php if (empty($feedbacks)): ?>
                <div class="px-6 py-16 text-center">
                    <svg class="w-16 h-16 text-white/10 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-white/60 text-[1.125rem]">No feedback entries found.</p>
                    <p class="text-white/15 text-[1rem] mt-1">Feedback will appear here once guests submit the form.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-[1.125rem]">
                        <thead>
                            <tr class="border-b border-white/[0.06]">
                                <th class="px-5 py-3.5 text-left text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Date</th>
                                <th class="px-5 py-3.5 text-left text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Guest</th>
                                <th class="px-5 py-3.5 text-center text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Room</th>
                                <th class="px-5 py-3.5 text-center text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Rating</th>
                                <th class="px-5 py-3.5 text-left text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Purpose</th>
                                <th class="px-5 py-3.5 text-center text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Stay</th>
                                <th class="px-5 py-3.5 text-center text-[0.85rem] font-bold text-gold-400/90 uppercase tracking-[0.15em]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedbacks as $fb): ?>
                                <?php
                                $ratingInfo = ratingLabel(
                                    $fb["overall_rating"],
                                );
                                $stayDates = "";
                                if (
                                    !empty($fb["check_in"]) &&
                                    !empty($fb["check_out"])
                                ) {
                                    $stayDates =
                                        date(
                                            "M d",
                                            strtotime($fb["check_in"]),
                                        ) .
                                        " - " .
                                        date(
                                            "M d",
                                            strtotime($fb["check_out"]),
                                        );
                                }
                                ?>
                                <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors">
                                    <td class="px-5 py-3.5 text-white/70 text-[1rem] font-medium"><?= date(
                                        "M d, Y",
                                        strtotime($fb["created_at"]),
                                    ) ?></td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-white/70 font-medium"><?= !empty(
                                            $fb["guest_name"]
                                        )
                                            ? htmlspecialchars(
                                                $fb["guest_name"],
                                            )
                                            : '<span class="text-white/60 italic">Anonymous</span>' ?></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-md bg-pine-800/50 text-gold-400/90 text-[1rem] font-semibold"><?= htmlspecialchars(
                                            $fb["room_no"],
                                        ) ?></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="font-bold text-white/70"><?= $fb[
                                                "overall_rating"
                                            ] ?></span>
                                            <span class="text-[0.8rem] font-semibold uppercase <?= $ratingInfo[1] ?>"><?= $ratingInfo[0] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-white/70 text-[1rem] font-medium"><?= htmlspecialchars(
                                        $fb["purpose_of_stay"] ?: "—",
                                    ) ?></td>
                                    <td class="px-5 py-3.5 text-center text-white/70 text-[1rem] font-medium"><?= $stayDates ?:
                                        "—" ?></td>
                                    <td class="px-5 py-3.5 text-center">
                                        <a href="view.php?id=<?= $fb[
                                            "id"
                                        ] ?>" class="text-gold-400/90 hover:text-gold-400 transition-colors text-[1rem] font-semibold uppercase tracking-wider">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($totalPages > 1): ?>
                    <div class="px-5 py-4 border-t border-white/[0.06] flex items-center justify-between no-print">
                        <div class="text-[0.85rem] text-white/50">
                            Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalCount) ?> of <?= $totalCount ?> entries
                        </div>
                        <div class="flex gap-2">
                            <?php
                            // Preserve query string parameters for pagination links
                            $qs = $_GET;
                            unset($qs['page']);
                            $queryString = http_build_query($qs);
                            $queryString = $queryString ? '&' . $queryString : '';
                            ?>
                            
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="px-3 py-1.5 rounded-md bg-white/5 hover:bg-white/10 text-white/80 border border-white/10 transition-colors text-[0.85rem]">Previous</a>
                            <?php else: ?>
                                <span class="px-3 py-1.5 rounded-md bg-white/5 text-white/30 border border-white/[0.03] cursor-not-allowed text-[0.85rem]">Previous</span>
                            <?php endif; ?>
                            
                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            if ($startPage > 1) {
                                echo '<a href="?page=1' . $queryString . '" class="px-3 py-1.5 rounded-md bg-white/5 hover:bg-white/10 text-white/80 border border-white/10 transition-colors text-[0.85rem]">1</a>';
                                if ($startPage > 2) echo '<span class="px-2 text-white/30">...</span>';
                            }
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <a href="?page=<?= $i ?><?= $queryString ?>" class="px-3 py-1.5 rounded-md <?= $i === $page ? 'bg-gold-400/20 text-gold-400 border border-gold-400/30' : 'bg-white/5 hover:bg-white/10 text-white/80 border border-white/10 transition-colors' ?> text-[0.85rem]">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) echo '<span class="px-2 text-white/30">...</span>';
                                echo '<a href="?page=' . $totalPages . $queryString . '" class="px-3 py-1.5 rounded-md bg-white/5 hover:bg-white/10 text-white/80 border border-white/10 transition-colors text-[0.85rem]">' . $totalPages . '</a>';
                            }
                            ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="px-3 py-1.5 rounded-md bg-white/5 hover:bg-white/10 text-white/80 border border-white/10 transition-colors text-[0.85rem]">Next</a>
                            <?php else: ?>
                                <span class="px-3 py-1.5 rounded-md bg-white/5 text-white/30 border border-white/[0.03] cursor-not-allowed text-[0.85rem]">Next</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div><!-- /max-w-7xl -->

    <!-- Footer -->
    <footer class="text-center py-6 border-t border-white/[0.04] mt-8">
        <p class="text-gold-400/100 text-[0.8rem] uppercase tracking-[0.3em]">John Hay Hotels - Forest Wing Admin Panel</p>
    </footer>

    <script src="../js/auto_refresh.js"></script>
</body>
</html>