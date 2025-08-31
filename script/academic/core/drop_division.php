<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$id = $_GET['id'];

try {

$stmt = $conn->prepare("DELETE FROM tbl_division_system WHERE division = ?");
$stmt->execute([$id]);

$_SESSION['reply'] = array (array("success",'Division deleted'));
header("location:../division-system.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}


}else{
header("location:../");
}
?>
