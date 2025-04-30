<?php
session_start();
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meetingId = $_POST['meetingId'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $signatureData = $_POST['signatureData'];

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_facility_attendees (meeting_id, name, role, signature_data) VALUES (:meetingId, :name, :role, :signatureData)");
        $stmt->bindParam(':meetingId', $meetingId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':signatureData', $signatureData, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->errorInfo()[2]]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}