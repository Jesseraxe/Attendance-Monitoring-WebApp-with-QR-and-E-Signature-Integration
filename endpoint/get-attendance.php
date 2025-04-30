<?php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $meetingId = $_GET['meetingId'];
    
    $stmt = $conn->prepare("SELECT tbl_attendance.*, tbl_student.* FROM tbl_attendance 
                            LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id
                            WHERE tbl_attendance.meeting_id = ?");
    $stmt->execute([$meetingId]);
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'attendance' => $result]);
} else {
    echo json_encode(['success' => false]);
}