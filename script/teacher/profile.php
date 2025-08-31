<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "2") {}else{header("location:../");}
?>
<?php
// Set page title
$page_title = "My Profile";
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person me-2"></i>My Profile</h1>
        <p>View and manage your account information.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-person-circle me-2"></i>Profile Information</h5>
            </div>
            <div class="widget-content">
                <div class="mb-4">
                    <label class="form-label fw-bold">First Name</label>
                    <input value="<?php echo $fname; ?>" required disabled name="fname" class="form-control" type="text" placeholder="Enter first name">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Last Name</label>
                    <input value="<?php echo $lname; ?>" required disabled name="lname" class="form-control" type="text" placeholder="Enter last name">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Email Address</label>
                    <input value="<?php echo $email; ?>" required disabled name="email" class="form-control" type="email" placeholder="Enter email address">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Gender</label>
                    <select disabled class="form-control" name="gender" required>
                        <option selected disabled value="">Select gender</option>
                        <option <?php if ($gender == "Male") { print ' selected '; } ?> value="Male">Male</option>
                        <option <?php if ($gender == "Female") { print ' selected '; } ?> value="Female">Female</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-lock me-2"></i>Change Password</h5>
            </div>
            <div class="widget-content">
                <form class="app_frm" action="teacher/core/update_password" method="POST" autocomplete="off">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" class="form-control" id="cpass" name="cpassword" placeholder="Enter your current password">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" class="form-control" id="npass" name="npassword" placeholder="Enter your new password">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" class="form-control" id="cnpass" placeholder="Repeat your new password">
                    </div>

                    <button type="submit" id="sub_btnp" name="submit" value="1" class="btn btn-primary app_btn">
                        <i class="bi bi-lock me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>
