<?php
include("../conn/conn.php");

if (isset($_POST['qr_code']) && isset($_POST['signature_data']) && isset($_POST['meeting_id'])) {
    $qrCode = $_POST['qr_code'];
    $signatureData = $_POST['signature_data'];
    $meetingId = $_POST['meeting_id'];

    $selectStmt = $conn->prepare("SELECT tbl_student_id FROM tbl_student WHERE generated_code = :generated_code");
    $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);

    if ($selectStmt->execute()) {
        $result = $selectStmt->fetch();
        if ($result !== false) {
            $studentID = $result["tbl_student_id"];

            // Set the timezone to UTC+8
            $timezone = new DateTimeZone('Asia/Manila');
            $currentTime = new DateTime('now', $timezone);
            $currentTime = $currentTime->format('Y-m-d H:i:s');

            // Check if the student has already timed in for this meeting
            $checkStmt = $conn->prepare("SELECT tbl_attendance_id, time_in, time_out FROM tbl_attendance WHERE tbl_student_id = :student_id AND meeting_id = :meeting_id");
            $checkStmt->bindParam(":student_id", $studentID, PDO::PARAM_STR);
            $checkStmt->bindParam(":meeting_id", $meetingId, PDO::PARAM_INT);
            $checkStmt->execute();
            $existingRecord = $checkStmt->fetch();

            if ($existingRecord) {
                if ($existingRecord['time_out'] === null) {
                    // Student has already timed in but not out, update with time out
                    $updateStmt = $conn->prepare("UPDATE tbl_attendance SET time_out = :time_out WHERE tbl_attendance_id = :attendance_id");
                    $updateStmt->bindParam(":time_out", $currentTime, PDO::PARAM_STR);
                    $updateStmt->bindParam(":attendance_id", $existingRecord['tbl_attendance_id'], PDO::PARAM_INT);
                    $updateStmt->execute();
                    $message = "Time out recorded successfully.";
                } else {
                    // Student has already timed in and out
                    $message = "You have already timed in and out for this meeting.";
                }
            } else {
                // Student hasn't timed in yet, create new record
                $insertStmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id, meeting_id, time_in, signature_data) VALUES (:tbl_student_id, :meeting_id, :time_in, :signature_data)");
                $insertStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                $insertStmt->bindParam(":meeting_id", $meetingId, PDO::PARAM_INT);
                $insertStmt->bindParam(":time_in", $currentTime, PDO::PARAM_STR);
                $insertStmt->bindParam(":signature_data", $signatureData, PDO::PARAM_STR);
                $insertStmt->execute();
                $message = "Time in recorded successfully.";
            }

            // Redirect with message
            header("Location: /qr-code-attendance-system/index.php?message=" . urlencode($message) . "&meeting_id=" . urlencode($meetingId));
            exit();
        } else {
            echo "No member found in QR Code";
            exit();
        }
    } else {
        echo "Failed to execute the statement.";
        exit();
    }
} else {
    echo "
        <script>
            alert('Please fill in all fields, provide a signature, and select a meeting!');
            window.location.href = '/qr-code-attendance-system/index.php';
        </script>
    ";
}
?>