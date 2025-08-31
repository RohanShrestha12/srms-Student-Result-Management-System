<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Calculate statistics
$total_students = 0;
$active_classes = 0;
$active_students = 0;

try {
    // Get total students
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_students");
    $stmt->execute();
    $total_students = $stmt->fetchColumn();
    
    // Get active classes (classes with students)
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT class) FROM tbl_students WHERE class IS NOT NULL");
    $stmt->execute();
    $active_classes = $stmt->fetchColumn();
    
    // Get active students (students with status = 1)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_students WHERE status = 1");
    $stmt->execute();
    $active_students = $stmt->fetchColumn();
    
} catch(PDOException $e) {
    // Keep default values if there's an error
}

// Set page title for header
$page_title = 'Students Management';
$include_datatables = true;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-people me-2"></i>Students Management</h1>
        <p>Manage student information and records</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-2"></i>Add Student
            </button>
        </li>
    </ul>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalStudents"><?php echo $total_students; ?></h3>
                <p class="text-muted">Total Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-success">
                <i class="bi bi-building"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalClasses"><?php echo $active_classes; ?></h3>
                <p class="text-muted">Active Classes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-info">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stats-content">
                <h3 id="activeStudents"><?php echo $active_students; ?></h3>
                <p class="text-muted">Active Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_student.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-hash me-2"></i>Registration Number
                            </label>
                            <input type="text" class="form-control" name="regno" required maxlength="20">
                            <small class="text-muted">Unique student identifier</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2"></i>First Name
                            </label>
                            <input type="text" class="form-control" name="fname" required maxlength="70">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2"></i>Middle Name
                            </label>
                            <input type="text" class="form-control" name="mname" maxlength="70">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2"></i>Last Name
                            </label>
                            <input type="text" class="form-control" name="lname" required maxlength="70">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-gender-ambiguous me-2"></i>Gender
                            </label>
                            <select class="form-control" name="gender" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control" name="email" required maxlength="90">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building me-2"></i>Class
                            </label>
                            <select class="form-control select2" name="class" required style="width: 100%;">
                                <option selected disabled value="">Choose a class</option>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                <?php
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" name="password" required maxlength="90">
                            <small class="text-muted">Student login password</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image me-2"></i>Profile Image
                            </label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Optional profile picture (JPG, PNG, JPEG only)</small>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg app_btn">
                            <i class="bi bi-check-circle me-2"></i>Add Student
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

<!-- Students Table -->
<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Students List</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">ID</th>
                                <th><i class="bi bi-person me-2"></i>Student</th>
                                <th><i class="bi bi-building me-2"></i>Class</th>
                                <th><i class="bi bi-envelope me-2"></i>Email</th>
                                <th width="200" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT s.*, c.name as class_name 
                                                      FROM tbl_students s 
                                                      LEFT JOIN tbl_classes c ON s.class = c.id 
                                                      ORDER BY s.fname, s.lname");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    foreach($result as $row) {
                                        // Get student image with proper fallback
                                        $student_image = '';
                                        $default_image = '';
                                        
                                        if (!empty($row['display_image']) && $row['display_image'] !== 'DEFAULT' && $row['display_image'] !== 'Blank') {
                                            $student_image = './images/students/' . $row['display_image'];
                                        } else {
                                            // Use gender-specific default avatar
                                            $gender = strtolower($row['gender']);
                                            if ($gender === 'male') {
                                                $default_image = './images/students/Male.png';
                                            } elseif ($gender === 'female') {
                                                $default_image = './images/students/Female.png';
                                            } else {
                                                // Generic default for unspecified gender
                                                $default_image = './images/students/Male.png';
                                            }
                                            $student_image = $default_image;
                                        }
                            ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill"><?php echo $row['id']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="student-avatar me-3">
                                                        <img src="<?php echo $student_image; ?>" 
                                                             alt="Student" 
                                                             class="student-img" 
                                                             onerror="this.src='<?php echo $default_image ?: './images/students/Male.png'; ?>'">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row['fname'].' '.$row['lname']); ?></h6>
                                                        <small class="text-muted">ID: <?php echo $row['id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($row['class_name'] ?? 'N/A'); ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <a href="academic/student_profile.php?id=<?php echo $row['id']; ?>" 
                                                       class="btn btn-view" 
                                                       title="View Student Profile">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button onclick="del('academic/core/drop_student.php?id=<?php echo $row['id']; ?>&img=<?php echo !empty($row['display_image']) ? $row['display_image'] : 'DEFAULT'; ?>', 'Are you sure you want to delete this student?');"
                                                            class="btn btn-delete"
                                                            title="Delete Student">
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
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                                                <h5 class="text-muted">No Students Found</h5>
                                                <p class="text-muted mb-3">No students have been added yet. Start by adding your first student.</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                    <i class="bi bi-plus-circle me-2"></i>Add First Student
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

.student-icon {
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

.student-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e9ecef;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.student-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
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

.btn-view {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
}

.btn-view:hover {
    background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
}

.btn-view:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
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

.btn-view i, .btn-delete i {
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
// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        dropdownParent: $("#addModal"),
        theme: "bootstrap-5"
    });
    
    // Initialize DataTable
    $('#srmsTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "language": {
            "search": "Search students:",
            "lengthMenu": "Show _MENU_ students per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ students",
            "infoEmpty": "Showing 0 to 0 of 0 students",
            "infoFiltered": "(filtered from _MAX_ total students)",
            "emptyTable": "No students found",
            "zeroRecords": "No matching students found"
        }
    });
});
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?> 