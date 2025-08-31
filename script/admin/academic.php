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

// Set page title
$page_title = "Academic Account";

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-workspace me-2"></i>Academic Account</h1>
        <p>Manage the academic account for the system</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <?php
                try {
                    // Use the connection from school.php instead of creating a new one
                    // $conn is already available from school.php
                
                    $stmt = $conn->prepare("SELECT * FROM tbl_staff WHERE level = '1'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if (count($result) < 1) {
                        ?>
                        <div class="text-center mb-4">
                            <div class="empty-state">
                                <i class="bi bi-person-plus display-1 text-muted"></i>
                                <h4 class="mt-3">No Academic Account Found</h4>
                                <p class="text-muted">Create the first academic account for the system</p>
                            </div>
                        </div>

                        <form class="app_frm" method="POST" autocomplete="OFF" action="admin/core/new_user.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person me-1"></i>First Name
                                        </label>
                                        <input required name="fname" class="form-control form-control-lg" type="text"
                                            onkeypress="return lettersOnly(event)" placeholder="Enter first name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person me-1"></i>Last Name
                                        </label>
                                        <input required name="lname" class="form-control form-control-lg" type="text"
                                            onkeypress="return lettersOnly(event)" placeholder="Enter last name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-envelope me-1"></i>Email Address
                                </label>
                                <input required name="email" class="form-control form-control-lg" type="email"
                                    placeholder="Enter email address">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-lock me-1"></i>Password
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control form-control-lg" id="npass"
                                                name="password" placeholder="Enter password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('npass')">
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
                                            <input type="password" class="form-control form-control-lg" id="cnpass"
                                                placeholder="Confirm password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('cnpass')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                </label>
                                <select class="form-control form-control-lg" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button id="sub_btnp2" type="submit" name="submit" value="1" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Create Academic Account
                                </button>
                            </div>
                        </form>
                        <?php
                    } else {
                        foreach ($result as $row) {
                            ?>
                            <div class="academic-profile">
                                <div class="profile-header text-center mb-4">
                                    <div class="profile-avatar">
                                        <i class="bi bi-person-circle display-1 text-primary"></i>
                                    </div>
                                    <h3 class="mt-3"><?php echo $row[1] . ' ' . $row[2]; ?></h3>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-envelope me-1"></i><?php echo $row[4]; ?>
                                    </p>
                                    <span class="badge bg-success mt-2">
                                        <i class="bi bi-shield-check me-1"></i>Academic Account
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="bi bi-person me-1"></i>First Name
                                            </label>
                                            <input value="<?php echo $row[1]; ?>" disabled name="fname"
                                                class="form-control form-control-lg bg-light" type="text"
                                                placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="bi bi-person me-1"></i>Last Name
                                            </label>
                                            <input value="<?php echo $row[2]; ?>" disabled required name="lname"
                                                class="form-control form-control-lg bg-light" type="text"
                                                placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="bi bi-envelope me-1"></i>Email Address
                                    </label>
                                    <input value="<?php echo $row[4]; ?>" disabled required name="email"
                                        class="form-control form-control-lg bg-light" type="email"
                                        placeholder="Enter email address">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                    </label>
                                    <select disabled class="form-control form-control-lg bg-light" name="gender" required>
                                        <option selected disabled value="">Select gender</option>
                                        <option <?php if ($row[4] == "Male") {
                                            print ' selected ';
                                        } ?> value="Male">Male</option>
                                        <option <?php if ($row[4] == "Female") {
                                            print ' selected ';
                                        } ?> value="Female">Female
                                        </option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Note:</strong> Academic account already exists. You can only delete it.
                                    </div>

                                    <a onclick="del('admin/core/drop_user.php?id=<?php echo $row[0]; ?>', 'Delete Academic Account?');"
                                        href="javascript:void(0);" class="btn btn-danger btn-lg">
                                        <i class="bi bi-trash me-2"></i>Delete Academic Account
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Connection failed: ' . $e->getMessage() . '
                          </div>';
                }
                ?>
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
</script>

<?php include('admin-footer.php'); ?>