<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if(ISSET($_POST['verifyNow'])){
		
    $otp_num = $_POST['verification_number'];
    $admin_id = $_POST['adminid'];
    $verified = 'Verified';

$stmt = $conn->prepare("SELECT verification_code FROM admin_account WHERE id=?");
$stmt->execute([$admin_id]);
$user = $stmt->fetch(); # get users data

if($user["verification_code"]==$otp_num)
{
$stmt = $conn->prepare("UPDATE admin_account SET verify_status=? WHERE id=?");
    $stmt->execute([$verified, $admin_id]);
$_SESSION['alert'] = "Success!";
$_SESSION['status'] = "Account Verified. Log In Again.";
$_SESSION['status-code'] = "success"; 
header("location: ../adminportal/index.php");
}


else {
    $_SESSION['alert'] = "Error!";
    $_SESSION['status'] = "Wrong Verification Number";
    $_SESSION['status-code'] = "error"; 
    header("location: ../adminportal/admin_verify_account.php?id=$admin_id");
}




}

?>