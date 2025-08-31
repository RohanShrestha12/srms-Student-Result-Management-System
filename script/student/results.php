<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/calculations.php');
if ($res == "1" && $level == "3") {
} else {
    header("location:../");
}

$stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
$stmt->execute();
$grading = $stmt->fetchAll();

// Set page title
$page_title = "My Examination Results";

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-file-text me-2"></i>My Examination Results</h1>
        <p>View your academic performance and examination scores</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-graph-up me-2"></i>Academic Performance</h5>
            </div>
            <div class="widget-content">
                <?php
                if (WBResAvi == "1") {
                    try {
                        $conn = new PDO('mysql:host=' . DBHost . ';port=' . DBPort . ';dbname=' . DBName . ';charset=' . DBCharset . ';collation=' . DBCollation . ';prefix=' . DBPrefix . '', DBUser, DBPass);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $conn->prepare("SELECT class FROM tbl_exam_results GROUP BY class");
                        $stmt->execute();
                        $_classes = $stmt->fetchAll();

                        foreach ($_classes as $key => $class) {
                            $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
                            $stmt->execute([$class[0]]);
                            $class_de = $stmt->fetchAll();

                            $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND student = ? LIMIT 1");
                            $stmt->execute([$class[0], $account_id]);
                            $myyyyy = $stmt->fetchAll();

                            if (count($myyyyy) > 0) {
                                $stmt = $conn->prepare("SELECT term FROM tbl_exam_results WHERE class = ? GROUP BY term");
                                $stmt->execute([$class[0]]);
                                $_terms = $stmt->fetchAll();
                ?>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="dashboard-widget">
                                            <div class="widget-header">
                                                <h5><i class="bi bi-mortarboard me-2"></i><?php echo $class_de[0][1]; ?></h5>
                                            </div>
                                            <div class="widget-content">
                                                <div class="bs-component">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <?php
                                                        $t = 1;
                                                        foreach ($_terms as $key => $_term) {
                                                            $stmt = $conn->prepare("SELECT name FROM tbl_terms WHERE id = ?");
                                                            $stmt->execute([$_term[0]]);
                                                            $_term_data = $stmt->fetchAll();

                                                            if ($t == "1") {
                                                        ?>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active" data-bs-toggle="tab" href="#term_<?php echo $_term[0]; ?>" aria-selected="true" role="tab">
                                                                        <i class="bi bi-calendar-event me-1"></i><?php echo $_term_data[0][0]; ?>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" data-bs-toggle="tab" href="#term_<?php echo $_term[0]; ?>" aria-selected="false" tabindex="-1" role="tab">
                                                                        <i class="bi bi-calendar-event me-1"></i><?php echo $_term_data[0][0]; ?>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            }
                                                            $t++;
                                                        }
                                                        ?>
                                                    </ul>
                                                    <div class="tab-content mt-3" id="myTabContent">
                                                        <?php
                                                        $t = 1;
                                                        foreach ($_terms as $key => $_term) {
                                                            if ($t == "1") {
                                                        ?>
                                                                <div class="tab-pane fade active show" id="term_<?php echo $_term[0]; ?>" role="tabpanel">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-hover">
                                                                            <thead class="table-dark">
                                                                                <tr>
                                                                                    <th scope="col" width="40"><i class="bi bi-hash me-1"></i>#</th>
                                                                                    <th scope="col"><i class="bi bi-book me-1"></i>SUBJECT</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-percent me-1"></i>SCORE</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-award me-1"></i>GRADE</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-chat-text me-1"></i>REMARK</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
                                                                                $stmt->execute();
                                                                                $result = $stmt->fetchAll();
                                                                                $n = 1;
                                                                                $tscore = 0;
                                                                                $t_subjects = 0;
                                                                                $subssss = array();

                                                                                foreach ($result as $key => $row) {
                                                                                    $class_list = unserialize($row[1]);

                                                                                    if (in_array($class[0], $class_list)) {
                                                                                        $t_subjects++;
                                                                                        $score = 0;
                                                                                        $grd = "N/A";
                                                                                        $rm = "N/A";

                                                                                        $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                                                                                        $stmt->execute([$class[0], $row[0], $_term[0], $account_id]);
                                                                                        $ex_result = $stmt->fetchAll();

                                                                                        if (!empty($ex_result[0][5])) {
                                                                                            $score = $ex_result[0][5];
                                                                                        }
                                                                                        array_push($subssss, $score);

                                                                                        $tscore = $tscore + $score;
                                                                                        foreach ($grading as $grade) {
                                                                                            if ($score >= $grade[2] && $score <= $grade[3]) {
                                                                                                $grd = $grade[1];
                                                                                                $rm = $grade[4];
                                                                                            }
                                                                                        }
                                                                                ?>
                                                                                        <tr class="align-middle">
                                                                                            <td><span class="badge bg-secondary"><?php echo $n; ?></span></td>
                                                                                            <td><strong class="text-primary"><?php echo $row[6]; ?></strong></td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-info rounded-pill"><?php echo $score; ?>%</span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-warning rounded-pill"><?php echo $grd; ?></span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light text-dark rounded-pill"><?php echo $rm; ?></span>
                                                                                            </td>
                                                                                        </tr>
                                                                                <?php
                                                                                    }
                                                                                    $n++;
                                                                                }
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <?php
                                                                    if ($t_subjects == "0") {
                                                                        $av = '0';
                                                                    } else {
                                                                        $av = round($tscore / $t_subjects);
                                                                    }
                                                                    foreach ($grading as $grade) {
                                                                        if ($av >= $grade[2] && $av <= $grade[3]) {
                                                                            $grd_ = $grade[1];
                                                                            $rm_ = $grade[4];
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12">
                                                                            <div class="card bg-light">
                                                                                <div class="card-body">
                                                                                    <div class="row text-center">
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">TOTAL SCORE</h6>
                                                                                            <span class="badge bg-primary fs-6"><?php echo $tscore; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">AVERAGE</h6>
                                                                                            <span class="badge bg-success fs-6"><?php echo $av; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">GRADE</h6>
                                                                                            <span class="badge bg-warning fs-6"><?php echo $grd_; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">REMARK</h6>
                                                                                            <span class="badge bg-info fs-6"><?php echo strtoupper($rm_); ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-3">
                                                                        <a target="_blank" href="student/save_pdf.php?term=<?php echo $_term[0]; ?>" class="btn btn-primary">
                                                                            <i class="bi bi-download me-2"></i>Save PDF
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="tab-pane fade" id="term_<?php echo $_term[0]; ?>" role="tabpanel">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-hover">
                                                                            <thead class="table-dark">
                                                                                <tr>
                                                                                    <th scope="col" width="40"><i class="bi bi-hash me-1"></i>#</th>
                                                                                    <th scope="col"><i class="bi bi-book me-1"></i>SUBJECT</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-percent me-1"></i>SCORE</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-award me-1"></i>GRADE</th>
                                                                                    <th scope="col" class="text-center"><i class="bi bi-chat-text me-1"></i>REMARK</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
                                                                                $stmt->execute();
                                                                                $result = $stmt->fetchAll();
                                                                                $n = 1;
                                                                                $tscore = 0;
                                                                                $t_subjects = 0;
                                                                                $subssss = array();

                                                                                foreach ($result as $key => $row) {
                                                                                    $class_list = unserialize($row[1]);

                                                                                    if (in_array($class[0], $class_list)) {
                                                                                        $t_subjects++;
                                                                                        $score = 0;
                                                                                        $grd = "N/A";
                                                                                        $rm = "N/A";

                                                                                        $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                                                                                        $stmt->execute([$class[0], $row[0], $_term[0], $account_id]);
                                                                                        $ex_result = $stmt->fetchAll();

                                                                                        if (!empty($ex_result[0][5])) {
                                                                                            $score = $ex_result[0][5];
                                                                                        }
                                                                                        array_push($subssss, $score);

                                                                                        $tscore = $tscore + $score;
                                                                                        foreach ($grading as $grade) {
                                                                                            if ($score >= $grade[2] && $score <= $grade[3]) {
                                                                                                $grd = $grade[1];
                                                                                                $rm = $grade[4];
                                                                                            }
                                                                                        }
                                                                                ?>
                                                                                        <tr class="align-middle">
                                                                                            <td><span class="badge bg-secondary"><?php echo $n; ?></span></td>
                                                                                            <td><strong class="text-primary"><?php echo $row[6]; ?></strong></td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-info rounded-pill"><?php echo $score; ?>%</span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-warning rounded-pill"><?php echo $grd; ?></span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light text-dark rounded-pill"><?php echo $rm; ?></span>
                                                                                            </td>
                                                                                        </tr>
                                                                                <?php
                                                                                    }
                                                                                    $n++;
                                                                                }
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <?php
                                                                    if ($t_subjects == "0") {
                                                                        $av = '0';
                                                                    } else {
                                                                        $av = round($tscore / $t_subjects);
                                                                    }
                                                                    foreach ($grading as $grade) {
                                                                        if ($av >= $grade[2] && $av <= $grade[3]) {
                                                                            $grd_ = $grade[1];
                                                                            $rm_ = $grade[4];
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12">
                                                                            <div class="card bg-light">
                                                                                <div class="card-body">
                                                                                    <div class="row text-center">
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">TOTAL SCORE</h6>
                                                                                            <span class="badge bg-primary fs-6"><?php echo $tscore; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">AVERAGE</h6>
                                                                                            <span class="badge bg-success fs-6"><?php echo $av; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">GRADE</h6>
                                                                                            <span class="badge bg-warning fs-6"><?php echo $grd_; ?></span>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <h6 class="text-muted">REMARK</h6>
                                                                                            <span class="badge bg-info fs-6"><?php echo strtoupper($rm_); ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-3">
                                                                        <a target="_blank" href="student/save_pdf.php?term=<?php echo $_term[0]; ?>" class="btn btn-primary">
                                                                            <i class="bi bi-download me-2"></i>Save PDF
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            }
                                                            $t++;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                <?php
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('student-footer.php'); ?>