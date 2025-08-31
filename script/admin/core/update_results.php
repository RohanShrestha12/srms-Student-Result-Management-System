<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

try {
// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

$stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id");
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $key => $row) {
$class_list = unserialize($row[1]);

if (in_array($_POST['class'], $class_list))
{

$stmt = $conn->prepare("SELECT * FROM tbl_exam_results WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
$stmt->execute([$_POST['class'], $row[0], $_POST['term'], $_POST['student']]);
$ex_result = $stmt->fetchAll();

if (count($ex_result) > 0) {
$stmt = $conn->prepare("UPDATE tbl_exam_results SET score = ? WHERE class = ? AND subject_combination = ? AND term = ? AND student = ?");
$stmt->execute([$_POST[$row[0]], $_POST['class'], $row[0], $_POST['term'], $_POST['student']]);
}else{
$stmt = $conn->prepare("INSERT INTO tbl_exam_results (class, subject_combination, term, student, score) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$_POST['class'], $row[0], $_POST['term'], $_POST['student'], $_POST[$row[0]]]);
}

}

}

$_SESSION['reply'] = array (array("success","Results updated"));
header("location:../single_results.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
