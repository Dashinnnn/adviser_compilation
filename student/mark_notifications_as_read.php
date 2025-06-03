<?php
include '../connection/config.php';
session_start();

if (isset($_SESSION['auth_user']['student_id'])) {
    $studID = $_SESSION['auth_user']['student_id'];

    $read = 'Read';
    // Adjust your SQL query to update notifications as read based on your database schema
    $stmt = $conn->prepare("UPDATE system_notification SET status = ? WHERE student_id = ?");
    $stmt->execute([$read, $studID]);

    // Respond to the AJAX request with a JSON response
    $response = array("success" => true);
    echo json_encode($response);
    exit;
}
?>
