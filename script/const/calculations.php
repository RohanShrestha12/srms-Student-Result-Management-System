<?php

// Use the connection from school.php instead of creating a new one
// $conn is already available from school.php

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_grade_system");
    $stmt->execute();
    $grades = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Define divisions array
$divisions = array(
    array("DISTINCTION", "Distinction"),
    array("FIRST", "First Division"),
    array("SECOND", "Second Division"),
    array("THIRD", "Third Division"),
    array("FAIL", "Fail")
);

// Add the missing get_division function
function get_division($scores) {
    // Calculate division based on scores
    // This is a simplified implementation - you may need to adjust based on your grading system
    $total_subjects = count($scores);
    $passed_subjects = 0;
    
    foreach ($scores as $score) {
        if ($score >= 40) { // Assuming 40 is passing mark
            $passed_subjects++;
        }
    }
    
    $pass_percentage = ($passed_subjects / $total_subjects) * 100;
    
    if ($pass_percentage >= 80) {
        return "DISTINCTION";
    } elseif ($pass_percentage >= 60) {
        return "FIRST";
    } elseif ($pass_percentage >= 45) {
        return "SECOND";
    } elseif ($pass_percentage >= 35) {
        return "THIRD";
    } else {
        return "FAIL";
    }
}

// Add the missing get_points function
function get_points($scores) {
    // Calculate points based on scores
    $total_points = 0;
    $total_subjects = count($scores);
    
    foreach ($scores as $score) {
        if ($score >= 80) {
            $total_points += 5; // Distinction
        } elseif ($score >= 60) {
            $total_points += 4; // First
        } elseif ($score >= 45) {
            $total_points += 3; // Second
        } elseif ($score >= 35) {
            $total_points += 2; // Third
        } else {
            $total_points += 0; // Fail
        }
    }
    
    return $total_points;
}

?>


