<?php
// Set page title and include DataTables
$page_title = "List Students";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-people me-2"></i>List Students</h1>
        <p>Select classes to view and manage your students.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-list-ul me-2"></i>Student List Generator</h5>
            </div>
            <div class="widget-content">
                <form class="app_frm" method="POST" autocomplete="OFF" action="teacher/core/list_students.php">
                    <div class="mb-3">
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
                                        echo '<div class="alert alert-warning mb-0">No classes available for selection.</div>';
                                    } else {
                                        foreach($result as $row) {
                                            ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input class-radio" type="radio" name="class[]" value="<?php echo $row[0]; ?>" id="class_<?php echo $row[0]; ?>" required>
                                                <label class="form-check-label" for="class_<?php echo $row[0]; ?>" style="cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: all 0.3s ease; display: block;">
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
                        <i class="bi bi-search me-2"></i>List Students
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-info-circle me-2"></i>Instructions</h5>
            </div>
            <div class="widget-content">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>How to use this feature:</h6>
                    <ol class="mb-0">
                        <li>Select a class from the list above</li>
                        <li>Click "List Students" to view all students in that class</li>
                        <li>You can then view, export, or manage student data</li>
                    </ol>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Note:</h6>
                    <p class="mb-0">You can only view students from classes that you are assigned to teach.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>