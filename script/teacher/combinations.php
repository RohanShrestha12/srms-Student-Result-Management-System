<?php
// Set page title and include DataTables
$page_title = "Subject Combinations";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

    <div class="app-title">
        <div>
            <h1><i class="bi bi-collection me-2"></i>Subject Combinations</h1>
            <p>Manage and view your assigned subject combinations across different classes.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title"><i class="bi bi-book-open me-2"></i>Your Subject Combinations</h3>
                    <p>Overview of subjects you teach and their assigned classes</p>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="srmsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <i class="bi bi-book me-2"></i>Subject
                                    </th>
                                    <th>
                                        <i class="bi bi-house me-2"></i>Classes
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar me-2"></i>Added On
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                try {
                                    $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $empty_classes = array();

                                    $stmt = $conn->prepare("SELECT * FROM tbl_classes");
                                    $stmt->execute();
                                    $classes = $stmt->fetchAll();

                                    foreach ($classes as $value) {
                                        $empty_classes[$value[0]] = $value[1];
                                    }

                                    $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                                      LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
                                      LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id WHERE tbl_subject_combinations.teacher = ?");
                                    $stmt->execute([$account_id]);
                                    $result = $stmt->fetchAll();

                                    if (count($result) < 1) {
                                        ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>No subject combinations found</strong>
                                                    <br>
                                                    <small>You haven't been assigned to any subject combinations yet.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        $row_count = 0;
                                        foreach($result as $row) {
                                            $row_count++;
                                            $row_class = $row_count % 2 == 0 ? 'table-light' : '';
                                            $class_list = unserialize($row[1]);
                                            ?>
                                            <tr class="<?php echo $row_class; ?>">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="subject-icon me-3" style="width: 40px; height: 40px; background: #00695C; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-book text-white"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo $row[6]; ?></strong>
                                                            <br>
                                                            <small class="text-muted">Subject</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="class-badges">
                                                        <?php
                                                        $st = 1;
                                                        foreach ($class_list as $value2) {
                                                            if (isset($empty_classes[$value2])) {
                                                                ?>
                                                                <span class="badge bg-primary me-1 mb-1">
                                                                    <?php echo $empty_classes[$value2]; ?>
                                                                </span>
                                                                <?php
                                                            }
                                                            $st++;
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M d, Y', strtotime($row[3])); ?>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }

                                } catch(PDOException $e) {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Database Error</strong>
                                                <br>
                                                <small>Unable to load subject combinations. Please try again later.</small>
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

<?php include 'teacher-footer.php'; ?>