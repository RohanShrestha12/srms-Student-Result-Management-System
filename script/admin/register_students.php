<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "0") {}else{header("location:../");}

// Set page title and include datatables
$page_title = "Register Students";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-plus me-2"></i>Register Students</h1>
        <p>Add new students to the system</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <div class="text-center mb-4">
                        <div class="registration-header">
                            <i class="bi bi-person-plus display-1 text-primary mb-3"></i>
                            <h3 class="tile-title">Student Registration Form</h3>
                            <p class="text-muted">Please fill in all required fields to register a new student</p>
                        </div>
                    </div>
                    
                    <form enctype="multipart/form-data" action="admin/core/new_student.php" class="app_frm" method="POST" autocomplete="OFF">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-card-text me-1"></i>Registration Number
                                    </label>
                                    <input name="regno" required class="form-control form-control-lg" type="text" placeholder="Enter registration number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-envelope me-1"></i>Email Address
                                    </label>
                                    <input name="email" required class="form-control form-control-lg" type="email" placeholder="Enter email address">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>First Name
                                    </label>
                                    <input name="fname" required class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter first name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Middle Name
                                    </label>
                                    <input name="mname"  class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter middle name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Last Name
                                    </label>
                                    <input name="lname" required class="form-control form-control-lg" type="text" onkeypress="return lettersOnly(event)" placeholder="Enter last name">
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
                            </div>
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

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image me-1"></i>Display Image (Optional)
                            </label>
                            <div class="input-group">
                                <input name="image" class="form-control form-control-lg" type="file" accept=".png, .jpg, .jpeg">
                                <button class="btn btn-outline-secondary" type="button" onclick="document.querySelector('input[name=image]').click()">
                                    <i class="bi bi-upload"></i> Choose File
                                </button>
                            </div>
                            <div class="form-text">Accepted formats: PNG, JPG, JPEG (Max size: 2MB)</div>
                        </div>

                        <div class="d-grid">
                            <button id="sub_btnp2" class="btn btn-primary btn-lg" type="submit">
                                <i class="bi bi-person-plus me-2"></i>Register Student
                            </button>
                        </div>
                    </form>
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
