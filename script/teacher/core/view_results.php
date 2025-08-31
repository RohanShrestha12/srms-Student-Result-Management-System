<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "2") {
    if (isset($_POST['term']) && isset($_POST['class']) && isset($_POST['subject_combination'])) {
        $_SESSION['result__data'] = [
            'term' => $_POST['term'],
            'class' => $_POST['class'],
            'subject_combination' => $_POST['subject_combination']
        ];
        header("location:../results.php");
    } else {
        header("location:../manage_results.php");
    }
} else {
    header("location:../");
}
?>
