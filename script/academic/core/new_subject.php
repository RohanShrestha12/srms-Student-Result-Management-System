<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$name = ucfirst($_POST['name']);

try {

$stmt = $conn->prepare("SELECT * FROM tbl_subjects WHERE name = ?");
$stmt->execute([$name]);
$result = $stmt->fetchAll();

if (count($result) < 1) {
$stmt = $conn->prepare("INSERT INTO tbl_subjects (name) VALUES (?)");
$stmt->execute([$name]);

$_SESSION['reply'] = array (array("success",'Subject registered successfully'));
header("location:../subjects.php");

}else{

$_SESSION['reply'] = array (array("danger",'Subject is already registered'));
header("location:../subjects.php");

}

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}


}else{
header("location:../");
}
?>
