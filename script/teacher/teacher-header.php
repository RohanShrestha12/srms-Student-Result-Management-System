<?php
// Only include this if session hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only include files if not already included
if (!defined('CONFIG_INCLUDED')) {
    define('CONFIG_INCLUDED', true);
    
    // Get the script directory and navigate to the correct location
    $script_dir = dirname(__FILE__);
    $base_dir = dirname($script_dir); // Go up one level from teacher directory
    
    // Change to the base directory
    chdir($base_dir);
    
    // Include required files
    if (file_exists('db/config.php')) {
        require_once('db/config.php');
    } else {
        die('Configuration file not found. Please check the file paths.');
    }
    
    if (file_exists('const/school.php')) {
        require_once('const/school.php');
    }
    
    if (file_exists('const/check_session.php')) {
        require_once('const/check_session.php');
    }
    
    if (file_exists('const/teacher_dashboard.php')) {
        require_once('const/teacher_dashboard.php');
    }
    
    if ($res == "1" && $level == "2") {
    } else {
        header("location:../");
    }
}

// Set default page title if not set
if (!isset($page_title)) {
    $page_title = "Teacher Dashboard";
}

// Get school logo from database
try {
    $stmt = $conn->prepare("SELECT name, logo FROM tbl_school LIMIT 1");
    $stmt->execute();
    $school_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $school_name = $school_data['name'] ?? 'SRMS';
    $school_logo = $school_data['logo'] ?? '';
} catch(PDOException $e) {
    $school_name = 'SRMS';
    $school_logo = '';
}

// Get current time for greeting
$hour = date('G');
if ($hour >= 5 && $hour <= 11) {
    $greeting = "Good morning";
} elseif ($hour >= 12 && $hour <= 15) {
    $greeting = "Good afternoon";
} else {
    $greeting = "Good evening";
}
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
<title>SRMS - <?php echo isset($page_title) ? $page_title : 'Teacher Dashboard'; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href="../">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="icon" href="images/icon.ico">
<link rel="stylesheet" type="text/css" href="cdn.jsdelivr.net/npm/bootstrap-icons%401.10.5/font/bootstrap-icons.css">
<?php if (isset($include_datatables) && $include_datatables): ?>
<link rel="stylesheet" href="cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css">
<?php endif; ?>
<link type="text/css" rel="stylesheet" href="loader/waitMe.css">
<link rel="stylesheet" type="text/css" href="css/header-enhanced.css">
<link rel="stylesheet" type="text/css" href="css/sidebar-enhanced.css">
<link rel="stylesheet" type="text/css" href="css/teacher-academic.css">
</head>
<body class="app sidebar-mini">

<header class="app-header">
    <div class="header-left">
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar">
            <i class="bi bi-list"></i>
        </a>
        
        <div class="app-header__logo">
            <?php if (!empty($school_logo)): ?>
                <img src="images/logo/<?php echo htmlspecialchars($school_logo); ?>" alt="<?php echo htmlspecialchars($school_name); ?>" class="school-logo">
            <?php else: ?>
                <h2 class="mb-0"><?php echo htmlspecialchars($school_name); ?></h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="header-center">
        <div class="search-container">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search students, results, subjects...">
                <button class="search-btn">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="header-right">
        <div class="user-section">
            <div class="user-info">
                <span class="user-name"><?php echo $fname . ' ' . $lname; ?></span>
                <span class="user-role">Teacher</span>
                <small class="user-greeting"><?php echo $greeting; ?></small>
            </div>
            
            <div class="dropdown">
                <a class="user-avatar" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu">
                    <i class="bi bi-person-circle"></i>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="teacher/profile.php"><i class="bi bi-person me-2 fs-5"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <div class="user-info" style="text-align: left;">
            <p class="app-sidebar__user-name"><?php echo $fname . ' ' . $lname; ?></p>
            <p class="app-sidebar__user-designation">Teacher</p>
            <small class="user-status"><?php echo $greeting; ?>! 👋</small>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>"
                href="teacher/index.php"><i class="app-menu__icon bi bi-speedometer2"></i><span
                    class="app-menu__label">Dashboard</span></a></li>
        <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'terms.php') ? 'active' : ''; ?>"
                href="teacher/terms.php"><i class="app-menu__icon bi bi-calendar-event"></i><span
                    class="app-menu__label">Academic Terms</span></a></li>
        <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'combinations.php') ? 'active' : ''; ?>"
                href="teacher/combinations.php"><i class="app-menu__icon bi bi-collection"></i><span
                    class="app-menu__label">Subject Combinations</span></a></li>
        <li class="treeview">
            <a class="app-menu__item" href="javascript:void(0);" data-toggle="treeview">
                <i class="app-menu__icon bi bi-people"></i><span class="app-menu__label">Students</span>
                <i class="treeview-indicator bi bi-chevron-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list_students.php') ? 'active' : ''; ?>"
                        href="teacher/list_students.php"><i class="icon bi bi-list-ul"></i> List Students</a></li>
                <li><a class="treeview-item <?php echo (basename($_SERVER['PHP_SELF']) == 'export_students.php') ? 'active' : ''; ?>"
                        href="teacher/export_students.php"><i class="icon bi bi-download"></i> Export Students</a></li>
            </ul>
        </li>
        <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_results.php') ? 'active' : ''; ?>"
                href="teacher/manage_results.php"><i class="app-menu__icon bi bi-file-earmark-text"></i><span
                    class="app-menu__label">Examination Results</span></a></li>
        <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'grading-system.php') ? 'active' : ''; ?>"
                href="teacher/grading-system.php"><i class="app-menu__icon bi bi-award"></i><span
                    class="app-menu__label">Grading System</span></a></li>
    </ul>
</aside>
<main class="app-content">