<?php
include '../connection/config.php';
error_reporting(0);

session_start();

if (isset($_POST['SaveDailyTime_Journal'])) {
  $STUDid = $_POST['studID'];
  $dateOFsubmit = $_POST['dateofSUBMIT'];
  $Objectives = $_POST['objectives'];
  $Accomplishments = $_POST['accomplishments'];
  $Reflection = $_POST['reflection'];
  $Realization = $_POST['realization'];
  $Knowledge = $_POST['knowledge'];

  date_default_timezone_set('Asia/Manila');
  $date = date('F / d l / Y');
  $time = date('g:i A');
  $logs = 'Daily time journal has been inserted.';

  $sql = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
  $sql->execute([$stud_id, $logs, $date, $time]);

  $sql2 = $conn->prepare("INSERT INTO narrative_reports(student_id, dateOFSubmit, objectives, accomplishments, reflections, realizations, knowledge) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $sql2->execute([$STUDid, $dateOFsubmit, $Objectives, $Accomplishments, $Reflection, $Realization, $Knowledge]);


            $_SESSION['alert'] = "Success";
            $_SESSION['status'] = "Daily time journal saved successfully";
            $_SESSION['status-code'] = "success";
            header("Location: narrativeReport.php");

}

?>