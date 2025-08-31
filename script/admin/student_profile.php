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

// Include the result prediction functions
require_once('academic/result_prediction.php');

// Get student ID from URL parameter
$student_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$student_id) {
    header("location:students.php");
    exit();
}

// Set page title for header
$page_title = 'Student Profile';
$include_datatables = false;

// Include the admin header
include('admin-header.php');

// Get student data
try {
    $stmt = $conn->prepare("SELECT s.*, c.name as class_name 
                          FROM tbl_students s 
                          LEFT JOIN tbl_classes c ON s.class = c.id 
                          WHERE s.id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("location:students.php");
        exit();
    }

    // Get student results
    $student_results = getStudentResults($conn, $student_id);

    // Get predictions based on student's class
    $predictions = [];
    if ($student['class'] == 10) { // Class 11
        $predictions = predictClass11FinalResult($conn, $student_id);
    } elseif ($student['class'] == 11) { // Class 12
        $predictions = predictClass12FinalResult($conn, $student_id);
    }
} catch (PDOException $e) {
    header("location:students.php");
    exit();
}
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-circle me-2"></i>Student Profile</h1>
        <p>Complete information for <?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">
            <a href="admin/students.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Students
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="admin/manage_students.php" class="btn btn-outline-secondary">
                <i class="bi bi-gear me-2"></i>Manage Students
            </a>
        </li>
    </ul>
</div>

<!-- Student Profile Section -->
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-person-circle me-2"></i>Profile Information</h5>
            </div>
            <div class="widget-content text-center">
                <div class="profile-avatar mb-4">
                    <?php
                    // Get student image with proper fallback
                    $student_image = '';
                    $default_image = '';

                    if (!empty($student['display_image']) && $student['display_image'] !== 'DEFAULT' && $student['display_image'] !== 'Blank') {
                        $student_image = './images/students/' . $student['display_image'];
                    } else {
                        // Use gender-specific default avatar
                        $gender = strtolower($student['gender']);
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
                    <img src="<?php echo $student_image; ?>" alt="Student Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>

                <h4 class="mb-3"><?php echo htmlspecialchars($student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']); ?></h4>

                <div class="profile-details text-start">
                    <div class="detail-item mb-2">
                        <strong>Registration ID:</strong>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($student['id']); ?></span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Email:</strong>
                        <span><?php echo htmlspecialchars($student['email']); ?></span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Gender:</strong>
                        <span class="badge bg-info"><?php echo htmlspecialchars($student['gender']); ?></span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Class:</strong>
                        <span class="badge bg-success"><?php echo htmlspecialchars($student['class_name']); ?></span>
                    </div>
                    <div class="detail-item mb-2">
                        <strong>Status:</strong>
                        <?php if ($student['status'] == 1): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="mt-4">
                    <a href="admin/manage_students.php?id=<?php echo $student['id']; ?>" class="btn btn-primary btn-sm me-2">
                        <i class="bi bi-pencil me-1"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Academic Performance -->
        <div class="dashboard-widget mb-4">
            <div class="widget-header">
                <h5><i class="bi bi-graph-up me-2"></i>Academic Performance</h5>
            </div>
            <div class="widget-content">
                <?php if (!empty($student_results)): ?>
                    <?php foreach ($student_results as $class_id => $class_data): ?>
                        <div class="class-performance mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-mortarboard me-2"></i><?php echo htmlspecialchars($class_data['class_name']); ?>
                            </h6>

                            <?php foreach ($class_data['terms'] as $term_id => $term_data): ?>
                                <div class="term-performance mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($term_data['term_name']); ?></h6>
                                        <div>
                                            <span class="badge bg-primary me-2"><?php echo $term_data['average']; ?>%</span>
                                            <span class="badge bg-secondary"><?php echo $term_data['grade']; ?></span>
                                        </div>
                                    </div>

                                    <div class="subject-breakdown">
                                        <small class="text-muted">Subject Breakdown:</small>
                                        <div class="row mt-2">
                                            <?php foreach ($term_data['subjects'] as $subject): ?>
                                                <div class="col-md-6 mb-1">
                                                    <small>
                                                        <strong><?php echo htmlspecialchars($subject['subject']); ?>:</strong>
                                                        <span class="badge bg-light text-dark"><?php echo $subject['score']; ?>%</span>
                                                    </small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i><?php echo htmlspecialchars($term_data['remark']); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>No academic results available</strong>
                        <p class="mb-0">This student has not yet taken any examinations.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Result Prediction Section -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-graph-up me-2"></i>Result Prediction</h5>
                <small class="text-muted">Regression Based prediction using linear regression algorithm</small>
            </div>
            <div class="widget-content">
                <?php if ($predictions['available']): ?>
                    <div class="prediction-card p-4 border rounded bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="text-primary mb-2">
                                    <i class="bi bi-robot me-2"></i>Predicted Final Result
                                </h6>
                                <p class="mb-2"><?php echo htmlspecialchars($predictions['message']); ?></p>
                                <div class="prediction-details">
                                    <span class="badge bg-success fs-6 me-2"><?php echo $predictions['prediction']['percentage']; ?>%</span>
                                    <span class="badge bg-primary me-2"><?php echo $predictions['prediction']['grade']; ?></span>
                                    <span class="badge bg-info"><?php echo $predictions['prediction']['remark']; ?></span>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="bi bi-shield-check me-1"></i>Confidence: <?php echo $predictions['prediction']['confidence']; ?>
                                </small>
                                <small class="text-muted d-block">
                                    <i class="bi bi-gear me-1"></i>Method: <?php echo $predictions['prediction']['method']; ?>
                                </small>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="prediction-icon">
                                    <i class="bi bi-graph-up-arrow text-success" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Prediction Not Available</strong>
                        <p class="mb-0"><?php echo htmlspecialchars($predictions['message']); ?></p>
                        <?php if (isset($predictions['required_terms']) && isset($predictions['available_terms'])): ?>
                            <small class="text-muted">
                                Required: <?php echo $predictions['required_terms']; ?> terms |
                                Available: <?php echo $predictions['available_terms']; ?> terms
                            </small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleStudentStatus(studentId, status) {
        const action = status == 1 ? 'activate' : 'deactivate';
        if (confirm(`Are you sure you want to ${action} this student?`)) {
            $.ajax({
                url: 'admin/core/toggle_student_status.php',
                type: 'POST',
                data: {
                    student_id: studentId,
                    status: status
                },
                success: function(response) {
                    if (response.includes('success')) {
                        alert(`Student ${action}d successfully`);
                        location.reload();
                    } else {
                        alert('Error updating student status');
                    }
                },
                error: function() {
                    alert('Error updating student status');
                }
            });
        }
    }
</script>

<?php include('admin-footer.php'); ?>