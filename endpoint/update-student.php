<?php
include("../conn/conn.php");

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tbl_student_id'], $_POST['student_name'], $_POST['student_age'], $_POST['student_gender'], $_POST['student_email'], $_POST['student_phone'], $_POST['course_section'], $_POST['course_section'])) {
        $studentId = $_POST['tbl_student_id'];
        $studentName = $_POST['student_name'];
        $studentAge = $_POST['student_age'];
        $studentGender = $_POST['student_gender'];
        $studentEmail = $_POST['student_email'];
        $studentPhone = $_POST['student_phone'];
        $studentCourse = $_POST['course_section'];
        $studentPosition = $_POST['student_position'];

        // Validate input data
        if (!empty($studentId) && !empty($studentName) && !empty($studentAge) && !empty($studentGender) && !empty($studentEmail) && !empty($studentPhone) && !empty($studentCourse) && !empty($studentCourse)) {
            try {
                // Prepare the update statement
                $stmt = $conn->prepare("UPDATE tbl_student SET student_name = :student_name, student_age = :student_age, student_gender = :student_gender, student_email = :student_email, student_phone = :student_phone, course_section = :course_section, student_position =:student_position WHERE tbl_student_id = :tbl_student_id");

                // Bind parameters
                $stmt->bindParam(":tbl_student_id", $studentId, PDO::PARAM_INT);
                $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR);
                $stmt->bindParam(":student_age", $studentAge, PDO::PARAM_INT);
                $stmt->bindParam(":student_gender", $studentGender, PDO::PARAM_STR);
                $stmt->bindParam(":student_email", $studentEmail, PDO::PARAM_STR);
                $stmt->bindParam(":student_phone", $studentPhone, PDO::PARAM_STR);
                $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
                $stmt->bindParam("student_position", $studentPosition, PDO::PARAM_STR);

                // Execute the update
                $stmt->execute();

                // Redirect to the master list page
                header("Location: /qr-code-attendance-system/masterlist.php");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "
                <script>
                    alert('Please fill in all fields!');
                    window.location.href = '/qr-code-attendance-system/masterlist.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Invalid request!');
                window.location.href = '/qr-code-attendance-system/masterlist.php';
            </script>
        ";
    }
}
?>
