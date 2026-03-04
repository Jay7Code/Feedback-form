<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * DATABASE SETUP SCRIPT
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 *
 * Run this script ONCE to create the database and feedbacks table.
 * Access it via: http://localhost/Feedback-form/setup_database.php
 * ═══════════════════════════════════════════════════════════════
 */

// ─── Database credentials (same as config.php) ───
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'feedback_form_db';

try {
    // Connect without database selected
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");

    // Create feedbacks table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `feedbacks` (
            `id`                    INT AUTO_INCREMENT PRIMARY KEY,

            -- Front of House ratings (1=Poor, 2=Good, 3=Excellent, 0=Not rated)
            `frontdesk`             TINYINT NOT NULL DEFAULT 0,
            `reservations`          TINYINT NOT NULL DEFAULT 0,
            `telephone_operator`    TINYINT NOT NULL DEFAULT 0,
            `valet`                 TINYINT NOT NULL DEFAULT 0,
            `housekeeping`          TINYINT NOT NULL DEFAULT 0,
            `accommodation`         TINYINT NOT NULL DEFAULT 0,
            `safety`                TINYINT NOT NULL DEFAULT 0,
            `security`              TINYINT NOT NULL DEFAULT 0,
            `overall_service`       TINYINT NOT NULL DEFAULT 0,
            `frontdesk_comments`    TEXT,

            -- Food & Beverage ratings
            `food_quality`          TINYINT NOT NULL DEFAULT 0,
            `serving_time`          TINYINT NOT NULL DEFAULT 0,
            `wait_staff`            TINYINT NOT NULL DEFAULT 0,
            `grooming`              TINYINT NOT NULL DEFAULT 0,
            `behavior`              TINYINT NOT NULL DEFAULT 0,
            `fnb_service`           TINYINT NOT NULL DEFAULT 0,
            `bar`                   TINYINT NOT NULL DEFAULT 0,
            `bartender`             TINYINT NOT NULL DEFAULT 0,
            `fnb_comments`          TEXT,
            `helpful_staff_names`   VARCHAR(500),

            -- Overall experience (1-10 NPS)
            `overall_rating`        TINYINT NOT NULL DEFAULT 0,

            -- Additional comments
            `suggestions_future`    TEXT,
            `other_comments`        TEXT,

            -- Guest information
            `first_stay`            VARCHAR(10),
            `purpose_of_stay`       VARCHAR(100),
            `other_purpose_text`    VARCHAR(255),
            `guest_name`            VARCHAR(255),
            `email`                 VARCHAR(255),
            `address`               TEXT,
            `contact_no`            VARCHAR(50),
            `room_no`               VARCHAR(20) NOT NULL,
            `check_in`              DATE,
            `check_out`             DATE,

            -- Metadata
            `created_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            INDEX `idx_created_at` (`created_at`),
            INDEX `idx_room_no` (`room_no`),
            INDEX `idx_overall_rating` (`overall_rating`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    echo "<!DOCTYPE html><html><head><title>Setup Complete</title>";
    echo "<script src='https://cdn.tailwindcss.com'></script>";
    echo "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap' rel='stylesheet'>";
    echo "</head><body class='bg-[#0A1912] text-white font-[Inter] flex items-center justify-center min-h-screen'>";
    echo "<div class='text-center max-w-md'>";
    echo "<div class='w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center' style='background:linear-gradient(135deg,#C9A96E,#b5893a)'>";
    echo "<svg class='w-10 h-10' fill='none' stroke='white' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M5 13l4 4L19 7'/></svg></div>";
    echo "<h1 class='text-2xl font-semibold mb-3' style='color:#C9A96E'>Database Setup Complete!</h1>";
    echo "<p class='text-white/50 mb-6'>The database <strong class='text-white/70'>$dbName</strong> and <strong class='text-white/70'>feedbacks</strong> table have been created successfully.</p>";
    echo "<div class='space-y-3'>";
    echo "<a href='index.php' class='block px-6 py-3 rounded-full font-semibold text-sm uppercase tracking-wider' style='background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912'>Go to Feedback Form</a>";
    echo "<a href='admin/login.php' class='block px-6 py-3 rounded-full font-semibold text-sm uppercase tracking-wider border border-[#C9A96E]/30 text-[#C9A96E]/70 hover:text-[#C9A96E]'>Go to Admin Panel</a>";
    echo "</div></div></body></html>";

} catch (PDOException $e) {
    echo "<!DOCTYPE html><html><head><title>Setup Error</title>";
    echo "<script src='https://cdn.tailwindcss.com'></script>";
    echo "</head><body class='bg-[#0A1912] text-white flex items-center justify-center min-h-screen'>";
    echo "<div class='text-center max-w-md'>";
    echo "<div class='w-20 h-20 mx-auto mb-6 rounded-full bg-red-900/30 flex items-center justify-center'>";
    echo "<svg class='w-10 h-10 text-red-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg></div>";
    echo "<h1 class='text-2xl font-semibold text-red-400 mb-3'>Setup Failed</h1>";
    echo "<p class='text-white/50 mb-2'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='text-white/30 text-sm'>Make sure MySQL is running in XAMPP.</p>";
    echo "</div></body></html>";
}
?>