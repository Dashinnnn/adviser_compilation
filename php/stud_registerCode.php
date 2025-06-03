<?php

include '../connection/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../phpmailer/src/PHPMailer.php';
include '../phpmailer/src/SMTP.php';
include '../phpmailer/src/Exception.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_POST['register'])) {

    $first_name = $_POST['f_name'];
    $middle_name = $_POST['m_name'];
    $last_name = $_POST['l_name'];
    $student_id = $_POST['student_id'];
    $student_course = $_POST['student_course'];
    $student_section = $_POST['student_section'];
    $CompleteAddress = $_POST['C_address'];
    $gender = $_POST['gender'];
    $PhoneNumber = $_POST['cpNum'];
    $email = $_POST['eMail'];
    $pword = md5($_POST['pword']);
    $c_pword = md5($_POST['cpword']);
    $guardians_name = $_POST['guardians_name'];
    $guardians_cpNumber = $_POST['guardians_cpNumber'];
    $verification_code = rand(100000, 999999);

    $uniqueId = uniqid() . mt_rand(1000, 9999);

    // Define the directory where you want to save the images
    $uploadDirectory = '../student_file_images/'; // Change this to your desired directory

    // Generate a unique filename for the updated image
    $uniqueFilename = uniqid() . '-' . $_FILES['stud_pic']['name'];

    // Define the full path to the saved image file
    $imagePath = $uploadDirectory . $uniqueFilename;

    $stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Email already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Email already exists";
        $_SESSION['status-code'] = "error";
        header("location: ../student/stud_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM students_data WHERE phone_number = ?");
    $stmt->execute([$PhoneNumber]);
    $user = $stmt->fetch(); # get user data

    if ($user) {
        // Phone Number already exists
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "Phone Number Already Exists!";
        $_SESSION['status-code'] = "error";
        header("location: ../student/stud_register.php");
        exit();
    }

    if ($pword == $c_pword) {
        // Move the uploaded image to the desired directory
        if (move_uploaded_file($_FILES['stud_pic']['tmp_name'], $imagePath)) {

            $sql = $conn->prepare("INSERT INTO students_data(uniqueID, first_name, middle_name, last_name, student_ID, stud_course, stud_section, 
            complete_address, stud_gender, phone_number, stud_email, stud_password, guardians_name, guardians_cpNumber, profile_picture, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$uniqueId, $first_name, $middle_name, $last_name, $student_id, $student_course, $student_section,
            $CompleteAddress, $gender, $PhoneNumber, $email, $pword, $guardians_name, $guardians_cpNumber,  $imagePath, $verification_code]);

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'itechojtportal@gmail.com';
            $mail->Password = 'iidkdsgmmvthssov';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('itechojtportal@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Account';
            $mail->Body = 'Your account verification code is <h1> ' . $verification_code . ' </h1>';
            $mail->send();

            $_SESSION['alert'] = "Success";
            $_SESSION['status'] = "Student Registered";
            $_SESSION['status-code'] = "success";
            header("location: ../student/stud_register.php");
        } else {
            $_SESSION['alert'] = "Oppss...";
            $_SESSION['status'] = "Failed to move image file.";
            $_SESSION['status-code'] = "error";
            header("location: ../student/stud_register.php");
        }
    } else {
        $_SESSION['alert'] = "Oppss...";
        $_SESSION['status'] = "PASSWORD NOT MATCH";
        $_SESSION['status-code'] = "error";
        header("location: ../student/stud_register.php");
    }
}
?>
