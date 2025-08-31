<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

// Check if this is an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($res == "1" && $level == "0") {
    // Admin access verified
} else {
    if ($isAjax) {
        http_response_code(401);
        die("Access denied");
    } else {
        header("location:../");
        exit();
    }
}

try {
    $student_id = $_POST['id'] ?? '';
    
    if (empty($student_id)) {
        die(json_encode(['error' => 'Student ID is required']));
    }
    
    // Get student data with class name
    $stmt = $conn->prepare("SELECT s.*, c.name as class_name 
                           FROM tbl_students s 
                           LEFT JOIN tbl_classes c ON s.class = c.id 
                           WHERE s.id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        // Add registration date (you might need to add this field to your database)
        $student['registration_date'] = date('Y-m-d'); // Default to current date if not available
        
        echo json_encode($student);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 