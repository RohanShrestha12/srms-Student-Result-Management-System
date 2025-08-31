<?php
// Set page title and include DataTables
$page_title = "Export Students";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-download me-2"></i>Export Students</h1>
        <p>Export student data to CSV format for external use.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-file-earmark-arrow-down me-2"></i>Export Configuration</h5>
            </div>
            <div class="widget-content">
                <form class="app_frm" method="POST" autocomplete="OFF" action="teacher/core/export_students.php">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Class</label>
                        <div class="class-checkboxes" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                            <?php
                            try {
                                // Use the centralized connection from school.php
                                if (!isset($conn) || $conn === null) {
                                    throw new Exception("Database connection not available");
                                }

                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                                  LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
                                  LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id WHERE tbl_subject_combinations.teacher = ?");
                                $stmt->execute([$account_id]);
                                $result = $stmt->fetchAll();

                                $myclasses = array();

                                foreach ($result as $value) {
                                    $class_arr = unserialize($value[1]);

                                    foreach ($class_arr as $value) {
                                        array_push($myclasses, $value);
                                    }
                                }

                                if (count($myclasses) > 0) {
                                    $matches = str_split(str_repeat("?", count($myclasses)));
                                    $matches = implode(",", $matches);

                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id IN ($matches)");
                                    $stmt->execute($myclasses);
                                    $result = $stmt->fetchAll();

                                    if (count($result) < 1) {
                                        echo '<div class="alert alert-warning mb-0">No classes available for export.</div>';
                                    } else {
                                        foreach($result as $row) {
                                            ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input class-radio" type="radio" name="class" value="<?php echo $row[0]; ?>" id="export_class_<?php echo $row[0]; ?>" required>
                                                <label class="form-check-label" for="export_class_<?php echo $row[0]; ?>" style="cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: all 0.3s ease; display: block;">
                                                    <div class="d-flex align-items-center">
                                                        <div style="width: 30px; height: 30px; background: linear-gradient(135deg, #00695C 0%, #00594e 100%); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                                            <i class="bi bi-house text-white" style="font-size: 12px;"></i>
                                                        </div>
                                                        <div>
                                                            <strong style="font-size: 14px; color: #2c3e50;"><?php echo $row[1]; ?></strong>
                                                            <br>
                                                            <small class="text-muted">Class</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo '<div class="alert alert-info mb-0">No classes assigned to you yet.</div>';
                                }

                            } catch(Exception $e) {
                                echo '<div class="alert alert-danger mb-0">Connection failed: ' . $e->getMessage() . '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">
                        <i class="bi bi-download me-2"></i>Export Students
                    </button>
                </form>

                <?php
                if (isset($_SESSION['export_file'])) {
                    $file = $_SESSION['export_file'];
                    $filePath = 'import_sheets/' . $file;
                    ?>
                    <div class="alert alert-success mt-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-3 fs-4 text-success"></i>
                            <div>
                                <h6 class="mb-1">Export Successful!</h6>
                                <p class="mb-2">Student data has been exported to: <strong><?php echo $file; ?></strong></p>
                                <?php if (file_exists($filePath)) { ?>
                                    <a href="<?php echo $filePath; ?>" class="btn btn-sm btn-success" download>
                                        <i class="bi bi-download me-1"></i>Download CSV
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION['export_file']);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-info-circle me-2"></i>Export Information</h5>
            </div>
            <div class="widget-content">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>About CSV Export:</h6>
                    <ul class="mb-0">
                        <li>Exports student data in CSV format</li>
                        <li>Includes registration numbers and full names</li>
                        <li>Ready for import into spreadsheet applications</li>
                        <li>Can be used for external data processing</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>You can only export students from your assigned classes</li>
                        <li>Files are saved in the import_sheets directory</li>
                        <li>Download the file immediately after export</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>
