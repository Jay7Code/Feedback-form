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
    "WHERE DATE(f.created_at) >= :date_from AND DATE(f.created_at) <= :date_to";
$params = [":date_from" => $dateFrom, ":date_to" => $dateTo];

function toTenScale($val)
{
    $v = (float) $val;
    return $v > 0 ? round(($v * 10) / 3, 1) : 0;
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_responses, ROUND(AVG(overall_rating),2) as avg_nps,
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
        ROUND(AVG(CASE WHEN fg.bathroom>0 THEN fg.bathroom END),2) as avg_bathroom
        FROM feedbacks f 
        LEFT JOIN feedback_foh foh ON f.id=foh.feedback_id 
        LEFT JOIN feedback_fnb fnb ON f.id=fnb.feedback_id 
        LEFT JOIN feedback_guestroom fg ON f.id=fg.feedback_id 
        $dateFilter");
    $stmt->execute($params);
    $summary = $stmt->fetch();

    $stmt = $pdo->prepare(
        "SELECT f.overall_rating as rating, COUNT(*) as count FROM feedbacks f $dateFilter GROUP BY f.overall_rating ORDER BY f.overall_rating",
    );
    $stmt->execute($params);
    $npsDist = [];
    for ($i = 1; $i <= 5; $i++) {
        $npsDist[$i] = 0;
    }
    while ($row = $stmt->fetch()) {
        if ($row["rating"] >= 1 && $row["rating"] <= 5) {
            $npsDist[(int) $row["rating"]] = (int) $row["count"];
        }
    }
    $excellent = ($npsDist[4] ?? 0) + ($npsDist[5] ?? 0);
    $good = ($npsDist[3] ?? 0);
    $poor = ($npsDist[1] ?? 0) + ($npsDist[2] ?? 0);

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN s.purpose_of_stay='' OR s.purpose_of_stay IS NULL THEN 'Not Specified' ELSE s.purpose_of_stay END as purpose, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id $dateFilter GROUP BY purpose ORDER BY count DESC",
    );
    $stmt->execute($params);
    $purposeBreakdown = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN s.first_stay='Yes' THEN 'First Stay' WHEN s.first_stay='No' THEN 'Returning' ELSE 'Not Specified' END as type, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id $dateFilter GROUP BY type ORDER BY count DESC",
    );
    $stmt->execute($params);
    $firstStayData = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT CASE WHEN g.nationality='' OR g.nationality IS NULL THEN 'Not Specified' ELSE g.nationality END as nation, COUNT(*) as count FROM feedbacks f JOIN stays s ON f.stay_id=s.id JOIN guests g ON s.guest_id=g.id $dateFilter GROUP BY nation ORDER BY count DESC",
    );
    $stmt->execute($params);
    $nationalityBreakdown = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT g.guest_name, s.room_no, f.overall_rating, f.general_comments, (SELECT GROUP_CONCAT(staff_name SEPARATOR ', ') FROM feedback_helpful_staff WHERE feedback_id=f.id) as helpful_staff_names, f.created_at FROM feedbacks f JOIN stays s ON f.stay_id=s.id JOIN guests g ON s.guest_id=g.id $dateFilter ORDER BY f.created_at DESC LIMIT 20",
    );
    $stmt->execute($params);
    $comments = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT h.staff_name as name, COUNT(*) as count FROM feedback_helpful_staff h JOIN feedbacks f ON h.feedback_id=f.id $dateFilter GROUP BY h.staff_name ORDER BY count DESC LIMIT 10",
    );
    $stmt->execute($params);
    $recognizedStaff = $stmt->fetchAll();

    $stmt = $pdo->prepare(
        "SELECT DATE(f.created_at) as date, COUNT(*) as count, ROUND(AVG(f.overall_rating),1) as avg_rating FROM feedbacks f $dateFilter GROUP BY DATE(f.created_at) ORDER BY date",
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
            "poor" => $poor,
            "good" => $good,
            "excellent" => $excellent,
        ],
        "hotel_process" => [
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
                "label" => "Accommodation",
                "avg" => toTenScale($summary["avg_accommodation"] ?? 0),
            ],
            [
                "label" => "Telephone Operator",
                "avg" => toTenScale($summary["avg_telephone"] ?? 0),
            ],
            [
                "label" => "Front Office Agents",
                "avg" => toTenScale($summary["avg_frontdesk"] ?? 0),
            ],
            [
                "label" => "Housekeeping",
                "avg" => toTenScale($summary["avg_housekeeping"] ?? 0),
            ],
            [
                "label" => "Security",
                "avg" => toTenScale($summary["avg_security"] ?? 0),
            ],
        ],
        "forest_wing" => [
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
        ],
        "guestroom" => [
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
