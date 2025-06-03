<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if(ISSET($_POST['verifyNow'])){
		
    $otp_num = $_POST['verification_number'];
    $coordinatorsid = $_POST['coordinatorsid'];
    $verified = 'Verified';

$stmt = $conn->prepare("SELECT verification_code FROM coordinators_account WHERE id=?");
$stmt->execute([$coordinatorsid]);
$user = $stmt->fetch(); # get users data

if($user["verification_code"]==$otp_num)
{
$stmt = $conn->prepare("UPDATE coordinators_account SET verify_status=? WHERE id=?");
    $stmt->execute([$verified, $coordinatorsid]);
$_SESSION['alert'] = "Success!";
$_SESSION['status'] = "Student Account Verified. Log In Again.";
$_SESSION['status-code'] = "success"; 
header("location: ../coordinator/index.php");
}


else {
    $_SESSION['alert'] = "Error!";
    $_SESSION['status'] = "Wrong Verification Number";
    $_SESSION['status-code'] = "error"; 
    header("location: ../coordinator/coordinator_verify_account.php?id=$coordinatorsid");
}




}

?>