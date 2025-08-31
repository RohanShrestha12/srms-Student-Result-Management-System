<?php

/**
 * Result Prediction using Linear Regression Algorithm
 * This file contains functions to predict student results based on historical data
 * 
 * Updated Logic:
 * - For Class 11: Requires results from first, second, and third terms
 * - For Class 12: Requires results from first, second, and third terms
 * - Only predicts final result when all three terms are available
 */

/**
 * Calculate grade from percentage score
 * @param float $percentage
 * @return array ['grade', 'remark']
 */
function calculateGrade($percentage)
{
    $grade_ranges = [
        ['grade' => 'A+', 'min' => 90, 'max' => 100, 'remark' => 'Outstanding'],
        ['grade' => 'A', 'min' => 80, 'max' => 89, 'remark' => 'Excellent'],
        ['grade' => 'B+', 'min' => 70, 'max' => 79, 'remark' => 'Very Good'],
        ['grade' => 'B', 'min' => 60, 'max' => 69, 'remark' => 'Good'],
        ['grade' => 'C+', 'min' => 50, 'max' => 59, 'remark' => 'Satisfactory'],
        ['grade' => 'C', 'min' => 40, 'max' => 49, 'remark' => 'Acceptable'],
        ['grade' => 'D', 'min' => 30, 'max' => 39, 'remark' => 'Partially Acceptable'],
        ['grade' => 'NG', 'min' => 0, 'max' => 29, 'remark' => 'Failed']
    ];

    foreach ($grade_ranges as $range) {
        if ($percentage >= $range['min'] && $percentage <= $range['max']) {
            return ['grade' => $range['grade'], 'remark' => $range['remark']];
        }
    }

    return ['grade' => 'NG', 'remark' => 'Failed'];
}

/**
 * Calculate average percentage from exam results
 * @param array $results
 * @return float
 */
function calculateAveragePercentage($results)
{
    if (empty($results)) {
        return 0;
    }

    $total_score = 0;
    $total_subjects = count($results);

    foreach ($results as $result) {
        $total_score += $result['score'];
    }

    return round($total_score / $total_subjects, 2);
}

/**
 * Simple Linear Regression Algorithm
 * @param array $x_values - Independent variable (previous results)
 * @param array $y_values - Dependent variable (target results)
 * @return array ['slope', 'intercept', 'prediction']
 */
function linearRegression($x_values, $y_values)
{
    $n = count($x_values);

    if ($n < 2) {
        return ['slope' => 0, 'intercept' => 0, 'prediction' => 0];
    }

    // Calculate means
    $x_mean = array_sum($x_values) / $n;
    $y_mean = array_sum($y_values) / $n;

    // Calculate slope and intercept
    $numerator = 0;
    $denominator = 0;

    for ($i = 0; $i < $n; $i++) {
        $numerator += ($x_values[$i] - $x_mean) * ($y_values[$i] - $y_mean);
        $denominator += ($x_values[$i] - $x_mean) * ($x_values[$i] - $x_mean);
    }

    if ($denominator == 0) {
        return ['slope' => 0, 'intercept' => $y_mean, 'prediction' => $y_mean];
    }

    $slope = $numerator / $denominator;
    $intercept = $y_mean - ($slope * $x_mean);

    return [
        'slope' => $slope,
        'intercept' => $intercept,
        'prediction' => 0 // Will be calculated based on input
    ];
}

/**
 * Check if student has results for required terms (first, second, third)
 * @param array $term_averages
 * @return bool
 */
function hasRequiredTerms($term_averages)
{
    // Check if we have at least 3 terms (any 3 terms are sufficient)
    $term_count = count($term_averages);

    // For prediction, we need at least 3 terms to establish a trend
    // The terms don't have to be consecutive (1,2,3) - any 3 terms will work
    return $term_count >= 3;
}

/**
 * Predict final result for Class 11 student
 * @param PDO $conn - Database connection
 * @param string $student_id
 * @return array
 */
