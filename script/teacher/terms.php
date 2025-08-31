<?php
// Set page title and include DataTables
$page_title = "Academic Terms";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-calendar-event me-2"></i>Academic Terms</h1>
        <p>View and manage academic terms and their current status.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-calendar-check me-2"></i>Academic Terms Overview</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-calendar me-2"></i>Term Name</th>
                                <th width="150" class="text-center"><i class="bi bi-toggle-on me-2"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Use the centralized connection from school.php
                                if (!isset($conn) || $conn === null) {
                                    throw new Exception("Database connection not available");
                                }

                                $stmt = $conn->prepare("SELECT * FROM tbl_terms");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) < 1) {
                            ?>
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-event fs-1 mb-3 d-block"></i>
                                            <h5>No Academic Terms Found</h5>
                                            <p>No academic terms have been configured yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                } else {
                                    foreach ($result as $row) {
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="term-icon me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #00695C 0%, #00594e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-calendar-event text-white"></i>
                                            </div>
                                            <div>
                                                <strong class="text-primary"><?php echo $row[1]; ?></strong>
                                                <br>
                                                <small class="text-muted">Academic Term</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row[2] == "1") { ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>ACTIVE
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>INACTIVE
                                            </span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                                    }
                                }
                            } catch(Exception $e) {
                            ?>
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <div class="alert alert-danger">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Connection Error:</strong> <?php echo $e->getMessage(); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>