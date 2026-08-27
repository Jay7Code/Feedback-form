<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * PDF REPORT GENERATOR (Matches Web Print Layout)
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 */

require_once __DIR__ . '/../libs/fpdf/fpdf.php';
require_once __DIR__ . '/../config.php';

class ExecutiveReportPDF extends FPDF
{
    public $reportTitle = 'GUEST FEEDBACK SUMMARY REPORT';
    public $periodLabel = '';
    public $generatedDate = '';

    public function Header()
    {
        // Add logo if it exists
        $logoPath = __DIR__ . '/../img/logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 95, 10, 20); // Center logo approximately
        }

        $this->SetY(35);
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(20, 43, 33); // Dark Pine
        $this->Cell(0, 10, $this->reportTitle, 0, 1, 'C');

        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 5, 'Report Generated on: ' . $this->generatedDate, 0, 1, 'C');
        $this->Cell(0, 5, 'Period: ' . $this->periodLabel, 0, 1, 'C');
        $this->Ln(10);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'C');
    }

    public function SectionTitle($title)
    {
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(20, 43, 33); // Dark Pine
        $this->Cell(0, 6, $title, 0, 1, 'L');

        // Gold underline
        $this->SetDrawColor(201, 169, 110);
        $this->SetLineWidth(0.4);
        $this->Line($this->GetX(), $this->GetY(), 196, $this->GetY());
        $this->Ln(4);
    }
}

class ReportPDFGenerator
{
    public static function generateReport(string $dateFrom, string $dateTo, ?string $outputDir = null): array
    {
        $mysqli = getDBConnection();

        if ($outputDir === null) {
            $outputDir = __DIR__ . '/reports';
        }
        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }

        $dateFilter = "WHERE DATE(f.created_at) BETWEEN ? AND ?";

