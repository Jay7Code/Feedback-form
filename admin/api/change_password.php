<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN CHANGE PASSWORD API
 * Allows administrators to update their own password, 
 * typically used for forced changes after a reset.
 * ═══════════════════════════════════════════════════════════════
 */

session_start();
require_once "../../config.php";

header("Content-Type: application/json");

// 1. Auth Check
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$mysqli = getDBConnection();

// 2. Handle POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin_id = $_SESSION["admin_id"];
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Validation
    if (empty($new_password)) {
        echo json_encode(["error" => "Password is required."]);
        exit();
    }

    if (strlen($new_password) < 6) {
        echo json_encode(["error" => "Password must be at least 6 characters."]);
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(["error" => "Passwords do not match."]);
        exit();
    }

    try {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password and clear the flag
        $stmt = $mysqli->prepare(
            "UPDATE admins SET password = ?, must_change_password = 0 WHERE id = ?"
        );
        $stmt->bind_param("si", $hashedPassword, $admin_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $check = $mysqli->prepare("SELECT id FROM admins WHERE id = ?");
            $check->bind_param("i", $admin_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                echo json_encode(["error" => "Admin account not found."]);
                exit();
            }
        }

        // Update Session
        $_SESSION["admin_must_change_password"] = false;

        echo json_encode([
            "success" => true,
            "message" => "Password updated successfully."
        ]);
    } catch (Exception $e) {
        error_log("Change password error: " . $e->getMessage());
        echo json_encode(["error" => "Failed to update password."]);
    }
    exit();
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
?>