function predictClass11FinalResult($conn, $student_id)
{
    try {

        // First get the student's current class
        $stmt = $conn->prepare("SELECT class FROM tbl_students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            return [
                'available' => false,
                'message' => 'Student not found',
                'prediction' => null
            ];
        }

        $student_class = $student['class'];

        // Get student's results from their current class
        $stmt = $conn->prepare("
            SELECT er.score, t.name as term_name, t.id as term_id
            FROM tbl_exam_results er
            JOIN tbl_terms t ON er.term = t.id
            WHERE er.student = ? AND er.class = ?
            ORDER BY t.id ASC
        ");
        $stmt->execute([$student_id, $student_class]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return [
                'available' => false,
                'message' => 'No results available for prediction',
                'prediction' => null
            ];
        }

        // Group results by term and calculate average score per term
        $term_results = [];
        foreach ($results as $result) {
            $term_id = $result['term_id'];
            if (!isset($term_results[$term_id])) {
                $term_results[$term_id] = [];
            }
            $term_results[$term_id][] = $result['score'];
        }

        // Calculate average for each term
        $term_averages = [];
        foreach ($term_results as $term_id => $scores) {
            $term_averages[$term_id] = array_sum($scores) / count($scores);
        }

        // Check if we have the required terms (first, second, third)
        if (!hasRequiredTerms($term_averages)) {
            $available_terms = count($term_averages);
            return [
                'available' => false,
                'message' => "Insufficient data for prediction. Need results from at least 3 different terms to establish a trend. Currently have {$available_terms} term(s).",
                'prediction' => null,
                'required_terms' => 3,
                'available_terms' => $available_terms
            ];
        }

        // Use linear regression with the available terms
        $x_values = array_keys($term_averages);
        $y_values = array_values($term_averages);

        $regression = linearRegression($x_values, $y_values);

        // Predict final result (assuming 4 terms total)
        $final_term = 4;
        $predicted_final = $regression['slope'] * $final_term + $regression['intercept'];
        $predicted_final = max(0, min(100, $predicted_final)); // Clamp between 0-100

        $grade_info = calculateGrade($predicted_final);

        $result = [
            'available' => true,
            'message' => 'Prediction based on first, second, and third term performance using linear regression',
            'prediction' => [
                'percentage' => round($predicted_final, 2),
                'grade' => $grade_info['grade'],
                'remark' => $grade_info['remark'],
                'confidence' => 'High',
                'method' => 'Linear regression (3 terms required)',
                'terms_used' => count($term_averages)
            ]
        ];

        return $result;
    } catch (PDOException $e) {
        return [
            'available' => false,
            'message' => 'Error calculating prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    } catch (Exception $e) {
        return [
            'available' => false,
            'message' => 'Error calculating prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    }
}

/**
 * Predict final result for Class 12 student
 * @param PDO $conn - Database connection
 * @param string $student_id
 * @return array
 */
function predictClass12FinalResult($conn, $student_id)
{
    try {

        // First get the student's current class
        $stmt = $conn->prepare("SELECT class FROM tbl_students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            return [
                'available' => false,
                'message' => 'Student not found',
                'prediction' => null
            ];
        }

        $student_class = $student['class'];

        // Get student's class 11 final result (if available) - assuming class 10 is previous year
        $stmt = $conn->prepare("
            SELECT er.score, t.name as term_name, t.id as term_id
            FROM tbl_exam_results er
            JOIN tbl_terms t ON er.term = t.id
            WHERE er.student = ? AND er.class = 10
            ORDER BY t.id ASC
        ");
        $stmt->execute([$student_id]);
        $class11_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get student's class 12 results from their current class
        $stmt = $conn->prepare("
            SELECT er.score, t.name as term_name, t.id as term_id
            FROM tbl_exam_results er
            JOIN tbl_terms t ON er.term = t.id
            WHERE er.student = ? AND er.class = ?
            ORDER BY t.id ASC
        ");
        $stmt->execute([$student_id, $student_class]);
        $class12_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($class12_results)) {
            return [
                'available' => false,
                'message' => 'No Class 12 results available for prediction',
                'prediction' => null
            ];
        }

        // Calculate class 12 term averages
        $class12_term_results = [];
        foreach ($class12_results as $result) {
            $term_id = $result['term_id'];
            if (!isset($class12_term_results[$term_id])) {
                $class12_term_results[$term_id] = [];
            }
            $class12_term_results[$term_id][] = $result['score'];
        }

        $class12_averages = [];
        foreach ($class12_term_results as $term_id => $scores) {
            $class12_averages[$term_id] = array_sum($scores) / count($scores);
        }

        // Check if we have the required terms (first, second, third) for Class 12
        if (!hasRequiredTerms($class12_averages)) {
            $available_terms = count($class12_averages);
            return [
                'available' => false,
                'message' => "Insufficient Class exams data for prediction. Need results from at least 3 different terms to establish a trend. Currently have {$available_terms} term(s).",
                'prediction' => null,
                'required_terms' => 3,
                'available_terms' => $available_terms
            ];
        }

        // Use linear regression with Class 12 terms
        $x_values = array_keys($class12_averages);
        $y_values = array_values($class12_averages);

        $regression = linearRegression($x_values, $y_values);

        // Predict final result (assuming 4 terms total)
        $final_term = 4;
        $predicted_final = $regression['slope'] * $final_term + $regression['intercept'];
        $predicted_final = max(0, min(100, $predicted_final)); // Clamp between 0-100

        $grade_info = calculateGrade($predicted_final);

        $result = [
            'available' => true,
            'message' => 'Prediction based on Class 12 first, second, and third term performance using linear regression',
            'prediction' => [
                'percentage' => round($predicted_final, 2),
                'grade' => $grade_info['grade'],
                'remark' => $grade_info['remark'],
                'confidence' => 'High',
                'method' => 'Linear regression on Class 12 data (3 terms required)',
                'terms_used' => count($class12_averages)
            ]
        ];

        return $result;
    } catch (PDOException $e) {
        return [
            'available' => false,
            'message' => 'Error calculating prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    } catch (Exception $e) {
        return [
            'available' => false,
            'message' => 'Error calculating prediction: ' . $e->getMessage(),
            'prediction' => null
        ];
    }
}

/**
 * Get student's available results for display
 * @param PDO $conn - Database connection
 * @param string $student_id
 * @return array
 */
function getStudentResults($conn, $student_id)
{
    try {
        $stmt = $conn->prepare("
            SELECT 
                er.score,
                t.name as term_name,
                t.id as term_id,
                c.name as class_name,
                c.id as class_id,
                s.name as subject_name
            FROM tbl_exam_results er
            JOIN tbl_terms t ON er.term = t.id
            JOIN tbl_classes c ON er.class = c.id
            JOIN tbl_subject_combinations sc ON er.subject_combination = sc.id
            JOIN tbl_subjects s ON sc.subject = s.id
            WHERE er.student = ?
            ORDER BY c.id ASC, t.id ASC, s.name ASC
        ");
        $stmt->execute([$student_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group results by class and term
        $organized_results = [];
        foreach ($results as $result) {
            $class_id = $result['class_id'];
            $term_id = $result['term_id'];

            if (!isset($organized_results[$class_id])) {
                $organized_results[$class_id] = [
                    'class_name' => $result['class_name'],
                    'terms' => []
                ];
            }

            if (!isset($organized_results[$class_id]['terms'][$term_id])) {
                $organized_results[$class_id]['terms'][$term_id] = [
                    'term_name' => $result['term_name'],
                    'subjects' => []
                ];
            }

            $organized_results[$class_id]['terms'][$term_id]['subjects'][] = [
                'subject' => $result['subject_name'],
                'score' => $result['score']
            ];
        }

        // Calculate averages for each term
        foreach ($organized_results as $class_id => &$class_data) {
            foreach ($class_data['terms'] as $term_id => &$term_data) {
                $scores = array_column($term_data['subjects'], 'score');
                $term_data['average'] = round(array_sum($scores) / count($scores), 2);
                $grade_info = calculateGrade($term_data['average']);
                $term_data['grade'] = $grade_info['grade'];
                $term_data['remark'] = $grade_info['remark'];
            }
        }

        return $organized_results;
    } catch (PDOException $e) {
        return [];
    }
}
