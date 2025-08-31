<?php
// Academic Header - Include this at the top of all academic pages
// Make sure $page_title is set before including this file
if (!isset($page_title)) {
    $page_title = 'Academic Portal';
}

// Set greeting based on time
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <title>SRMS - <?php echo isset($page_title) ? $page_title : 'Academic Portal'; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="../">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" type="text/css"
        href="cdn.jsdelivr.net/npm/bootstrap-icons%401.10.5/font/bootstrap-icons.css">
    <?php if (isset($include_datatables) && $include_datatables): ?>
        <link rel="stylesheet" href="cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css">
    <?php endif; ?>
    <link type="text/css" rel="stylesheet" href="loader/waitMe.css">
    <link rel="stylesheet" type="text/css" href="css/header-enhanced.css">
    <link rel="stylesheet" type="text/css" href="css/sidebar-enhanced.css">
    <link rel="stylesheet" type="text/css" href="css/academic.css">
</head>

<body class="app sidebar-mini">

    <header class="app-header">
        <div class="header-left">
            <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar">
                <i class="bi bi-list"></i>
            </a>

            <div class="app-header__logo">
                <?php if (!empty(WBLogo) && WBLogo != 'default_logo.png'): ?>
                    <img src="images/logo/<?php echo htmlspecialchars(WBLogo); ?>"
                        alt="<?php echo htmlspecialchars(WBName); ?>" class="school-logo">
                <?php else: ?>
                    <h2 class="mb-0"><?php echo htmlspecialchars(WBName); ?></h2>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-center">
            <div class="search-container">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search classes, subjects, results...">
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
                    <span class="user-role">Academic Staff</span>
                    <small class="user-greeting"><?php echo $greeting; ?></small>
                </div>

                <div class="dropdown">
                    <a class="user-avatar" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu settings-menu dropdown-menu-right">
                        <li><a class="dropdown-item" href="academic/profile.php"><i class="bi bi-person me-2 fs-5"></i>
                                My Profile</a></li>
                        
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2 fs-5"></i>
                                Logout</a></li>
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
                <p class="app-sidebar__user-designation">Academic Staff</p>
                <small class="user-status"><?php echo $greeting; ?>! 👋</small>
            </div>
        </div>
        <ul class="app-menu">
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>"
                    href="academic/index.php"><i class="app-menu__icon bi bi-speedometer2"></i><span
                        class="app-menu__label">Dashboard</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'terms.php') ? 'active' : ''; ?>"
                    href="academic/terms.php"><i class="app-menu__icon bi bi-calendar-event"></i><span
                        class="app-menu__label">Academic Terms</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'classes.php') ? 'active' : ''; ?>"
                    href="academic/classes.php"><i class="app-menu__icon bi bi-building"></i><span
                        class="app-menu__label">Classes</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'subjects.php') ? 'active' : ''; ?>"
                    href="academic/subjects.php"><i class="app-menu__icon bi bi-book"></i><span
                        class="app-menu__label">Subjects</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'combinations.php') ? 'active' : ''; ?>"
                    href="academic/combinations.php"><i class="app-menu__icon bi bi-collection"></i><span
                        class="app-menu__label">Subject Combinations</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'students_list.php') ? 'active' : ''; ?>"
                    href="academic/students_list.php"><i class="app-menu__icon bi bi-people"></i><span
                        class="app-menu__label">Students</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_results.php') ? 'active' : ''; ?>"
                    href="academic/manage_results.php"><i class="app-menu__icon bi bi-file-earmark-text"></i><span
                        class="app-menu__label">Examination Results</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'grading-system.php') ? 'active' : ''; ?>"
                    href="academic/grading-system.php"><i class="app-menu__icon bi bi-award"></i><span
                        class="app-menu__label">Grading System</span></a></li>
            <li><a class="app-menu__item <?php echo (basename($_SERVER['PHP_SELF']) == 'announcement.php') ? 'active' : ''; ?>"
                    href="academic/announcement.php"><i class="app-menu__icon bi bi-bell"></i><span
                        class="app-menu__label">Announcements</span></a></li>
        </ul>
    </aside>
    <main class="app-content">