<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * DATABASE CONFIGURATION
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 */

// ─── Database credentials ───
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedback_form_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// ─── Admin credentials ───
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

/**
 * Get a PDO database connection.
 * Uses UTF-8 encoding and throws exceptions on errors.
 *
 * @return PDO
 */
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        die("Database connection failed. Please contact the administrator.");
    }
}
?>