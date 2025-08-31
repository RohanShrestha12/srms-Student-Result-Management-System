<?php
// Set page title
$page_title = "Teacher Dashboard";
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer2 me-2"></i>Teacher Dashboard</h1>
        <p>Welcome back, <?php echo $fname; ?>! Here's your teaching overview.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card subjects">
            <div class="card-icon">
                <i class="bi bi-book"></i>
            </div>
            <div class="card-content">
                <h4>Subjects</h4>
                <p class="card-number"><?php echo number_format($my_subject); ?></p>
                <small class="card-description">Your assigned subjects</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card classes">
            <div class="card-icon">
                <i class="bi bi-house"></i>
            </div>
            <div class="card-content">
                <h4>Classes</h4>
                <p class="card-number"><?php echo number_format($my_class); ?></p>
                <small class="card-description">Classes you teach</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card students">
            <div class="card-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="card-content">
                <h4>Students</h4>
                <p class="card-number"><?php echo number_format($my_students); ?></p>
                <small class="card-description">Total students</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-megaphone me-2"></i>Announcements</h5>
            </div>
            <div class="widget-content">
                <?php
                try {
                    $stmt = $conn->prepare("SELECT * FROM tbl_announcements WHERE level = '0' OR level = '2' ORDER BY id DESC");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if (count($result) < 1) {
                        ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>No announcements at the moment</strong>
                        </div>
                        <?php
                    } else {
                        foreach ($result as $row) {
                            ?>
                            <div class="announcement-item">
                                <div class="announcement-header">
                                    <h6 class="announcement-title"><?php echo $row[1]; ?></h6>
                                    <small class="announcement-date"><?php echo $row[3]; ?></small>
                                </div>
                                <p class="announcement-content"><?php echo $row[2]; ?></p>
                            </div>
                            <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="widget-content">
                <div class="quick-actions">
                    <a href="teacher/list_students.php" class="quick-action-item">
                        <i class="bi bi-people"></i>
                        <span>View Students</span>
                    </a>
                    <a href="teacher/import_results.php" class="quick-action-item">
                        <i class="bi bi-upload"></i>
                        <span>Import Results</span>
                    </a>
                    <a href="teacher/manage_results.php" class="quick-action-item">
                        <i class="bi bi-eye"></i>
                        <span>View Results</span>
                    </a>
                    <a href="teacher/combinations.php" class="quick-action-item">
                        <i class="bi bi-collection"></i>
                        <span>Subject Combinations</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>