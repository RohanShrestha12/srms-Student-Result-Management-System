<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "3") {
} else {
    header("location:../");
}

// Set page title
$page_title = "My Profile";

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-person-circle me-2"></i>My Profile</h1>
        <p>View and manage your student profile information</p>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header text-center">
                <h5><i class="bi bi-person-circle me-2"></i>Profile Photo</h5>
            </div>
            <div class="widget-content text-center">
                <div class="profile-photo-container mb-3">
                    <?php
                    if ($display_image == "DEFAULT" || empty($display_image)) {
                        echo '<img src="images/students/' . $gender . '.png" class="profile-photo-large" alt="Profile Photo">';
                    } else {
                        echo '<img src="images/students/' . $display_image . '" class="profile-photo-large" alt="Profile Photo">';
                    }
                    ?>
                </div>
                <h5 class="mb-1"><?php echo $fname . ' ' . $lname; ?></h5>
                <p class="text-muted mb-0">Student ID: <?php echo $account_id; ?></p>
                <div class="mt-3">
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
                        $stmt->execute([$class]);
                        $class_result = $stmt->fetch();
                        $class_name = $class_result ? $class_result['name'] : 'Class ' . $class;
                    } catch (PDOException $e) {
                        $class_name = 'Class ' . $class;
                    }
                    ?>
                    <span class="badge bg-primary"><?php echo $class_name; ?></span>
                    <span class="badge bg-secondary"><?php echo ucfirst($gender); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="col-md-8">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-card-text me-2"></i>Personal Information</h5>
            </div>
            <div class="widget-content">
                <table class="table table-borderless profile-table">
                    <tbody>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-card-text me-2"></i>Registration Number
                            </td>
                            <td class="profile-data"><?php echo $account_id; ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-person me-2"></i>First Name
                            </td>
                            <td class="profile-data"><?php echo $fname; ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-person me-2"></i>Middle Name
                            </td>
                            <td class="profile-data"><?php echo $mname ?: 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-person me-2"></i>Last Name
                            </td>
                            <td class="profile-data"><?php echo $lname; ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-gender-ambiguous me-2"></i>Gender
                            </td>
                            <td class="profile-data"><?php echo ucfirst($gender); ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-envelope me-2"></i>Email Address
                            </td>
                            <td class="profile-data"><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-mortarboard me-2"></i>Current Class
                            </td>
                            <td class="profile-data">
                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
                                    $stmt->execute([$class]);
                                    $class_result = $stmt->fetch();
                                    echo $class_result ? $class_result['name'] : 'Class ' . $class;
                                } catch (PDOException $e) {
                                    echo 'Class ' . $class;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="profile-label">
                                <i class="bi bi-calendar me-2"></i>Registration Date
                            </td>
                            <td class="profile-data"><?php echo date('F j, Y'); ?></td>
                        </tr>
                    </tbody>
                </table>
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
                <?php
                // Include the result prediction functions
                require_once('academic/result_prediction.php');

                // Get predictions based on student's class
                $predictions = [];

                try {
                    if ($class == 10) { // Class 10 in DB = "Eleven (Management)" = Class 11
                        $predictions = predictClass11FinalResult($conn, $account_id);
                    } elseif ($class == 11) { // Class 11 in DB = "Twelve (Management)" = Class 12
                        $predictions = predictClass12FinalResult($conn, $account_id);
                    } elseif ($class == 12) { // Class 12 in DB = "Twelve (Management)" = Class 12
                        $predictions = predictClass12FinalResult($conn, $account_id);
                    } else {
                        // Try to get class name to understand what class this is
                        try {
                            $class_stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
                            $class_stmt->execute([$class]);
                            $class_info = $class_stmt->fetch();

                            if ($class_info) {
                                // Check if this class name contains "Eleven" or "Twelve"
                                if (stripos($class_info['name'], 'eleven') !== false) {
                                    $predictions = predictClass11FinalResult($conn, $account_id);
                                } elseif (stripos($class_info['name'], 'twelve') !== false) {
                                    $predictions = predictClass12FinalResult($conn, $account_id);
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

                if (!$predictions['available']): ?>
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

<!-- Student Results Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-file-earmark-text me-2"></i>My Exam Results</h5>
                <small class="text-muted">Complete overview of your academic performance</small>
            </div>
            <div class="widget-content">
                <?php
                try {
                    // Get student's results organized by class and term
                    $results = getStudentResults($conn, $account_id);

                    if (empty($results)) {
                ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-3">No exam results available yet.</p>
                            <small class="text-muted">Results will appear here once they are entered by your teachers.</small>
                        </div>
                        <?php
                    } else {
                        foreach ($results as $class_id => $class_data) {
                        ?>
                            <div class="class-results mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-mortarboard me-2"></i><?php echo htmlspecialchars($class_data['class_name']); ?>
                                </h6>

                                <div class="row">
                                    <?php foreach ($class_data['terms'] as $term_id => $term_data) { ?>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="term-card p-3 border rounded bg-light">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 text-secondary">
                                                        <i class="bi bi-calendar-event me-2"></i><?php echo htmlspecialchars($term_data['term_name']); ?>
                                                    </h6>
                                                    <span class="badge bg-<?php echo getGradeColor($term_data['grade']); ?>">
                                                        <?php echo $term_data['grade']; ?>
                                                    </span>
                                                </div>

                                                <div class="term-performance mb-3">
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <div class="performance-item">
                                                                <label class="form-label fw-bold text-muted small">Average Score</label>
                                                                <div class="h5 text-primary mb-0"><?php echo $term_data['average']; ?>%</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="performance-item">
                                                                <label class="form-label fw-bold text-muted small">Remark</label>
                                                                <div class="text-muted small"><?php echo htmlspecialchars($term_data['remark']); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="subjects-breakdown">
                                                    <label class="form-label fw-bold text-muted small mb-2">Subject Scores</label>
                                                    <?php foreach ($term_data['subjects'] as $subject) { ?>
                                                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                                            <span class="text-muted small"><?php echo htmlspecialchars($subject['subject']); ?></span>
                                                            <span class="badge bg-outline-primary"><?php echo $subject['score']; ?>%</span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                    <?php
                        }
                    }
                } catch (Exception $e) {
                    ?>
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                        <p class="text-muted mt-3">Unable to load results at this time.</p>
                        <small class="text-danger">Error: <?php echo htmlspecialchars($e->getMessage()); ?></small>
                    </div>
                <?php
                }
                ?>
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
    /* Profile Photo Styling */
    .profile-photo-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Simple Profile Table Styling */
    .profile-table {
        margin: 0;
    }

    .profile-table td {
        padding: 12px 0;
        border: none;
        vertical-align: middle;
    }

    .profile-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-table tr:last-child {
        border-bottom: none;
    }

    .profile-table tr:hover {
        background-color: #f8f9fa;
    }

    .profile-label {
        font-weight: 500;
        color: #6c757d;
        width: 40%;
        padding-right: 20px !important;
    }

    .profile-label i {
        color: #007bff;
        width: 16px;
    }

    .profile-data {
        font-weight: 600;
        color: #2c3e50;
        text-align: left;
    }

    /* Info Card Styling */
    .info-card {
        display: flex;
        align-items: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        color: white;
        font-size: 20px;
    }

    .info-content h6 {
        margin: 0 0 5px 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .info-value {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #007bff;
    }

    /* Prediction Section Styling */
    .prediction-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }

    .prediction-details label {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .prediction-circle {
        position: relative;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#007bff 0deg, #007bff calc(var(--percentage, 0) * 3.6deg), #e9ecef calc(var(--percentage, 0) * 3.6deg), #e9ecef 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
    }

    .progress-circle::before {
        content: '';
        position: absolute;
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
    }

    .progress-circle-inner {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .percentage-text {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
    }

    .prediction-info {
        margin-top: 1rem;
    }

    .prediction-info small {
        line-height: 1.4;
    }

    /* Results Section Styling */
    .class-results {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1.5rem;
    }

    .class-results:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .term-card {
        background: white;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }

    .term-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #007bff;
    }

    .term-performance {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .performance-item {
        text-align: center;
    }

    .performance-item label {
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
        color: #6c757d;
    }

    .performance-item .h5 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .subjects-breakdown {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }

    .subjects-breakdown label {
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
        color: #6c757d;
    }

    .subjects-breakdown .border-bottom:last-child {
        border-bottom: none !important;
    }

    .bg-outline-primary {
        background-color: transparent;
        color: #007bff;
        border: 1px solid #007bff;
    }

    .bg-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-table td {
            display: block;
            width: 100%;
            padding: 8px 0;
        }

        .profile-label {
            width: 100%;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .profile-data {
            font-size: 16px;
            padding-left: 20px;
        }

        .info-card {
            margin-bottom: 15px;
        }

        .profile-photo-large {
            width: 100px;
            height: 100px;
        }
    }
</style>

<?php include('student-footer.php'); ?>

<script>
    // Initialize progress circles
    document.addEventListener('DOMContentLoaded', function() {
        const progressCircles = document.querySelectorAll('.progress-circle');

        progressCircles.forEach(circle => {
            const percentage = circle.getAttribute('data-percentage');
            if (percentage) {
                circle.style.setProperty('--percentage', percentage);
            }
        });
    });
</script>