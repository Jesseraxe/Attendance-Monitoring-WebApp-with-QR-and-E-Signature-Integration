<?php
include('../conn/conn.php');

$studentId = $_POST['studentId'];
$meetingId = $_POST['meetingId'];

try {
    $stmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id, meeting_id, time_in) VALUES (:studentId, :meetingId, NOW())");
    $stmt->execute(['studentId' => $studentId, 'meetingId' => $meetingId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}