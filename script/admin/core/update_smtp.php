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

$stmt = $conn->prepare("UPDATE tbl_smtp SET server = ?, username = ?, password = ?, port = ?, encryption = ? WHERE id = 1");
$stmt->execute([$_POST['mail_server'], $_POST['mail_username'], $_POST['mail_password'], $_POST['mail_port'], $_POST['mail_security']]);

$_SESSION['reply'] = array (array("success","SMTP settings updated"));
header("location:../smtp.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
