<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "2") {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Validate that class is selected
        if (!isset($_POST['class']) || empty($_POST['class'])) {
            $_SESSION['error'] = "Please select a class to view students.";
            header("location:../list_students.php");
            exit;
        }
        
        $selected_class = $_POST['class'];
        
        // Validate that only one class is selected (since we're using radio buttons)
        if (is_array($selected_class) && count($selected_class) > 1) {
            $_SESSION['error'] = "Please select only one class at a time.";
            header("location:../list_students.php");
            exit;
        }
        
        // If it's an array, take the first element (radio button behavior)
        if (is_array($selected_class)) {
            $selected_class = $selected_class[0];
        }
        
        // Validate that the teacher has access to this class
        try {
            
            // Check if teacher has access to this class
            $stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations 
                                   LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id 
                                   LEFT JOIN tbl_staff ON tbl_subject_combinations.teacher = tbl_staff.id 
                                   WHERE tbl_subject_combinations.teacher = ?");
            $stmt->execute([$account_id]);
            $result = $stmt->fetchAll();
            
            $teacher_classes = array();
            foreach ($result as $value) {
                $class_arr = unserialize($value[1]);
                foreach ($class_arr as $class_id) {
                    $teacher_classes[] = $class_id;
                }
            }
            
            if (!in_array($selected_class, $teacher_classes)) {
                $_SESSION['error'] = "You don't have access to this class.";
                header("location:../list_students.php");
                exit;
            }
            
            // Store the selected class in session
            $_SESSION['student_list'] = array($selected_class);
            $_SESSION['selected_class_id'] = $selected_class;
            
            // Get class name for display
            $stmt = $conn->prepare("SELECT name FROM tbl_classes WHERE id = ?");
            $stmt->execute([$selected_class]);
            $class_data = $stmt->fetch();
            $_SESSION['selected_class_name'] = $class_data['name'];
            
            header("location:../students.php");
            exit;
            
        } catch(PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header("location:../list_students.php");
            exit;
        }
        
    } else {
        header("location:../list_students.php");
        exit;
    }
} else {
    header("location:../");
    exit;
}
?>
