<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/academic_dashboard.php');
if ($res == "1" && $level == "0") {
} else {
    header("location:../");
}

// Get additional dynamic statistics
try {
    // Get class distribution
    $stmt = $conn->prepare("SELECT c.name, COUNT(s.id) as student_count 
                           FROM tbl_classes c 
                           LEFT JOIN tbl_students s ON c.id = s.class 
                           GROUP BY c.id, c.name 
                           ORDER BY c.name");
    $stmt->execute();
    $class_distribution = $stmt->fetchAll();

    // Get gender statistics
    $stmt = $conn->prepare("SELECT gender, COUNT(*) as count FROM tbl_students GROUP BY gender");
    $stmt->execute();
    $gender_stats = $stmt->fetchAll();

    // Get recent student registrations (last 5)
    $stmt = $conn->prepare("SELECT s.id, s.fname, s.lname, s.email, c.name as class_name 
                           FROM tbl_students s 
                           LEFT JOIN tbl_classes c ON s.class = c.id 
                           ORDER BY s.id DESC LIMIT 5");
    $stmt->execute();
    $recent_students = $stmt->fetchAll();

    // Get recent teacher registrations (last 5)
    $stmt = $conn->prepare("SELECT id, fname, lname, email
                           FROM tbl_staff 
                           WHERE level = '2' 
                           ORDER BY id DESC LIMIT 5");
    $stmt->execute();
    $recent_teachers = $stmt->fetchAll();

    // Get subject distribution
    $stmt = $conn->prepare("SELECT s.name, COUNT(sc.id) as combination_count 
                           FROM tbl_subjects s 
                           LEFT JOIN tbl_subject_combinations sc ON s.id = sc.subject 
                           GROUP BY s.id, s.name 
                           ORDER BY combination_count DESC");
    $stmt->execute();
    $subject_distribution = $stmt->fetchAll();

    // Debug: Print data counts
    echo "<!-- Debug Info: Gender Stats: " . count($gender_stats) . ", Class Distribution: " . count($class_distribution) . " -->";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
// Set page title
$page_title = "Dashboard";

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
        <p>Welcome back, <?php echo $fname; ?>! Here's what's happening in your system.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="dashboard-card academic-terms">
            <div class="card-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="card-content">
                <h4>Academic Terms</h4>
                <p class="card-number"><?php echo number_format($academic_terms); ?></p>
                <small class="card-description">Active terms in system</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="dashboard-card teachers">
            <div class="card-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div class="card-content">
                <h4>Teachers</h4>
                <p class="card-number"><?php echo number_format($teachers); ?></p>
                <small class="card-description">Registered teachers</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="dashboard-card students">
            <div class="card-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="card-content">
                <h4>Students</h4>
                <p class="card-number"><?php echo number_format($my_students); ?></p>
                <small class="card-description">Total students</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="dashboard-card subjects">
            <div class="card-icon">
                <i class="bi bi-book"></i>
            </div>
            <div class="card-content">
                <h4>Subjects</h4>
                <p class="card-number"><?php echo number_format($subjects); ?></p>
                <small class="card-description">Available subjects</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-pie-chart me-2"></i>Student Gender Distribution</h5>
            </div>
            <div class="widget-content">
                <canvas id="genderChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-bar-chart me-2"></i>Class Distribution</h5>
            </div>
            <div class="widget-content">
                <canvas id="classChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-graph-up me-2"></i>Recent Activity</h5>
            </div>
            <div class="widget-content">
                <?php if (!empty($recent_students) || !empty($recent_teachers)): ?>
                    <div class="recent-activity">
                        <?php if (!empty($recent_students)): ?>
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-plus me-1"></i>Recent Student Registrations
                            </h6>
                            <div class="activity-list">
                                <?php foreach ($recent_students as $student): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div class="activity-content">
                                            <strong><?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?></strong>
                                            <small class="text-muted d-block">
                                                <?php echo htmlspecialchars($student['email']); ?> • 
                                                Class: <?php echo htmlspecialchars($student['class_name'] ?? 'Not Assigned'); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($recent_teachers)): ?>
                            <h6 class="text-success mb-3 mt-4">
                                <i class="bi bi-person-workspace me-1"></i>Recent Teacher Registrations
                            </h6>
                            <div class="activity-list">
                                <?php foreach ($recent_teachers as $teacher): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <div class="activity-content">
                                            <strong><?php echo htmlspecialchars($teacher['fname'] . ' ' . $teacher['lname']); ?></strong>
                                            <small class="text-muted d-block">
                                                <?php echo htmlspecialchars($teacher['email']); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No recent activities to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-bell me-2"></i>Quick Actions</h5>
            </div>
            <div class="widget-content">
                <div class="quick-actions">
                    <a href="admin/register_students.php" class="quick-action-item">
                        <i class="bi bi-person-plus"></i>
                        <span>Register Students</span>
                    </a>
                    <a href="admin/teachers.php" class="quick-action-item">
                        <i class="bi bi-people"></i>
                        <span>Manage Teachers</span>
                    </a>
                    <a href="admin/report.php" class="quick-action-item">
                        <i class="bi bi-bar-chart"></i>
                        <span>Generate Reports</span>
                    </a>
                    <a href="admin/manage_students.php" class="quick-action-item">
                        <i class="bi bi-people-fill"></i>
                        <span>Manage Students</span>
                    </a>
                </div>
            </div>
        </div>

        <?php if (!empty($gender_stats)): ?>
        <div class="dashboard-widget mt-4">
            <div class="widget-header">
                <h5><i class="bi bi-pie-chart me-2"></i>Student Gender Distribution</h5>
            </div>
            <div class="widget-content">
                <div class="gender-stats">
                    <?php foreach ($gender_stats as $stat): ?>
                        <div class="gender-stat-item">
                            <div class="gender-label">
                                <i class="bi bi-<?php echo strtolower($stat['gender']) === 'male' ? 'gender-male' : 'gender-female'; ?>"></i>
                                <?php echo htmlspecialchars($stat['gender']); ?>
                            </div>
                            <div class="gender-count">
                                <?php echo number_format($stat['count']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.dashboard-card:hover {
    transform: translateY(-2px);
}

.card-icon {
    font-size: 2rem;
    margin-bottom: 15px;
}

.card-number {
    font-size: 2rem;
    font-weight: bold;
    margin: 10px 0;
}

.dashboard-widget {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.widget-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.widget-content {
    padding: 20px;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.quick-action-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: background 0.2s;
}

.quick-action-item:hover {
    background: #e9ecef;
    color: #333;
}

.quick-action-item i {
    margin-right: 10px;
    font-size: 1.2rem;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    margin-right: 15px;
    font-size: 1.2rem;
    color: #007bff;
}

.gender-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.gender-stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.gender-label {
    display: flex;
    align-items: center;
    gap: 8px;
}

.gender-count {
    font-weight: bold;
    color: #007bff;
}
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Debug: Check if Chart.js loaded
if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded!');
    document.querySelectorAll('canvas').forEach(canvas => {
        canvas.style.display = 'none';
        canvas.parentElement.innerHTML += '<p class="text-danger">Chart.js failed to load. Please check your internet connection.</p>';
    });
} else {
    console.log('Chart.js loaded successfully');
}

// Prepare data for charts with fallbacks
const genderData = <?php echo json_encode($gender_stats ?? []); ?>;
const classData = <?php echo json_encode($class_distribution ?? []); ?>;

console.log('Gender Data:', genderData);
console.log('Class Data:', classData);

// Only create charts if we have data
if (genderData && genderData.length > 0) {
    // Gender Distribution Chart (Pie Chart)
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: genderData.map(item => item.gender),
                datasets: [{
                    data: genderData.map(item => parseInt(item.count)),
                    backgroundColor: [
                        '#007bff',
                        '#dc3545',
                        '#28a745',
                        '#ffc107'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

if (classData && classData.length > 0) {
    // Class Distribution Chart (Bar Chart)
    const classCtx = document.getElementById('classChart');
    if (classCtx) {
        new Chart(classCtx, {
            type: 'bar',
            data: {
                labels: classData.map(item => item.name),
                datasets: [{
                    label: 'Students per Class',
                    data: classData.map(item => parseInt(item.student_count)),
                    backgroundColor: [
                        '#007bff',  // Bootstrap Primary Blue
                        '#28a745',  // Bootstrap Success Green
                        '#ffc107',  // Bootstrap Warning Yellow
                        '#dc3545',  // Bootstrap Danger Red
                        '#6f42c1',  // Bootstrap Purple
                        '#fd7e14',  // Bootstrap Orange
                        '#20c997',  // Bootstrap Info Teal
                        '#e83e8c',  // Bootstrap Pink
                        '#6c757d',  // Bootstrap Secondary Gray
                        '#17a2b8'   // Bootstrap Info Cyan
                    ],
                    borderColor: [
                        '#0056b3',
                        '#1e7e34',
                        '#d39e00',
                        '#c82333',
                        '#5a2d8a',
                        '#e55a00',
                        '#1a7a6b',
                        '#c71f5d',
                        '#545b62',
                        '#138496'
                    ],
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#6c757d'
                        },
                        grid: {
                            color: 'rgba(108, 117, 125, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#007bff',
                        borderWidth: 1,
                        cornerRadius: 6,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                }
            }
        });
    }
}

// Show fallback messages if no data
if (!genderData || genderData.length === 0) {
    const genderChart = document.getElementById('genderChart');
    if (genderChart) {
        genderChart.style.display = 'none';
        genderChart.parentElement.innerHTML += '<p class="text-muted text-center">No gender data available</p>';
    }
}

if (!classData || classData.length === 0) {
    const classChart = document.getElementById('classChart');
    if (classChart) {
        classChart.style.display = 'none';
        classChart.parentElement.innerHTML += '<p class="text-muted text-center">No class data available</p>';
    }
}
</script>

<?php include('admin-footer.php'); ?>