<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "3") {
} else {
    header("location:../");
}

// Set page title
$page_title = "Settings";

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-gear me-2"></i>Settings</h1>
        <p>Manage your account settings and preferences</p>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-md-6 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-person me-2"></i>Profile Information</h5>
            </div>
            <div class="widget-content">
                <form method="POST" action="student/core/update_profile.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="mname" name="mname" value="<?php echo htmlspecialchars($mname); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registration Number</label>
                            <input type="text" class="form-control" value="<?php echo $account_id; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Class</label>
                            <input type="text" class="form-control" value="<?php 
                                try {
                                    $stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
                                    $stmt->execute([$class]);
                                    $class_result = $stmt->fetch();
                                    echo $class_result ? $class_result['name'] : 'Class ' . $class;
                                } catch (PDOException $e) {
                                    echo 'Class ' . $class;
                                }
                            ?>" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Image -->
    <div class="col-md-6 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-image me-2"></i>Profile Image</h5>
            </div>
            <div class="widget-content">
                <div class="text-center mb-4">
                    <div class="profile-photo-container">
                        <?php
                        if ($display_image == "DEFAULT" || empty($display_image)) {
                            echo '<img src="images/students/' . $gender . '.png" class="profile-photo-large" alt="Profile Photo">';
                        } else {
                            echo '<img src="images/students/' . $display_image . '" class="profile-photo-large" alt="Profile Photo">';
                        }
                        ?>
                    </div>
                </div>
                <form method="POST" action="student/core/update_image.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                        <div class="form-text">Maximum file size: 5MB. Supported formats: JPG, PNG, GIF</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Update Image
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password -->
<div class="row">
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-lock me-2"></i>Change Password</h5>
            </div>
            <div class="widget-content">
                <form method="POST" action="student/core/update_password.php">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text">Password must be at least 6 characters long</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-photo-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
}

.alert {
    border-radius: 8px;
    border: none;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

<?php include('student-footer.php'); ?>