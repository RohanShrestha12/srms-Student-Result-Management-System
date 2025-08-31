<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('db/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$file = $_FILES['file']['tmp_name'];
$file = fopen($file, "r");
$st_rec = 0;
$imported_count = 0;
$updated_count = 0;

$term = $_POST['term'];
$class = $_POST['class'];
$subject = $_POST['subject'];

try {

while (($r = fgetcsv($file, 10000, ",")) !== FALSE) {

if ($st_rec == 0) {
// Skip header row
}else{

$reg_no = $r[0];
$score = $r[2];

$stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE student = ? AND class=? AND subject_combination=? AND term = ?");
$stmt->execute([$reg_no, $class, $subject, $term]);
$result = $stmt->fetchAll();

if (count($result) < 1) {
$stmt = $conn->prepare("INSERT INTO tbl_exam_results (student, class, subject_combination, term, score) VALUES (?,?,?,?,?)");
$stmt->execute([$reg_no, $class, $subject, $term, $score]);
$imported_count++;
} else {
$updated_count++;
}

}
$st_rec++;
}

// Set success message based on import results
if ($imported_count > 0 && $updated_count == 0) {
$_SESSION['reply'] = array (array("success",'Results import completed successfully. ' . $imported_count . ' new records imported.'));
} elseif ($imported_count > 0 && $updated_count > 0) {
$_SESSION['reply'] = array (array("success",'Results import completed. ' . $imported_count . ' new records imported, ' . $updated_count . ' existing records found.'));
} elseif ($imported_count == 0 && $updated_count > 0) {
$_SESSION['reply'] = array (array("success",'Import completed. All ' . $updated_count . ' records already exist in the database.'));
} else {
$_SESSION['reply'] = array (array("success",'Import completed.'));
}

header("location:../import_results.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
