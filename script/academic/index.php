<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
require_once('const/academic_dashboard.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}

// Set page title
$page_title = "Dashboard";

// Include the academic header
include('academic-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-speedometer2 me-2"></i>Academic Dashboard</h1>
        <p>Welcome to the academic management portal - <?php echo date('l, F j, Y'); ?></p>
    </div>
    <div class="app-title-actions">
        <div class="current-time">
            <i class="bi bi-clock me-2"></i>
            <span id="current-time"></span>
        </div>
    </div>
</div>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-person-workspace me-2"></i>Welcome, <?php echo $fname . ' ' . $lname; ?>!</h5>
            </div>
            <div class="widget-content">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary mb-2">Academic Management Portal</h4>
                        <p class="text-muted mb-0">Manage classes, subjects, results, and academic activities efficiently.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="status-indicator">
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>System Online
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-primary">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($academic_terms); ?></h3>
                <p>Academic Terms</p>
                <small class="text-muted">Active terms</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-success">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($teachers); ?></h3>
                <p>Teachers</p>
                <small class="text-muted">Active staff</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-info">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($my_students); ?></h3>
                <p>Students</p>
                <small class="text-muted">Enrolled students</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-warning">
                <i class="bi bi-book"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($subjects); ?></h3>
                <p>Subjects</p>
                <small class="text-muted">Available subjects</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-secondary">
                <i class="bi bi-building"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($my_class); ?></h3>
                <p>Classes</p>
                <small class="text-muted">Active classes</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-dark">
                <i class="bi bi-collection"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($my_subject); ?></h3>
                <p>Subject Combinations</p>
                <small class="text-muted">Available combinations</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-danger">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stats-content">
                <h3>0</h3>
                <p>Pending Results</p>
                <small class="text-muted">Awaiting entry</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon bg-purple">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stats-content">
                <h3>85%</h3>
                <p>System Health</p>
                <small class="text-muted">Performance</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="academic/classes.php" class="quick-action-card">
                            <div class="action-icon bg-primary">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="action-content">
                                <h6>Manage Classes</h6>
                                <small>Add, edit, or remove classes</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="academic/subjects.php" class="quick-action-card">
                            <div class="action-icon bg-success">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="action-content">
                                <h6>Manage Subjects</h6>
                                <small>Configure academic subjects</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="academic/manage_results.php" class="quick-action-card">
                            <div class="action-icon bg-info">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="action-content">
                                <h6>Manage Results</h6>
                                <small>Enter and manage exam results</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="academic/report.php" class="quick-action-card">
                            <div class="action-icon bg-warning">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <div class="action-content">
                                <h6>Generate Reports</h6>
                                <small>Create academic reports</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics Section -->
<div class="row">
    <div class="col-md-8">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-graph-up me-2"></i>Student Enrollment Trends</h5>
            </div>
            <div class="widget-content">
                <div class="chart-container">
                    <canvas id="enrollmentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-pie-chart me-2"></i>Academic Performance</h5>
            </div>
            <div class="widget-content">
                <div class="chart-container">
                    <canvas id="performanceChart" width="300" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Analytics Row -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-bar-chart me-2"></i>Subject Distribution</h5>
            </div>
            <div class="widget-content">
                <div class="chart-container">
                    <canvas id="subjectChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="dashboard-widget">
            <div class="widget-header simple-header">
                <h5><i class="bi bi-speedometer me-2"></i>System Metrics</h5>
            </div>
            <div class="widget-content">
                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="metric-circle" data-percentage="85">
                            <svg viewBox="0 0 36 36" class="circular-chart">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <text x="18" y="20.35" class="percentage">85%</text>
                            </svg>
                        </div>
                        <div class="metric-label">
                            <h6>Database Performance</h6>
                            <small>Optimal</small>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-circle" data-percentage="92">
                            <svg viewBox="0 0 36 36" class="circular-chart">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="circle" stroke-dasharray="92, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <text x="18" y="20.35" class="percentage">92%</text>
                            </svg>
                        </div>
                        <div class="metric-label">
                            <h6>System Uptime</h6>
                            <small>Excellent</small>
                        </div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-circle" data-percentage="78">
                            <svg viewBox="0 0 36 36" class="circular-chart">
                                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="circle" stroke-dasharray="78, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <text x="18" y="20.35" class="percentage">78%</text>
                            </svg>
                        </div>
                        <div class="metric-label">
                            <h6>Data Accuracy</h6>
                            <small>Good</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    document.getElementById('current-time').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

// Enrollment Chart
const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
const enrollmentChart = new Chart(enrollmentCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Student Enrollment',
            data: [120, 135, 142, 158, 165, 172, 180, 185, 190, 195, 200, 205],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'doughnut',
    data: {
        labels: ['Excellent', 'Good', 'Average', 'Below Average'],
        datasets: [{
            data: [35, 40, 20, 5],
            backgroundColor: [
                '#28a745',
                '#17a2b8',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Subject Distribution Chart
const subjectCtx = document.getElementById('subjectChart').getContext('2d');
const subjectChart = new Chart(subjectCtx, {
    type: 'bar',
    data: {
        labels: ['Mathematics', 'Science', 'English', 'History', 'Geography', 'Arts'],
        datasets: [{
            label: 'Students',
            data: [45, 38, 42, 25, 30, 20],
            backgroundColor: [
                '#667eea',
                '#764ba2',
                '#f093fb',
                '#f5576c',
                '#4facfe',
                '#00f2fe'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<style>
/* Additional styles for improved UI */
.app-title-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.current-time {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

/* Simple header style */
.simple-header {
    background: #f8f9fa !important;
    border-bottom: 1px solid #e9ecef !important;
}

.simple-header h5 {
    color: #495057 !important;
    font-weight: 600;
}

.chart-container {
    position: relative;
    height: 300px;
    margin: 20px 0;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

.metric-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.metric-circle {
    position: relative;
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
}

.circular-chart {
    width: 100%;
    height: 100%;
}

.circle-bg {
    fill: none;
    stroke: #e9ecef;
    stroke-width: 3;
}

.circle {
    fill: none;
    stroke-width: 3;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}

.circle:nth-child(1) .circle {
    stroke: #28a745;
}

.circle:nth-child(2) .circle {
    stroke: #17a2b8;
}

.circle:nth-child(3) .circle {
    stroke: #ffc107;
}

.percentage {
    fill: #495057;
    font-size: 8px;
    text-anchor: middle;
    font-weight: bold;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.metric-label h6 {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
}

.metric-label small {
    color: #6c757d;
    font-size: 12px;
}

.quick-action-card {
    display: flex;
    align-items: center;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 20px;
}

.action-content h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #2c3e50;
}

.action-content small {
    color: #6c757d;
}

.bg-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%) !important;
}

@media (max-width: 768px) {
    .app-title-actions {
        margin-top: 15px;
        justify-content: center;
    }
    
    .quick-action-card {
        margin-bottom: 15px;
    }
    
    .chart-container {
        height: 250px;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}
</style>

<?php include('academic-footer.php'); ?>
