<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * API - REPORT DATA
 * Serves JSON data for generating the printable summary reports
 * in the Admin Reports page (reports.php).
 * Requires admin authentication.
 * ═══════════════════════════════════════════════════════════════
 */
session_start();
require_once "../../config.php";
header("Content-Type: application/json");
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}
$pdo = getDBConnection();
$dateFrom = $_GET["date_from"] ?? "";
$dateTo = $_GET["date_to"] ?? "";
if (empty($dateFrom) || empty($dateTo)) {
    http_response_code(400);
    echo json_encode(["error" => "date_from and date_to are required"]);
    exit();
}
$dateFrom = preg_replace("/[^0-9\-]/", "", $dateFrom);
$dateTo = preg_replace("/[^0-9\-]/", "", $dateTo);
$dateFilter =
    "WHERE DATE(created_at) >= :date_from AND DATE(created_at) <= :date_to";
$params = [":date_from" => $dateFrom, ":date_to" => $dateTo];

function toTenScale($val)
{
    $v = (float) $val;
    return $v > 0 ? round(($v * 10) / 3, 1) : 0;
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_responses, ROUND(AVG(overall_rating),2) as avg_nps,
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
        ROUND(AVG(CASE WHEN bartender>0 THEN bartender END),2) as avg_bartender
        FROM feedbacks $dateFilter");
    $stmt->execute($params);
    $summary = $stmt->fetch();

    $stmt = $pdo->prepare(
        "SELECT overall_rating as rating, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY overall_rating ORDER BY overall_rating",
    );
    $stmt->execute($params);
    $npsDist = [];
    for ($i = 1; $i <= 10; $i++) {
        $npsDist[$i] = 0;
    }
    while ($row = $stmt->fetch()) {
        if ($row["rating"] >= 1 && $row["rating"] <= 10) {
            $npsDist[(int) $row["rating"]] = (int) $row["count"];
        }
    }
    $promoters = ($npsDist[9] ?? 0) + ($npsDist[10] ?? 0);
    $passives = ($npsDist[7] ?? 0) + ($npsDist[8] ?? 0);
    $detractors = 0;
    for ($i = 1; $i <= 6; $i++) {
        $detractors += $npsDist[$i] ?? 0;
    }

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN purpose_of_stay='' OR purpose_of_stay IS NULL THEN 'Not Specified' ELSE purpose_of_stay END as purpose, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY purpose ORDER BY count DESC",
    );
    $stmt->execute($params);
    $purposeBreakdown = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN first_stay='Yes' THEN 'First Stay' WHEN first_stay='No' THEN 'Returning' ELSE 'Not Specified' END as type, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY type ORDER BY count DESC",
    );
    $stmt->execute($params);
    $firstStayData = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN nationality='' OR nationality IS NULL THEN 'Not Specified' ELSE nationality END as nation, COUNT(*) as count FROM feedbacks $dateFilter GROUP BY nation ORDER BY count DESC",
    );
    $stmt->execute($params);
    $nationalityBreakdown = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT guest_name, room_no, overall_rating, frontdesk_comments, fnb_comments, suggestions_future, other_comments, helpful_staff_names, created_at FROM feedbacks $dateFilter ORDER BY created_at DESC LIMIT 20",
    );
    $stmt->execute($params);
    $comments = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT helpful_staff_names FROM feedbacks $dateFilter",
    );
    $stmt->execute($params);
    $staffCounts = [];
    while ($row = $stmt->fetch()) {
        $raw = trim($row["helpful_staff_names"] ?? "");
        if ($raw !== "") {
            $parts = preg_split("/(,|\band\b|&)/i", $raw);
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part !== "") {
                    $key = strtolower($part);
                    if (!isset($staffCounts[$key])) {
                        $staffCounts[$key] = [
                            "name" => ucwords($part),
                            "count" => 0,
                        ];
                    }
                    $staffCounts[$key]["count"]++;
                }
            }
        }
    }
    usort($staffCounts, function ($a, $b) {
        return $b["count"] <=> $a["count"];
    });
    $recognizedStaff = array_slice($staffCounts, 0, 10);

    $stmt = $pdo->prepare(
        "SELECT DATE(created_at) as date, COUNT(*) as count, ROUND(AVG(overall_rating),1) as avg_rating FROM feedbacks $dateFilter GROUP BY DATE(created_at) ORDER BY date",
    );
    $stmt->execute($params);
    $dailyBreakdown = $stmt->fetchAll();

    $response = [
        "date_from" => $dateFrom,
        "date_to" => $dateTo,
        "summary" => [
            "total_responses" => (int) ($summary["total_responses"] ?? 0),
            "avg_nps" =>
                $summary["avg_nps"] !== null
                    ? (float) $summary["avg_nps"]
                    : null,
            "promoters" => $promoters,
            "passives" => $passives,
            "detractors" => $detractors,
        ],
        "front_of_house" => [
            [
                "label" => "Front Desk",
                "avg" => toTenScale($summary["avg_frontdesk"] ?? 0),
            ],
            [
                "label" => "Reservations",
                "avg" => toTenScale($summary["avg_reservations"] ?? 0),
            ],
            [
                "label" => "Telephone Operator",
                "avg" => toTenScale($summary["avg_telephone"] ?? 0),
            ],
            [
                "label" => "Valet",
                "avg" => toTenScale($summary["avg_valet"] ?? 0),
            ],
            [
                "label" => "Housekeeping",
                "avg" => toTenScale($summary["avg_housekeeping"] ?? 0),
            ],
            [
                "label" => "Accommodation",
                "avg" => toTenScale($summary["avg_accommodation"] ?? 0),
            ],
            [
                "label" => "Safety",
                "avg" => toTenScale($summary["avg_safety"] ?? 0),
            ],
            [
                "label" => "Security",
                "avg" => toTenScale($summary["avg_security"] ?? 0),
            ],
            [
                "label" => "Overall Service",
                "avg" => toTenScale($summary["avg_overall_service"] ?? 0),
            ],
        ],
        "food_beverage" => [
            [
                "label" => "Food Quality",
                "avg" => toTenScale($summary["avg_food_quality"] ?? 0),
            ],
            [
                "label" => "Serving Time",
                "avg" => toTenScale($summary["avg_serving_time"] ?? 0),
            ],
            [
                "label" => "Wait Staff",
                "avg" => toTenScale($summary["avg_wait_staff"] ?? 0),
            ],
            [
                "label" => "Grooming",
                "avg" => toTenScale($summary["avg_grooming"] ?? 0),
            ],
            [
                "label" => "Behavior",
                "avg" => toTenScale($summary["avg_behavior"] ?? 0),
            ],
            [
                "label" => "Service",
                "avg" => toTenScale($summary["avg_fnb_service"] ?? 0),
            ],
            ["label" => "Bar", "avg" => toTenScale($summary["avg_bar"] ?? 0)],
            [
                "label" => "Bartender",
                "avg" => toTenScale($summary["avg_bartender"] ?? 0),
            ],
        ],
        "nps_distribution" => $npsDist,
        "purpose_breakdown" => $purposeBreakdown,
        "first_stay" => $firstStayData,
        "nationality_breakdown" => $nationalityBreakdown,
        "comments" => $comments,
        "recognized_staff" => $recognizedStaff,
        "daily_breakdown" => $dailyBreakdown,
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
