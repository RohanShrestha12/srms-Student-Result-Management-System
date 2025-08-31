<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

try {
// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

$stmt = $conn->prepare("DELETE FROM tbl_staff WHERE id = ?");
$stmt->execute([$_GET['id']]);

$_SESSION['reply'] = array (array("success","Teacher deleted"));
header("location:../teachers.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
