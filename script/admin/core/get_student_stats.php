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
    $stats = [];
    
    // Total students
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbl_students");
    $stmt->execute();
    $result = $stmt->fetch();
    $stats['total'] = $result['total'];
    
    // Active students
    $stmt = $conn->prepare("SELECT COUNT(*) as active FROM tbl_students WHERE status = 1");
    $stmt->execute();
    $result = $stmt->fetch();
    $stats['active'] = $result['active'];
    
    // Total classes
    $stmt = $conn->prepare("SELECT COUNT(*) as classes FROM tbl_classes");
    $stmt->execute();
    $result = $stmt->fetch();
    $stats['classes'] = $result['classes'];
    
    // Male students
    $stmt = $conn->prepare("SELECT COUNT(*) as male FROM tbl_students WHERE gender = 'Male'");
    $stmt->execute();
    $result = $stmt->fetch();
    $stats['male'] = $result['male'];
    
    echo json_encode($stats);
    
} catch (PDOException $e) {
    echo json_encode([
        'total' => 0,
        'active' => 0,
        'classes' => 0,
        'male' => 0
    ]);
}
?> 