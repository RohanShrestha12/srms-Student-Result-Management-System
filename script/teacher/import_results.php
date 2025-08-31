<?php
// Set page title and include DataTables
$page_title = "Import Results";
$include_datatables = true;
?>

<?php include 'teacher-header.php'; ?>

    <div class="app-title">
        <div>
            <h1><i class="bi bi-upload me-2"></i>Import Results</h1>
            <p>Import examination results from CSV files for your classes.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title"><i class="bi bi-file-earmark-arrow-up me-2"></i>Import Configuration</h3>
                    <p>Configure import settings and upload CSV file</p>
                </div>
                <div class="tile-body">
                    <form class="app_frm" enctype="multipart/form-data" method="POST" autocomplete="OFF" action="teacher/core/import_results.php">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-event me-1"></i>Select Term
                                    </label>
                                    <select class="form-control select2" name="term" required>
                                        <option selected disabled value="">Choose Academic Term</option>
                                        <?php
                                        try {
                                            $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            $stmt = $conn->prepare("SELECT * FROM tbl_terms WHERE status = '1'");
                                            $stmt->execute();
                                            $result = $stmt->fetchAll();

                                            if (count($result) < 1) {
                                                echo '<option disabled>No active terms available</option>';
                                            } else {
                                                foreach($result as $row) {
                                                    ?>
                                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                                    <?php
                                                }
                                            }

                                        } catch(PDOException $e) {
                                            echo '<option disabled>Database connection failed</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-people me-1"></i>Select Class
                                    </label>
                                    <select onchange="fetch_subjects(this.value);" class="form-control select2" name="class" required>
                                        <option selected disabled value="">Choose Class</option>
                                        <?php
                                        try {
                                            $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
                                            LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id
                                            LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_subject_combinations.teacher = tbl_staff.id WHERE tbl_subject_combinations.teacher = ?");
                                            $stmt->execute([$account_id]);
                                            $result = $stmt->fetchAll();

                                            $myclasses = array();

                                            foreach ($result as $value) {
                                                $class_arr = unserialize($value[1]);

                                                foreach ($class_arr as $value) {
                                                    array_push($myclasses, $value);
                                                }
                                            }

                                            $matches = str_split(str_repeat("?", count($myclasses)));
                                            $matches = implode(",", $matches);

                                            $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id IN ($matches)");
                                            $stmt->execute($myclasses);
                                            $result = $stmt->fetchAll();

                                            if (count($result) < 1) {
                                                echo '<option disabled>No classes available</option>';
                                            } else {
                                                foreach($result as $row) {
                                                    ?>
                                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                                    <?php
                                                }
                                            }

                                        } catch(PDOException $e) {
                                            echo '<option disabled>Database connection failed</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-book me-1"></i>Select Subject
                            </label>
                            <select class="form-control" name="subject" required id="sub_imp">
                                <option selected disabled value="">Select Class First</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-file-earmark-text me-1"></i>CSV File Upload
                            </label>
                            <div class="file-upload-wrapper" id="fileUploadArea">
                                <input required accept=".csv" type="file" name="file" id="csvFile" class="form-control">
                                <div class="upload-content">
                                    <i class="bi bi-cloud-upload text-primary"></i>
                                    <h5>Drag & Drop CSV File Here</h5>
                                    <p class="text-muted">or click to browse files</p>
                                    <div class="file-info" id="fileInfo" style="display: none;">
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <span id="fileName"></span>
                                        </div>
                                    </div>
                                    <div class="upload-hint">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Supported format: CSV only | Max size: 10MB
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-8">
                                    <button type="submit" name="submit" value="1" class="btn btn-primary">
                                        <i class="bi bi-upload me-2"></i>Import Results
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="downloadSampleCSV()">
                                        <i class="bi bi-download me-2"></i>Download Sample CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title"><i class="bi bi-info-circle me-2"></i>Import Guidelines</h3>
                    <p>Important information for successful import</p>
                </div>
                <div class="tile-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-lightbulb me-2"></i>CSV Format Requirements:</h6>
                        <ul class="mb-0">
                            <li>First column: Student Registration Number</li>
                            <li>Second column: Student Name (optional)</li>
                            <li>Third column: Score/Marks</li>
                            <li>Include header row (will be skipped)</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Important Notes:</h6>
                        <ul class="mb-0">
                            <li>Ensure registration numbers match existing students</li>
                            <li>Scores should be numeric values</li>
                            <li>Duplicate entries will be skipped</li>
                            <li>Maximum file size: 10MB</li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6><i class="bi bi-table me-2"></i>Sample CSV Format:</h6>
                            <code>
                                Reg_No,Student_Name,Score<br>
                                2023001,John Doe,85<br>
                                2023002,Jane Smith,92<br>
                                2023003,Mike Johnson,78
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('csvFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const uploadArea = document.getElementById('fileUploadArea');

    // File upload handling
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileInfo.style.display = 'block';
            uploadArea.style.borderColor = '#28a745';
            uploadArea.style.background = '#f8fff9';
        }
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#00695C';
        uploadArea.style.background = '#e9ecef';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#00695C';
        uploadArea.style.background = '#f8f9fa';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    // Click to upload
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
});

function downloadSampleCSV() {
    const csvContent = 'Reg_No,Student_Name,Score\n2023001,John Doe,85\n2023002,Jane Smith,92\n2023003,Mike Johnson,78';
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sample_results.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>

<?php include 'teacher-footer.php'; ?>

