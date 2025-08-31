<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

// Check if this is an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($res == "1" && $level == "0") {
    // Admin access verified
} else {
    if ($isAjax) {
        http_response_code(401);
        die("Access denied");
    } else {
        header("location:../");
        exit();
    }
}

try {
    // Get filter parameters
    $class_filter = $_POST['class_filter'] ?? '';
    $gender_filter = $_POST['gender_filter'] ?? '';
    $search_term = $_POST['search_term'] ?? '';
    $page = (int)($_POST['page'] ?? 1);
    $per_page = (int)($_POST['per_page'] ?? 10);
    
    // Validate pagination parameters
    if ($page < 1) $page = 1;
    if ($per_page < 1 || $per_page > 100) $per_page = 10;
    
    $offset = ($page - 1) * $per_page;
    
    // Build the base query for counting total records
    $count_query = "SELECT COUNT(*) as total FROM tbl_students s 
                    LEFT JOIN tbl_classes c ON s.class = c.id 
                    WHERE 1=1";
    $count_params = [];
    
    // Build the main query for fetching data
    $query = "SELECT s.*, c.name as class_name 
              FROM tbl_students s 
              LEFT JOIN tbl_classes c ON s.class = c.id 
              WHERE 1=1";
    $params = [];
    
    // Add filters to both queries
    if (!empty($class_filter)) {
        $count_query .= " AND s.class = ?";
        $query .= " AND s.class = ?";
        $count_params[] = $class_filter;
        $params[] = $class_filter;
    }
    
    if (!empty($gender_filter)) {
        $count_query .= " AND s.gender = ?";
        $query .= " AND s.gender = ?";
        $count_params[] = $gender_filter;
        $params[] = $gender_filter;
    }
    
    if (!empty($search_term)) {
        $search_pattern = "%$search_term%";
        $count_query .= " AND (s.id LIKE ? OR s.fname LIKE ? OR s.mname LIKE ? OR s.lname LIKE ? OR s.email LIKE ?)";
        $query .= " AND (s.id LIKE ? OR s.fname LIKE ? OR s.mname LIKE ? OR s.lname LIKE ? OR s.email LIKE ?)";
        for ($i = 0; $i < 5; $i++) {
            $count_params[] = $search_pattern;
            $params[] = $search_pattern;
        }
    }
    
    // Get total count
    $stmt = $conn->prepare($count_query);
    $stmt->execute($count_params);
    $total_records = $stmt->fetch()['total'];
    
    // Calculate pagination info
    $total_pages = ceil($total_records / $per_page);
    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
        $offset = ($page - 1) * $per_page;
    }
    
    // Add pagination to main query
    $query .= " ORDER BY s.fname, s.mname, s.lname LIMIT " . (int)$offset . ", " . (int)$per_page;
    
    // Execute main query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $students = $stmt->fetchAll();
    
    $counter = $offset + 1;
    
    if (count($students) > 0) {
        foreach ($students as $student) {
            $photo_src = '';
            if ($student['display_image'] == 'DEFAULT') {
                $photo_src = "images/students/" . strtolower($student['gender']) . ".png";
            } else {
                $photo_src = "images/students/" . $student['display_image'];
            }
            
            $status_badge = $student['status'] == 1 ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-danger">Inactive</span>';
            
            echo '<tr>';
            echo '<td>' . $counter . '</td>';
            echo '<td><img src="' . $photo_src . '" class="avatar-img" alt="Student Photo"></td>';
            echo '<td><strong>' . htmlspecialchars($student['id']) . '</strong></td>';
            echo '<td>' . htmlspecialchars($student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']) . '</td>';
            echo '<td>' . htmlspecialchars($student['gender']) . '</td>';
            echo '<td>' . htmlspecialchars($student['email']) . '</td>';
            echo '<td>' . htmlspecialchars($student['class_name']) . '</td>';
            echo '<td>' . $status_badge . '</td>';
            echo '<td class="action-buttons">';
            echo '<a href="admin/student_profile.php?id=' . $student['id'] . '" class="btn btn-info btn-sm" title="View Profile">';
            echo '<i class="bi bi-person-circle"></i>';
            echo '</a>';
            echo '<button class="btn btn-primary btn-sm" onclick="editStudent(\'' . $student['id'] . '\')" title="Edit Student">';
            echo '<i class="bi bi-pencil"></i>';
            echo '</button>';
            echo '<button class="btn btn-danger btn-sm" onclick="deleteStudent(\'' . $student['id'] . '\')" title="Delete Student">';
            echo '<i class="bi bi-trash"></i>';
            echo '</button>';
            echo '</td>';
            echo '</tr>';
            
            $counter++;
        }
    } else {
        echo '<tr><td colspan="9" class="text-center text-muted">No students found matching your criteria</td></tr>';
    }
    
    // Return pagination info as JSON
    $pagination_info = [
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_records' => $total_records,
        'per_page' => $per_page,
        'showing_from' => $offset + 1,
        'showing_to' => min($offset + $per_page, $total_records)
    ];
    
    // Add pagination info to response
    echo '<script>updatePagination(' . json_encode($pagination_info) . ');</script>';
    
} catch (PDOException $e) {
    echo '<tr><td colspan="9" class="text-center text-danger">Error loading students: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
?> 