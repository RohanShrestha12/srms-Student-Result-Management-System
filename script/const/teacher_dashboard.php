<?php
$my_subject = 0;
$my_class = 0;
$my_students = 0;

try {
    $conn = new PDO('mysql:host=' . DBHost . ';port=' . DBPort . ';dbname=' . DBName . ';charset=' . DBCharset . ';collation=' . DBCollation . ';prefix=' . DBPrefix . '', DBUser, DBPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usable_classes = array();
    $my_very_classes = array();

    // Get all classes that have students
    $stmt = $conn->prepare("SELECT class FROM tbl_students GROUP BY class");
    $stmt->execute();
    $_classes = $stmt->fetchAll();

    foreach ($_classes as $key => $value) {
        array_push($usable_classes, $value[0]);
    }

    // Get classes assigned to this teacher
    $stmt = $conn->prepare("SELECT class FROM tbl_subject_combinations WHERE teacher = ?");
    $stmt->execute([$account_id]);
    $result = $stmt->fetchAll();

    foreach ($result as $row) {
        $class_list = unserialize($row[0]);

        foreach ($class_list as $key => $value) {
            if (in_array($value, $usable_classes)) {
                $my_class++;
                if (!in_array($value, $my_very_classes)) {
                    array_push($my_very_classes, $value);
                }
            }
        }
        $my_subject++;
    }

    // Count students in teacher's classes
    if (!empty($my_very_classes)) {
        $placeholders = str_repeat('?,', count($my_very_classes) - 1) . '?';
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_students WHERE class IN ($placeholders)");
        $stmt->execute($my_very_classes);
        $result = $stmt->fetch();
        $my_students = $result[0];
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>