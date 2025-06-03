<?php
include '../connection/config.php';

header('Content-Type: application/json');

try {
    // Check if required POST parameters are set
    if (!isset($_POST['dtr_id']) || !isset($_POST['status'])) {
        echo json_encode(['message' => 'Invalid request parameters']);
        exit;
    }

    $dtr_id = $_POST['dtr_id'];
    $status = $_POST['status'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;

    // Validate status
    if (!in_array($status, ['Accepted', 'Denied'])) {
        echo json_encode(['message' => 'Invalid status value']);
        exit;
    }

    // For Denied status, ensure remarks are provided
    if ($status === 'Denied' && empty($remarks)) {
        echo json_encode(['message' => 'Remarks are required for rejection']);
        exit;
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE stud_daily_time_records SET status = ?, remarks = ? WHERE id = ?");
    $stmt->execute([$status, $remarks, $dtr_id]);

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'DTR status updated successfully']);
    } else {
        echo json_encode(['message' => 'No DTR record found with the provided ID']);
    }
} catch (PDOException $e) {
    error_log("DTR status update error: " . $e->getMessage());
    echo json_encode(['message' => 'Failed to update status']);
}
?>