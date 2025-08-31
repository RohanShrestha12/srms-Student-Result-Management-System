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

// Import logic here
// This is a placeholder - you'll need to implement the actual import logic

$_SESSION['reply'] = array (array("success","Students imported"));
header("location:../import_students.php");

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}else{
header("location:../");
}
?>
