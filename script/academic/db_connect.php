<?php
/**
 * Shared Database Connection for Academic Module
 * This file provides a centralized database connection for all academic files
 * to prevent connection duplication and ensure consistent configuration.
 */

// Prevent direct access
if (!defined('DBHost') || !defined('DBUser') || !defined('DBPass')) {
    die('Database configuration not loaded. Please include db/config.php first.');
}

/**
 * Get database connection with proper error handling
 * @return PDO Database connection object
 * @throws Exception If connection fails
 */
function getAcademicDBConnection() {
    try {
        $conn = new PDO(
            'mysql:host=' . DBHost . 
            ';port=' . (defined('DBPort') ? DBPort : '3306') . 
            ';dbname=' . DBName . 
            ';charset=' . DBCharset . 
            ';collation=' . (defined('DBCollation') ? DBCollation : 'utf8_general_ci') . 
            ';prefix=' . (defined('DBPrefix') ? DBPrefix : ''),
            DBUser, 
            DBPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DBCharset
            ]
        );
        
        // Test the connection
        $conn->query('SELECT 1');
        
        return $conn;
    } catch(PDOException $e) {
        $error_details = "Database connection failed: " . $e->getMessage();
        $error_details .= "\n\nConnection Details:";
        $error_details .= "\nHost: " . DBHost;
        $error_details .= "\nPort: " . (defined('DBPort') ? DBPort : '3306');
        $error_details .= "\nDatabase: " . DBName;
        $error_details .= "\nUser: " . DBUser;
        $error_details .= "\nCharset: " . DBCharset;
        
        throw new Exception($error_details);
    }
}

/**
 * Get database connection with fallback to default port if custom port fails
 * @return PDO Database connection object
 * @throws Exception If all connection attempts fail
 */
function getAcademicDBConnectionWithFallback() {
    try {
        // Try with custom port first
        return getAcademicDBConnection();
    } catch (Exception $e) {
        // If custom port fails, try default port
        try {
            $conn = new PDO(
                'mysql:host=' . DBHost . 
                ';port=3306' . 
                ';dbname=' . DBName . 
                ';charset=' . DBCharset,
                DBUser, 
                DBPass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DBCharset
                ]
            );
            
            // Test the connection
            $conn->query('SELECT 1');
            
            return $conn;
        } catch(PDOException $e2) {
            throw new Exception("All connection attempts failed. Original error: " . $e->getMessage());
        }
    }
}
?> 