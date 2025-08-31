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

$stmt = $conn->prepare("UPDATE tbl_staff SET fname = ?, lname = ?, email = ?, gender = ? WHERE id = ?");
$stmt->execute([$_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['gender'], $_SESSION['id']]);

$_SESSION['reply'] = array (array("success","Profile updated"));
header("location:../profile.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
