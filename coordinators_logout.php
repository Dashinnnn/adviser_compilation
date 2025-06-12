<?php 
include '../connection/config.php';
session_start();

if(isset($_SESSION['auth_user']['coordinators_id'])){

    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];

date_default_timezone_set('Asia/Manila');
$date = date('F / d l / Y');
$time = date('g:i A');
$logs = 'You successfully logged out to your account.';
$online_offline_status = 'Offline';

$sql = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
$sql->execute([$coordinators_id, $logs, $date, $time]);

$sql2 = $conn->prepare("UPDATE coordinators_account SET online_offlineStatus = ? WHERE id = ?");
$sql2->execute([$online_offline_status, $coordinators_id]);

}

session_destroy();

header("Location: ../index.php");

?>