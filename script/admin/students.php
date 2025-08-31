<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "0") {
} else {
    header("location:../");
}

// Set page title and include datatables
$page_title = "Manage Students";
$include_datatables = true;

// Include the admin header
include('admin-header.php');
?>

<div class="app-title">
    <div>
        <h1><i class="bi bi-people me-2"></i>Manage Students</h1>
        <p>View and manage all students in the system</p>
    </div>
    <div class="app-title-actions">
        <a href="admin/register_students.php" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Add New Student
        </a>
        <a href="admin/import_students.php" class="btn btn-outline-primary">
            <i class="bi bi-upload me-2"></i>Import Students
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalStudents">0</h3>
                <p>Total Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-success">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stats-content">
                <h3 id="activeStudents">0</h3>
                <p>Active Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-info">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="stats-content">
                <h3 id="totalClasses">0</h3>
                <p>Total Classes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-warning">
                <i class="bi bi-gender-ambiguous"></i>
            </div>
            <div class="stats-content">
                <h3 id="maleStudents">0</h3>
                <p>Male Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Filter by Class</label>
                        <select class="form-control" id="classFilter">
                            <option value="">All Classes</option>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT id, name FROM tbl_classes ORDER BY name ASC");
                                $stmt->execute();
                                $classes = $stmt->fetchAll();
                                
                                foreach ($classes as $class) {
                                    echo '<option value="' . $class[0] . '">' . htmlspecialchars($class[1]) . '</option>';
                                }
                            } catch (PDOException $e) {
                                echo '<option value="">Error loading classes</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter by Gender</label>
                        <select class="form-control" id="genderFilter">
                            <option value="">All Genders</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search Students</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by name, email, or ID...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Records per Page</label>
                        <select class="form-control" id="perPageSelect">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="refreshTable()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="srmsTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="50">#</th>
                                <th width="80">Photo</th>
                                <th>Registration ID</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Student data will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="pagination-info">
                            <small class="text-muted">
                                Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> students
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Student pagination">
                            <ul class="pagination justify-content-end" id="paginationControls">
                                <!-- Pagination buttons will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="admin/core/update_student.php" class="app_frm" method="POST" autocomplete="OFF">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">First Name</label>
                                <input id="fname" name="fname" required class="form-control" onkeypress="return lettersOnly(event)" type="text" placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input id="mname" name="mname" required class="form-control" onkeypress="return lettersOnly(event)" type="text" placeholder="Enter middle name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Name</label>
                                <input id="lname" name="lname" required class="form-control" onkeypress="return lettersOnly(event)" type="text" placeholder="Enter last name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <select id="gender" class="form-control" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input id="email" name="email" required class="form-control" type="email" placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Class</label>
                                <select id="class" class="form-control select2" name="class" required style="width: 100%;">
                                    <option value="" selected disabled>Select Class</option>
                                    <?php
                                    try {
                                        $stmt = $conn->prepare("SELECT id, name FROM tbl_classes ORDER BY name ASC");
                                        $stmt->execute();
                                        $classes = $stmt->fetchAll();
                                        
                                        foreach ($classes as $class) {
                                            echo '<option value="' . $class[0] . '">' . htmlspecialchars($class[1]) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option value="">Error loading classes</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Image (Optional)</label>
                        <input name="image" class="form-control" type="file" accept=".png, .jpg, .jpeg">
                        <div class="form-text">Leave empty to keep current image</div>
                    </div>

                    <input type="hidden" name="old_photo" id="photo">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button class="btn btn-primary app_btn" type="submit">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Student Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">
                    <i class="bi bi-person me-2"></i>Student Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="student-photo-container">
                            <img id="viewPhoto" src="" alt="Student Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="student-details">
                            <h4 id="viewName" class="mb-3"></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Registration ID:</strong> <span id="viewId"></span></p>
                                    <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                                    <p><strong>Gender:</strong> <span id="viewGender"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Class:</strong> <span id="viewClass"></span></p>
                                    <p><strong>Status:</strong> <span id="viewStatus"></span></p>
                                    <p><strong>Registration Date:</strong> <span id="viewDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editStudentFromView()">Edit Student</button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for pagination
let currentPage = 1;
let totalPages = 1;
let totalRecords = 0;
let perPage = 10;

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Class',
        allowClear: true
    });
    
    // Load initial data
    loadStudents();
    loadStatistics();
    
    // Setup filters
    $('#classFilter, #genderFilter').change(function() {
        currentPage = 1; // Reset to first page when filters change
        loadStudents();
    });
    
    // Setup search
    $('#searchInput').on('keyup', function() {
        currentPage = 1; // Reset to first page when searching
        loadStudents();
    });
    
    // Setup per page selector
    $('#perPageSelect').change(function() {
        perPage = parseInt($(this).val());
        currentPage = 1; // Reset to first page when changing per page
        loadStudents();
    });
});

function loadStudents() {
    const classFilter = $('#classFilter').val();
    const genderFilter = $('#genderFilter').val();
    const searchTerm = $('#searchInput').val();
    
    // Show loading state
    $('#studentsTableBody').html('<tr><td colspan="9" class="text-center"><div class="loading-spinner"></div> Loading students...</td></tr>');
    
    $.ajax({
        url: 'admin/core/get_students.php',
        type: 'POST',
        data: {
            class_filter: classFilter,
            gender_filter: genderFilter,
            search_term: searchTerm,
            page: currentPage,
            per_page: perPage
        },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            $('#studentsTableBody').html(response);
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401 || xhr.status === 403) {
                // Session expired or access denied
                alert('Session expired. Please refresh the page and try again.');
                window.location.reload();
            } else {
                $('#studentsTableBody').html('<tr><td colspan="9" class="text-center text-danger">Error loading students. Please try again.</td></tr>');
            }
        }
    });
}

