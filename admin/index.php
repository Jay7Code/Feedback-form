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

$pdo = getDBConnection();

// ─── Handle CSV Export ───
if (isset($_GET["export"]) && $_GET["export"] === "csv") {
    $stmt = $pdo->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
    $rows = $stmt->fetchAll();

    header("Content-Type: text/csv");
    header(
        'Content-Disposition: attachment; filename="feedback_export_' .
            date("Y-m-d") .
            '.csv"',
    );

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

if (!empty($_GET["date_from"])) {
    $where[] = "DATE(created_at) >= :date_from";
    $params[":date_from"] = $_GET["date_from"];
}
if (!empty($_GET["date_to"])) {
    $where[] = "DATE(created_at) <= :date_to";
    $params[":date_to"] = $_GET["date_to"];
}
if (!empty($_GET["room"])) {
    $where[] = "room_no = :room";
    $params[":room"] = $_GET["room"];
}
if (!empty($_GET["search"])) {
    $where[] =
        "(guest_name LIKE :search OR email LIKE :search2 OR room_no LIKE :search3)";
    $params[":search"] = "%" . $_GET["search"] . "%";
    $params[":search2"] = "%" . $_GET["search"] . "%";
    $params[":search3"] = "%" . $_GET["search"] . "%";
}

$whereSQL = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// ─── Stats ───
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM feedbacks $whereSQL");
$totalStmt->execute($params);
$totalCount = $totalStmt->fetchColumn();

$avgStmt = $pdo->prepare(
    "SELECT ROUND(AVG(overall_rating), 1) FROM feedbacks $whereSQL",
);
$avgStmt->execute($params);
$avgRating = $avgStmt->fetchColumn() ?: "—";

$latestStmt = $pdo->prepare("SELECT MAX(created_at) FROM feedbacks $whereSQL");
$latestStmt->execute($params);
$latestDate = $latestStmt->fetchColumn();
$latestFormatted = $latestDate ? date("M d, Y", strtotime($latestDate)) : "—";

// ─── Feedback list ───
$listStmt = $pdo->prepare(
    "SELECT id, guest_name, room_no, overall_rating, purpose_of_stay, check_in, check_out, created_at FROM feedbacks $whereSQL ORDER BY created_at DESC LIMIT 200",
);
$listStmt->execute($params);
$feedbacks = $listStmt->fetchAll();

// Rating label helper
function ratingLabel($val)
{
    if ($val >= 9) {
        return ["Excellent", "text-emerald-400"];
    }
    if ($val >= 7) {
        return ["Good", "text-green-400"];
    }
    if ($val >= 5) {
        return ["Average", "text-yellow-400"];
    }
    if ($val >= 3) {
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
        .nav-link { padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s ease; }
        .nav-link:hover { background: rgba(201,169,110,0.08); color: rgba(255,255,255,0.7); }
        .nav-link.active { background: rgba(201,169,110,0.12); color: #C9A96E; }
    </style>
</head>
<body class="font-sans text-white min-h-screen">
    <!-- ═══ TOP NAV BAR ═══ -->
    <nav class="border-b border-white/[0.06] px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="font-script text-[2.125rem] text-white/70">John Hay Hotels</h1>
                <span class="text-[0.8rem] font-bold text-gold-400/80 uppercase tracking-[0.2em] px-3 py-1 rounded-full border border-gold-400/20">Admin</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="index.php" class="nav-link active">Dashboard</a>
                <a href="analytics.php" class="nav-link text-white/40">Analytics</a>
                <a href="reports.php" class="nav-link text-white/40">Reports</a>
                <span class="text-white/10 mx-2">|</span>
                <span class="text-white/30 text-[1.125rem]">Welcome, <span class="text-gold-400/90"><?= htmlspecialchars(
                    $_SESSION["admin_username"] ?? "Admin",
                ) ?></span></span>
                <a href="logout.php" class="text-[1.125rem] text-white/30 hover:text-red-400/70 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
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
                <p class="text-[2.125rem] font-bold text-white/80"><?= $avgRating ?><span class="text-[1.375rem] text-white/30">/10</span></p>
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
            <?php endif; ?>
        </div>

    </div><!-- /max-w-7xl -->

    <!-- Footer -->
    <footer class="text-center py-6 border-t border-white/[0.04] mt-8">
        <p class="text-gold-400/100 text-[0.8rem] uppercase tracking-[0.3em]">John Hay Hotels - Forest Wing Admin Panel</p>
    </footer>

</body>
</html>