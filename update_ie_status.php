<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 1 temporarily for debugging
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

header('Content-Type: application/json'); // Ensure JSON response

$document_id = $_POST['document_id'] ?? null;
$remarks = $_POST['remarks'] ?? null;
$status = $_POST['status'] ?? null;

if (!$document_id || !$status || !in_array($status, ['accepted', 'denied'])) {
    echo json_encode(['message' => 'Invalid input provided.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE internship_experience SET status = ?, remarks = ? WHERE id = ?");
    $stmt->execute([$status, $remarks, $document_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => "Submitted file status updated to $status successfully"]);
    } else {
        echo json_encode(['message' => "No rows updated. Document ID may not exist."]);
    }
} catch (PDOException $e) {
    error_log("Update error: " . $e->getMessage());
    echo json_encode(['message' => "Database error occurred: " . $e->getMessage()]);
}
?>