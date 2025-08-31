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

// Set page title
$page_title = "Individual Results";

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-lines-fill me-2"></i>Individual Results</h1>
        <p>View individual student examination results</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 center_form">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <h3 class="tile-title">Individual Results</h3>
                    <form enctype="multipart/form-data" action="admin/core/start_individual" class="app_frm" method="POST" autocomplete="OFF">

                        <div class="mb-2">
                            <label class="form-label">Select Student</label>
                            <select class="form-control select2" name="student" required style="width: 100%;">
                                <option value="" selected disabled> Select One</option>
                                <?php
                                try {
                                    // Use the connection from school.php instead of creating a new one
                                    // $conn is already available from school.php

                                    $stmt = $conn->prepare("SELECT * FROM tbl_students");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach ($result as $row) {
                                ?>
                                        <option value="<?php echo $row[0]; ?>"><?php echo $row[1] . ' ' . $row[2] . ' ' . $row[3]; ?> </option>
                                <?php
                                    }
                                } catch (PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Term</label>
                            <select class="form-control select2" name="term" required style="width: 100%;">
                                <option selected disabled value="">Select One</option>
                                <?php
                                try {
                                    // Use the connection from school.php instead of creating a new one
                                    // $conn is already available from school.php

                                    $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE status = '1'");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach ($result as $row) {
                                ?>
                                        <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?> </option>
                                <?php
                                    }
                                } catch (PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>

                        <div class="">
                            <button class="btn btn-primary app_btn" type="submit">View Results</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('admin-footer.php'); ?>
