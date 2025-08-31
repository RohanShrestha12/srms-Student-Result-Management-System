<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "3") {
    // Student session is valid
} else {
    header("location:../");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($fname)) {
        $errors[] = "First name is required.";
    } elseif (strlen($fname) > 50) {
        $errors[] = "First name must be less than 50 characters.";
    }
    
    if (strlen($mname) > 50) {
        $errors[] = "Middle name must be less than 50 characters.";
    }
    
    if (empty($lname)) {
        $errors[] = "Last name is required.";
    } elseif (strlen($lname) > 50) {
        $errors[] = "Last name must be less than 50 characters.";
    }
    
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email address must be less than 100 characters.";
    }
    
    // Check if email already exists for another student
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT id FROM tbl_students WHERE email = ? AND id != ?");
            $stmt->execute([$email, $account_id]);
            if ($stmt->fetch()) {
                $errors[] = "Email address is already in use by another student.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error occurred.";
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE tbl_students SET fname = ?, mname = ?, lname = ?, email = ? WHERE id = ?");
            $stmt->execute([$fname, $mname, $lname, $email, $account_id]);
            
            // Update session variables
            $_SESSION['fname'] = $fname;
            $_SESSION['mname'] = $mname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            
            $_SESSION['reply'] = array(array("success", "Profile updated successfully!"));
        } catch (PDOException $e) {
            $_SESSION['reply'] = array(array("danger", "Error updating profile: " . $e->getMessage()));
        }
    } else {
        $_SESSION['reply'] = array(array("danger", implode(" ", $errors)));
    }
    
    header("location:../settings.php");
    exit();
} else {
    header("location:../settings.php");
    exit();
}
?> 