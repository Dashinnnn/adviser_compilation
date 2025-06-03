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

    $id_number = $_POST['id_number'];
    $position = $_POST['position'];
    $complete_ADDRESS = $_POST['completeADDRESS'];
    $PhoneNumber = $_POST['cpNum'];
    $email = $_POST['eMail'];
    $pword = md5($_POST['pword']);
    $c_pword = md5($_POST['cpword']);
    $verification_code = rand(100000, 999999);

    $uniqueId = uniqid() . mt_rand(1000, 9999);


    // Define the directory where you want to save the images
    $uploadDirectory = '../student_file_images/'; // Change this to your desired directory

    // Generate a unique filename for the updated image
    $uniqueFilename = uniqid() . '-' . $_FILES['admin_pic']['name'];

    // Define the full path to the saved image file
    $imagePath = $uploadDirectory . $uniqueFilename;

    $stmt = $conn->prepare("SELECT * FROM admin_account WHERE admin_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Email already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Email already exists";
        $_SESSION['status-code'] = "error";
        header("location: ../adminportal/admin_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM admin_account WHERE phone_number = ?");
    $stmt->execute([$PhoneNumber]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Phone Number already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Phone Number Already Exists!";
        $_SESSION['status-code'] = "error";
        header("location: ../adminportal/admin_register.php");
        exit();
    }

    if ($pword == $c_pword) {
        // Move the uploaded image to the desired directory
        if (move_uploaded_file($_FILES['admin_pic']['tmp_name'], $imagePath)) {

            $sql = $conn->prepare("INSERT INTO admin_account(uniqueID, first_name, middle_name, last_name, id_number, position, address, phone_number, admin_profile_picture, admin_email, admin_password, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$uniqueId, $First_name, $Middle_name, $Last_name, $id_number, $position, $complete_ADDRESS, $PhoneNumber, $imagePath, $email, $pword, $verification_code]);

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
            $_SESSION['status'] = "Admin Account Registered";
            $_SESSION['status-code'] = "success";
            header("location: ../adminportal/admin_register.php");
        } else {
            $_SESSION['alert'] = "Oppss...";
            $_SESSION['status'] = "Failed to move image file.";
            $_SESSION['status-code'] = "error";
            header("location: ../adminportal/admin_register.php");
        }
    } else {
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "PASSWORD NOT MATCH";
        $_SESSION['status-code'] = "error";
        header("location: ../adminportal/admin_register.php");
    }
}
?>
