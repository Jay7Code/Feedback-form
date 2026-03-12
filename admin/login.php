<?php
/**
 * ADMIN LOGIN PAGE
 * John Hay Hotels - Forest Wing Guest Feedback System
 */
session_start();
require_once "../config.php";

if (
    isset($_SESSION["admin_logged_in"]) &&
    $_SESSION["admin_logged_in"] === true
) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (!empty($username) && !empty($password)) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare(
                "SELECT id, username, password, full_name, is_active FROM admins WHERE username = :username LIMIT 1",
            );
            $stmt->execute([":username" => $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin["password"])) {
                if ($admin["is_active"] == 1) {
                    $_SESSION["admin_logged_in"] = true;
                    $_SESSION["show_welcome_modal"] = true;
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];
                    $_SESSION["admin_full_name"] = $admin["full_name"];
                    header("Location: index.php");
                    exit();
                } else {
                    $error =
                        "Your account has been deactivated. Please contact the Super Admin.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "A system error occurred. Please try again.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - John Hay Hotels</title>
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
        .scene-bg { position: fixed; inset: 0; z-index: 0; background: url('../img/forest-bg.jpg') center/cover no-repeat; }
        .scene-bg::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,25,18,0.7) 0%, rgba(10,25,18,0.5) 50%, rgba(10,25,18,0.8) 100%); }
        .glass-card { background: rgba(245,235,224,0.08); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(201,169,110,0.15); box-shadow: 0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(201,169,110,0.1); }
        .lodge-input { width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid rgba(201,169,110,0.2); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); font-family: 'Inter', sans-serif; font-size: 0.9rem; transition: all 0.3s ease; outline: none; }
        .lodge-input::placeholder { color: rgba(255,255,255,0.3); }
        .lodge-input:focus { border-color: #C9A96E; background: rgba(255,255,255,0.1); box-shadow: 0 0 0 3px rgba(201,169,110,0.1); }
        @keyframes fadeUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .fade-up { opacity: 0; animation: fadeUp 0.6s ease-out forwards; }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center text-white relative">
    <div class="scene-bg"></div>
    <div class="relative z-10 w-full max-w-md px-6">
        <div class="text-center mb-10 fade-up">
            <h1 class="font-script text-[3.25rem] text-white/80 mb-2">John Hay Hotels</h1>
            <div class="flex items-center justify-center gap-3">
                <span class="w-10 h-px bg-gold-400/40"></span>
                <span class="text-gold-400/90 text-[0.85rem] font-semibold tracking-[0.3em] uppercase">Admin Panel</span>
                <span class="w-10 h-px bg-gold-400/40"></span>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-8 fade-up" style="animation-delay: 0.2s;">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-gold-400/10 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="font-serif text-[1.5rem] text-white/80 tracking-wide">Sign In</h2>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-900/20 border border-red-400/20 rounded-xl px-4 py-3 mb-6 text-center">
                    <p class="text-red-400/80 text-[1.125rem]"><?= htmlspecialchars(
                        $error,
                    ) ?></p>
                </div>
            <?php endif; ?>
            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-[0.9rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-2">Username</label>
                    <input type="text" name="username" required autofocus placeholder="Enter username" class="lodge-input">
                </div>
                <div>
                    <label class="block text-[0.9rem] font-semibold text-gold-400/90 uppercase tracking-[0.15em] mb-2">Password</label>
                    <input type="password" name="password" required placeholder="Enter password" class="lodge-input">
                </div>
                <button type="submit" class="w-full py-3.5 rounded-full font-semibold text-[1.125rem] uppercase tracking-[0.15em] transition-all duration-300 hover:shadow-lg" style="background: linear-gradient(135deg, #C9A96E, #b5893a); color: #0A1912;">Sign In</button>
            </form>
        </div>
        <p class="text-center text-white/15 text-[0.85rem] uppercase tracking-[0.3em] mt-8 fade-up" style="animation-delay: 0.4s;">Forest Wing - Camp John Hay - Baguio City, 2600</p>
    </div>
</body>
</html>
