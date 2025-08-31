<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title for header
$page_title = 'Grading System';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-award me-2"></i>Grading System</h1>
        <p>Manage grade definitions and scoring criteria</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Grade
            </button>
        </li>
    </ul>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Grades</h5>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_grade.php">
                    <div class="mb-2">
                        <label class="form-label">Grade Name</label>
                        <input required type="text" name="grade_name" class="form-control txt-cap" placeholder="Enter grade name">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Minimum Percentage</label>
                        <input required type="number" name="min" class="form-control txt-cap" placeholder="Enter minimum percentage">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Maximum Percentage</label>
                        <input required type="number" name="max" class="form-control txt-cap" placeholder="Enter maximum percentage">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <input required type="text" name="remark" class="form-control txt-cap" placeholder="Enter Grade Remark">
                    </div>

                    <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Grade</h5>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_grade.php">
                    <div class="mb-2">
                        <label class="form-label">Grade Name</label>
                        <input id="grade" required type="text" name="grade_name" class="form-control txt-cap" placeholder="Enter grade name">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Minimum Percentage</label>
                        <input id="min" required type="number" name="min" class="form-control txt-cap" placeholder="Enter minimum percentage">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Maximum Percentage</label>
                        <input id="max" required type="number" name="max" class="form-control txt-cap" placeholder="Enter maximum percentage">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <input id="remark" required type="text" name="remark" class="form-control txt-cap" placeholder="Enter Grade Remark">
                    </div>

                    <input type="hidden" name="id" id="id">
                    <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Grading System</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-award me-2"></i>Grade Name</th>
                                <th><i class="bi bi-arrow-down me-2"></i>Minimum Score</th>
                                <th><i class="bi bi-arrow-up me-2"></i>Maximum Score</th>
                                <th><i class="bi bi-chat-text me-2"></i>Remark</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_grade_system ORDER BY min DESC");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    foreach($result as $row) {
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($row['name']); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo htmlspecialchars($row['min']); ?>%</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo htmlspecialchars($row['max']); ?>%</span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['remark']); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <button onclick="set_grade('<?php echo $row['id']; ?>');" 
                                                    class="btn btn-edit" 
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    title="Edit Grade">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button onclick="del('academic/core/drop_grade.php?id=<?php echo $row['id']; ?>', 'Delete Grade?');"
                                                    class="btn btn-delete"
                                                    title="Delete Grade">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Hidden fields to store grade data -->
                                        <textarea style="display:none;" id="grade_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></textarea>
                                        <textarea style="display:none;" id="min_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['min']); ?></textarea>
                                        <textarea style="display:none;" id="max_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['max']); ?></textarea>
                                        <textarea style="display:none;" id="remark_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['remark']); ?></textarea>
                                    </td>
                                </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-award fs-1 text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No Grades Found</h5>
                                            <p class="text-muted mb-3">No grading criteria have been added yet. Start by adding your first grade.</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                <i class="bi bi-plus-circle me-2"></i>Add First Grade
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            } catch(PDOException $e) {
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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

<style>
.dashboard-widget {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.widget-header {
    background: #f8f9fa;
    color: #495057;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
}

.widget-header h5 {
    margin: 0;
    font-weight: 600;
}

.widget-content {
    padding: 20px;
}

.empty-state {
    padding: 40px 20px;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    color: #495057;
    background-color: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.btn-edit {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
}

.btn-edit:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(255, 107, 107, 0.3);
}

.btn-delete:hover {
    background: linear-gradient(135deg, #ff5252 0%, #d32f2f 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 107, 107, 0.4);
}

.btn-edit i, .btn-delete i {
    font-size: 16px;
}

.modal-content {
    border-radius: 10px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-radius: 10px 10px 0 0;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: #f8f9fa;
}

.table-hover > tbody > tr:hover > td {
    background-color: #e9ecef;
}
</style>

<script>
// Initialize DataTable
$(document).ready(function() {
    $('#srmsTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[ 1, "desc" ]],
        "language": {
            "search": "Search grades:",
            "lengthMenu": "Show _MENU_ grades per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ grades",
            "infoEmpty": "Showing 0 to 0 of 0 grades",
            "infoFiltered": "(filtered from _MAX_ total grades)",
            "emptyTable": "No grades found",
            "zeroRecords": "No matching grades found"
        }
    });
});

// Function to set grade data for editing
function set_grade(id) {
    document.getElementById('grade').value = document.getElementById('grade_' + id).value;
    document.getElementById('min').value = document.getElementById('min_' + id).value;
    document.getElementById('max').value = document.getElementById('max_' + id).value;
    document.getElementById('remark').value = document.getElementById('remark_' + id).value;
    document.getElementById('id').value = id;
}
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>
