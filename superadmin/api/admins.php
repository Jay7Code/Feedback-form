<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * SUPER ADMIN API — Manage Admin Accounts
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 *
 * GET              — List all admins
 * POST action=create       — Create new admin
 * POST action=toggle_status — Activate/deactivate admin
 * POST action=reset_password — Reset admin password
 */
session_start();
require_once "../../config.php";

header("Content-Type: application/json");

// Auth check
if (
    !isset($_SESSION["superadmin_logged_in"]) ||
    $_SESSION["superadmin_logged_in"] !== true
) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$pdo = getDBConnection();

// ─── GET: List all admins ───
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $stmt = $pdo->query(
            "SELECT id, username, full_name, is_active, created_at, updated_at FROM admins ORDER BY created_at DESC",
        );
        $admins = $stmt->fetchAll();
        echo json_encode(["success" => true, "admins" => $admins]);
    } catch (PDOException $e) {
        error_log("API error: " . $e->getMessage());
        echo json_encode(["error" => "Failed to fetch admins"]);
    }
    exit();
}

// ─── POST: Actions ───
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    switch ($action) {
        // ── Create new admin ──
        case "create":
            $username = trim($_POST["username"] ?? "");
            $password = trim($_POST["password"] ?? "");
            $full_name = trim($_POST["full_name"] ?? "");

            if (empty($username) || empty($password)) {
                echo json_encode([
                    "error" => "Username and password are required.",
                ]);
                exit();
            }
            if (strlen($password) < 6) {
                echo json_encode([
                    "error" => "Password must be at least 6 characters.",
                ]);
                exit();
            }
            if (strlen($username) < 3) {
                echo json_encode([
                    "error" => "Username must be at least 3 characters.",
                ]);
                exit();
            }

            // Check if username already exists
            $check = $pdo->prepare(
                "SELECT COUNT(*) FROM admins WHERE username = :username",
            );
            $check->execute([":username" => $username]);
            if ($check->fetchColumn() > 0) {
                echo json_encode(["error" => "Username already exists."]);
                exit();
            }

            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare(
                    "INSERT INTO admins (username, password, full_name, is_active) VALUES (:username, :password, :full_name, 1)",
                );
                $stmt->execute([
                    ":username" => $username,
                    ":password" => $hashedPassword,
                    ":full_name" => $full_name ?: $username,
                ]);
                echo json_encode([
                    "success" => true,
                    "message" => "Admin account created successfully.",
                ]);
            } catch (PDOException $e) {
                error_log("Create admin error: " . $e->getMessage());
                echo json_encode([
                    "error" => "Failed to create admin account.",
                ]);
            }
            break;

        // ── Toggle active status ──
        case "toggle_status":
            $adminId = intval($_POST["admin_id"] ?? 0);
            if ($adminId <= 0) {
                echo json_encode(["error" => "Invalid admin ID."]);
                exit();
            }

            try {
                // Get current status
                $stmt = $pdo->prepare(
                    "SELECT is_active FROM admins WHERE id = :id",
                );
                $stmt->execute([":id" => $adminId]);
                $admin = $stmt->fetch();

                if (!$admin) {
                    echo json_encode(["error" => "Admin not found."]);
                    exit();
                }

                $newStatus = $admin["is_active"] == 1 ? 0 : 1;
                $update = $pdo->prepare(
                    "UPDATE admins SET is_active = :status WHERE id = :id",
                );
                $update->execute([":status" => $newStatus, ":id" => $adminId]);

                $statusText = $newStatus == 1 ? "activated" : "deactivated";
                echo json_encode([
                    "success" => true,
                    "message" => "Admin account $statusText.",
                    "new_status" => $newStatus,
                ]);
            } catch (PDOException $e) {
                error_log("Toggle status error: " . $e->getMessage());
                echo json_encode(["error" => "Failed to update admin status."]);
            }
            break;

        // ── Reset password ──
        case "reset_password":
            $adminId = intval($_POST["admin_id"] ?? 0);
            $newPassword = trim($_POST["new_password"] ?? "");

            if ($adminId <= 0) {
                echo json_encode(["error" => "Invalid admin ID."]);
                exit();
            }
            if (strlen($newPassword) < 6) {
                echo json_encode([
                    "error" => "Password must be at least 6 characters.",
                ]);
                exit();
            }

            try {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare(
                    "UPDATE admins SET password = :password WHERE id = :id",
                );
                $stmt->execute([
                    ":password" => $hashedPassword,
                    ":id" => $adminId,
                ]);

                if ($stmt->rowCount() === 0) {
                    echo json_encode(["error" => "Admin not found."]);
                } else {
                    echo json_encode([
                        "success" => true,
                        "message" => "Password reset successfully.",
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Reset password error: " . $e->getMessage());
                echo json_encode(["error" => "Failed to reset password."]);
            }
            break;

        default:
            echo json_encode(["error" => "Unknown action."]);
    }
    exit();
}

// Other methods
http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
?>
