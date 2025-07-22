<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('tcpdf/tcpdf.php');
require_once('const/calculations.php');

if ($res == "1" && $level == "1" && isset($_GET['term'])) {} else { header("location:../"); }

$term = $_GET['term'];
$std = $_GET['std'];

try {
    $conn = new PDO('mysql:host=' . DBHost . ';dbname=' . DBName . ';charset=' . DBCharset, DBUser, DBPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE id = ?");
    $stmt->execute([$std]);
    $result = $stmt->fetchAll();

    foreach ($result as $value) {
        $dob_bs = $value[5] ?? '';
        $symbol_no = $value[6] ?? '';
    }

    $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
    $stmt->execute([$term]);
    $result = $stmt->fetchAll();

    if (count($result) < 1) {
        header("location:./");
    }

    $title = $result[0][1] . ' Examination Result';
    $exam_year = date('Y');

    $stmt = $conn->prepare("SELECT * FROM tbl_exam_results LEFT JOIN tbl_classes ON tbl_exam_results.class = tbl_classes.id WHERE tbl_exam_results.term = ? AND tbl_exam_results.student = ?");
    $stmt->execute([$term, $std]);
    $result2 = $stmt->fetchAll();

    if (count($result2) < 1) {
        header("location:./");
    }
} catch (PDOException $e) {
    ob_end_clean();
    die("Connection failed: " . $e->getMessage());
}

$pdf = new TCPDF('P', 'mm', array(210, 297), true, 'UTF-8', false);
$pdf->SetMargins(19.3, 5.08, 22.35);
$pdf->SetAutoPageBreak(TRUE, 5.84);
$pdf->SetCellHeightRatio(1.5);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 11, '', true);

$pdf->AddPage();

ob_start();

// Registration No.
$html = '<div style="text-align:right; font-family:calibri; font-size:11px; font-weight:bold; width:170.43mm; padding-right:50px; margin-bottom:6px;">
<u>REGISTRATION NO.: ' . $std . '</u> <br> <br> <br> <br>
</div>';
$pdf->writeHTMLCell(170.43, 0, '', '', $html, 0, 1, 0, true, 'C', true);
$pdf->Ln(3);

// Grade Sheet Header
$html = '
<div style="font-size:11px; font-weight:bold; font-family:helvetica; text-align:left; width:0cm;">
  THE FOLLOWING ARE THE GRADE(S) SECURED BY: ' . $value[1] . '
  <div style="border-bottom:1px solid black; width:-1cm; margin-top:2px;"></div>
  <br>
  DATE OF BIRTH: ' . $dob_bs . ' B.S. SYMBOL NO.: ' . $symbol_no . ' GRADE XI IN THE <br>
  ANNUAL EXAMINATION CONDUCTED IN ' . $exam_year . ' A.D. ARE GIVEN BELOW.
</div>';



$pdf->writeHTMLCell(170.43, 0, '', '', $html, 0, 1, 0, true, 'L', true);
$pdf->Ln(2);

// Table Header
$html = '<table border="1" cellpadding="1.02" cellspacing="0" style="font-size:10px; border-collapse:collapse;" width="170.43mm">
<tr>
<th width="17.53mm" style="text-align:center; font-weight:bold; vertical-align:middle;">SUBJECT CODE</th>
<th width="68.07mm" style="text-align:left; font-weight:bold; vertical-align:middle;">SUBJECTS</th>
<th width="17.02mm" style="text-align:center; font-weight:bold; vertical-align:middle;">CREDIT HOURS</th>
<th width="11.82mm" style="text-align:center; font-weight:bold; vertical-align:middle;">IN</th>
<th width="11.82mm" style="text-align:center; font-weight:bold; vertical-align:middle;">TH</th>
<th width="11.82mm" style="text-align:center; font-weight:bold; vertical-align:middle;">GRADE</th>
<th width="11.82mm" style="text-align:center; font-weight:bold; vertical-align:middle;">FINAL GRADE</th>
<th width="11.82mm" style="text-align:center; font-weight:bold; vertical-align:middle;">REMARKS</th>
</tr>';

$stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $row) {
    $class_list = unserialize($row[1]);

    if (in_array($result2[0][6], $class_list)) {
        $subject_code = $row[0];
        $subject_name = $row[6];
        $credit_hour = 3;
        $internal_marks = 0;
        $theory_marks = 0;
        $grade = "N/A";
        $final_grade = "N/A";
        $remark = "N/A";

        $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
        $stmt->execute([$result2[0][6], $row[0], $term, $std]);
        $ex_result = $stmt->fetchAll();

        if (!empty($ex_result[0][5])) {
            $total_score = $ex_result[0][5];
            $internal_marks = round($total_score * 0.4);
            $theory_marks = round($total_score * 0.6);
            if ($total_score >= 90) { $grade = "A"; }
            elseif ($total_score >= 80) { $grade = "B+"; }
            elseif ($total_score >= 70) { $grade = "B"; }
            elseif ($total_score >= 60) { $grade = "C+"; }
            elseif ($total_score >= 50) { $grade = "C"; }
            else { $grade = "F"; }
            $final_grade = $grade;
            $remark = ($total_score >= 50) ? "Pass" : "Fail";
        }

        $html .= '<tr>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $subject_code . '</td>
        <td style="text-align:left; vertical-align:top; height:20px">' . $subject_name . '</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $credit_hour . '</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $internal_marks . '%</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $theory_marks . '%</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $grade . '</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $final_grade . '</td>
        <td style="text-align:center; vertical-align:middle; height:20px">' . $remark . '</td>
        </tr>';
    }
}

$html .= '<tr><td colspan="2" style="text-align:center; height:20px"><b>EXTRA CREDIT SUBJECT</b></td><td colspan="6"></td></tr>';
$html .= '</table>';

$pdf->writeHTMLCell(170.43, 0, '', '', $html, 0, 1, 0, true, 'L', true);
$pdf->Ln(5);

$date_of_issue = date('g:i A +0545, l, F j, Y');
$html = '<table width="100%">
<tr>
<td width="33%" style="text-align:center; font-size:11px; font-weight:bold;">PREPARED BY: _____________________</td>
<td width="33%" style="text-align:center; font-size:11px; font-weight:bold;">CHECKED BY: _____________________</td>
<td width="33%" style="text-align:center; font-size:11px; font-weight:bold;">DATE OF ISSUE: ' . $date_of_issue . '</td>
</tr>
<tr>
<td colspan="2"></td>
<td style="text-align:center; font-size:11px; font-weight:bold;">PRINCIPAL: _____________________</td>
</tr>
</table>';

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'C', true);
$pdf->Ln(5);

$html = '<p style="text-align:center; font-size:10px;">NOTE: ONE CREDIT HOUR EQUALS TO 32 WORKING HOURS</p>
<p style="text-align:center; font-size:10px;">INTERNAL (IN): THIS COVERS THE PARTICIPATION, PRACTICAL/PROJECT WORKS, PRESENTATIONS TERMINAL EXAMINATIONS.</p>
<p style="text-align:center; font-size:10px;">THEORY (TH): THIS COVERS WRITTEN EXTERNAL EXAMINATION</p>
<p style="text-align:center; font-size:10px;">ABS = ABSENT  W = WITHHELD</p>';

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'C', true);

ob_end_clean();
$pdf->Output($title . '.pdf', 'I');
