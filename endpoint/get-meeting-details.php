<?php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $meetingId = $_GET['meetingId'];

    $stmt = $conn->prepare("SELECT * FROM tbl_meeting_sessions WHERE id = ?");
    $stmt->execute([$meetingId]);

    $meeting = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($meeting) {
        echo json_encode(['success' => true, 'meeting' => $meeting]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Meeting not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>