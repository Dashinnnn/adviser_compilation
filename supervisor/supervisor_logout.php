<?php 
include '../connection/config.php';
session_start();

if(isset($_SESSION['auth_user']['supervisor_id'])){

    $supervisor_id = $_SESSION['auth_user']['supervisor_id'];

date_default_timezone_set('Asia/Manila');
$date = date('F / d l / Y');
$time = date('g:i A');
$logs = 'You successfully logged out to your account.';
$online_offline_status = 'Offline';

$sql = $conn->prepare("INSERT INTO supervisor_system_notification(supervisor_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
$sql->execute([$supervisor_id, $logs, $date, $time]);

$sql2 = $conn->prepare("UPDATE supervisor SET online_offlineStatus = ? WHERE id = ?");
$sql2->execute([$online_offline_status, $supervisor_id]);

}

session_destroy();

header("Location: index.php");

?>