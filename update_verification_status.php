<?php
header('Content-Type: application/json');
include '../connection/config.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1); 
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

$student_id = $_POST['student_id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$student_id || !$status || !in_array($status, ['accept', 'reject'])) {
    echo json_encode(['message' => 'Invalid input provided.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE students_data SET verification_status = ? WHERE id = ?");
    $stmt->execute([$status, $student_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => "Student verification status updated to $status successfully."]);
    } else {
        echo json_encode(['message' => 'No rows updated. Student ID may not exist.']);
    }
} catch (PDOException $e) {
    error_log("Update error: " . $e->getMessage());
    echo json_encode(['message' => 'Database error occurred: ' . $e->getMessage()]);
}
?>