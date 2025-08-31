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

$stmt = $conn->prepare("INSERT INTO tbl_staff (fname, lname, gender, email, password, level, status) VALUES (?, ?, ?, ?, ?, '1', '1')");
$stmt->execute([$_POST['fname'], $_POST['lname'], $_POST['gender'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);

$_SESSION['reply'] = array (array("success","Academic account created"));
header("location:../academic.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
