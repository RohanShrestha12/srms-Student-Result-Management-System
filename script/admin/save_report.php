<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('tcpdf/tcpdf.php');
require_once('const/calculations.php');
if ($res == "1" && $level == "0") {
} else {
    header("location:../");
}

if (isset($_SESSION['bulk_result_2'])) {
    $class = $_SESSION['bulk_result_2']['student'];
    $term = $_SESSION['bulk_result_2']['term'];

    $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
    $stmt->execute();
    $grading = $stmt->fetchAll();

    // Initialize $_MATOKEO array
    $_MATOKEO = array(
        'DISTINCTION' => array('BOYS' => 0, 'GIRLS' => 0),
        'FIRST' => array('BOYS' => 0, 'GIRLS' => 0),
        'SECOND' => array('BOYS' => 0, 'GIRLS' => 0),
        'THIRD' => array('BOYS' => 0, 'GIRLS' => 0),
        'FAIL' => array('BOYS' => 0, 'GIRLS' => 0)
    );

    try {
        // Use the connection from school.php instead of creating a new one
        // $conn is already available from school.php

        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ?");
        $stmt->execute([$class]);
        $std_data = $stmt->fetchAll();

        // Debug: Check if students exist
        if (count($std_data) == 0) {
            echo "No students found for class ID: " . $class;
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
        $stmt->execute([$term]);
        $term_data = $stmt->fetchAll();

        // Debug: Check if term exists
        if (count($term_data) == 0) {
            echo "No term found for term ID: " . $term;
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
        $stmt->execute([$std_data[0][6]]);
        $class_data = $stmt->fetchAll();

        // Debug: Check if class exists
        if (count($class_data) == 0) {
            echo "No class found for class ID: " . $std_data[0][6];
            exit();
        }

        $title = '' . $class_data[0][1] . ' (' . $term_data[0][1] . ' Perfomance Report)';
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    try {
        // Use the connection from school.php instead of creating a new one
        // $conn is already available from school.php

        $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
        $stmt->execute();
        $result = $stmt->fetchAll();

        // Debug: Check if subject combinations exist
        if (count($result) == 0) {
            echo "No subject combinations found";
            exit();
        }

        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ?");
        $stmt->execute([$class]);
        $result2 = $stmt->fetchAll();

        // Debug: Check if students exist
        if (count($result2) == 0) {
            echo "No students found for class ID: " . $class;
            exit();
        }

        foreach ($result2 as $row2) {
            $tscore = 0;
            $t_subjects = 0;
            $subssss = array();

            foreach ($result as $key => $row) {
                $class_list = unserialize($row[1]);

                if (in_array($class, $class_list)) {
                    $t_subjects++;
                    $score = 0;
                    $gnd = $row2[4];
                    $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                    $stmt->execute([$class, $row[0], $term, $row2[0]]);
                    $ex_result = $stmt->fetchAll();

                    if (!empty($ex_result[0][5])) {
                        $score = $ex_result[0][5];
                        $tscore = $tscore + $score;
                    }
                    array_push($subssss, $score);
                }
            }

            if ($t_subjects == "0") {
                $av = '0';
            } else {
                $av = round($tscore / $t_subjects);
            }

            foreach ($grading as $grade) {
                if ($av >= $grade[2] && $av <= $grade[3]) {
                    $grd = $grade[1];
                    $rm = $grade[4];
                }
            }

            $div = get_division($subssss);

            if ($gnd == "Male") {
                $_MATOKEO[$div]['BOYS'] = $_MATOKEO[$div]['BOYS'] + 1;
            } else {
                $_MATOKEO[$div]['GIRLS'] = $_MATOKEO[$div]['GIRLS'] + 1;
            }
        }


        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(WBName);
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        $pdf->SetKeywords('SRMS', WBName);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 14, '', true);

        $pdf->AddPage();
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));


        $html = '<table width="100%">
<tr>
<td width="15%"><img src="images/logo/' . WBLogo . '"></td>
<td width="85%">
<h5><b style="font-size:18px;">' . WBName . '</b>
<br>Student Perfomance Report<br>
' . $class_data[0][1] . '<br>
' . $term_data[0][1] . '</h5>
</td>

</tr>
</table>';

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->SetFont('helvetica', '', 10, '', true);

        $pdf->cell(0, 0, '', 0, 1, 'C');

        $htmls = '<table border="1" cellpadding="5">
<tr>
<td>DIVISION</td>
<td>BOYS</td>
<td>GIRLS</td>
<td>Total</td>
</tr>
';

        foreach ($divisions as $key => $value) {

            $htmls = $htmls . '
<tr>
<td>' . $value[0] . '</td>
<td>' . $_MATOKEO[$value[0]]['BOYS'] . '</td>
<td>' . $_MATOKEO[$value[0]]['GIRLS'] . '</td>
<td>' . $_MATOKEO[$value[0]]['BOYS'] + $_MATOKEO[$value[0]]['GIRLS'] . '</td>
</tr>
';
        }

        $htmls = $htmls . '</table>';

        $pdf->writeHTMLCell(0, 0, '', '', $htmls, 0, 1, 0, true, '', true);

        $html2 = '<br><br><b>Date : ' . date('F d, Y G:i:s A') . '</b>';
        $pdf->writeHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);

        ob_end_clean();
        $pdf->Output('' . $title . '.pdf', 'I');
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    header("location:./");
}
