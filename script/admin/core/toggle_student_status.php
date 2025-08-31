<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/check_session.php');

// Check if user is admin
if ($res != "1" || $level != "0") {
    http_response_code(403);
    echo "Access denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    $status = isset($_POST['status']) ? (int)$_POST['status'] : null;
    
    if (!$student_id || !in_array($status, [0, 1])) {
        echo "Invalid parameters";
        exit();
    }
    
    try {
        $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if student exists
        $stmt = $conn->prepare("SELECT id FROM tbl_students WHERE id = ?");
        $stmt->execute([$student_id]);
        
        if ($stmt->rowCount() == 0) {
            echo "Student not found";
            exit();
        }
        
        // Update student status
        $stmt = $conn->prepare("UPDATE tbl_students SET status = ? WHERE id = ?");
        $stmt->execute([$status, $student_id]);
        
        if ($stmt->rowCount() > 0) {
            echo "success";
        } else {
            echo "No changes made";
        }
        
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method";
}
?>
