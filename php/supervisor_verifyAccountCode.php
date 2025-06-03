<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if(ISSET($_POST['verifyNow'])){
		
    $otp_num = $_POST['verification_number'];
    $supervisor_id = $_POST['supervisorid'];
    $verified = 'Verified';

$stmt = $conn->prepare("SELECT verification_code FROM supervisor WHERE id=?");
$stmt->execute([$supervisor_id]);
$user = $stmt->fetch(); # get users data

if($user["verification_code"]==$otp_num)
{
$stmt = $conn->prepare("UPDATE supervisor SET verify_status=? WHERE id=?");
    $stmt->execute([$verified, $supervisor_id]);
$_SESSION['alert'] = "Success!";
$_SESSION['status'] = "Company Account Verified. Log In Again.";
$_SESSION['status-code'] = "success"; 
header("location: ../supervisor/index.php");
}


else {
    $_SESSION['alert'] = "Error!";
    $_SESSION['status'] = "Wrong Verification Number";
    $_SESSION['status-code'] = "error"; 
    header("location: ../supervisor/supervisor_verify_account.php?id=$supervisor_id");
}




}

?>