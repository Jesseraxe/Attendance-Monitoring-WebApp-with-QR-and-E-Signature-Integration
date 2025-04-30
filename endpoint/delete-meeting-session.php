<?php
include('../conn/conn.php');

header('Content-Type: application/json');

if (!isset($_POST['sessionId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing sessionId']);
    exit;
}

$sessionId = $_POST['sessionId'];

try {
    $conn->beginTransaction();

    // Delete facility attendees for the current session
    $stmt = $conn->prepare("DELETE FROM tbl_facility_attendees WHERE meeting_id = ?");
    $stmt->execute([$sessionId]);

    // Delete attendees for the current session
    $stmt = $conn->prepare("DELETE FROM tbl_attendance WHERE meeting_id = ?");
    $stmt->execute([$sessionId]);

    // Delete the current session
    $stmt = $conn->prepare("DELETE FROM tbl_meeting_sessions WHERE id = ?");
    $stmt->execute([$sessionId]);

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Meeting session and associated data deleted successfully']);
} catch(Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>