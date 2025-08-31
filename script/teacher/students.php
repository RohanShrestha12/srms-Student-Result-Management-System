<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "2") {}else{header("location:../");}
if (!isset($_SESSION['student_list'])) {
header("location:./");
}
$students = $_SESSION['student_list'];

// Set page title and include datatables
$page_title = "Students List";
$include_datatables = true;

// Include the teacher header
include('teacher-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-people me-2"></i>Students List</h1>
        <p>View and manage your assigned students</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-widget">
            <div class="widget-header">
                <h5><i class="bi bi-table me-2"></i>Students List</h5>
                <div class="widget-actions">
                    <span class="badge bg-info"><?php echo count($students); ?> Students</span>
                </div>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">Photo</th>
                                <th>Registration Number</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th width="100" class="text-center">Gender</th>
                                <th width="120" class="text-center">Class</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Use the centralized connection from school.php
                                if (!isset($conn) || $conn === null) {
                                    throw new Exception("Database connection not available");
                                }

                                $empty_classes = array();

                                $stmt = $conn->prepare("SELECT * FROM tbl_classes");
                                $stmt->execute();
                                $classes = $stmt->fetchAll();

                                foreach ($classes as $value) {
                                    $empty_classes[$value[0]] = $value[1];
                                }

                                // Create placeholders for the IN clause
                                $placeholders = str_repeat('?,', count($students) - 1) . '?';
                                
                                $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class IN ($placeholders)");
                                $stmt->execute($students);
                                $result = $stmt->fetchAll();

                                if (count($result) > 0) {
                                    foreach($result as $row)
                                    {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        if ($row[9] == "DEFAULT") {
                                        ?>
                                            <img src="images/students/<?php echo $row[4]; ?>.png" class="avatar_img_sm" alt="Student Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        <?php
                                        } else {
                                        ?>
                                            <img src="images/students/<?php echo $row[9]; ?>" class="avatar_img_sm" alt="Student Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <strong class="text-primary"><?php echo $row[0]; ?></strong>
                                    </td>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><?php echo $row[3]; ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo ($row[4] == 'Male') ? 'bg-primary' : 'bg-pink'; ?>">
                                            <i class="bi <?php echo ($row[4] == 'Male') ? 'bi-gender-male' : 'bi-gender-female'; ?> me-1"></i>
                                            <?php echo $row[4]; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            <?php echo $empty_classes[$row[6]]; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-1 mb-3 d-block"></i>
                                            <h5>No Students Found</h5>
                                            <p>No students are currently assigned to your classes.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                }

                            } catch(Exception $e) {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="alert alert-danger">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Connection Error:</strong> <?php echo $e->getMessage(); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the teacher footer
include('teacher-footer.php');
?>
