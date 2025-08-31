<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "1") {
    if (isset($_GET['id'])) {
        $student_id = $_GET['id'];
        
        try {
            // Get student data
            $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE id = ?");
            $stmt->execute([$student_id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($student) {
                // Get all classes for dropdown
                $stmt2 = $conn->prepare("SELECT * FROM tbl_classes ORDER BY name");
                $stmt2->execute();
                $classes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_student.php">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-hash me-2"></i>Student ID
                            </label>
                            <input type="text" class="form-control" value="<?php echo $student['id']; ?>" readonly>
                            <small class="text-muted">Student ID cannot be changed</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2"></i>First Name
                            </label>
                            <input type="text" class="form-control" name="fname" value="<?php echo htmlspecialchars($student['fname']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-2"></i>Last Name
                            </label>
                            <input type="text" class="form-control" name="lname" value="<?php echo htmlspecialchars($student['lname']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building me-2"></i>Class
                            </label>
                            <select class="form-control select2" name="class" required style="width: 100%;">
                                <option value="">Choose a class</option>
                                <?php foreach($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo ($class['id'] == $student['class']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($class['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg app_btn">
                            <i class="bi bi-check-circle me-2"></i>Update Student
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </form>

                <script>
                // Re-initialize Select2 for the dynamically loaded form
                $(document).ready(function() {
                    $('.select2').select2({
                        dropdownParent: $("#editModal"),
                        theme: "bootstrap-5"
                    });
                });
                </script>
                
                <?php
            } else {
                echo '<div class="alert alert-danger">Student not found!</div>';
            }
        } catch(PDOException $e) {
            echo '<div class="alert alert-danger">Database Error: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Student ID not provided!</div>';
    }
} else {
    echo '<div class="alert alert-danger">Access denied!</div>';
}
?> 