<?php
require '../connection/config.php'; 

header('Content-Type: application/json');

try {

    if (!isset($_POST['student_ID']) || !isset($_POST['required_hours'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
        exit;
    }

    $studentID = $_POST['student_ID'];
    $requiredHours = (int)$_POST['required_hours'];

    if ($requiredHours < 0) {
        echo json_encode(['success' => false, 'message' => 'Required hours cannot be negative.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE students_data SET required_hours = ? WHERE student_ID = ?");
    $stmt->execute([$requiredHours, $studentID]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No student found with the provided ID.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>