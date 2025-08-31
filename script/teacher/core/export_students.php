<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class = $_POST['class'];

    try {
        // Use the centralized connection from school.php
        if (!isset($conn) || $conn === null) {
            throw new Exception("Database connection not available");
        }

        $stmt = $conn->prepare("SELECT * FROM tbl_classes WHERE id = ?");
        $stmt->execute([$class]);
        $result = $stmt->fetchAll();

        if (count($result) < 1) {
            throw new Exception("Class not found");
        }

        $fileName = $result[0][1] . '.csv';
        $_SESSION['export_file'] = $fileName;

        // Create import_sheets directory if it doesn't exist
        $directory = 'import_sheets';
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception("Failed to create directory: $directory");
            }
        }

        $filePath = $directory . '/' . $fileName;

        // Remove existing file if it exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Open file for writing
        $fp = fopen($filePath, 'w');
        if ($fp === false) {
            throw new Exception("Failed to open file for writing: $filePath");
        }

        // Write CSV header
        $rowData = array('REGISTRATION NUMBER', 'STUDENT NAME', 'SCORE');
        fputcsv($fp, $rowData);

        // Get students for the selected class
        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE class = ?");
        $stmt->execute([$class]);
        $result = $stmt->fetchAll();

        // Write student data
        foreach ($result as $row) {
            $studentName = $row[1] . ' ' . $row[2] . ' ' . $row[3];
            $rowData = array($row[0], $studentName, "0");
            fputcsv($fp, $rowData);
        }

        // Close file
        fclose($fp);

        // Set success message
        $_SESSION['reply'] = array(array("success", "Student list exported successfully to $fileName"));
        header("location:../export_students.php");

    } catch (Exception $e) {
        $_SESSION['reply'] = array(array("error", "Export failed: " . $e->getMessage()));
        header("location:../export_students.php");
    }

} else {
    header("location:../");
}
?>
