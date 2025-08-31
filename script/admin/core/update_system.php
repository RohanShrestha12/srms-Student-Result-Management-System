<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if($_FILES['company_logo']['name'] == "")  {
try {
// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

$stmt = $conn->prepare("UPDATE tbl_school SET name = ?");
$stmt->execute([$_POST['name']]);

$_SESSION['reply'] = array (array("success","System settings updated"));
header("location:../system.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}
}else{

$target_dir = "images/logo/";
$target_file = $target_dir . basename($_FILES["company_logo"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$destn_file = 'school_logo'.time().'.'.$imageFileType.'';
$destn_upload = $target_dir . $destn_file;
$unlink = 'images/logo/'.$_POST['old_logo'].'';

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
$_SESSION['reply'] = array (array("error","Only JPG, PNG and JPEG files are allowed"));
header("location:../system.php");
}else{

if (move_uploaded_file($_FILES["company_logo"]["tmp_name"], $destn_upload)) {
unlink($unlink);

try {
// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

$stmt = $conn->prepare("UPDATE tbl_school SET name = ?, logo = ?");
$stmt->execute([$_POST['name'], $destn_file]);

$_SESSION['reply'] = array (array("success","System settings updated"));
header("location:../system.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
$_SESSION['reply'] = array (array("danger","Could not upload file"));
header("location:../system.php");
}
}

}

}else{
header("location:../");
}
?>
