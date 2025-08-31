<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "3") {
} else {
    header("location:../");
}

// Set page title
$page_title = "My Subjects";

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-book me-2"></i>My Subjects</h1>
        <p>View all subjects assigned to your class</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-list-ul me-2"></i>Subject List</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="srmsTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col"><i class="bi bi-book me-1"></i>Subject</th>
                                <th scope="col"><i class="bi bi-person me-1"></i>Teacher</th>
                                <th scope="col"><i class="bi bi-mortarboard me-1"></i>Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $conn = new PDO('mysql:host=' . DBHost . ';port=' . DBPort . ';dbname=' . DBName . ';charset=' . DBCharset . ';collation=' . DBCollation . ';prefix=' . DBPrefix . '', DBUser, DBPass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $empty_classes = array();

                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                foreach ($result as $row) {
                                    $class_list = unserialize($row[1]);

                                    if (in_array($class, $class_list)) {
                            ?>
                                        <tr class="align-middle">
                                            <td><strong class="text-primary"><?php echo $row[6]; ?></strong></td>
                                            <td><i class="bi bi-person-circle me-1"></i><?php echo $row[8] . ' ' . $row[9]; ?></td>
                                            <td><span class="badge bg-success rounded-pill"><i class="bi bi-mortarboard me-1"></i><?php echo $class; ?></span></td>
                                        </tr>
                                    <?php
                                    } else {
                                    }
                                    ?>
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

<?php include('student-footer.php'); ?>