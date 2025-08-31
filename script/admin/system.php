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
$page_title = "System Settings";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-gear me-2"></i>System Settings</h1>
        <p>Configure system-wide settings and branding</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <div class="text-center mb-4">
                    <div class="system-header">
                        <i class="bi bi-building display-1 text-primary mb-3"></i>
                        <h3 class="tile-title">School Configuration</h3>
                        <p class="text-muted">Update school information and branding settings</p>
                    </div>
                </div>
                
                <form class="app_frm" method="POST" enctype="multipart/form-data" autocomplete="OFF" action="admin/core/update_system.php">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-building me-1"></i>School Name
                        </label>
                        <input required type="text" name="name" value="<?php echo htmlspecialchars(WBName); ?>" class="form-control form-control-lg" placeholder="Enter School Name">
                        <div class="form-text">This name will be displayed throughout the system</div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image me-1"></i>School Logo
                        </label>
                        <div class="input-group">
                            <input type="file" name="company_logo" class="form-control form-control-lg" accept="image/*">
                            <button class="btn btn-outline-secondary" type="button" onclick="document.querySelector('input[name=company_logo]').click()">
                                <i class="bi bi-upload"></i> Choose File
                            </button>
                        </div>
                        <div class="form-text">Recommended: PNG, JPG, or JPEG format (Max size: 2MB)</div>
                        
                        <?php if (!empty(WBLogo)): ?>
                        <div class="mt-2">
                            <small class="text-muted">Current Logo:</small>
                            <img src="images/logo/<?php echo htmlspecialchars(WBLogo); ?>" alt="Current Logo" class="img-thumbnail" style="max-height: 50px; max-width: 150px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <input type="hidden" name="old_logo" value="<?php echo htmlspecialchars(WBLogo); ?>">
                    
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-3 fs-4"></i>
                            <div>
                                <strong>System Configuration:</strong> 
                                These settings affect the overall appearance and branding of the system.
                                <br>
                                <small class="text-muted">Changes will be reflected across all pages and user interfaces.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">
                            <i class="bi bi-gear me-2"></i>Update System Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('admin-footer.php'); ?>
