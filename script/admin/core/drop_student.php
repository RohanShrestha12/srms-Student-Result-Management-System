<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

// Check if this is an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($res == "1" && $level == "0") {
    // Admin access verified
} else {
    if ($isAjax) {
        http_response_code(401);
        die("Access denied");
    } else {
        header("location:../");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$id = $_POST['id'];

try {
// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

$stmt = $conn->prepare("DELETE FROM tbl_students WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['reply'] = array (array("success",'Student deleted successfully'));
header("location:../students.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
