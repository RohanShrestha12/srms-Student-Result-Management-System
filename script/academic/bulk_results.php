<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/calculations.php');
if ($res == "1" && $level == "1") {
} else {
    header("location:../");
}

if (isset($_SESSION['bulk_result'])) {
    $class = $_SESSION['bulk_result']['student'];
    $term = $_SESSION['bulk_result']['term'];

    $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
    $stmt->execute();
    $grading = $stmt->fetchAll();

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ?");
        $stmt->execute([$class]);
        $std_data = $stmt->fetchAll();

        $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
        $stmt->execute([$term]);
        $term_data = $stmt->fetchAll();

        $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
        $stmt->execute([$std_data[0]['class']]);
        $class_data = $stmt->fetchAll();

        $tit = '' . $class_data[0]['name'] . ' (' . $term_data[0]['name'] . ' Results)';
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    header("location:./");
}

// Set page title for header
$page_title = $tit ?? 'Bulk Results';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-file-text me-2"></i><?php echo $tit; ?></h1>
        <p>View and manage examination results for all students</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 center_form">
        <div class="tile">
            <div class="tile-body">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>REGISTRATION NUMBER</th>
                                <th>STUDENT NAME</th>
                                <th>TOTAL SCORE</th>
                                <th>AVERAGE</th>
                                <th>GRADE</th>
                                <th>REMARKS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ?");
                                $stmt->execute([$class]);
                                $result2 = $stmt->fetchAll();

                                foreach ($result2 as $row2) {
                                    $tscore = 0;
                                    $t_subjects = 0;
                                    $subssss = array();

                                    foreach ($result as $key => $row) {
                                        $class_list = unserialize($row[1]);

                                        if (in_array($class, $class_list)) {
                                            $t_subjects++;
                                            $score = 0;

                                            $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                                            $stmt->execute([$class, $row[0], $term, $row2['id']]);
                                            $ex_result = $stmt->fetchAll();

                                            if (!empty($ex_result[0]['score'])) {
                                                $score = $ex_result[0]['score'];
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

                                        if ($av >= $grade['min'] && $av <= $grade['max']) {

                                            $grd = $grade['name'];
                                            $rm = $grade['remark'];
                                        }
                                    }
                                    ?>

                                    <tr>
                                        <td width="10">
                                            <?php
                                            if ($row2['display_image'] == "DEFAULT") {
                                                ?><img src="images/students/<?php echo $row2['id']; ?>.png"
                                                    class="avatar_img_sm"><?php
                                            } else {
                                                ?><img src="images/students/<?php echo $row2['display_image']; ?>"
                                                    class="avatar_img_sm"><?php
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row2['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row2['fname'] . ' ' . $row2['lname']); ?></td>
                                        <td><?php echo $tscore; ?></td>
                                        <td><?php echo $av; ?></td>
                                        <td><?php echo $grd; ?></td>
                                        <td><?php echo $rm; ?></td>


                                        <td align="center" width="190">
                                            <a href="academic/core/edit_result?std=<?php echo $row2['id']; ?>&term=<?php echo $term; ?>"
                                                class="btn btn-primary btn-sm" href="javascript:void(0);">Edit</a>
                                            <a href="academic/save_pdf?std=<?php echo $row2['id']; ?>&term=<?php echo $term; ?>"
                                                class="btn btn-primary btn-sm" href="javascript:void(0);">Report</a>
                                            <a onclick="del('academic/core/drop_results?src=bulk_results&std=<?php echo $row2['id']; ?>&class=<?php echo $class; ?>&term=<?php echo $term; ?>', 'Delete Results?');"
                                                href="javascript:void(0);" class="btn btn-danger btn-sm">Delete</a>
                                        </td>

                                    </tr>
                                    <?php
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }

                            ?>

                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>