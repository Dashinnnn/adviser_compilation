<?php
header('Content-Type: application/json');
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

$document_id = $_POST['document_id'] ?? null;
$status = $_POST['status'] ?? null;
$remarks = $_POST['remarks'] ?? null;

if (!$document_id || !$status || !in_array($status, ['accepted', 'denied'])) {
    echo json_encode(['message' => 'Invalid input']);
    exit;
} 

try {
    $stmt = $conn->prepare("UPDATE photo_documentation SET status = ?, remarks = ? WHERE id = ?");
    $stmt->execute([$status, $remarks, $document_id]);

    if ($stmt->rowCount() > 0) {
      echo json_encode(['message' => "Submitted file status updated to $status successfully"]);
    } else {
      echo json_encode(["message" => "No rows updated. Document ID may not exist"]);
    }
} catch (PDOException $e) {
    error_log('Update error: ' . $e->getMessage());
    echo json_encode(['message' => 'Database error occured: ' . $e->getMessage()]);
}
?>