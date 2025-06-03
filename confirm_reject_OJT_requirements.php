<?php
include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}


if (isset($_GET['confirmID'])) {
    $coordinatorID = $_SESSION['auth_user']['coordinators_id'];
    $requirementID = $_GET['confirmID'];
    $student_ID = $_GET['studentID'];

    // Check the current status before attempting to update
    $stmtCheck = $conn->prepare("SELECT status FROM ojt_requirements WHERE id = ?");
    $stmtCheck->execute([$requirementID]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus === 'Approved') {
        // The document is already approved.
        $_SESSION['alert'] = "Info";
        $_SESSION['status'] = "The document is already approved";
        $_SESSION['status-code'] = "info";
        header('Location: VIEWojt_requirements.php?student_ID=' . $student_ID . '');
        exit;
    } else {
        // Document approval process
        $status = 'Approved';

        // Prepare and execute the SQL update query
        $stmt = $conn->prepare("UPDATE ojt_requirements SET status = ? WHERE id = ?");
        $stmt->execute([$status, $requirementID]);

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logsONcoordinator = 'OJT Requirement successfully approved.';
        $logsONstudent = 'OJT Requirement successfully approved by the coordinator.';

        $sql2 = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql2->execute([$coordinatorID, $logsONcoordinator, $date, $time]);

        $sql3 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql3->execute([$student_ID, $logsONstudent, $date, $time]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "Document Approved";
        $_SESSION['status-code'] = "success";
        header('Location: VIEWojt_requirements.php?student_ID=' . $student_ID . '');
        exit;
    }
}


if (isset($_GET['rejectID'])) {
    $coordinatorID = $_SESSION['auth_user']['coordinators_id'];
    $requirementID = $_GET['rejectID'];
    $student_ID = $_GET['studentID'];

    // Check the current status before attempting to update
    $stmtCheck = $conn->prepare("SELECT status FROM ojt_requirements WHERE id = ?");
    $stmtCheck->execute([$requirementID]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus === 'Rejected') {
        // The document is already approved.
        $_SESSION['alert'] = "Info";
        $_SESSION['status'] = "The document is already rejected";
        $_SESSION['status-code'] = "info";
        header('Location: VIEWojt_requirements.php?student_ID=' . $student_ID . '');
        exit;
    } else {
        // Document approval process
        $status = 'Rejected';

        // Prepare and execute the SQL update query
        $stmt = $conn->prepare("UPDATE ojt_requirements SET status = ? WHERE id = ?");
        $stmt->execute([$status, $requirementID]);

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logsONcoordinator = 'OJT Requirement rejected.';
        $logsONstudent = 'OJT Requirement rejected by the coordinator.';

        $sql2 = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql2->execute([$coordinatorID, $logsONcoordinator, $date, $time]);

        $sql3 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql3->execute([$student_ID, $logsONstudent, $date, $time]);

        $_SESSION['alert'] = "Not Approved";
        $_SESSION['status'] = "Document Rejected";
        $_SESSION['status-code'] = "error";
        header('Location: VIEWojt_requirements.php?student_ID=' . $student_ID . '');
        exit;
    }
}



?>