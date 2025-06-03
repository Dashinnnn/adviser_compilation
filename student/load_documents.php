<?php
include '../connection/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['auth_user']['student_id'])) {
    $senderId = $_SESSION['auth_user']['student_id'];
    $receiverId = $_POST['receiver_id'];

    $stmt = $conn->prepare("
        SELECT * FROM shared_documents
        WHERE (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY timestamp DESC
    ");
    $stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
    
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($files);
    exit();
}
?>