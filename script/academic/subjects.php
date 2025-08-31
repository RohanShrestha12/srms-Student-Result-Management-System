<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Get combinations count
$combinations_count = 0;
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_subject_combinations");
    $stmt->execute();
    $combinations_count = $stmt->fetchColumn();
} catch(PDOException $e) {
    // Keep default value of 0 if query fails
}

// Set page title for header
$page_title = 'Subjects Management';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-book me-2"></i>Subjects Management</h1>
        <p>Manage academic subjects and course materials</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Subject
            </button>
        </li>
    </ul>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-primary">
                <i class="bi bi-book"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalSubjects">0</h3>
                <p class="text-muted">Total Subjects</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stats-content">
                <h3 id="activeSubjects">0</h3>
                <p class="text-muted">Active Subjects</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-info">
                <i class="bi bi-collection"></i>
            </div>
            <div class="stats-content">
                <h3 id="combinations">0</h3>
                <p class="text-muted">Subject Combinations</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_subject">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-book me-2"></i>Subject Name
                        </label>
                        <input required name="name" class="form-control form-control-lg" type="text" 
                               placeholder="Enter subject name (e.g., Mathematics, Science)">
                        <div class="form-text">Enter a descriptive name for the subject</div>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg app_btn">
                            <i class="bi bi-check-circle me-2"></i>Add Subject
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_subject.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-book me-2"></i>Subject Name
                        </label>
                        <input id="name" required name="name" class="form-control form-control-lg" type="text"
                            placeholder="Enter subject name">
                        <div class="form-text">Update the subject name as needed</div>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg app_btn">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Subjects List</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th><i class="bi bi-book me-2"></i>Subject Name</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Use the existing $conn variable from school.php
                                $stmt = $conn->prepare("SELECT * FROM tbl_subjects ORDER BY name ASC");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    $counter = 1;
                                    foreach ($result as $row) {
                            ?>
                                        <textarea style="display:none;" id="subject_<?php echo $row[0]; ?>"><?php echo $row[1]; ?></textarea>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill"><?php echo $counter; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="subject-icon me-3">
                                                        <i class="bi bi-book text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row[1]); ?></h6>
                                                        <small class="text-muted">Subject ID: <?php echo $row[0]; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <button onclick="set_subject('<?php echo $row[0]; ?>');" 
                                                            class="btn btn-edit" 
                                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                                            title="Edit Subject">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button onclick="del('academic/core/drop_subject.php?id=<?php echo $row[0]; ?>', 'Are you sure you want to delete this subject?');"
                                                            class="btn btn-delete"
                                                            title="Delete Subject">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                            <?php
                                        $counter++;
                                    }
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-book fs-1 text-muted mb-3 d-block"></i>
                                                <h5 class="text-muted">No Subjects Found</h5>
                                                <p class="text-muted mb-3">No subjects have been added yet. Start by adding your first subject.</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                    <i class="bi bi-plus-circle me-2"></i>Add First Subject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } catch (PDOException $e) {
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

<style>
.stats-card {
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    border: 1px solid #e9ecef;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.stats-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
    color: #495057;
}

.stats-content p {
    margin-bottom: 0;
}

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

.subject-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    border: 1px solid #e9ecef;
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

.btn-group .btn {
    border-radius: 6px !important;
    margin: 0 2px;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.btn-edit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.btn-edit:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
}

.btn-edit:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
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

.btn-delete:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(255, 107, 107, 0.3);
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

.form-control-lg {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
}

.form-control-lg:focus {
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
// Update statistics
document.addEventListener('DOMContentLoaded', function() {
    const totalSubjects = document.querySelectorAll('#srmsTable tbody tr').length;
    const emptyRow = document.querySelector('#srmsTable tbody tr td[colspan="3"]');
    
    if (emptyRow) {
        document.getElementById('totalSubjects').textContent = '0';
        document.getElementById('activeSubjects').textContent = '0';
        document.getElementById('combinations').textContent = '0';
    } else {
        document.getElementById('totalSubjects').textContent = totalSubjects;
        document.getElementById('activeSubjects').textContent = totalSubjects;
        document.getElementById('combinations').textContent = <?php echo $combinations_count; ?>;
    }
});
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>