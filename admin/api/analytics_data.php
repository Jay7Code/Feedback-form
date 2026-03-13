<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * API - ANALYTICS DATA
 * Serves aggregated JSON data for the Chart.js visualizations
 * in the Admin Analytics Dashboard (analytics.php).
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
$period = $_GET["period"] ?? "all";
$dateFilter = "";
switch ($period) {
    case "today":
        $dateFilter = "WHERE DATE(f.created_at) = CURDATE()";
        break;
    case "week":
        $dateFilter = "WHERE f.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case "month":
        $dateFilter =
            "WHERE f.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        break;
    case "quarter":
        $dateFilter =
            "WHERE f.created_at >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)";
        break;
    default:
        $dateFilter = "";
        break;
}

function toTenScale($val)
{
    $v = (float) $val;
    return $v > 0 ? round(($v * 10) / 3, 1) : 0;
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total_responses, ROUND(AVG(f.overall_rating),1) as avg_nps,
        ROUND(AVG(CASE WHEN foh.frontdesk>0 THEN foh.frontdesk END),2) as avg_frontdesk,
        ROUND(AVG(CASE WHEN foh.reservations>0 THEN foh.reservations END),2) as avg_reservations,
        ROUND(AVG(CASE WHEN foh.check_in_rating>0 THEN foh.check_in_rating END),2) as avg_check_in_rating,
        ROUND(AVG(CASE WHEN foh.check_out_rating>0 THEN foh.check_out_rating END),2) as avg_check_out_rating,
        ROUND(AVG(CASE WHEN foh.telephone_operator>0 THEN foh.telephone_operator END),2) as avg_telephone,
        ROUND(AVG(CASE WHEN foh.valet>0 THEN foh.valet END),2) as avg_valet,
        ROUND(AVG(CASE WHEN foh.housekeeping>0 THEN foh.housekeeping END),2) as avg_housekeeping,
        ROUND(AVG(CASE WHEN foh.accommodation>0 THEN foh.accommodation END),2) as avg_accommodation,
        ROUND(AVG(CASE WHEN foh.safety>0 THEN foh.safety END),2) as avg_safety,
        ROUND(AVG(CASE WHEN foh.security>0 THEN foh.security END),2) as avg_security,
        ROUND(AVG(CASE WHEN foh.friendliness>0 THEN foh.friendliness END),2) as avg_friendliness,
        ROUND(AVG(CASE WHEN foh.attentiveness>0 THEN foh.attentiveness END),2) as avg_attentiveness,
        ROUND(AVG(CASE WHEN foh.courteousness>0 THEN foh.courteousness END),2) as avg_courteousness,
        ROUND(AVG(CASE WHEN fnb.food_quality>0 THEN fnb.food_quality END),2) as avg_food_quality,
        ROUND(AVG(CASE WHEN fnb.serving_time>0 THEN fnb.serving_time END),2) as avg_serving_time,
        ROUND(AVG(CASE WHEN fnb.grooming>0 THEN fnb.grooming END),2) as avg_grooming,
        ROUND(AVG(CASE WHEN fnb.behavior>0 THEN fnb.behavior END),2) as avg_behavior,
        ROUND(AVG(CASE WHEN fnb.fnb_service>0 THEN fnb.fnb_service END),2) as avg_fnb_service,
        ROUND(AVG(CASE WHEN fnb.bar>0 THEN fnb.bar END),2) as avg_bar,
        ROUND(AVG(CASE WHEN fg.cleanliness>0 THEN fg.cleanliness END),2) as avg_cleanliness,
        ROUND(AVG(CASE WHEN fg.ambiance>0 THEN fg.ambiance END),2) as avg_ambiance,
        ROUND(AVG(CASE WHEN fg.comfort>0 THEN fg.comfort END),2) as avg_comfort,
        ROUND(AVG(CASE WHEN fg.bathroom>0 THEN fg.bathroom END),2) as avg_bathroom,
        MIN(f.created_at) as earliest, MAX(f.created_at) as latest
        FROM feedbacks f 
        LEFT JOIN feedback_foh foh ON f.id=foh.feedback_id 
        LEFT JOIN feedback_fnb fnb ON f.id=fnb.feedback_id 
        LEFT JOIN feedback_guestroom fg ON f.id=fg.feedback_id 
        $dateFilter");
    $summary = $stmt->fetch();

    $stmt = $pdo->query(
        "SELECT f.overall_rating as rating, COUNT(*) as count FROM feedbacks f $dateFilter GROUP BY f.overall_rating ORDER BY f.overall_rating",
    );
    $npsDistribution = [];
    for ($i = 1; $i <= 5; $i++) {
        $npsDistribution[$i] = 0;
    }
    while ($row = $stmt->fetch()) {
        if ($row["rating"] >= 1 && $row["rating"] <= 5) {
            $npsDistribution[(int) $row["rating"]] = (int) $row["count"];
        }
    }

    $stmt = $pdo->query(
        "SELECT DATE(f.created_at) as date, COUNT(*) as count FROM feedbacks f $dateFilter GROUP BY DATE(f.created_at) ORDER BY date",
    );
    $dailyVolume = $stmt->fetchAll();

    $stmt = $pdo->query(
        "SELECT CASE WHEN s.purpose_of_stay='' OR s.purpose_of_stay IS NULL THEN 'Not Specified' ELSE s.purpose_of_stay END as purpose, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id $dateFilter GROUP BY purpose ORDER BY count DESC",
    );
    $purposeBreakdown = $stmt->fetchAll();

    $stmt = $pdo->query(
        "SELECT CASE WHEN s.first_stay='Yes' THEN 'First Stay' WHEN s.first_stay='No' THEN 'Returning' ELSE 'Not Specified' END as type, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id $dateFilter GROUP BY type ORDER BY count DESC",
    );
    $firstStayData = $stmt->fetchAll();

    $stmt = $pdo->query(
        "SELECT CASE WHEN g.nationality='' OR g.nationality IS NULL THEN 'Not Specified' ELSE g.nationality END as nation, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id JOIN guests g ON s.guest_id=g.id $dateFilter GROUP BY nation ORDER BY count DESC",
    );
    $nationalityData = $stmt->fetchAll();

    $stmt = $pdo->query(
        "SELECT DATE(f.created_at) as date, ROUND(AVG(f.overall_rating),1) as avg_rating, COUNT(*) as count FROM feedbacks f $dateFilter GROUP BY DATE(f.created_at) ORDER BY date",
    );
    $npsTrend = $stmt->fetchAll();

    $response = [
        "summary" => [
            "total_responses" => (int) ($summary["total_responses"] ?? 0),
            "avg_nps" =>
                $summary["avg_nps"] !== null
                    ? (float) $summary["avg_nps"]
                    : null,
            "earliest" => $summary["earliest"] ?? null,
            "latest" => $summary["latest"] ?? null,
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
                "label" => "Check-in",
                "avg" => toTenScale($summary["avg_check_in_rating"] ?? 0),
            ],
            [
                "label" => "Check-out",
                "avg" => toTenScale($summary["avg_check_out_rating"] ?? 0),
            ],
            [
                "label" => "Telephone",
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
                "label" => "Friendliness",
                "avg" => toTenScale($summary["avg_friendliness"] ?? 0),
            ],
            [
                "label" => "Attentiveness",
                "avg" => toTenScale($summary["avg_attentiveness"] ?? 0),
            ],
            [
                "label" => "Courteousness",
                "avg" => toTenScale($summary["avg_courteousness"] ?? 0),
            ],
            [
                "label" => "Cleanliness",
                "avg" => toTenScale($summary["avg_cleanliness"] ?? 0),
            ],
            [
                "label" => "Ambiance",
                "avg" => toTenScale($summary["avg_ambiance"] ?? 0),
            ],
            [
                "label" => "Comfort",
                "avg" => toTenScale($summary["avg_comfort"] ?? 0),
            ],
            [
                "label" => "Bathroom",
                "avg" => toTenScale($summary["avg_bathroom"] ?? 0),
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
