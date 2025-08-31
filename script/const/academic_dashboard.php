<?php
// Initialize variables
$my_subject = 0;
$my_class = 0;
$my_students = 0;
$academic_terms = 0;
$teachers = 0;
$students = 0;
$subjects = 0;

try {
    // Use the connection from school.php instead of creating a new one
    // $conn is already available from school.php

    // Get active academic terms
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_terms WHERE status = '1'");
    $stmt->execute();
    $academic_terms = $stmt->fetchColumn();

    // Get total teachers (level 2 = teachers)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_staff WHERE level = '2' AND status = '1'");
    $stmt->execute();
    $teachers = $stmt->fetchColumn();

    // Get total students
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_students");
    $stmt->execute();
    $my_students = $stmt->fetchColumn();

    // Get total subjects
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_subjects");
    $stmt->execute();
    $subjects = $stmt->fetchColumn();

    // Get total classes
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_classes");
    $stmt->execute();
    $my_class = $stmt->fetchColumn();

    // Get total subject combinations
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_subject_combinations");
    $stmt->execute();
    $my_subject = $stmt->fetchColumn();

    // Get total students (same as my_students for admin dashboard)
    $students = $my_students;

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
