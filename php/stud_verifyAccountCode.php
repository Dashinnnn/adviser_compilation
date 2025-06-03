<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if(ISSET($_POST['verifyNow'])){
		
    $otp_num = $_POST['verification_number'];
    $stud_id = $_POST['studid'];
    $verified = 'Verified';

$stmt = $conn->prepare("SELECT verification_code FROM students_data WHERE id=?");
$stmt->execute([$stud_id]);
$user = $stmt->fetch(); # get users data

if($user["verification_code"]==$otp_num)
{
$stmt = $conn->prepare("UPDATE students_data SET verify_status=? WHERE id=?");
    $stmt->execute([$verified, $stud_id]);
$_SESSION['alert'] = "Success!";
$_SESSION['status'] = "Student Account Verified. Log In Again.";
$_SESSION['status-code'] = "success"; 
header("location: ../student/index.php");
}


else {
    $_SESSION['alert'] = "Error!";
    $_SESSION['status'] = "Wrong Verification Number";
    $_SESSION['status-code'] = "error"; 
    header("location: ../student/student_verify_account.php?id=$stud_id");
}




}

?>