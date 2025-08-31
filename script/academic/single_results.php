<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Initialize variables
$tit = 'Student Results';
$page_title = 'Student Results';
$std_data = [];
$term_data = [];
$class_data = [];

if (isset($_SESSION['student_result'])) {
$std = $_SESSION['student_result']['student'];
$term = $_SESSION['student_result']['term'];

try {
// $conn is already available from school.php
// No need to create a new connection

$stmt = $conn->prepare("SELECT * FROM tbl_students WHERE id = ?");
$stmt->execute([$std]);
$std_data = $stmt->fetchAll();

if (empty($std_data)) {
    throw new Exception("Student not found");
}

$stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE id = ?");
$stmt->execute([$term]);
$term_data = $stmt->fetchAll();

if (empty($term_data)) {
    throw new Exception("Term not found");
}

$stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
$stmt->execute([$std_data[0][6]]);
$class_data = $stmt->fetchAll();

if (empty($class_data)) {
    throw new Exception("Class not found");
}

$tit = ''.$std_data[0][1].' '.$std_data[0][2].' '.$std_data[0][3].' ('.$term_data[0][1].' Results)';

// Set page title for header
$page_title = $tit;

}catch(PDOException $e)
{
$error_message = "Database connection failed: " . $e->getMessage();
$tit = 'Error - Database Connection Failed';
$page_title = 'Error';
}catch(Exception $e) {
$error_message = "Error: " . $e->getMessage();
$tit = 'Error - ' . $e->getMessage();
$page_title = 'Error';
}

}else{
header("location:./");
exit();
}

// Include the academic header
require_once('academic/academic-header.php');
?>

<?php if (isset($error_message)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> <?php echo htmlspecialchars($error_message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (empty($std_data) || empty($term_data) || empty($class_data)): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Warning!</strong> Required data is missing. Please check your session and try again.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php else: ?>

<div class="app-title">
<div>
<h1><?php echo htmlspecialchars($tit); ?></h1>
</div>
</div>

<div class="row">
<div class="col-md-12 ">
<div class="tile">
<div class="tile-body">

<form enctype="multipart/form-data" action="academic/core/update_results.php" class="app_frm row" method="POST" autocomplete="OFF">

<?php
$tscore = 0;
$stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $key => $row) {
$class_list = unserialize($row[1]);

if (in_array($std_data[0][6], $class_list))
{

$score = 0;

$stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
$stmt->execute([$std_data[0][6], $row[0], $term, $std]);
$ex_result = $stmt->fetchAll();

if (!empty($ex_result[0][5])) {
$score = $ex_result[0][5];
$tscore = $tscore + $score;
}

?>

<div class="mb-3 col-md-2">
<label class="form-label"><?php echo htmlspecialchars($row[6]); ?></label>
<input value="<?php echo htmlspecialchars($score); ?>" 
       name="<?php echo htmlspecialchars($row[0]);?>" 
       class="form-control score-input" 
       required 
       type="number" 
       min="0" 
       max="100" 
       step="0.01"
       placeholder="Enter score (0-100)"
       oninput="validateScore(this)"
       onblur="validateScore(this)">
<div class="invalid-feedback score-error"></div>
</div>

<?php
}


}

?>
<input type="hidden" name="student" value="<?php echo htmlspecialchars($std); ?>">
<input type="hidden" name="term" value="<?php echo htmlspecialchars($term); ?>">
<input type="hidden" name="class" value="<?php echo htmlspecialchars($std_data[0][6]); ?>">
<div class="">
<button class="btn btn-primary app_btn" type="submit">Save Results</button>
<?php if ($tscore > 0) {
?><a onclick="del('academic/core/drop_results?src=single_results&std=<?php echo htmlspecialchars($std); ?>&class=<?php echo htmlspecialchars($std_data[0][6]); ?>&term=<?php echo htmlspecialchars($term); ?>', 'Delete Results?');" href="javascript:void(0);" class="btn btn-danger">Delete</a><?php
}
?>
</div>
</form>

</div>
</div>
</div>
</div>

<?php endif; ?>

<style>
/* Score input validation styling */
.score-input.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.score-input.is-valid {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.score-error {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Enhanced form styling */
.app_frm .score-input:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Responsive grid adjustments */
@media (max-width: 768px) {
    .col-md-2 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<script>
// Score validation function
function validateScore(input) {
    const score = parseFloat(input.value);
    const errorDiv = input.parentNode.querySelector('.score-error');
    
    // Clear previous errors
    input.classList.remove('is-invalid');
    errorDiv.textContent = '';
    
    // Check if empty
    if (input.value === '') {
        return true;
    }
    
    // Check if it's a valid number
    if (isNaN(score)) {
        input.classList.add('is-invalid');
        errorDiv.textContent = 'Please enter a valid number';
        return false;
    }
    
    // Check range (0-100)
    if (score < 0 || score > 100) {
        input.classList.add('is-invalid');
        errorDiv.textContent = 'Score must be between 0 and 100';
        return false;
    }
    
    // Check decimal places (max 2)
    if (input.value.includes('.') && input.value.split('.')[1].length > 2) {
        input.classList.add('is-invalid');
        errorDiv.textContent = 'Maximum 2 decimal places allowed';
        return false;
    }
    
    return true;
}

// Form validation before submission
document.querySelector('.app_frm').addEventListener('submit', function(e) {
    const scoreInputs = document.querySelectorAll('.score-input');
    let isValid = true;
    
    scoreInputs.forEach(input => {
        if (!validateScore(input)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        // Show error message
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fix the errors before submitting the form.',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }
    
    // Show loading state
    Swal.fire({
        title: 'Saving Results...',
        text: 'Please wait while we save the results.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

// Real-time validation on input
document.addEventListener('DOMContentLoaded', function() {
    const scoreInputs = document.querySelectorAll('.score-input');
    
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateScore(this);
        });
        
        input.addEventListener('blur', function() {
            validateScore(this);
        });
    });
});
</script>

<?php
// Include the academic footer
require_once('academic/academic-footer.php');
?>
