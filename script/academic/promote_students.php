<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title for header
$page_title = 'Promote Students';
$include_datatables = false;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-arrow-up-circle me-2"></i>Promote Students</h1>
        <p>Promote students from one class to another</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalStudents">0</h3>
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
                <h3 id="totalClasses">0</h3>
                <p class="text-muted">Active Classes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card bg-light">
            <div class="stats-icon text-info">
                <i class="bi bi-arrow-up-circle"></i>
            </div>
            <div class="stats-content">
                <h3 id="promotionHistory">0</h3>
                <p class="text-muted">Promotions This Year</p>
            </div>
        </div>
    </div>
</div>

<!-- Promote Students Form -->
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-arrow-up-circle me-2"></i>Student Promotion</h5>
            </div>
            <div class="widget-content">
                <form enctype="multipart/form-data" action="academic/core/promote_students.php" class="app_frm" method="POST" autocomplete="OFF">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building me-2"></i>Select Current Class
                            </label>
                            <select class="form-control select2" name="class" required style="width: 100%;">
                                <option value="" selected disabled>Choose current class</option>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                        // Check if class has students
                                        $stmt2 = $conn->prepare("SELECT COUNT(*) FROM tbl_students WHERE class = ?");
                                        $stmt2->execute([$row['id']]);
                                        $student_count = $stmt2->fetchColumn();

                                        if ($student_count > 0) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['name']); ?> (<?php echo $student_count; ?> students)
                                    </option>
                                <?php
                                        }
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                            <div class="form-text">Select the class from which students will be promoted</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-arrow-up-circle me-2"></i>Promote to Class
                            </label>
                            <select class="form-control select2" name="class2" required style="width: 100%;">
                                <option value="" selected disabled>Choose target class</option>
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row) {
                                        // Check if class has students
                                        $stmt2 = $conn->prepare("SELECT COUNT(*) FROM tbl_students WHERE class = ?");
                                        $stmt2->execute([$row['id']]);
                                        $student_count = $stmt2->fetchColumn();

                                        if ($student_count > 0) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['name']); ?> (<?php echo $student_count; ?> students)
                                    </option>
                                <?php
                                        }
                                    }
                                } catch(PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }
                                ?>
                            </select>
                            <div class="form-text">Select the class to which students will be promoted</div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> This action will move all students from the selected class to the target class. This action cannot be undone.
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary btn-lg app_btn" type="submit">
                            <i class="bi bi-arrow-up-circle me-2"></i>Promote Students
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg ms-2">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Student Preview Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Student Distribution</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="studentTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th><i class="bi bi-building me-2"></i>Class</th>
                                <th><i class="bi bi-people me-2"></i>Student Count</th>
                                <th><i class="bi bi-person me-2"></i>Sample Students</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT c.id, c.name, COUNT(s.id) as student_count 
                                                      FROM tbl_classes c 
                                                      LEFT JOIN tbl_students s ON c.id = s.class 
                                                      GROUP BY c.id, c.name 
                                                      HAVING student_count > 0 
                                                      ORDER BY c.name");
                                $stmt->execute();
                                $classes = $stmt->fetchAll();

                                if (count($classes) > 0) {
                                    $counter = 1;
                                    foreach($classes as $class) {
                                        // Get sample students for this class
                                        $stmt2 = $conn->prepare("SELECT fname, lname FROM tbl_students WHERE class = ? LIMIT 3");
                                        $stmt2->execute([$class['id']]);
                                        $sample_students = $stmt2->fetchAll();
                            ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill"><?php echo $counter; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="class-icon me-3">
                                                        <i class="bi bi-building text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($class['name']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo $class['student_count']; ?> students</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php 
                                                    $student_names = array();
                                                    foreach($sample_students as $student) {
                                                        $student_names[] = $student['fname'] . ' ' . $student['lname'];
                                                    }
                                                    echo htmlspecialchars(implode(', ', $student_names));
                                                    if (count($sample_students) == 3 && $class['student_count'] > 3) {
                                                        echo ' and ' . ($class['student_count'] - 3) . ' more...';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-primary btn-sm" onclick="viewClassStudents(<?php echo $class['id']; ?>)">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                            <?php
                                        $counter++;
                                    }
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                                                <h5 class="text-muted">No Students Found</h5>
                                                <p class="text-muted mb-3">No students have been registered yet.</p>
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

.class-icon {
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

.alert {
    border-radius: 8px;
    border: none;
}
</style>

<script>
// Update statistics
document.addEventListener('DOMContentLoaded', function() {
    const totalStudents = document.querySelectorAll('#studentTable tbody tr').length;
    const emptyRow = document.querySelector('#studentTable tbody tr td[colspan="5"]');
    
    if (emptyRow) {
        document.getElementById('totalStudents').textContent = '0';
        document.getElementById('totalClasses').textContent = '0';
        document.getElementById('promotionHistory').textContent = '0';
    } else {
        // Calculate total students from badges
        let totalStudentCount = 0;
        const badges = document.querySelectorAll('#studentTable .badge.bg-primary');
        badges.forEach(badge => {
            const text = badge.textContent;
            const match = text.match(/(\d+) students/);
            if (match) {
                totalStudentCount += parseInt(match[1]);
            }
        });
        
        document.getElementById('totalStudents').textContent = totalStudentCount;
        document.getElementById('totalClasses').textContent = totalStudents;
        document.getElementById('promotionHistory').textContent = '0'; // This would need to be calculated from promotion history
    }
});

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap-5"
    });
});

// Function to view class students (placeholder)
function viewClassStudents(classId) {
    // This could open a modal with detailed student list
    alert('View students for class ID: ' + classId);
}
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>