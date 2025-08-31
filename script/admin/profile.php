<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/academic_dashboard.php');
if ($res == "1" && $level == "0") {
} else {
    header("location:../");
}

// Set page title
$page_title = "My Profile";

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person me-2"></i>My Profile</h1>
        <p>Manage your profile information and change your password.</p>
    </div>
</div>

<div class="row">

    <div class="tile">
        <h3 class="tile-title">Profile Information</h3>
        <div class="tile-body">
            <form class="app_frm" action="admin/core/update_profile.php" method="POST" autocomplete="off">

                <div class="mb-2">
                    <label class="form-label">First Name</label>
                    <input value="<?php echo $fname; ?>" required name="fname" onkeypress="return lettersOnly(event)"
                        class="form-control" type="text" placeholder="Enter first name">
                </div>
                <div class="mb-2">
                    <label class="form-label">Last Name</label>
                    <input value="<?php echo $lname; ?>" required name="lname" onkeypress="return lettersOnly(event)"
                        class="form-control" type="text" placeholder="Enter last name">
                </div>
                <div class="mb-2">
                    <label class="form-label">Email Address</label>
                    <input value="<?php echo $email; ?>" required name="email" class="form-control" type="email"
                        placeholder="Enter email address">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-control" name="gender" required>
                        <option selected disabled value="">Select gender</option>
                        <option <?php if ($gender == "Male") {
                            print ' selected ';
                        } ?> value="Male">Male</option>
                        <option <?php if ($gender == "Female") {
                            print ' selected ';
                        } ?> value="Female">Female</option>
                    </select>
                </div>

                <button type="submit" name="submit" value="1" class="btn btn-primary">Update Profile</button>

            </form>
        </div>
    </div>
</div>

<div class="row">

    <div class="tile">
        <h3 class="tile-title">Change Password</h3>
        <div class="tile-body">

            <form class="app_frm" action="admin/core/update_password.php" method="POST" autocomplete="off">

                <div class="mb-2">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="cpass" name="cpassword"
                        placeholder="Enter your current password">
                </div>
                <div class="mb-2">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" id="npass" name="npassword"
                        placeholder="Enter your new password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="cnpass" name="cnpassword"
                        placeholder="Repeat your new password">
                </div>

                <button type="submit" id="sub_btnp" name="submit" value="1" class="btn btn-primary">Change
                    Password</button>

            </form>
        </div>
    </div>
</div>

<?php include('admin-footer.php'); ?>