<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$id = $_GET['id'];
$img = $_GET['img'];

if ($img == "DEFAULT") {

}else{
unlink('images/students/'.$img.'');
}

try {

$stmt = $conn->prepare("DELETE FROM tbl_students WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['reply'] = array (array("success",'Student deleted successfully'));
header("location:../students_list.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}


}else{
header("location:../");
}
?>
