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
$page_title = "SMTP Settings";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-envelope me-2"></i>SMTP Settings</h1>
        <p>Configure email server settings for the system</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="text-center mb-4">
                    <div class="smtp-header">
                        <i class="bi bi-gear display-1 text-warning mb-3"></i>
                        <h3 class="tile-title">Email Server Configuration</h3>
                        <p class="text-muted">Configure SMTP settings for sending emails from the system</p>
                    </div>
                </div>
                
                <form class="app_frm" method="POST" autocomplete="OFF" action="admin/core/update_smtp.php">
                    <?php
                    try {
                        // Use the connection from school.php instead of creating a new one
                        // $conn is already available from school.php

                        $stmt = $conn->prepare("SELECT * FROM tbl_smtp");
                        $stmt->execute();
                        $result = $stmt->fetchAll();

                        if (count($result) > 0) {
                            foreach($result as $row) {
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-server me-1"></i>SMTP Server
                                    </label>
                                    <input required type="text" name="mail_server" value="<?php echo htmlspecialchars($row[1]); ?>" class="form-control form-control-lg" placeholder="e.g., smtp.gmail.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>SMTP Username
                                    </label>
                                    <input required type="text" name="mail_username" value="<?php echo htmlspecialchars($row[2]); ?>" class="form-control form-control-lg" placeholder="e.g., your-email@gmail.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-lock me-1"></i>SMTP Password
                                    </label>
                                    <div class="input-group">
                                        <input required type="password" id="mail_password" name="mail_password" value="<?php echo htmlspecialchars($row[3]); ?>" class="form-control form-control-lg" placeholder="Enter SMTP Password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('mail_password')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-hdd-network me-1"></i>SMTP Port
                                    </label>
                                    <input required type="text" name="mail_port" value="<?php echo htmlspecialchars($row[4]); ?>" class="form-control form-control-lg" placeholder="e.g., 587, 465, 25">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-shield-check me-1"></i>Security Connection
                            </label>
                            <select class="form-control form-control-lg" name="mail_security" required>
                                <option value="" selected disabled>Select Security Type</option>
                                <option <?php if ($row[5] == "ssl") { print ' selected ';}?> value="ssl">SSL (Secure Socket Layer)</option>
                                <option <?php if ($row[5] == "tls") { print ' selected ';}?> value="tls">TLS (Transport Layer Security)</option>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>SMTP Configuration:</strong> 
                                    These settings are used for sending emails from the system (notifications, reports, etc.).
                                    <br>
                                    <small class="text-muted">Common providers: Gmail (587/TLS), Outlook (587/TLS), Yahoo (465/SSL)</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="submit" value="1" class="btn btn-warning btn-lg">
                                <i class="bi bi-gear me-2"></i>Update SMTP Settings
                            </button>
                        </div>
                    <?php
                            }
                        } else {
                            echo '<div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>No SMTP Settings Found:</strong> Please contact your administrator to configure SMTP settings.
                                  </div>';
                        }
                    } catch(PDOException $e) {
                        echo '<div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Database Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
                              </div>';
                    }
                    ?>
                </form>
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
