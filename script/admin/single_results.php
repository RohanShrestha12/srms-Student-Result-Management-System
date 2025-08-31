<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "0") {
} else {
    header("location:../");
}

if (isset($_SESSION['student_result'])) {
    $std = $_SESSION['student_result']['student'];
    $term = $_SESSION['student_result']['term'];

    try {
        // Use the connection from school.php instead of creating a new one
        // $conn is already available from school.php

        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE id = ?");
        $stmt->execute([$std]);
        $std_data = $stmt->fetchAll();

        $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
        $stmt->execute([$term]);
        $term_data = $stmt->fetchAll();

        $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
        $stmt->execute([$std_data[0][6]]);
        $class_data = $stmt->fetchAll();

        $tit = '' . $std_data[0][1] . ' ' . $std_data[0][2] . ' ' . $std_data[0][3] . ' (' . $term_data[0][1] . ' Results)';
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    header("location:./");
}

// Set page title
$page_title = "Student Results";

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-file-earmark-text me-2"></i><?php echo $tit; ?></h1>
        <p>View and edit student examination results</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <form enctype="multipart/form-data" action="admin/core/update_results" class="app_frm row" method="POST" autocomplete="OFF">

                    <?php
                    $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    foreach ($result as $key => $row) {
                        $class_list = unserialize($row[1]);

                        if (in_array($std_data[0][6], $class_list)) {
                            $score = 0;

                            $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                            $stmt->execute([$std_data[0][6], $row[0], $term, $std]);
                            $ex_result = $stmt->fetchAll();

                            if (!empty($ex_result[0][5])) {
                                $score = $ex_result[0][5];
                            }
                    ?>

                            <div class="mb-3 col-md-2">
                                <label class="form-label"><?php echo $row[6]; ?></label>
                                <input value="<?php echo $score; ?>" name="<?php echo $row[0]; ?>" class="form-control" required type="number" placeholder="Enter score">
                            </div>

                    <?php
                        }
                    }
                    ?>
                    <input type="hidden" name="student" value="<?php echo $std; ?>">
                    <input type="hidden" name="term" value="<?php echo $term; ?>">
                    <input type="hidden" name="class" value="<?php echo $std_data[0][6]; ?>">
                    <div class="">
                        <button class="btn btn-primary app_btn" type="submit">Save Results</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('admin-footer.php'); ?>
