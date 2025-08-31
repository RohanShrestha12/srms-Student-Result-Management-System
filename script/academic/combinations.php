<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title for header
$page_title = 'Subject Combinations Management';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-collection me-2"></i>Subject Combinations Management</h1>
        <p>Manage subject combinations and teacher assignments</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Combination
            </button>
        </li>
    </ul>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-primary">
                <i class="bi bi-collection"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalCombinations">0</h3>
                <p class="text-muted">Total Combinations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-success">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalTeachers">0</h3>
                <p class="text-muted">Assigned Teachers</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-info">
                <i class="bi bi-building"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalClasses">0</h3>
                <p class="text-muted">Active Classes</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Combination Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add Subject Combination
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_comb.php">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-book me-2"></i>Select Subject
                            </label>
                            <select class="form-control select2" name="subject" required style="width: 100%;">
                                <option selected disabled value="">Choose a subject</option>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_subjects ORDER BY name");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                ?>
                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building me-2"></i>Select Classes
                            </label>
                            <select multiple="true" class="form-control select2" name="class[]" required style="width: 100%;">
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                ?>
                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                            <div class="form-text">Hold Ctrl/Cmd to select multiple classes</div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-workspace me-2"></i>Select Teacher
                            </label>
                            <select class="form-control select2" name="teacher" required style="width: 100%;">
                                <option selected disabled value="">Choose a teacher</option>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_staff WHERE level = '2' ORDER BY fname, lname");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                ?>
                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1].' '.$row[2]; ?></option>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg app_btn">
                            <i class="bi bi-check-circle me-2"></i>Add Combination
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

<!-- Edit Subject Combination Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Subject Combination
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="comb_feedback">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Subject Combinations Table -->
<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Subject Combinations List</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th><i class="bi bi-book me-2"></i>Subject</th>
                                <th><i class="bi bi-person-workspace me-2"></i>Teacher</th>
                                <th><i class="bi bi-building me-2"></i>Classes</th>
                                <th><i class="bi bi-calendar me-2"></i>Added On</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $empty_classes = array();

                                $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                $stmt->execute();
                                $classes = $stmt->fetchAll();

                                foreach ($classes as $value) {
                                    $empty_classes[$value[0]] = $value[1];
                                }

                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                                  LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
                                  LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id
                                  ORDER BY tbl_subjects.name");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    $counter = 1;
                                    foreach($result as $row) {
                                        $class_list = unserialize($row[1]);
                            ?>
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
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row[6]); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="teacher-icon me-3">
                                                        <i class="bi bi-person-workspace text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row[8].' '.$row[9]); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="class-tags">
                                                    <?php
                                                    $st = 1;
                                                    foreach ($class_list as $value2) {
                                                        $class_name = isset($empty_classes[$value2]) ? $empty_classes[$value2] : 'Unknown Class (ID: ' . $value2 . ')';
                                                        if ($st < count($class_list)) {
                                                            echo '<span class="badge bg-primary me-1">'.$class_name.'</span>';
                                                        } else {
                                                            echo '<span class="badge bg-primary">'.$class_name.'</span>';
                                                        }
                                                        $st++;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo date('M j, Y', strtotime($row[4])); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <button onclick="set_combination('<?php echo $row[0]; ?>');" 
                                                            class="btn btn-edit" 
                                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                                            title="Edit Combination">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button onclick="del('academic/core/drop_comb.php?id=<?php echo $row[0]; ?>', 'Are you sure you want to delete this subject combination?');"
                                                            class="btn btn-delete"
                                                            title="Delete Combination">
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
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-collection fs-1 text-muted mb-3 d-block"></i>
                                                <h5 class="text-muted">No Subject Combinations Found</h5>
                                                <p class="text-muted mb-3">No subject combinations have been added yet. Start by adding your first combination.</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                    <i class="bi bi-plus-circle me-2"></i>Add First Combination
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } catch(PDOException $e) {
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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

.subject-icon, .teacher-icon {
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

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.class-tags .badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
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
    const totalCombinations = document.querySelectorAll('#srmsTable tbody tr').length;
    const emptyRow = document.querySelector('#srmsTable tbody tr td[colspan="6"]');
    
    if (emptyRow) {
        document.getElementById('totalCombinations').textContent = '0';
        document.getElementById('totalTeachers').textContent = '0';
        document.getElementById('totalClasses').textContent = '0';
    } else {
        document.getElementById('totalCombinations').textContent = totalCombinations;
        document.getElementById('totalTeachers').textContent = totalCombinations; // Assuming one teacher per combination
        document.getElementById('totalClasses').textContent = '0'; // This would need to be calculated
    }
});

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        dropdownParent: $("#addModal"),
        theme: "bootstrap-5"
    });
});
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>