function updatePagination(paginationInfo) {
    currentPage = paginationInfo.current_page;
    totalPages = paginationInfo.total_pages;
    totalRecords = paginationInfo.total_records;
    perPage = paginationInfo.per_page;
    
    // Update pagination info
    $('#showingFrom').text(paginationInfo.showing_from);
    $('#showingTo').text(paginationInfo.showing_to);
    $('#totalRecords').text(paginationInfo.total_records);
    
    // Generate pagination controls
    generatePaginationControls();
}

function generatePaginationControls() {
    const paginationContainer = $('#paginationControls');
    paginationContainer.empty();
    
    if (totalPages <= 1) {
        return; // No pagination needed
    }
    
    // Previous button
    const prevDisabled = currentPage <= 1 ? 'disabled' : '';
    paginationContainer.append(`
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" onclick="goToPage(${currentPage - 1})" ${prevDisabled}>
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `);
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Adjust start page if we're near the end
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page
    if (startPage > 1) {
        paginationContainer.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(1)">1</a>
            </li>
        `);
        if (startPage > 2) {
            paginationContainer.append(`
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `);
        }
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        paginationContainer.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
            </li>
        `);
    }
    
    // Last page
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationContainer.append(`
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `);
        }
        paginationContainer.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="goToPage(${totalPages})">${totalPages}</a>
            </li>
        `);
    }
    
    // Next button
    const nextDisabled = currentPage >= totalPages ? 'disabled' : '';
    paginationContainer.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" onclick="goToPage(${currentPage + 1})" ${nextDisabled}>
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `);
}

function goToPage(page) {
    if (page >= 1 && page <= totalPages && page !== currentPage) {
        currentPage = page;
        loadStudents();
    }
}

function loadStatistics() {
    $.ajax({
        url: 'admin/core/get_student_stats.php',
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            const stats = JSON.parse(response);
            $('#totalStudents').text(stats.total);
            $('#activeStudents').text(stats.active);
            $('#totalClasses').text(stats.classes);
            $('#maleStudents').text(stats.male);
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401 || xhr.status === 403) {
                console.log('Session expired while loading statistics');
            } else {
                console.log('Error loading statistics');
            }
        }
    });
}

function refreshTable() {
    currentPage = 1; // Reset to first page
    loadStudents();
    loadStatistics();
}

function editStudent(id) {
    // Get student data and populate modal
    $.ajax({
        url: 'admin/core/get_student.php',
        type: 'POST',
        data: { id: id },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            const student = JSON.parse(response);
            
            $('#fname').val(student.fname);
            $('#mname').val(student.mname);
            $('#lname').val(student.lname);
            $('#gender').val(student.gender);
            $('#email').val(student.email);
            $('#class').val(student.class);
            $('#photo').val(student.display_image);
            $('#id').val(student.id);
            
            $('#class').trigger('change');
            $('#editModal').modal('show');
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401 || xhr.status === 403) {
                alert('Session expired. Please refresh the page and try again.');
                window.location.reload();
            } else {
                alert('Error loading student data');
            }
        }
    });
}

function viewStudent(id) {
    $.ajax({
        url: 'admin/core/get_student.php',
        type: 'POST',
        data: { id: id },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            const student = JSON.parse(response);
            
            $('#viewName').text(student.fname + ' ' + student.mname + ' ' + student.lname);
            $('#viewId').text(student.id);
            $('#viewEmail').text(student.email);
            $('#viewGender').text(student.gender);
            $('#viewClass').text(student.class_name);
            $('#viewStatus').text(student.status == 1 ? 'Active' : 'Inactive');
            $('#viewDate').text(student.registration_date || 'N/A');
            
            // Set photo
            if (student.display_image && student.display_image !== 'DEFAULT') {
                $('#viewPhoto').attr('src', 'images/students/' + student.display_image);
            } else {
                $('#viewPhoto').attr('src', 'images/students/' + student.gender.toLowerCase() + '.png');
            }
            
            $('#viewModal').modal('show');
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401 || xhr.status === 403) {
                alert('Session expired. Please refresh the page and try again.');
                window.location.reload();
            } else {
                alert('Error loading student data');
            }
        }
    });
}

function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        $.ajax({
            url: 'admin/core/drop_student.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response.includes('success')) {
                    loadStudents();
                    loadStatistics();
                    alert('Student deleted successfully');
                } else {
                    alert('Error deleting student');
                }
            },
            error: function() {
                alert('Error deleting student');
            }
        });
    }
}

function editStudentFromView() {
    $('#viewModal').modal('hide');
    setTimeout(function() {
        $('#editModal').modal('show');
    }, 500);
}
</script>

<style>
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stats-icon i {
    font-size: 24px;
    color: white;
}

.stats-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.stats-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.app-title-actions {
    display: flex;
    gap: 10px;
}

.student-photo-container {
    margin-bottom: 20px;
}

.student-details h4 {
    color: #333;
    margin-bottom: 20px;
}

.student-details p {
    margin-bottom: 10px;
    color: #666;
}

.student-details strong {
    color: #333;
}

.table th {
    background-color: #343a40;
    color: white;
    border-color: #454d55;
}

.avatar-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Pagination Styles */
.pagination-info {
    display: flex;
    align-items: center;
    height: 100%;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    color: white;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-info {
        text-align: center;
        margin-bottom: 10px;
    }
    
    .pagination {
        justify-content: center !important;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* Loading state */
.table-loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<?php include('admin-footer.php'); ?>