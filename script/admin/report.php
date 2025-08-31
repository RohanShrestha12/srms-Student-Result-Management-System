<?php
// Set page title and include datatables
$page_title = "Report Tool";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-graph-up me-2"></i>Report Tool</h1>
        <p>Generate comprehensive student reports</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <div class="text-center mb-4">
                        <div class="report-header">
                            <i class="bi bi-file-earmark-text display-1 text-info mb-3"></i>
                            <h3 class="tile-title">Generate Student Reports</h3>
                            <p class="text-muted">Select class and term to generate comprehensive reports</p>
                        </div>
                    </div>
                    
                    <form enctype="multipart/form-data" action="admin/core/start_report.php" class="app_frm" method="POST" autocomplete="OFF">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-mortarboard me-1"></i>Select Class
                            </label>
                            <select class="form-control form-control-lg select2" name="student" required style="width: 100%;">
                                <option value="" selected disabled>Select Class</option>
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
                                        echo '<option value="" disabled>No classes found</option>';
                                    }
                                } catch(PDOException $e) {
                                    echo '<option value="" disabled>Error loading classes: ' . htmlspecialchars($e->getMessage()) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar-event me-1"></i>Select Term
                            </label>
                            <select class="form-control form-control-lg select2" name="term" required style="width: 100%;">
                                <option selected disabled value="">Select Term</option>
                                <?php
                                try {
                                    // Use the connection from school.php instead of creating a new one
                                    // $conn is already available from school.php

                                    $stmt = $conn->prepare("SELECT id, name FROM tbl_terms WHERE status = '1' ORDER BY name ASC");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($result) > 0) {
                                        foreach($result as $row) {
                                ?>
                                            <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                <?php
                                        }
                                    } else {
                                        echo '<option value="" disabled>No active terms found</option>';
                                    }
                                } catch(PDOException $e) {
                                    echo '<option value="" disabled>Error loading terms: ' . htmlspecialchars($e->getMessage()) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>Report Generation:</strong> 
                                    This will generate a comprehensive report for all students in the selected class and term.
                                    <br>
                                    <small class="text-muted">The report will include academic performance, attendance, and other relevant data.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-info btn-lg" type="submit">
                                <i class="bi bi-file-earmark-text me-2"></i>Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Option',
        allowClear: true
    });
});
</script>

<?php include('admin-footer.php'); ?>
