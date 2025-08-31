<?php
// Database connection file
// Include this file to get a database connection

if (!isset($conn) || $conn === null) {
    try {
        $conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}
?> 