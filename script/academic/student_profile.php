<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {
} else {
    header("location:../");
}

// Include the result prediction functions
require_once('academic/result_prediction.php');

// Get student ID from URL parameter
$student_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$student_id) {
    header("location:students_list.php");
    exit();
}

// Set page title for header
$page_title = 'Student Profile';
$include_datatables = false;

// Include the academic header
require_once('academic/academic-header.php');

// Get student data
try {
    $stmt = $conn->prepare("SELECT s.*, c.name as class_name 
                          FROM tbl_students s 
                          LEFT JOIN tbl_classes c ON s.class = c.id 
                          WHERE s.id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("location:students_list.php");
        exit();
    }

    // Get student results
    $student_results = getStudentResults($conn, $student_id);

    // Get predictions based on student's class
    $predictions = [];

    try {
        if ($student['class'] == 10) { // Class 10 in DB = "Eleven (Management)" = Class 11
            $predictions = predictClass11FinalResult($conn, $student_id);
        } elseif ($student['class'] == 11) { // Class 11 in DB = "Twelve (Management)" = Class 12
            $predictions = predictClass12FinalResult($conn, $student_id);
        } elseif ($student['class'] == 12) { // Class 12 in DB = "Twelve (Management)" = Class 12
            $predictions = predictClass12FinalResult($conn, $student_id);
        } else {
            // Try to get class name to understand what class this is
            try {
                $class_stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
                $class_stmt->execute([$student['class']]);
                $class_info = $class_stmt->fetch();

                if ($class_info) {
                    // Check if this class name contains "Eleven" or "Twelve"
                    if (stripos($class_info['name'], 'eleven') !== false) {
                        $predictions = predictClass11FinalResult($conn, $student_id);
                    } elseif (stripos($class_info['name'], 'twelve') !== false) {
                        $predictions = predictClass12FinalResult($conn, $student_id);
                    } else {
                        $predictions = [
                            'available' => false,
                            'message' => 'Result prediction is only available for Class 11 and Class 12 students.',
                            'prediction' => null
                        ];
                    }
                } else {
                    $predictions = [
                        'available' => false,
                        'message' => 'Invalid class information.',
                        'prediction' => null
                    ];
                }
            } catch (Exception $e) {
                $predictions = [
                    'available' => false,
                    'message' => 'Error retrieving class information.',
                    'prediction' => null
                ];
            }
        }
    } catch (Exception $e) {
        $predictions = [
            'available' => false,
            'message' => 'Error in prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    } catch (Error $e) {
        $predictions = [
            'available' => false,
            'message' => 'Error in prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    }

    // Ensure predictions array has required keys to avoid undefined array key warnings
    if (!isset($predictions['available'])) {
        $predictions['available'] = false;
    }
    if (!isset($predictions['message'])) {
        $predictions['message'] = 'Unable to generate prediction at this time.';
    }
    if (!isset($predictions['prediction'])) {
        $predictions['prediction'] = null;
    }
} catch (PDOException $e) {
    header("location:students_list.php");
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
            <a href="academic/students_list.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Students
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
                    <img src="<?php echo $student_image; ?>" alt="Student" class="profile-img"
                        onerror="this.src='<?php echo $default_image ?: './images/students/Male.png'; ?>'">
                </div>
                <h4 class="mb-2"><?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?></h4>
                <p class="text-muted mb-3">Student ID: <?php echo htmlspecialchars($student['id']); ?></p>
                <div class="profile-status">
                    <span class="badge bg-<?php echo $student['status'] == 1 ? 'success' : 'danger'; ?>">
                        <?php echo $student['status'] == 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-person me-2"></i>Personal Information</h5>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Full Name</label>
                        <p class="mb-0"><?php echo htmlspecialchars($student['fname'] . ' ' . ($student['mname'] ? $student['mname'] . ' ' : '') . $student['lname']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Gender</label>
                        <p class="mb-0"><?php echo htmlspecialchars($student['gender'] ?? 'Not specified'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Email Address</label>
                        <p class="mb-0"><?php echo htmlspecialchars($student['email'] ?? 'Not provided'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Class</label>
                        <p class="mb-0">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($student['class_name'] ?? 'Not assigned'); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Results Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-clipboard-data me-2"></i>Available Results</h5>
            </div>
            <div class="widget-content">
                <?php if (empty($student_results)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-3">No results available for this student yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($student_results as $class_id => $class_data): ?>
                        <div class="class-results mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-mortarboard me-2"></i><?php echo htmlspecialchars($class_data['class_name']); ?>
                            </h6>

                            <?php foreach ($class_data['terms'] as $term_id => $term_data): ?>
                                <div class="term-result mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($term_data['term_name']); ?></h6>
                                        <div class="text-end">
                                            <span class="badge bg-<?php echo getGradeColor($term_data['grade']); ?> fs-6">
                                                <?php echo $term_data['grade']; ?>
                                            </span>
                                            <span class="text-muted ms-2"><?php echo $term_data['average']; ?>%</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <?php foreach ($term_data['subjects'] as $subject): ?>
                                            <div class="col-md-4 col-sm-6 mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted"><?php echo htmlspecialchars($subject['subject']); ?></span>
                                                    <span class="fw-bold"><?php echo $subject['score']; ?>%</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Result Prediction Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-graph-up me-2"></i>Result Prediction</h5>
                <small class="text-muted">Regression Based prediction using linear regression algorithm</small>
            </div>
            <div class="widget-content">
                <?php if (!$predictions['available']): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                        <p class="text-muted mt-3"><?php echo htmlspecialchars($predictions['message']); ?></p>
                    </div>
                <?php else: ?>
                    <div class="prediction-card p-4 border rounded bg-light">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-primary mb-2">
                                    <i class="bi bi-target me-2"></i>Predicted Final Result
                                </h6>
                                <p class="text-muted mb-3"><?php echo htmlspecialchars($predictions['message']); ?></p>

                                <div class="prediction-details">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted">Predicted Percentage</label>
                                            <div class="h4 text-primary mb-0"><?php echo $predictions['prediction']['percentage']; ?>%</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted">Predicted Grade</label>
                                            <div class="h4 mb-0">
                                                <span class="badge bg-<?php echo getGradeColor($predictions['prediction']['grade']); ?> fs-5">
                                                    <?php echo $predictions['prediction']['grade']; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-muted">Confidence Level</label>
                                            <div class="h6 mb-0">
                                                <span class="badge bg-<?php echo getConfidenceColor($predictions['prediction']['confidence']); ?>">
                                                    <?php echo $predictions['prediction']['confidence']; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Remark</label>
                                            <p class="mb-0"><?php echo htmlspecialchars($predictions['prediction']['remark']); ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Algorithm Used</label>
                                            <p class="mb-0"><?php echo htmlspecialchars($predictions['prediction']['method']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="prediction-visual text-center">
                                    <div class="prediction-circle mb-3">
                                        <div class="progress-circle" data-percentage="<?php echo $predictions['prediction']['percentage']; ?>">
                                            <div class="progress-circle-inner">
                                                <span class="percentage-text"><?php echo $predictions['prediction']['percentage']; ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prediction-info">
                                        <small class="text-muted">
                                            <i class="bi bi-lightbulb me-1"></i>
                                            This prediction is based on historical performance patterns and may vary with actual results.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Helper functions for UI
function getGradeColor($grade)
{
    $colors = [
        'A+' => 'success',
        'A' => 'success',
        'B+' => 'primary',
        'B' => 'primary',
        'C+' => 'warning',
        'C' => 'warning',
        'D' => 'danger',
        'NG' => 'danger'
    ];
    return $colors[$grade] ?? 'secondary';
}

function getConfidenceColor($confidence)
{
    $colors = [
        'High' => 'success',
        'Medium' => 'warning',
        'Low' => 'danger'
    ];
    return $colors[$confidence] ?? 'secondary';
}
?>

<style>
    .profile-avatar {
        position: relative;
        display: inline-block;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-status {
        margin-top: 15px;
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

    .form-label {
        font-size: 0.875rem;
        margin-bottom: 5px;
    }

    .form-label+p {
        font-size: 1rem;
        font-weight: 500;
        color: #495057;
    }

    .term-result {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .term-result:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .prediction-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#007bff 0deg, #007bff calc(var(--percentage) * 3.6deg), #e9ecef calc(var(--percentage) * 3.6deg), #e9ecef 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
    }

    .progress-circle-inner {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .percentage-text {
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
    }

    .class-results {
        border-left: 4px solid #007bff;
        padding-left: 15px;
    }

    .prediction-details {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
</style>

<script>
    // Set up progress circle
    document.addEventListener('DOMContentLoaded', function() {
        const progressCircle = document.querySelector('.progress-circle');
        if (progressCircle) {
            const percentage = progressCircle.getAttribute('data-percentage');
            progressCircle.style.setProperty('--percentage', percentage);
        }
    });
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>