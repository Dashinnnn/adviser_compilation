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
    

    $first_name = $_POST['f_name'];
    $middle_name = $_POST['m_name'];
    $last_name = $_POST['l_name'];
    $faculty_ID = $_POST['faculty_ID'];
    $coor_dept = $_POST['coor_dept'];
    $course_handled = $_POST['course_handled'];
    $CompleteAddress = $_POST['C_address'];
    $PhoneNumber = $_POST['cpNum'];
    $email = $_POST['eMail'];
    $pword = md5($_POST['pword']);
    $c_pword = md5($_POST['cpword']);
    $verification_code = rand(100000, 999999);

    $uniqueId = uniqid() . mt_rand(1000, 9999);


    // Define the directory where you want to save the images
    $uploadDirectory = '../student_file_images/'; // Change this to your desired directory

    // Generate a unique filename for the updated image
    $uniqueFilename = uniqid() . '-' . $_FILES['coordinators_pic']['name'];

    // Define the full path to the saved image file
    $imagePath = $uploadDirectory . $uniqueFilename;

    $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE coordinators_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Email already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Email already exists";
        $_SESSION['status-code'] = "error";
        header("location: ../coordinator/coordinators_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE phone_number = ?");
    $stmt->execute([$PhoneNumber]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Phone Number already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Phone Number Already Exists!";
        $_SESSION['status-code'] = "error";
        header("location: ../coordinator/coordinators_register.php");
        exit();
    }

    if ($pword == $c_pword) {
        // Move the uploaded image to the desired directory
        if (move_uploaded_file($_FILES['coordinators_pic']['tmp_name'], $imagePath)) {

            $sql = $conn->prepare("INSERT INTO coordinators_account(uniqueID, first_name, middle_name, last_name, faculty_id, coor_dept, course_handled, complete_address, phone_number, coordinators_email, coordinators_password, coordinators_profile_picture, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$uniqueId, $first_name, $middle_name, $last_name, $faculty_ID, $coor_dept, $course_handled, $CompleteAddress, $PhoneNumber, $email, $pword, $imagePath, $verification_code]);

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
            $_SESSION['status'] = "Student Registered";
            $_SESSION['status-code'] = "success";
            header("location: ../coordinator/coordinators_register.php");
        } else {
            $_SESSION['alert'] = "Oppss...";
            $_SESSION['status'] = "Failed to move image file.";
            $_SESSION['status-code'] = "error";
            header("location: ../coordinator/coordinators_register.php");
        }
    } else {
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "PASSWORD NOT MATCH";
        $_SESSION['status-code'] = "error";
        header("location: ../coordinator/coordinators_register.php");
    }
}
?>
