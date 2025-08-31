<?php
// Set page title and include datatables
$page_title = "Manage Students";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-people me-2"></i>Manage Students</h1>
        <p>View and manage all students in the system</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <div class="text-center mb-4">
                        <div class="manage-header">
                            <i class="bi bi-people display-1 text-primary mb-3"></i>
                            <h3 class="tile-title">Student Management</h3>
                            <p class="text-muted">Select classes to view and manage students</p>
                        </div>
                    </div>
                    
                    <form class="app_frm" method="POST" autocomplete="OFF" action="admin/core/list_students.php">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-mortarboard me-1"></i>Select Classes
                            </label>
                            <select multiple="true" class="form-control form-control-lg select2" name="class[]" required style="width: 100%;">
                                <?php
                                try {
                                    // Use the connection from school.php instead of creating a new one
                                    // $conn is already available from school.php

                                    $stmt = $conn->prepare("SELECT id, name FROM tbl_classes ORDER BY name ASC");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($result) > 0) {
                                        foreach($result as $row) {
                                ?>
                                            <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                <?php
                                        }
                                    } else {
                                        echo '<option value="" disabled>No classes found in database</option>';
                                    }
                                } catch(PDOException $e) {
                                    echo '<option value="" disabled>Error loading classes: ' . htmlspecialchars($e->getMessage()) . '</option>';
                                }
                                ?>
                            </select>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple classes</div>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>Multiple Selection:</strong> 
                                    You can select multiple classes to view all students from those classes at once.
                                    <br>
                                    <small class="text-muted">Use Ctrl+Click (or Cmd+Click on Mac) to select multiple classes.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">
                                <i class="bi bi-search me-2"></i>Manage Students
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Select2 with multiple selection
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Classes',
        allowClear: true,
        multiple: true,
        closeOnSelect: false
    });
});
</script>

<?php include('admin-footer.php'); ?>