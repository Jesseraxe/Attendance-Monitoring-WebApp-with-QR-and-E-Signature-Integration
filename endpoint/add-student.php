<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_name'], $_POST['student_age'], $_POST['student_gender'], $_POST['student_email'], $_POST['student_phone'], $_POST['course_section'], $_POST['student_position'], $_POST['generated_code'])) {
        $studentName = $_POST['student_name'];
        $studentAge = $_POST['student_age'];
        $studentGender = $_POST['student_gender'];
        $studentEmail = $_POST['student_email'];
        $studentPhone = $_POST['student_phone'];
        $studentCourse = $_POST['course_section'];
        $studentPosition = $_POST['student_position'];
        $generatedCode = $_POST['generated_code'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_student (student_name, student_age, student_gender, student_email, student_phone, course_section, student_position, generated_code) VALUES (:student_name, :student_age, :student_gender, :student_email, :student_phone, :course_section, :student_position, :generated_code)");

            // Bind parameters with appropriate data types
            $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR);
            $stmt->bindParam(":student_age", $studentAge, PDO::PARAM_INT);
            $stmt->bindParam(":student_gender", $studentGender, PDO::PARAM_STR);
            $stmt->bindParam(":student_email", $studentEmail, PDO::PARAM_STR);
            $stmt->bindParam(":student_phone", $studentPhone, PDO::PARAM_STR);
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
            $stmt->bindParam(":student_position", $studentPosition, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

            $stmt->execute();

            // Redirect after successful insertion
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
}
?>
