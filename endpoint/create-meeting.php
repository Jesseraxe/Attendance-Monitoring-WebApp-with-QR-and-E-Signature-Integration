<?php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dateStart = $_POST['date_start'];
    $dateEnd = $_POST['date_end'];
    $timeStart = $_POST['time_start'];
    $timeEnd = $_POST['time_end'];
    $venue = $_POST['venue'];
    $purpose = $_POST['purpose'];

    $stmt = $conn->prepare("INSERT INTO tbl_meeting_sessions (name, date_start, date_end, time_start, time_end, venue, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $dateStart, $dateEnd, $timeStart, $timeEnd, $venue, $purpose]);

    $id = $conn->lastInsertId();

    echo json_encode(['success' => true, 'id' => $id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>