        // 1. Overall Summary
        $stmt = $mysqli->prepare("
            SELECT 
                COUNT(*) as total_responses,
                ROUND(AVG(CASE WHEN f.overall_rating>0 THEN f.overall_rating END),2) as avg_nps,
                ROUND(AVG(CASE WHEN foh.frontdesk>0 THEN foh.frontdesk END),2) as avg_frontdesk,
                ROUND(AVG(CASE WHEN foh.reservations>0 THEN foh.reservations END),2) as avg_reservations,
                ROUND(AVG(CASE WHEN foh.check_in_rating>0 THEN foh.check_in_rating END),2) as avg_check_in_rating,
                ROUND(AVG(CASE WHEN foh.check_out_rating>0 THEN foh.check_out_rating END),2) as avg_check_out_rating,
                ROUND(AVG(CASE WHEN foh.telephone_operator>0 THEN foh.telephone_operator END),2) as avg_telephone,
                ROUND(AVG(CASE WHEN foh.housekeeping>0 THEN foh.housekeeping END),2) as avg_housekeeping,
                ROUND(AVG(CASE WHEN foh.accommodation>0 THEN foh.accommodation END),2) as avg_accommodation,
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
            $dateFilter
        ");
        $stmt->bind_param("ss", $dateFrom, $dateTo);
        $stmt->execute();
        $summary = $stmt->get_result()->fetch_assoc();

        // 2. NPS distribution
        $stmt = $mysqli->prepare("SELECT f.overall_rating as rating, COUNT(*) as count FROM feedbacks f $dateFilter GROUP BY f.overall_rating");
        $stmt->bind_param("ss", $dateFrom, $dateTo);
        $stmt->execute();
        $npsRes = $stmt->get_result();
        $npsDist = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        while ($row = $npsRes->fetch_assoc()) {
            if ($row['rating'] >= 1 && $row['rating'] <= 5) {
                $npsDist[(int)$row['rating']] = (int)$row['count'];
            }
        }
        $poor = $npsDist[1] + $npsDist[2];
        $good = $npsDist[3];
        $excellent = $npsDist[4] + $npsDist[5];

        // Build PDF
        $pdf = new ExecutiveReportPDF('P', 'mm', 'A4');
        $pdf->periodLabel = date('M d, Y', strtotime($dateFrom)) . ' — ' . date('M d, Y', strtotime($dateTo));
        $pdf->generatedDate = date('F d, Y') . ' at ' . date('h:i A');
        $pdf->AliasNbPages('{nb}');
        $pdf->AddPage();

        // ─── Summary Overview ───
        $pdf->SectionTitle('Summary Overview');

        $totalSubmissions = (int)($summary['total_responses'] ?? 0);
        $avgScore = $summary['avg_nps'] !== null ? number_format((float)$summary['avg_nps'], 1) : '—';

        // 4 Cards Layout (Top row)
        $cardW = 42;
        $cardH = 20;
        $startX = 14;
        $startY = $pdf->GetY() + 2;

        $topCards = [
            ['label' => 'TOTAL RESPONSES', 'val' => (string)$totalSubmissions],
            ['label' => 'AVG. SATISFACTION', 'val' => $avgScore . '/5'],
            ['label' => 'POOR (1-2)', 'val' => (string)$poor],
            ['label' => 'GOOD (3)', 'val' => (string)$good]
        ];

        foreach ($topCards as $idx => $card) {
            $cx = $startX + ($idx * ($cardW + 4));
            $pdf->SetXY($cx, $startY);
            $pdf->SetDrawColor(220, 220, 220); // Light gray border
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($cx, $startY, $cardW, $cardH, 'DF');

            $pdf->SetXY($cx, $startY + 4);
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->SetTextColor(20, 43, 33);
            $pdf->Cell($cardW, 7, $card['val'], 0, 1, 'C');

            $pdf->SetX($cx);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell($cardW, 4, $card['label'], 0, 1, 'C');
        }

        // 1 Full Width Card (Bottom row)
        $startY += $cardH + 4;
        $fullW = ($cardW * 4) + 12;
        $pdf->SetXY($startX, $startY);
        $pdf->SetDrawColor(220, 220, 220);
        $pdf->Rect($startX, $startY, $fullW, $cardH, 'DF');
        
        $pdf->SetXY($startX, $startY + 4);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(20, 43, 33);
        $pdf->Cell($fullW, 7, (string)$excellent, 0, 1, 'C');
        
        $pdf->SetX($startX);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($fullW, 4, 'EXCELLENT (4-5)', 0, 1, 'C');

        $pdf->Ln(8);

        // ─── Satisfaction Score Distribution ───
        $pdf->SectionTitle('Satisfaction Score Distribution');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(20, 43, 33); // Dark Pine
        $pdf->SetTextColor(255, 255, 255);
        
        // Table Header
        $pdf->Cell(42, 8, 'SCORE', 0, 0, 'C', true);
        $pdf->Cell(28, 8, '1', 0, 0, 'C', true);
        $pdf->Cell(28, 8, '2', 0, 0, 'C', true);
        $pdf->Cell(28, 8, '3', 0, 0, 'C', true);
        $pdf->Cell(28, 8, '4', 0, 0, 'C', true);
        $pdf->Cell(28, 8, '5', 0, 0, 'C', true);
        $pdf->Cell(0, 8, 'TOTAL', 0, 1, 'C', true);

        // Table Row
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(20, 43, 33);
        $pdf->SetDrawColor(220, 220, 220);
        
        $pdf->Cell(42, 8, 'Responses', 'B', 0, 'C');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(28, 8, (string)$npsDist[1], 'B', 0, 'C');
        $pdf->Cell(28, 8, (string)$npsDist[2], 'B', 0, 'C');
        $pdf->Cell(28, 8, (string)$npsDist[3], 'B', 0, 'C');
        $pdf->Cell(28, 8, (string)$npsDist[4], 'B', 0, 'C');
        $pdf->Cell(28, 8, (string)$npsDist[5], 'B', 0, 'C');
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(0, 8, (string)$totalSubmissions, 'B', 1, 'C');

        $pdf->Ln(5);

        // ─── Our Hotel Process & Associates ───
        $pdf->SectionTitle('Our Hotel Process & Associates');

        // Table Header
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(20, 43, 33); // Dark Pine
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(91, 8, 'CATEGORY', 0, 0, 'L', true);
        $pdf->Cell(45.5, 8, 'AVG. SCORE', 0, 0, 'C', true);
        $pdf->Cell(45.5, 8, 'RATING', 0, 1, 'C', true);

        $processCategories = [
            'Reservations & Booking' => $summary['avg_reservations'],
            'Front Desk / Reception' => $summary['avg_frontdesk'],
            'Check-in Experience' => $summary['avg_check_in_rating'],
            'Check-out Experience' => $summary['avg_check_out_rating'],
            'Telephone Operator' => $summary['avg_telephone'],
            'Safety & Security' => $summary['avg_security'],
            'Room Cleanliness' => $summary['avg_cleanliness'],
            'Room Ambiance' => $summary['avg_ambiance'],
            'Bed & Living Comfort' => $summary['avg_comfort'],
            'Bathroom Quality & Cleanliness' => $summary['avg_bathroom'],
            'Housekeeping Quality' => $summary['avg_housekeeping'],
            'Overall Accommodation' => $summary['avg_accommodation'],
            'Staff Friendliness' => $summary['avg_friendliness'],
            'Staff Attentiveness' => $summary['avg_attentiveness'],
            'Staff Courteousness' => $summary['avg_courteousness'],
            'Food Quality' => $summary['avg_food_quality'],
            'Serving Time & Speed' => $summary['avg_serving_time'],
            'Staff Grooming' => $summary['avg_grooming'],
            'Service Professionalism' => $summary['avg_fnb_service'],
            'Bar / Beverage Experience' => $summary['avg_bar']
        ];

        foreach ($processCategories as $catName => $val) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->Cell(91, 7, '  ' . $catName, 'B', 0, 'L');
            
            if ($val !== null && $val > 0) {
                $scoreStr = number_format((float)$val, 1) . ' / 5.0';
                
                // Determine rating text and color
                if ($val >= 4.0) {
                    $ratingTxt = 'EXCELLENT';
                    $pdf->SetTextColor(34, 139, 34); // Green
                } elseif ($val >= 3.0) {
                    $ratingTxt = 'GOOD';
                    $pdf->SetTextColor(181, 137, 58); // Gold
                } else {
                    $ratingTxt = 'POOR';
                    $pdf->SetTextColor(178, 34, 34); // Red
                }

                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(45.5, 7, $scoreStr, 'B', 0, 'C');
                $pdf->Cell(45.5, 7, $ratingTxt, 'B', 1, 'C');
            } else {
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetTextColor(178, 34, 34); // Red N/A
                $pdf->Cell(45.5, 7, 'N/A', 'B', 0, 'C');
                $pdf->Cell(45.5, 7, 'N/A', 'B', 1, 'C');
            }
        }

        // Save PDF file
        $timestamp = date('Y-m-d_His');
        $filename = "Feedback_Report_{$timestamp}.pdf";
        $filepath = rtrim($outputDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        $pdf->Output('F', $filepath);

        return [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath,
            'filesize' => filesize($filepath),
            'filesize_formatted' => round(filesize($filepath) / 1024, 2) . ' KB',
            'period_from' => $dateFrom,
            'period_to' => $dateTo,
            'submissions' => $totalSubmissions,
            'avg_score' => $avgScore,
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }
}
?>
