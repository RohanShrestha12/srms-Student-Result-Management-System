<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title and include datatables
$page_title = "Classes Management";
$include_datatables = true;

// Include the academic header
include('academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-building me-2"></i>Classes Management</h1>
        <p>Manage academic classes and divisions</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Class
            </button>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Classes List</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th>Class Name</th>
                                <th width="120" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name ASC");
                                $stmt->execute();
                                $classes = $stmt->fetchAll();
                                
                                if (count($classes) > 0) {
                                    $counter = 1;
                                    foreach($classes as $class) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter; ?></td>
                                    <td>
                                        <strong class="text-primary"><?php echo htmlspecialchars($class['name']); ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm" onclick="editClass(<?php echo $class['id']; ?>, '<?php echo htmlspecialchars($class['name']); ?>')" title="Edit Class">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteClass(<?php echo $class['id']; ?>)" title="Delete Class">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                                        $counter++;
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-building fs-1 mb-3 d-block"></i>
                                            <h5>No Classes Found</h5>
                                            <p>No classes have been added yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            } catch(Exception $e) {
                            ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
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

<!-- Add Class Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_class.php">
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input required name="name" class="form-control" type="text" placeholder="Enter Class Name">
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">
                            <i class="bi bi-check-circle me-2"></i>Add Class
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Class Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_class.php">
                    <input type="hidden" name="id" id="edit_class_id">
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input required name="name" id="edit_class_name" class="form-control" type="text" placeholder="Enter Class Name">
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">
                            <i class="bi bi-check-circle me-2"></i>Update Class
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editClass(id, name) {
    document.getElementById('edit_class_id').value = id;
    document.getElementById('edit_class_name').value = name;
    $('#editModal').modal('show');
}

function deleteClass(id) {
    if (confirm('Are you sure you want to delete this class? This action cannot be undone.')) {
        window.location.href = 'academic/core/drop_class.php?id=' + id;
    }
}
</script>

<?php include('academic-footer.php'); ?>
