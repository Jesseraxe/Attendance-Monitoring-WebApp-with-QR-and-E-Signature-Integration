<?php
session_start();
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendeeId = $_POST['attendeeId'];

    try {
        $stmt = $conn->prepare("DELETE FROM tbl_facility_attendees WHERE id = :attendeeId");
        $stmt->bindParam(':attendeeId', $attendeeId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->errorInfo()[2]]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}