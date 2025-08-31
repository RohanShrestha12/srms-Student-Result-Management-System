<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$fname = ucfirst($_POST['fname']);
$lname = ucfirst($_POST['lname']);
$email = $_POST['email'];
$gender = $_POST['gender'];
$status = '1';
$id = $account_id;

try {

$stmt = $conn->prepare("SELECT email FROM tbl_staff WHERE email = ? AND id != ? UNION SELECT email FROM tbl_students WHERE email = ? AND id != ?");
$stmt->execute([$email, $id, $email, $id]);
$result = $stmt->fetchAll();

if (count($result) > 0) {
$_SESSION['reply'] = array (array("error",'Email is already added'));
header("location:../profile.php");
}else{

$stmt = $conn->prepare("UPDATE tbl_staff SET fname=?, lname=?, gender=?, email=?, status=? WHERE id = ?");
$stmt->execute([$fname, $lname, $gender, $email, $status, $id]);

$_SESSION['reply'] = array (array("success",'Account updated successfully'));
header("location:../profile.php");
}

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
