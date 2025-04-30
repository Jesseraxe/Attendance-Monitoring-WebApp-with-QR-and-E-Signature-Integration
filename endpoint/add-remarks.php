<?php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceId = $_POST['attendanceId'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $meetingId = $_POST['meetingId'] ?? '';

    error_log("Received data - Attendance ID: $attendanceId, Remarks: $remarks, Meeting ID: $meetingId");

    if (empty($attendanceId) || empty($meetingId)) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE tbl_attendance SET remarks = ? WHERE tbl_attendance_id = ? AND meeting_id = ?");
    $result = $stmt->execute([$remarks, $attendanceId, $meetingId]);

    if ($result) {
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo json_encode(['success' => true, 'message' => 'Remarks updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No matching record found to update']);
        }
    } else {
        $errorInfo = $stmt->errorInfo();
        error_log("Database error: " . print_r($errorInfo, true));
        echo json_encode(['success' => false, 'message' => 'Failed to update remarks: ' . $errorInfo[2]]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}