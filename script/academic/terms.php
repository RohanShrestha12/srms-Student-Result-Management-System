<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/academic_dashboard.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title
$page_title = "Academic Terms";

// Include the academic header
include('academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-calendar-event me-2"></i>Academic Terms</h1>
        <p>Manage academic terms and semesters</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i>Add Term
            </button>
        </li>
    </ul>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add Academic Term
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_term.php">
                    <div class="mb-3">
                        <label class="form-label">Academic Term</label>
                        <input required name="name" class="form-control" type="text" placeholder="Enter Academic Term">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" required>
                            <option selected disabled value="">Select status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">
                            <i class="bi bi-check-circle me-1"></i>Add
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Academic Term
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_term.php">
                    <div class="mb-3">
                        <label class="form-label">Academic Term</label>
                        <input id="term" required name="name" class="form-control" type="text"
                            placeholder="Enter Academic Term">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="status" class="form-control" name="status" required>
                            <option selected disabled value="">Select status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">
                            <i class="bi bi-check-circle me-1"></i>Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-calendar-event me-2"></i>Academic Terms</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th width="120" align="center">Status</th>
                                <th width="150" align="center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Use the shared connection from config instead of creating a new one
                                $stmt = $conn->prepare("SELECT * FROM tbl_terms");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                foreach($result as $row)
                                {
                            ?>
                            <textarea style="display:none;" id="term_<?php echo $row[0]; ?>"><?php echo $row[1]; ?></textarea>
                            <tr>
                                <td><?php echo $row[1]; ?></td>
                                <td align="center">
                                    <?php if ($row[2] == "1") { 
                                        print '<span class="badge bg-success">ACTIVE</span>'; 
                                    } else { 
                                        print '<span class="badge bg-danger">INACTIVE</span>'; 
                                    } ?>
                                </td>
                                <td align="center">
                                    <button onclick="set_term('<?php echo $row[0]; ?>', '<?php echo $row[2]; ?>');" 
                                            class="btn btn-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </button>
                                    <button onclick="del('academic/core/drop_term.php?id=<?php echo $row[0]; ?>', 'Delete Academic Term?');" 
                                            class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                            <?php
                                }
                            } catch(PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Remove gradient styling and use solid colors */
.btn-primary {
    background-color: #007bff !important;
    border-color: #007bff !important;
}

.btn-primary:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
}

.btn-secondary {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.btn-secondary:hover {
    background-color: #545b62 !important;
    border-color: #545b62 !important;
}

.btn-danger {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
}

.btn-danger:hover {
    background-color: #c82333 !important;
    border-color: #c82333 !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

/* Remove any gradient backgrounds */
.dashboard-widget {
    background: #ffffff !important;
    border: 1px solid #e9ecef !important;
}

.widget-header {
    background: #f8f9fa !important;
    border-bottom: 1px solid #e9ecef !important;
}

.simple-header {
    background: #f8f9fa !important;
    border-bottom: 1px solid #e9ecef !important;
}

.simple-header h5 {
    color: #495057 !important;
    font-weight: 600;
}
</style>

<script>
function set_term(id, status) {
    document.getElementById('id').value = id;
    document.getElementById('term').value = document.getElementById('term_' + id).value;
    document.getElementById('status').value = status;
}
</script>

<?php include('academic-footer.php'); ?>