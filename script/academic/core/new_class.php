<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$name = ucfirst($_POST['name']);
$reg_date = date('Y-m-d G:i:s');

try {

$stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE name = ?");
$stmt->execute([$name]);
$result = $stmt->fetchAll();

if (count($result) < 1) {
$stmt = $conn->prepare("INSERT INTO tbl_classes (name, registration_date) VALUES (?,?)");
$stmt->execute([$name, $reg_date]);

$_SESSION['reply'] = array (array("success",'Class registered successfully'));
header("location:../classes.php");

}else{

$_SESSION['reply'] = array (array("danger",'Class is already registered'));
header("location:../classes.php");

}

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}


}else{
header("location:../");
}
?>
