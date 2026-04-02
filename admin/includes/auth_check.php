<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN AUTHENTICATION CHECK
 * Included in all admin-only pages to verify session and 
 * enforce mandatory password changes.
 * ═══════════════════════════════════════════════════════════════
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Basic Login Check
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}

// 2. Specific Page Check (Optional - prevent looping)
$currentPage = basename($_SERVER['PHP_SELF']);

// 3. Force Password Change Check
if (
    isset($_SESSION["admin_must_change_password"]) && 
    $_SESSION["admin_must_change_password"] === true &&
    $currentPage !== 'index.php' &&
    $currentPage !== 'logout.php' &&
    $currentPage !== 'login.php'
) {
    header("Location: index.php");
    exit();
}
?>
