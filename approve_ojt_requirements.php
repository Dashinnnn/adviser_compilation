<?php
include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}


if (isset($_GET['studID'])) {
    $coordinatorID = $_SESSION['auth_user']['coordinators_id'];
    $studID = $_GET['studID'];

    // Check the current status before attempting to update
    $stmtCheck = $conn->prepare("SELECT status FROM ojt_requirements WHERE student_id = ?");
    $stmtCheck->execute([$studID]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus === 'Approved') {
        // The document is already approved.
        $_SESSION['alert'] = "Info";
        $_SESSION['status'] = "The document is already approved";
        $_SESSION['status-code'] = "info";
        header('Location: VIEWojt_requirements.php?student_ID=' . $studID . '');
        exit;
    } else {
        // Document approval process
        $status = 'Approved';

        // Prepare and execute the SQL update query
        $stmt = $conn->prepare("UPDATE ojt_requirements SET status = ? WHERE student_id = ?");
        $stmt->execute([$status, $studID]);

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logsONcoordinator = 'OJT Requirement successfully approved.';
        $logsONstudent = 'OJT Requirement successfully approved by the coordinator.';

        $sql2 = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql2->execute([$coordinatorID, $logsONcoordinator, $date, $time]);

        $sql3 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql3->execute([$studID, $logsONstudent, $date, $time]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "Document Approved";
        $_SESSION['status-code'] = "success";
        header('Location: VIEWojt_requirements.php?student_ID=' . $studID . '');
        exit;
    }
}



?>