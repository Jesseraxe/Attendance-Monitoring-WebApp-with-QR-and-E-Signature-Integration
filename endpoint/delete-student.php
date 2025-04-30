<?php
include ('../conn/conn.php');

if (isset($_GET['student'])) {
    $student = $_GET['student'];

    try {

        $query = "DELETE FROM tbl_student WHERE tbl_student_id = '$student'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('Member deleted successfully!');
                    window.location.href = '/qr-code-attendance-system/masterlist.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Failed to delete member!');
                    window.location.href = '/qr-code-attendance-system/masterlist.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
