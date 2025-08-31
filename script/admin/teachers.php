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

// Set page title and include datatables
$page_title = "Teachers";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-workspace me-2"></i>Teachers</h1>
        <p>Manage all teachers in the system</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-person-plus me-1"></i>Add Teacher
            </button>
        </li>
        <li class="breadcrumb-item">
            <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i>Import Teachers
            </button>
        </li>
    </ul>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Add New Teacher
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="admin/core/new_user2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-person me-1"></i>First Name
                                </label>
                                <input required name="fname" class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-person me-1"></i>Last Name
                                </label>
                                <input required name="lname" class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter last name">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input required name="email" class="form-control form-control-lg" type="email" placeholder="Enter email address">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-lock me-1"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="npass" name="password" placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('npass')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-lock-fill me-1"></i>Confirm Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="cnpass" placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('cnpass')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                </label>
                                <select class="form-control form-control-lg" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-toggle-on me-1"></i>Status
                                </label>
                                <select class="form-control form-control-lg" name="status" required>
                                    <option selected disabled value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button id="sub_btnp2" type="submit" name="submit" value="1" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>Add Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="admin/core/update_user2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-person me-1"></i>First Name
                                </label>
                                <input id="fname" required name="fname" class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-person me-1"></i>Last Name
                                </label>
                                <input id="lname" required name="lname" class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter last name">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input id="email" required name="email" class="form-control form-control-lg" type="email" placeholder="Enter email address">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                </label>
                                <select id="gender" class="form-control form-control-lg" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-toggle-on me-1"></i>Status
                                </label>
                                <select id="status" class="form-control form-control-lg" name="status" required>
                                    <option selected disabled value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" id="id">
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" name="submit" value="1" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Teachers Modal -->
<div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bi bi-upload me-2"></i>Import Teachers
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" class="app_frm" method="POST" autocomplete="OFF" action="admin/core/import_users">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-file-earmark-excel me-1"></i>Excel File
                        </label>
                        <input required accept=".xlsx" type="file" name="file" class="form-control form-control-lg" accept="application/msexcel">
                        <div class="form-text">Please upload an Excel file (.xlsx format)</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Download Template:</strong> 
                        <a download href="templates/import_teachers.xlsx" class="alert-link">
                            <i class="bi bi-download me-1"></i>Download Excel Template
                        </a>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" name="submit" value="1" class="btn btn-success">
                            <i class="bi bi-upload me-1"></i>Import Teachers
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="tile-title mb-0">
                            <i class="bi bi-people me-2"></i>Teachers List
                        </h3>
                        <div class="table-actions">
                            <span class="badge bg-primary">Total: <span id="teacherCount">0</span></span>
                        </div>
                    </div>
                    
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-person me-1"></i>First Name</th>
                                <th><i class="bi bi-person me-1"></i>Last Name</th>
                                <th><i class="bi bi-envelope me-1"></i>Email</th>
                                <th><i class="bi bi-gender-ambiguous me-1"></i>Gender</th>
                                <th width="120" align="center"><i class="bi bi-toggle-on me-1"></i>Status</th>
                                <th width="150" align="center"><i class="bi bi-gear me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        try {
                            // Use the connection from school.php instead of creating a new one
                            // $conn is already available from school.php

                            $stmt = $conn->prepare("SELECT * FROM tbl_staff WHERE level > 1");
                            $stmt->execute();
                            $result = $stmt->fetchAll();

                            foreach($result as $row) {
                                if ($row[7] == "1") {
                                    $st = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>';
                                } else {
                                    $st = '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Blocked</span>';
                                }
                        ?>
                            <tr>
                                <td><strong><?php echo $row[1];?></strong></td>
                                <td><strong><?php echo $row[2];?></strong></td>
                                <td>
                                    <i class="bi bi-envelope me-1 text-muted"></i>
                                    <?php echo $row[4];?>
                                </td>
                                <td>
                                    <i class="bi bi-gender-ambiguous me-1 text-muted"></i>
                                    <?php echo $row[3];?>
                                </td>
                                <td width="100" align="center"><?php echo $st;?></td>
                                <td width="150" align="center">
                                    <textarea style="display:none;" id="fname_<?php echo $row[0]; ?>"><?php echo $row[1]; ?></textarea>
                                    <textarea style="display:none;" id="lname_<?php echo $row[0]; ?>"><?php echo $row[2]; ?></textarea>
                                    <textarea style="display:none;" id="email_<?php echo $row[0]; ?>"><?php echo $row[4]; ?></textarea>
                                    
                                    <div class="btn-group" role="group">
                                        <button onclick="set_user('<?php echo $row[0]; ?>', '<?php echo $row[3]; ?>', '<?php echo $row[7]; ?>');" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-warning btn-sm" type="button" title="Edit Teacher">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a onclick="del('admin/core/drop_user.php?id=<?php echo $row[0]; ?>', 'Delete Teacher?');" href="javascript:void(0);" class="btn btn-danger btn-sm" title="Delete Teacher">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } catch(PDOException $e) {
                            echo '<tr><td colspan="6" class="text-center text-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Connection failed: ' . $e->getMessage() . '
                                  </td></tr>';
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Update teacher count
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('srmsTable');
    const tbody = table.querySelector('tbody');
    const rowCount = tbody.querySelectorAll('tr').length;
    document.getElementById('teacherCount').textContent = rowCount;
});
</script>

<?php include('admin-footer.php'); ?>
