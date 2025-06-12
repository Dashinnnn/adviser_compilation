<?php
header('Content-Type: application/json');

include '../connection/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    if (!isset($_POST['student_id']) || !isset($_POST['status'])) {
        throw new Exception('Missing required parameters.');
    }

    $studentId = $_POST['student_id'];
    $status = $_POST['status'];

    // Validate status
    $validStatuses = ['deployed', 'completed', 'dropped'];
    if (!in_array($status, $validStatuses)) {
        throw new Exception('Invalid OJT status.');
    }

    // Update status in database
    $stmt = $conn->prepare('UPDATE students_data SET ojt_status = ? WHERE id = ?');
    $stmt->execute([$status, $studentId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'OJT status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No student found with the provided ID']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>