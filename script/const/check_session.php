<?php
if(!isset($_COOKIE["__SRMS__logged"])) {
    $res = "0";
} else {

    if(!isset($_COOKIE["__SRMS__key"])) {
        $res = "0";
    } else {
        $session_key = $_COOKIE["__SRMS__key"];
        $current_ip = $_SERVER['REMOTE_ADDR'];

        if ($_COOKIE["__SRMS__logged"] < 3) {

            try {
                // Check if $conn is available and not null
                if (!isset($conn) || $conn === null) {
                    $res = "0";
                } else {
                    $stmt = $conn->prepare("SELECT * FROM tbl_login_sessions
                    LEFT JOIN tbl_staff ON tbl_staff.id = tbl_login_sessions.staff WHERE tbl_login_sessions.session_key = ? ");
                    $stmt->execute([$session_key]);
                    $result = $stmt->fetchAll();

                    if (count($result) < 1) {
                        $res = "0";
                    } else {
                        foreach($result as $row) {
                            $session_ip = $row[3];
                        }

                        foreach($result as $row) {
                            $account_id = $row[4];
                            $fname = $row[5];
                            $lname = $row[6];
                            $gender = $row[7];
                            $email = $row[8];
                            $login = $row[9];
                            $level = $row[10];
                            $status = $row[11];

                            if ($current_ip == $session_ip) {
                                if ($status == "1") {
                                    $res = "1";
                                } else {
                                    $res = "2";
                                }
                            } else {
                                $res = "3";
                            }
                        }
                    }
                }
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                $res = "0";
            }

        } else {

            try {
                // Check if $conn is available and not null
                if (!isset($conn) || $conn === null) {
                    $res = "0";
                } else {
                    $stmt = $conn->prepare("SELECT * FROM tbl_login_sessions
                    LEFT JOIN tbl_students ON tbl_students.id = tbl_login_sessions.student WHERE tbl_login_sessions.session_key = ? ");
                    $stmt->execute([$session_key]);
                    $result = $stmt->fetchAll();

                    if (count($result) < 1) {
                        $res = "0";
                    } else {
                        foreach($result as $row) {
                            $session_ip = $row[3];
                        }

                        foreach($result as $row) {
                            $account_id = $row[4];
                            $fname = $row[5];
                            $mname = $row[6];
                            $lname = $row[7];
                            $gender = $row[8];
                            $email = $row[9];
                            $class = $row[10];
                            $login = $row[11];
                            $level = $row[12];
                            $display_image = $row[13];
                            $status = $row[14];

                            if ($current_ip == $session_ip) {
                                if ($status == "1") {
                                    $res = "1";
                                } else {
                                    $res = "2";
                                }
                            } else {
                                $res = "3";
                            }
                        }
                    }
                }
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                $res = "0";
            }
        }
    }
}
?>
