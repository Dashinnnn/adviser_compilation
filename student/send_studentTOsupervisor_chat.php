<?php
include '../connection/config.php';
session_start();

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['message']) && isset($_POST['receiver_id'])) {
    // Insert the new message into the database (you'll need to modify this part)
    $senderId = $_SESSION['auth_user']['student_uniqueID'];
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];

    date_default_timezone_set('Asia/Manila');
    $date = date('F d, Y'); 
    $time = date('g:i A');
    
    // Insert the message into the database (you'll need to modify this part)
    $stmt = $conn->prepare("INSERT INTO chat_system (sender_id, receiver_id, messages, date_only, time_only) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$senderId, $receiverId, $message, $date, $time]);


}

?>
