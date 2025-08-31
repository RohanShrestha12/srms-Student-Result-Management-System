<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "0") {}else{header("location:../");}

// Set page title and include datatables
$page_title = "Import Students";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-upload me-2"></i>Import Students</h1>
        <p>Import multiple students from Excel file</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <div class="text-center mb-4">
                        <div class="import-header">
                            <i class="bi bi-upload display-1 text-success mb-3"></i>
                            <h3 class="tile-title">Import Students from Excel</h3>
                            <p class="text-muted">Upload an Excel file to import multiple students at once</p>
                        </div>
                    </div>
                    
                    <form enctype="multipart/form-data" action="admin/core/import_students" class="app_frm" method="POST" autocomplete="OFF">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-file-earmark-excel me-1"></i>Excel File
                            </label>
                            <div class="input-group">
                                <input required accept=".xlsx" type="file" name="file" class="form-control form-control-lg" accept="application/msexcel">
                                <button class="btn btn-outline-secondary" type="button" onclick="document.querySelector('input[name=file]').click()">
                                    <i class="bi bi-folder2-open"></i> Browse
                                </button>
                            </div>
                            <div class="form-text">Please upload an Excel file (.xlsx format)</div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-mortarboard me-1"></i>Select Class
                            </label>
                            <select class="form-control form-control-lg select2" name="class" required style="width: 100%;">
                                <option value="" selected disabled>Select Class</option>
                                <?php
                                try {
                                    // Use the connection from school.php instead of creating a new one
                                    // $conn is already available from school.php

                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                ?>
                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    echo '<option value="">Error loading classes</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>Download Template:</strong> 
                                    <a download href="templates/import_students.xlsx" class="alert-link">
                                        <i class="bi bi-download me-1"></i>Download Excel Template
                                    </a>
                                    <br>
                                    <small class="text-muted">Make sure your Excel file follows the template format for successful import.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" type="submit">
                                <i class="bi bi-upload me-2"></i>Import Students
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
        placeholder: 'Select Class',
        allowClear: true
    });
});
</script>

<?php include('admin-footer.php'); ?>
