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

$stmt = $conn->prepare("UPDATE tbl_staff SET password = ? WHERE id = ?");
$stmt->execute([password_hash($_POST['password'], PASSWORD_DEFAULT), $_SESSION['id']]);

$_SESSION['reply'] = array (array("success","Password updated"));
header("location:../profile.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
