<?php
include('../conn/conn.php');

if (isset($_GET['attendance']) && is_numeric($_GET['attendance'])) {
    $attendanceId = intval($_GET['attendance']);

    try {
        $query = "DELETE FROM tbl_attendance WHERE tbl_attendance_id = :attendance_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':attendance_id', $attendanceId, PDO::PARAM_INT);
        $query_execute = $stmt->execute();

        if ($query_execute) {
            $message = "Attendance deleted successfully!";
        } else {
            $message = "Failed to delete attendance!";
        }

        // Redirect back to the index page with a message
        header("Location: /qr-code-attendance-system/index.php?message=" . urlencode($message));
        exit();

    } catch (PDOException $e) {
        // Log the error
        error_log("Delete Attendance Error: " . $e->getMessage());
        
        $message = "An error occurred while deleting the attendance.";
        header("Location: /qr-code-attendance-system/index.php?message=" . urlencode($message));
        exit();
    }
} else {
    $message = "Invalid attendance ID provided.";
    header("Location: /qr-code-attendance-system/index.php?message=" . urlencode($message));
    exit();
}
?>