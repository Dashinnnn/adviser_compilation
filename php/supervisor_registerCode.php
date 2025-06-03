<?php

include '../connection/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../phpmailer/src/PHPMailer.php';
include '../phpmailer/src/SMTP.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_POST['register'])) {
  
    $First_name = $_POST['f_name'];
    $Middle_name = $_POST['m_name'];
    $Last_name = $_POST['l_name'];

    $company_name = $_POST['companyNAME'];
    $position = $_POST['position'];
    $company_ADDRESS = $_POST['companyADDRESS'];
    $PhoneNumber = $_POST['cpNum'];
    $email = $_POST['eMail'];
    $pword = md5($_POST['pword']);
    $c_pword = md5($_POST['cpword']);
    $verification_code = rand(100000, 999999);

    $uniqueId = uniqid() . mt_rand(1000, 9999);


    // Define the directory where you want to save the images
    $uploadDirectory = '../student_file_images/'; // Change this to your desired directory

    // Generate a unique filename for the updated image
    $uniqueFilename = uniqid() . '-' . $_FILES['supervisor_pic']['name'];

    // Define the full path to the saved image file
    $imagePath = $uploadDirectory . $uniqueFilename;

    $stmt = $conn->prepare("SELECT * FROM supervisor WHERE supervisor_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Email already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Email already exists";
        $_SESSION['status-code'] = "error";
        header("location: ../supervisor/supervisor_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM supervisor WHERE phone_number = ?");
    $stmt->execute([$PhoneNumber]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Phone Number already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Phone Number Already Exists!";
        $_SESSION['status-code'] = "error";
        header("location: ../supervisor/supervisor_register.php");
        exit();
    }

    if ($pword == $c_pword) {
        // Move the uploaded image to the desired directory
        if (move_uploaded_file($_FILES['supervisor_pic']['tmp_name'], $imagePath)) {

            $sql = $conn->prepare("INSERT INTO supervisor(uniqueID, first_name, middle_name, last_name, company_name, position, company_address, supervisor_email, supervisor_password, phone_number, supervisor_profile_picture, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$uniqueId, $First_name, $Middle_name, $Last_name, $company_name, $position, $company_ADDRESS, $email, $pword, $PhoneNumber, $imagePath, $verification_code]);

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bulsuojtportala@gmail.com';
            $mail->Password = 'wbxgxtphzeptkjqa';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('bulsuojtportala@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Account';
            $mail->Body = 'Your account verification code is <h1> ' . $verification_code . ' </h1>';
            $mail->send();

            $_SESSION['alert'] = "Success";
            $_SESSION['status'] = "Company Account Registered";
            $_SESSION['status-code'] = "success";
            header("location: ../supervisor/supervisor_register.php");
        } else {
            $_SESSION['alert'] = "Oppss...";
            $_SESSION['status'] = "Failed to move image file.";
            $_SESSION['status-code'] = "error";
            header("location: ../supervisor/supervisor_register.php");
        }
    } else {
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "PASSWORD NOT MATCH";
        $_SESSION['status-code'] = "error";
        header("location: ../supervisor/supervisor_register.php");
    }
}
?>
