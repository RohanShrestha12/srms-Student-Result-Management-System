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
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    // Validation
    if (empty($current_password)) {
        $errors[] = "Current password is required.";
    }
    
    if (empty($new_password)) {
        $errors[] = "New password is required.";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters long.";
    } elseif (strlen($new_password) > 128) {
        $errors[] = "New password must be less than 128 characters.";
    }
    
    if (empty($confirm_password)) {
        $errors[] = "Password confirmation is required.";
    }
    
    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    }
    
    // Verify current password
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT password FROM tbl_students WHERE id = ?");
            $stmt->execute([$account_id]);
            $student = $stmt->fetch();
            
            if (!$student || !password_verify($current_password, $student['password'])) {
                $errors[] = "Current password is incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error occurred.";
        }
    }
    
    // Check if new password is same as current
    if (empty($errors)) {
        if (password_verify($new_password, $student['password'])) {
            $errors[] = "New password must be different from current password.";
        }
    }
    
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE tbl_students SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $account_id]);
            
            $_SESSION['reply'] = array(array("success", "Password updated successfully!"));
        } catch (PDOException $e) {
            $_SESSION['reply'] = array(array("danger", "Error updating password: " . $e->getMessage()));
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
