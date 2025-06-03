<?php 
include '../connection/config.php';
session_start();

if (isset($_GET['delete_id'])) {
    $stud_id = $_SESSION['auth_user']['student_id'];
    $doc_id = $_GET['delete_id'];

    // Retrieve the file location from the database
    $stmt = $conn->prepare("SELECT document_location FROM ojt_requirements WHERE id = ?");
    $stmt->execute([$doc_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Get the file location
        $fileLocation = $result['document_location'];

        // Delete the file from the file system
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }

        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM ojt_requirements WHERE id = ?");
        $stmt->execute([$doc_id]);

        // Add a log entry
        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logs = 'You deleted an ojt requirement file.';

        $sql = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql->execute([$stud_id, $logs, $date, $time]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "OJT Requirement Deleted";
        $_SESSION['status-code'] = "success";
        header("Location: ojt_requirements.php");
    } else {
        // Handle the case where the record doesn't exist or the file is not found
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "Record not found";
        $_SESSION['status-code'] = "error";
        header("Location: ojt_requirements.php");
    }
}



?>