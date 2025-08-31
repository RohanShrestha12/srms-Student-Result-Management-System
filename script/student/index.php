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
?>
<?php
// Set page title and include datatables
$page_title = "Student Dashboard";
$include_datatables = true;

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer2 me-2"></i>Student Dashboard</h1>
        <p>
            <?php
            $h = date('G');
            if ($h >= 5 && $h <= 11) {
                echo "Good morning " . $fname . "!";
            } else if ($h >= 12 && $h <= 15) {
                echo "Good afternoon " . $fname . "!";
            } else {
                echo "Good evening " . $fname . "!";
            }
            ?>
            Welcome to your student portal.
        </p>
    </div>
</div>

<!-- Analytics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="dashboard-card subjects">
            <div class="card-icon">
                <i class="bi bi-book"></i>
            </div>
            <div class="card-content">
                <h4>My Subjects</h4>
                <p class="card-number">
                    <?php
                    try {
                        // Get the student's class
                        $student_class = $class;
                        
                        // Query to count subjects for the student's class
                        // The class field stores serialized data like 'a:1:{i:0;s:2:"10";}'
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_subject_combinations 
                            LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id 
                            WHERE class LIKE ?");
                        $stmt->execute(['%"' . $student_class . '"%']);
                        $subject_count = $stmt->fetchColumn();
                        echo number_format($subject_count);
                    } catch (PDOException $e) {
                        echo "0";
                    }
                    ?>
                </p>
                <small class="card-description">Total subjects enrolled</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card results">
            <div class="card-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="card-content">
                <h4>Examination Results</h4>
                <p class="card-number">
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_exam_results WHERE student = ?");
                        $stmt->execute([$account_id]);
                        $result_count = $stmt->fetchColumn();
                        echo number_format($result_count);
                    } catch (PDOException $e) {
                        echo "0";
                    }
                    ?>
                </p>
                <small class="card-description">Results available</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card announcements">
            <div class="card-icon">
                <i class="bi bi-megaphone"></i>
            </div>
            <div class="card-content">
                <h4>Announcements</h4>
                <p class="card-number">
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_announcements WHERE level = '1' OR level = '2'");
                        $stmt->execute();
                        $announcement_count = $stmt->fetchColumn();
                        echo number_format($announcement_count);
                    } catch (PDOException $e) {
                        echo "0";
                    }
                    ?>
                </p>
                <small class="card-description">Active announcements</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card profile">
            <div class="card-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="card-content">
                <h4>My Profile</h4>
                <p class="card-number"><?php echo $class; ?></p>
                <small class="card-description">Current class</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-megaphone me-2"></i>Latest Announcements</h5>
                <p>Stay updated with important school announcements</p>
            </div>
            <div class="widget-content">
                <?php
                try {
                    $stmt = $conn->prepare("SELECT * FROM tbl_announcements WHERE level = '1' OR level = '2' ORDER BY id DESC LIMIT 5");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if (count($result) < 1) {
                ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>No announcements at the moment</strong>
                            <br>
                            <small>Check back later for updates from your school.</small>
                        </div>
                <?php
                    } else {
                        foreach ($result as $row) {
                ?>
                            <div class="announcement-item">
                                <div class="announcement-header">
                                    <h6 class="announcement-title">
                                        <i class="bi bi-bell me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($row[1]); ?>
                                    </h6>
                                    <small class="announcement-date">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo htmlspecialchars($row[3]); ?>
                                    </small>
                                </div>
                                <div class="announcement-content">
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($row[2])); ?></p>
                                </div>
                            </div>
                <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Database Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                <p>Access your most important features</p>
            </div>
            <div class="widget-content">
                <div class="quick-actions">
                    <a href="student/view.php" class="quick-action-item">
                        <div class="action-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="action-content">
                            <span class="action-title">My Profile</span>
                            <small class="action-description">View and update your information</small>
                        </div>
                    </a>
                    <a href="student/subjects.php" class="quick-action-item">
                        <div class="action-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="action-content">
                            <span class="action-title">My Subjects</span>
                            <small class="action-description">View your enrolled subjects</small>
                        </div>
                    </a>
                    <a href="student/results.php" class="quick-action-item">
                        <div class="action-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="action-content">
                            <span class="action-title">My Results</span>
                            <small class="action-description">Check your examination scores</small>
                        </div>
                    </a>
                    <a href="student/grading-system.php" class="quick-action-item">
                        <div class="action-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="action-content">
                            <span class="action-title">Grading System</span>
                            <small class="action-description">Understand grade criteria</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('student-footer.php'); ?>