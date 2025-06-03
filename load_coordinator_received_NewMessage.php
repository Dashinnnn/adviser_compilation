<?php
include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'])) {
    $receiverId = $_POST['receiver_id'];
    $status = 'Sent';
    $stmt = $conn->prepare("SELECT COUNT(*) AS new_message_count FROM chat_system WHERE sender_id = ? AND status = ?");
    $stmt->execute([$receiverId, $status]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    $newMessageCount = $results['new_message_count'];

    // Close the PDO connection
    $pdo = null;

    // Return the new message count as a response to the Ajax request
    echo $newMessageCount;

}
?>