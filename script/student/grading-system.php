<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "3") {
} else {
    header("location:../");
}

// Set page title
$page_title = "Grading System";

// Include the student header
include('student-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-award me-2"></i>Grading System</h1>
        <p>Understand how your academic performance is evaluated</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-list-check me-2"></i>Grade Scale</h5>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="srmsTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col"><i class="bi bi-star me-1"></i>Grade Name</th>
                                <th scope="col" class="text-center"><i class="bi bi-arrow-down me-1"></i>Minimum Score</th>
                                <th scope="col" class="text-center"><i class="bi bi-arrow-up me-1"></i>Maximum Score</th>
                                <th scope="col" class="text-center"><i class="bi bi-chat-text me-1"></i>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $conn = new PDO('mysql:host=' . DBHost . ';port=' . DBPort . ';dbname=' . DBName . ';charset=' . DBCharset . ';collation=' . DBCollation . ';prefix=' . DBPrefix . '', DBUser, DBPass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                foreach ($result as $row) {
                                    // Determine badge color based on grade
                                    $badgeClass = 'bg-secondary';
                                    if ($row[1] == 'A' || $row[1] == 'A+') {
                                        $badgeClass = 'bg-success';
                                    } elseif ($row[1] == 'B' || $row[1] == 'B+') {
                                        $badgeClass = 'bg-info';
                                    } elseif ($row[1] == 'C' || $row[1] == 'C+') {
                                        $badgeClass = 'bg-warning';
                                    } elseif ($row[1] == 'D' || $row[1] == 'D+') {
                                        $badgeClass = 'bg-danger';
                                    } elseif ($row[1] == 'F') {
                                        $badgeClass = 'bg-dark';
                                    }
                            ?>
                                    <tr class="align-middle">
                                        <td>
                                            <span class="badge <?php echo $badgeClass; ?> fs-6 rounded-pill">
                                                <i class="bi bi-star-fill me-1"></i><?php echo $row[1]; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark"><?php echo $row[2]; ?>%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark"><?php echo $row[3]; ?>%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark rounded-pill"><?php echo $row[4]; ?></span>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Additional Information Card -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Understanding Your Grades
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-check-circle text-success me-2"></i><strong>A+ to A:</strong> Excellent performance</li>
                                            <li><i class="bi bi-check-circle text-info me-2"></i><strong>B+ to B:</strong> Good performance</li>
                                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i><strong>C+ to C:</strong> Satisfactory performance</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-exclamation-triangle text-danger me-2"></i><strong>D+ to D:</strong> Below average performance</li>
                                            <li><i class="bi bi-x-circle text-dark me-2"></i><strong>F:</strong> Failed - requires improvement</li>
                                            <li><i class="bi bi-question-circle text-secondary me-2"></i><strong>N/A:</strong> Not available</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('student-footer.php'); ?>