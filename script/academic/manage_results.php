<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/calculations.php');
if ($res == "1" && $level == "1") {
} else {
    header("location:../");
}

// Initialize variables
$selected_class = '';
$selected_term = '';
$results_data = [];
$class_name = '';
$term_name = '';
$show_results = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student']) && isset($_POST['term'])) {
    $selected_class = $_POST['student'];
    $selected_term = $_POST['term'];
    $show_results = true;

    try {
        // Get class and term names
        $stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
        $stmt->execute([$selected_class]);
        $class_data = $stmt->fetch();
        $class_name = $class_data['name'];

        $stmt = $conn->prepare("SELECT name FROM tbl_terms WHERE id = ?");
        $stmt->execute([$selected_term]);
        $term_data = $stmt->fetch();
        $term_name = $term_data['name'];

        // Get grading system
        $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
        $stmt->execute();
        $grading = $stmt->fetchAll();

        // Get students in the selected class
        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ? ORDER BY fname, lname");
        $stmt->execute([$selected_class]);
        $students = $stmt->fetchAll();

        // Get subject combinations
        $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
        $stmt->execute();
        $subject_combinations = $stmt->fetchAll();

        // Process each student's results
        foreach ($students as $student) {
            $total_score = 0;
            $total_subjects = 0;
            $subject_scores = [];

            foreach ($subject_combinations as $combination) {
                $class_list = unserialize($combination[1]);

                if (in_array($selected_class, $class_list)) {
                    $total_subjects++;
                    $score = 0;

                    $stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
                    $stmt->execute([$selected_class, $combination[0], $selected_term, $student['id']]);
                    $exam_result = $stmt->fetch();

                    if (!empty($exam_result['score'])) {
                        $score = $exam_result['score'];
                        $total_score += $score;
                    }
                    $subject_scores[] = $score;
                }
            }

            // Calculate average
            $average = ($total_subjects > 0) ? round($total_score / $total_subjects) : 0;

            // Determine grade and remarks
            $grade = 'N/A';
            $remarks = 'N/A';
            foreach ($grading as $grade_data) {
                if ($average >= $grade_data['min'] && $average <= $grade_data['max']) {
                    $grade = $grade_data['name'];
                    $remarks = $grade_data['remark'];
                    break;
                }
            }

            $results_data[] = [
                'student' => $student,
                'total_score' => $total_score,
                'average' => $average,
                'grade' => $grade,
                'remarks' => $remarks,
                'total_subjects' => $total_subjects
            ];
        }

    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}

// Set page title for header
$page_title = 'Manage Results';
$include_datatables = $show_results;

