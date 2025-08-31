<?php
// Set page title and include DataTables
$page_title = "Grading System";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

    <div class="app-title">
        <div>
            <h1><i class="bi bi-award me-2"></i>Grading System</h1>
            <p>View the current grading system and grade boundaries.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title"><i class="bi bi-table me-2"></i>Grade System Overview</h3>
                    <p>Current grading criteria and score boundaries</p>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="srmsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <i class="bi bi-star me-2"></i>Grade Name
                                    </th>
                                    <th>
                                        <i class="bi bi-arrow-down me-2"></i>Minimum Score
                                    </th>
                                    <th>
                                        <i class="bi bi-arrow-up me-2"></i>Maximum Score
                                    </th>
                                    <th>
                                        <i class="bi bi-chat-text me-2"></i>Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                try {
                                    $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    if (count($result) < 1) {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>No grades configured</strong>
                                                    <br>
                                                    <small>Grading system has not been set up yet.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        $row_count = 0;
                                        foreach($result as $row) {
                                            $row_count++;
                                            $row_class = $row_count % 2 == 0 ? 'table-light' : '';
                                            ?>
                                            <tr class="<?php echo $row_class; ?>">
                                                <td>
                                                    <strong class="text-primary"><?php echo $row[1]; ?></strong>
                                                </td>
                                                <td>
                                                    <span><?php echo $row[2]; ?></span>
                                                </td>
                                                <td>
                                                    <span><?php echo $row[3]; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?php echo $row[4]; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }

                                } catch(PDOException $e) {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Database Error</strong>
                                                <br>
                                                <small>Unable to load grading system. Please try again later.</small>
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
