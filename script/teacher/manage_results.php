<?php
// Set page title and include DataTables
$page_title = "View Results";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-eye me-2"></i>View Results</h1>
        <p>View and manage examination results for your classes.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"><i class="bi bi-search me-2"></i>Results Configuration</h3>
                <p>Select criteria to view examination results</p>
            </div>
            <div class="tile-body">
                <form class="app_frm" enctype="multipart/form-data" method="POST" autocomplete="OFF"
                    action="teacher/core/view_results.php">

                    <div class="form-group">
                        <label class="form-label">Select Term</label>
                        <select class="form-control select2" name="term" required>
                            <option selected disabled value="">Select Term</option>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE status = '1'");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                if (count($result) < 1) {
                                    echo '<option disabled>No active terms available</option>';
                                } else {
                                    foreach ($result as $row) {
                                        ?>
                                        <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                        <?php
                                    }
                                }

                            } catch (PDOException $e) {
                                echo '<option disabled>Database connection failed</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Select Class</label>
                        <select onchange="fetch_subjects(this.value);" class="form-control select2" name="class"
                            required>
                            <option selected disabled value="">Select Class</option>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                                    LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
                                    LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id WHERE tbl_subject_combinations.teacher = ?");
                                $stmt->execute([$account_id]);
                                $result = $stmt->fetchAll();

                                $myclasses = array();

                                foreach ($result as $value) {
                                    $class_arr = unserialize($value[1]);

                                    foreach ($class_arr as $value) {
                                        array_push($myclasses, $value);
                                    }
                                }

                                if (!empty($myclasses)) {
                                    $placeholders = str_repeat('?,', count($myclasses) - 1) . '?';
                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id IN ($placeholders)");
                                    $stmt->execute($myclasses);
                                    $result = $stmt->fetchAll();

                                    if (count($result) < 1) {
                                        echo '<option disabled>No classes available</option>';
                                    } else {
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo '<option disabled>No classes available</option>';
                                }

                            } catch (PDOException $e) {
                                echo '<option disabled>Database connection failed</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject_combination">Select Subject</label>
                        <select class="form-control" name="subject_combination" id="subject_combination" required>
                            <option value="">Choose Subject</option>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT sc.id, s.name as subject_name, c.name as class_name
                                    FROM tbl_subject_combinations sc
                                    JOIN tbl_subjects s ON sc.subject = s.id
                                    JOIN tbl_classes c ON sc.class = c.id
                                    WHERE sc.teacher = ?
                                    ORDER BY c.name, s.name");
                                $stmt->execute([$account_id]);
                                $subjects = $stmt->fetchAll();
                                
                                foreach ($subjects as $subject) {
                                    echo '<option value="' . $subject['id'] . '">' . $subject['subject_name'] . ' (' . $subject['class_name'] . ')</option>';
                                }
                            } catch (PDOException $e) {
                                echo '<option value="">Error loading subjects</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="tile-footer">
                        <button type="submit" name="submit" value="1" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>View Results
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title"><i class="bi bi-info-circle me-2"></i>Results Information</h3>
                <p>Important information about viewing results</p>
            </div>
            <div class="tile-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>How to View Results:</h6>
                    <ul class="mb-0">
                        <li>Select the academic term</li>
                        <li>Choose the class you teach</li>
                        <li>Select the specific subject</li>
                        <li>Click "View Results" to see the data</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>Only results for your assigned subjects will be shown</li>
                        <li>Results are displayed in a table format</li>
                        <li>You can export results to CSV format</li>
                        <li>Results are sorted by student registration number</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'teacher-footer.php'; ?>