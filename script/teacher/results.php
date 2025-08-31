<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "2") {
    // Set page title and include DataTables
    $page_title = "Examination Results";
    $include_datatables = true;

    // Check for result data in session
    if (isset($_SESSION['result__data']) && 
        isset($_SESSION['result__data']['term']) && 
        isset($_SESSION['result__data']['class']) && 
        isset($_SESSION['result__data']['subject_combination']) &&
        !empty($_SESSION['result__data']['term']) &&
        !empty($_SESSION['result__data']['class']) &&
        !empty($_SESSION['result__data']['subject_combination'])) {
        
        $term = $_SESSION['result__data']['term'];
        $class = $_SESSION['result__data']['class'];
        $subject_combination = $_SESSION['result__data']['subject_combination'];

        try {
            $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
            $stmt->execute([$term]);
            $terms_data = $stmt->fetchAll();

            $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
            $stmt->execute([$class]);
            $class_data = $stmt->fetchAll();

            $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id 
                WHERE tbl_subject_combinations.id = ?");
            $stmt->execute([$subject_combination]);
            $sub_data = $stmt->fetchAll();

            if (count($terms_data) > 0 && count($class_data) > 0 && count($sub_data) > 0) {
                $tit = '' . $sub_data[0][6] . ' - ' . $terms_data[0][1] . ' - ' . $class_data[0][1] . ' Examination Results';
            } else {
                // Invalid data, redirect back to manage results
                unset($_SESSION['result__data']);
                header("location:manage_results.php");
                exit;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    } else {
        // Clear any invalid session data
        if (isset($_SESSION['result__data'])) {
            unset($_SESSION['result__data']);
        }
        header("location:manage_results.php");
        exit;
    }
} else {
    header("location:../");
    exit;
}
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-file-earmark-text me-2"></i><?php echo $tit; ?></h1>
        <p>View examination results for the selected criteria.</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <a href="teacher/manage_results.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Manage Results
            </a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"><i class="bi bi-table me-2"></i>Results Table</h3>
                <p>Student examination results with grades and remarks</p>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-2"></i>Registration Number</th>
                                <th><i class="bi bi-person me-2"></i>Student Name</th>
                                <th><i class="bi bi-percent me-2"></i>Score</th>
                                <th><i class="bi bi-award me-2"></i>Grade</th>
                                <th><i class="bi bi-chat-text me-2"></i>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
                                $stmt->execute();
                                $grading = $stmt->fetchAll();

                                $stmt = $conn->prepare("SELECT * FROM tbl_exam_results
                                    LEFT JOIN tbl_students ON tbl_exam_results.student = tbl_students.id
                                    WHERE tbl_exam_results.class = ? AND tbl_exam_results.subject_combination = ? AND tbl_exam_results.term = ?
                                    ORDER BY tbl_students.fname, tbl_students.lname");
                                $stmt->execute([$class, $subject_combination, $term]);
                                $result = $stmt->fetchAll();

                                if (count($result) < 1) {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>No results found</strong>
                                                <br>
                                                <small>No examination results available for the selected criteria.</small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    foreach ($result as $row) {
                                        $grd = 'N/A';
                                        $rm = 'N/A';
                                        foreach ($grading as $grade) {
                                            if ($row[5] >= $grade[2] && $row[5] <= $grade[3]) {
                                                $grd = $grade[1];
                                                $rm = $grade[4];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $row[6]; ?></strong></td>
                                            <td><?php echo $row[7] . ' ' . $row[8] . ' ' . $row[9]; ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo $row[5]; ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo $grd; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $rm; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            } catch (PDOException $e) {
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="alert alert-danger mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Database Error</strong>
                                            <br>
                                            <small>Unable to load results. Please try again later.</small>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>