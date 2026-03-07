<?php
session_start();
require_once "../../config.php";
header("Content-Type: application/json");
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}
$pdo = getDBConnection();
$period = $_GET["period"] ?? "all";
$dateFilter = "";
switch ($period) {
    case "today": $dateFilter = "WHERE DATE(created_at) = CURDATE()"; break;
    case "week": $dateFilter = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"; break;
    case "month": $dateFilter = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"; break;
    case "quarter": $dateFilter = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)"; break;
    default: $dateFilter = ""; break;
}

function toTenScale($val) {
    $v = (float) $val;
    return $v > 0 ? round($v * 10 / 3, 1) : 0;
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total_responses, ROUND(AVG(overall_rating),1) as avg_nps,
        ROUND(AVG(CASE WHEN frontdesk>0 THEN frontdesk END),2) as avg_frontdesk,
        ROUND(AVG(CASE WHEN reservations>0 THEN reservations END),2) as avg_reservations,
        ROUND(AVG(CASE WHEN telephone_operator>0 THEN telephone_operator END),2) as avg_telephone,
        ROUND(AVG(CASE WHEN valet>0 THEN valet END),2) as avg_valet,
        ROUND(AVG(CASE WHEN housekeeping>0 THEN housekeeping END),2) as avg_housekeeping,
        ROUND(AVG(CASE WHEN accommodation>0 THEN accommodation END),2) as avg_accommodation,
        ROUND(AVG(CASE WHEN safety>0 THEN safety END),2) as avg_safety,
        ROUND(AVG(CASE WHEN security>0 THEN security END),2) as avg_security,
        ROUND(AVG(CASE WHEN overall_service>0 THEN overall_service END),2) as avg_overall_service,
        ROUND(AVG(CASE WHEN food_quality>0 THEN food_quality END),2) as avg_food_quality,
        ROUND(AVG(CASE WHEN serving_time>0 THEN serving_time END),2) as avg_serving_time,
        ROUND(AVG(CASE WHEN wait_staff>0 THEN wait_staff END),2) as avg_wait_staff,
        ROUND(AVG(CASE WHEN grooming>0 THEN grooming END),2) as avg_grooming,
        ROUND(AVG(CASE WHEN behavior>0 THEN behavior END),2) as avg_behavior,
        ROUND(AVG(CASE WHEN fnb_service>0 THEN fnb_service END),2) as avg_fnb_service,
        ROUND(AVG(CASE WHEN bar>0 THEN bar END),2) as avg_bar,
        ROUND(AVG(CASE WHEN bartender>0 THEN bartender END),2) as avg_bartender,
        MIN(created_at) as earliest, MAX(created_at) as latest
        FROM feedbacks $dateFilter");
    $summary = $stmt->fetch();

    $stmt = $pdo->query("SELECT overall_rating as rating, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY overall_rating ORDER BY overall_rating");
    $npsDistribution = [];
    for ($i = 1; $i <= 10; $i++) { $npsDistribution[$i] = 0; }
    while ($row = $stmt->fetch()) {
        if ($row["rating"] >= 1 && $row["rating"] <= 10) {
            $npsDistribution[(int) $row["rating"]] = (int) $row["count"];
        }
    }

    $stmt = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY DATE(created_at) ORDER BY date");
    $dailyVolume = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT CASE WHEN purpose_of_stay='' OR purpose_of_stay IS NULL THEN 'Not Specified' ELSE purpose_of_stay END as purpose, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY purpose ORDER BY count DESC");
    $purposeBreakdown = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT CASE WHEN first_stay='Yes' THEN 'First Stay' WHEN first_stay='No' THEN 'Returning' ELSE 'Not Specified' END as type, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY type ORDER BY count DESC");
    $firstStayData = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT CASE WHEN nationality='' OR nationality IS NULL THEN 'Not Specified' ELSE nationality END as nation, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY nation ORDER BY count DESC");
    $nationalityData = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT DATE(created_at) as date, ROUND(AVG(overall_rating),1) as avg_rating, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY DATE(created_at) ORDER BY date");
    $npsTrend = $stmt->fetchAll();

    $response = [
        "summary" => [
            "total_responses" => (int) ($summary["total_responses"] ?? 0),
            "avg_nps" => $summary["avg_nps"] !== null ? (float) $summary["avg_nps"] : null,
            "earliest" => $summary["earliest"] ?? null,
            "latest" => $summary["latest"] ?? null,
        ],
        "front_of_house" => [
            ["label" => "Front Desk", "avg" => toTenScale($summary["avg_frontdesk"] ?? 0)],
            ["label" => "Reservations", "avg" => toTenScale($summary["avg_reservations"] ?? 0)],
            ["label" => "Telephone", "avg" => toTenScale($summary["avg_telephone"] ?? 0)],
            ["label" => "Valet", "avg" => toTenScale($summary["avg_valet"] ?? 0)],
            ["label" => "Housekeeping", "avg" => toTenScale($summary["avg_housekeeping"] ?? 0)],
            ["label" => "Accommodation", "avg" => toTenScale($summary["avg_accommodation"] ?? 0)],
            ["label" => "Safety", "avg" => toTenScale($summary["avg_safety"] ?? 0)],
            ["label" => "Security", "avg" => toTenScale($summary["avg_security"] ?? 0)],
            ["label" => "Overall Service", "avg" => toTenScale($summary["avg_overall_service"] ?? 0)],
        ],
        "food_beverage" => [
            ["label" => "Food Quality", "avg" => toTenScale($summary["avg_food_quality"] ?? 0)],
            ["label" => "Serving Time", "avg" => toTenScale($summary["avg_serving_time"] ?? 0)],
            ["label" => "Wait Staff", "avg" => toTenScale($summary["avg_wait_staff"] ?? 0)],
            ["label" => "Grooming", "avg" => toTenScale($summary["avg_grooming"] ?? 0)],
            ["label" => "Behavior", "avg" => toTenScale($summary["avg_behavior"] ?? 0)],
            ["label" => "Service", "avg" => toTenScale($summary["avg_fnb_service"] ?? 0)],
            ["label" => "Bar", "avg" => toTenScale($summary["avg_bar"] ?? 0)],
            ["label" => "Bartender", "avg" => toTenScale($summary["avg_bartender"] ?? 0)],
        ],
        "nps_distribution" => $npsDistribution,
        "daily_volume" => $dailyVolume,
        "purpose_breakdown" => $purposeBreakdown,
        "first_stay" => $firstStayData,
        "nationality_data" => $nationalityData,
        "nps_trend" => $npsTrend,
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}