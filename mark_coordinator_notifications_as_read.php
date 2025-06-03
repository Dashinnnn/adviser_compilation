<?php
include '../connection/config.php';
session_start();

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_SESSION['auth_user']['coordinators_id'])) {
    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];

    $read = 'Read';
    // Adjust your SQL query to update notifications as read based on your database schema
    $stmt = $conn->prepare("UPDATE coordinatorsystemnotification SET status = ? WHERE coordinator_id = ?");
    $stmt->execute([$read, $coordinators_id]);

    // Respond to the AJAX request with a JSON response
    $response = array("success" => true);
    echo json_encode($response);
    exit;
}
?>
