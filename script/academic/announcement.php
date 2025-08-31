<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title for header
$page_title = 'Announcements';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-bell me-2"></i>Announcements</h1>
        <p>Manage and publish announcements for students and teachers</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Announcement
            </button>
        </li>
    </ul>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Announcements</h5>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_announcement.php">
                    <div class="mb-2">
                        <label class="form-label">Enter Title</label>
                        <input required type="text" name="title" class="form-control txt-cap" placeholder="Enter Announcement Title">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Audience</label>
                        <select class="form-control" name="audience" required>
                            <option selected disabled value="">Select one</option>
                            <option value="1">Students Only</option>
                            <option value="0">Teachers Only</option>
                            <option value="2">Students & Teachers</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Announcement</label>
                        <textarea name="announcement" id="summernote" required></textarea>
                        <script>
                            $('#summernote').summernote({
                                tabsize: 2,
                                height: 120,
                                fontNames: ['Comic Sans MS']
                            });
                        </script>
                    </div>

                    <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Add</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Announcement</h5>
            </div>
            <div class="modal-body" id="ajax_callback">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Announcements</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-type me-2"></i>Title</th>
                                <th><i class="bi bi-people me-2"></i>Audience</th>
                                <th><i class="bi bi-calendar me-2"></i>Create Date</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_announcements ORDER BY id DESC");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    foreach($result as $row) {
                                        $audience_text = '';
                                        switch ($row[4]) {
                                            case '0':
                                                $audience_text = "Teachers Only";
                                                $audience_badge = "bg-warning";
                                                break;
                                            case '1':
                                                $audience_text = "Students Only";
                                                $audience_badge = "bg-info";
                                                break;
                                            case '2':
                                                $audience_text = "Teachers & Students";
                                                $audience_badge = "bg-success";
                                                break;
                                        }
                            ?>
                                <tr>
                                    <td>
                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row[1]); ?></h6>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $audience_badge; ?>"><?php echo $audience_text; ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo date('M j, Y', strtotime($row[3])); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <button onclick="set_announcement('<?php echo $row[0]; ?>');" 
                                                    class="btn btn-edit" 
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    title="Edit Announcement">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button onclick="del('academic/core/drop_announcement.php?id=<?php echo $row[0]; ?>', 'Delete Announcement?');"
                                                    class="btn btn-delete"
                                                    title="Delete Announcement">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-bell fs-1 text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No Announcements Found</h5>
                                            <p class="text-muted mb-3">No announcements have been created yet. Start by adding your first announcement.</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                <i class="bi bi-plus-circle me-2"></i>Add First Announcement
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }
                            } catch(PDOException $e) {
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
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
        "order": [[ 2, "desc" ]],
        "language": {
            "search": "Search announcements:",
            "lengthMenu": "Show _MENU_ announcements per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ announcements",
            "infoEmpty": "Showing 0 to 0 of 0 announcements",
            "infoFiltered": "(filtered from _MAX_ total announcements)",
            "emptyTable": "No announcements found",
            "zeroRecords": "No matching announcements found"
        }
    });
});

// Function to set announcement data for editing
function set_announcement(id) {
    // Load the edit form via AJAX
    $.ajax({
        url: 'academic/core/get_announcement.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            $('#ajax_callback').html(response);
        },
        error: function() {
            $('#ajax_callback').html('<div class="alert alert-danger">Error loading announcement data.</div>');
        }
    });
}
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>
