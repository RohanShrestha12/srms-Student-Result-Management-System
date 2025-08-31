<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($res == "1" && $level == "3") {
    // Student session is valid
} else {
    header("location:../");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Please select a valid image file.";
    } else {
        $file = $_FILES['profile_image'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = $file['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        }
        
        // Validate file size (5MB limit)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $max_size) {
            $errors[] = "File size too large. Maximum size is 5MB.";
        }
        
        // Validate file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "Invalid file extension. Only JPG, PNG, and GIF files are allowed.";
        }
        
        // Additional security checks
        if (empty($errors)) {
            // Check if file is actually an image
            $image_info = getimagesize($file['tmp_name']);
            if ($image_info === false) {
                $errors[] = "Uploaded file is not a valid image.";
            }
            
            // Check image dimensions (optional)
            if ($image_info && ($image_info[0] > 5000 || $image_info[1] > 5000)) {
                $errors[] = "Image dimensions are too large. Maximum size is 5000x5000 pixels.";
            }
        }
    }
    
    if (empty($errors)) {
        try {
            // Create upload directory if it doesn't exist
            $upload_dir = './images/students/';
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $errors[] = "Could not create upload directory.";
                }
            }
            
            if (empty($errors)) {
                // Generate unique filename with avator prefix
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $new_filename = 'avator_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                // Debug: Check if directory is writable
                if (!is_writable($upload_dir)) {
                    $errors[] = "Upload directory is not writable.";
                } else {
                    // Move uploaded file
                    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                        // Get current image to delete old one
                        $stmt = $conn->prepare("SELECT display_image FROM tbl_students WHERE id = ?");
                        $stmt->execute([$account_id]);
                        $current_image = $stmt->fetchColumn();
                        
                        // Update database
                        $stmt = $conn->prepare("UPDATE tbl_students SET display_image = ? WHERE id = ?");
                        $stmt->execute([$new_filename, $account_id]);
                        
                        // Update session
                        $_SESSION['display_image'] = $new_filename;
                        
                        // Delete old image if it exists and is not default
                        if ($current_image && $current_image !== 'DEFAULT' && file_exists($upload_dir . $current_image)) {
                            unlink($upload_dir . $current_image);
                        }
                        
                        $_SESSION['reply'] = array(array("success", "Profile image updated successfully!"));
                    } else {
                        $errors[] = "Error uploading file. Please try again.";
                    }
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['reply'] = array(array("danger", implode(" ", $errors)));
    }
    
    header("location:../settings.php");
    exit();
} else {
    header("location:../settings.php");
    exit();
}
?> 