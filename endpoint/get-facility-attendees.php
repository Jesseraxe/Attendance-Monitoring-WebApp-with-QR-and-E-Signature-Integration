<?php
session_start();
include('../conn/conn.php');

if (isset($_GET['meetingId'])) {
    $meetingId = $_GET['meetingId'];

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_facility_attendees WHERE meeting_id = :meetingId");
        $stmt->bindParam(':meetingId', $meetingId, PDO::PARAM_INT);
        $stmt->execute();

        $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'attendees' => $attendees]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Meeting ID not provided']);
}