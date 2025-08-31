<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$title = $_POST['title'];
$audience = $_POST['audience'];
$announcement = $_POST['announcement'];
$post_date = date('Y-m-d G:i:s');
$level = $_POST['audience'];

try {

$stmt = $conn->prepare("INSERT INTO tbl_announcements (title, announcement, create_date, level) VALUES (?,?,?,?)");
$stmt->execute([$title, $announcement, $post_date, $level]);

$_SESSION['reply'] = array (array("success",'Announcement created successfully'));
header("location:../announcement.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}


}else{
header("location:../");
}
?>