// Include the academic header
require_once('academic/academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-file-text me-2"></i>Manage Results</h1>
        <p>Select class and term to view and manage examination results</p>
    </div>
</div>

<!-- Form Section -->
<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-search me-2"></i>Select Parameters</h5>
                <p class="mb-0 text-muted">Choose a class and term to view examination results</p>
            </div>
            <div class="widget-content">
                <form method="POST" class="app_frm" autocomplete="OFF">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-building me-2"></i>Select Class
                                </label>
                                <div class="custom-select-wrapper">
                                    <div class="select-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <select class="form-control custom-select" name="student" required>
                                        <option value="" selected disabled>Choose a class</option>
                                        <?php
                                        try {
                                            $stmt = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                                            $stmt->execute();
                                            $result = $stmt->fetchAll();

                                            foreach($result as $row) {
                                                $selected = ($selected_class == $row['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                                        <?php
                                            }
                                        } catch(PDOException $e) {
                                            echo "Connection failed: " . $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <small class="form-text">Select the class to view results for</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar-event me-2"></i>Select Term
                                </label>
                                <div class="custom-select-wrapper">
                                    <div class="select-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <select class="form-control custom-select" name="term" required>
                                        <option selected disabled value="">Choose a term</option>
                                        <?php
                                        try {
                                            $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE status = '1' ORDER BY name");
                                            $stmt->execute();
                                            $result = $stmt->fetchAll();

                                            foreach($result as $row) {
                                                $selected = ($selected_term == $row['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                                        <?php
                                            }
                                        } catch(PDOException $e) {
                                            echo "Connection failed: " . $e->getMessage();
                                        }
                                        ?>
                                    </select>
                                </div>
                                <small class="form-text">Select the academic term for results</small>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg px-5 py-3 app_btn" type="submit">
                                    <i class="bi bi-search me-3"></i>View Results
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Section -->
<?php if ($show_results && !empty($results_data)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Results for <?php echo htmlspecialchars($class_name); ?> - <?php echo htmlspecialchars($term_name); ?></h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">#</th>
                                <th><i class="bi bi-person me-2"></i>Student</th>
                                <th><i class="bi bi-hash me-2"></i>Registration</th>
                                <th><i class="bi bi-calculator me-2"></i>Total Score</th>
                                <th><i class="bi bi-graph-up me-2"></i>Average</th>
                                <th><i class="bi bi-award me-2"></i>Grade</th>
                                <th><i class="bi bi-chat-text me-2"></i>Remarks</th>
                                <th width="200" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results_data as $index => $result): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill"><?php echo $index + 1; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-3">
                                                <?php 
                                                // Get student image with proper fallback
                                                $student_image = '';
                                                $default_image = '';
                                                
                                                if (!empty($result['student']['display_image']) && $result['student']['display_image'] !== 'DEFAULT' && $result['student']['display_image'] !== 'Blank') {
                                                    $student_image = './images/students/' . $result['student']['display_image'];
                                                } else {
                                                    // Use gender-specific default avatar
                                                    $gender = strtolower($result['student']['gender']);
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
                                                <img src="<?php echo $student_image; ?>" alt="Student" class="student-img" 
                                                     onerror="this.src='<?php echo $default_image ?: './images/students/Male.png'; ?>'">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($result['student']['fname'] . ' ' . $result['student']['lname']); ?></h6>
                                                <small class="text-muted">ID: <?php echo $result['student']['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($result['student']['id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo $result['total_score']; ?></span>
                                        <small class="text-muted d-block">(<?php echo $result['total_subjects']; ?> subjects)</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $result['average'] >= 80 ? 'success' : ($result['average'] >= 60 ? 'warning' : 'danger'); ?>">
                                            <?php echo $result['average']; ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo $result['grade']; ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo $result['remarks']; ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="academic/core/edit_result.php?std=<?php echo $result['student']['id']; ?>&term=<?php echo $selected_term; ?>" 
                                               class="btn btn-edit" title="Edit Results">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="academic/save_pdf.php?std=<?php echo $result['student']['id']; ?>&term=<?php echo $selected_term; ?>" 
                                               class="btn btn-view" title="Generate Report">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            <button onclick="del('academic/core/drop_results.php?src=manage_results&std=<?php echo $result['student']['id']; ?>&class=<?php echo $selected_class; ?>&term=<?php echo $selected_term; ?>', 'Delete Results?');"
                                                    class="btn btn-delete" title="Delete Results">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php elseif ($show_results && empty($results_data)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-content text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-file-text fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No Results Found</h5>
                    <p class="text-muted mb-3">No examination results found for the selected class and term.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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
    padding: 30px;
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

.btn-edit i, .btn-view i, .btn-delete i {
    font-size: 16px;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease;
    padding: 12px 15px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-label {
    font-size: 1rem;
    margin-bottom: 8px;
    color: #495057;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 5px;
    display: block;
}

.custom-select-wrapper {
    position: relative;
    display: block;
}

.custom-select {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: #fff;
    font-size: 1rem;
    font-weight: 500;
    color: #495057;
    transition: all 0.3s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

.custom-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    transform: translateY(-1px);
}

.custom-select:hover {
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
}

.select-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #007bff;
    font-size: 1.2rem;
    z-index: 2;
    pointer-events: none;
}

.custom-select-wrapper::after {
    content: '\f282';
    font-family: 'bootstrap-icons';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1rem;
    pointer-events: none;
    transition: color 0.3s ease;
}

.custom-select:focus + .select-icon,
.custom-select:hover + .select-icon {
    color: #0056b3;
}

.custom-select:focus ~ .custom-select-wrapper::after,
.custom-select:hover ~ .custom-select-wrapper::after {
    color: #0056b3;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 12px;
    padding: 15px 30px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    min-width: 200px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
}

.select2-container--bootstrap-5 .select2-selection {
    border-radius: 0 8px 8px 0;
    border: none;
    transition: all 0.3s ease;
}

.select2-container--bootstrap-5 .select2-selection--single {
    height: 45px;
    padding: 8px 12px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    padding-left: 0;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 43px;
}

.widget-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    padding: 25px 30px;
    border-bottom: 1px solid #e9ecef;
    border-radius: 10px 10px 0 0;
}

.widget-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
}

.widget-header p {
    margin: 5px 0 0 0;
    font-size: 0.95rem;
}

.widget-content {
    padding: 40px;
}

.dashboard-widget {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.dashboard-widget:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}
</style>

<script>
// Initialize custom select functionality
$(document).ready(function() {
    // Add custom dropdown arrow functionality
    $('.custom-select').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
    
    <?php if ($show_results && !empty($results_data)): ?>
    // Initialize DataTable for results
    $('#srmsTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [7] }, // Actions column
            { "width": "60px", "targets": 0 }, // # column
            { "width": "200px", "targets": 7 } // Actions column
        ],
        "language": {
            "search": "Search results:",
            "lengthMenu": "Show _MENU_ results per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ results",
            "infoEmpty": "Showing 0 to 0 of 0 results",
            "infoFiltered": "(filtered from _MAX_ total results)",
            "emptyTable": "No results found",
            "zeroRecords": "No matching results found"
        },
        "drawCallback": function(settings) {
            // Check if table has data
            if (settings.aiDisplay.length === 0) {
                $(this).find('tbody').html('<tr><td colspan="8" class="text-center">No results found</td></tr>');
            }
        }
    });
    <?php endif; ?>
});
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>