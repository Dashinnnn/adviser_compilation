<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if($_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}


if (isset($_GET['task_id'])) {
    $studID = $_SESSION['auth_user']['student_id'];
    $task_id = $_GET['task_id'];

    // Check the current status before attempting to update
    $stmtCheck = $conn->prepare("SELECT task_status FROM stud_task_list WHERE id = ?");
    $stmtCheck->execute([$task_id]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus === 'Finished') {
        // The task is already finished.
        $_SESSION['alert'] = "Info";
        $_SESSION['status'] = "The task is already finished";
        $_SESSION['status-code'] = "info";
        header('Location: stud_task_list.php');
        exit;
    } else {
        // Task approval process
        $status = 'Finished';

        // Prepare and execute the SQL update query
        $stmt = $conn->prepare("UPDATE stud_task_list SET task_status = ? WHERE id = ?");
        $stmt->execute([$status, $task_id]);

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logsONstudent = 'Your task successfully finished.';

        $sql3 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql3->execute([$studID, $logsONstudent, $date, $time]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "Task Finished";
        $_SESSION['status-code'] = "success";
        header('Location: stud_task_list.php');
        exit;
    }
}



?>