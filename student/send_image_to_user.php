<?php
include '../connection/config.php';
session_start();

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $senderId = $_SESSION['auth_user']['student_uniqueID'];
        $receiverId = $_POST['receiver_id'];

        $uploadDir = 'uploads/images'; // Specify the directory where you want to store the uploaded images

        // Check for and handle potential errors during file upload
        if ($_FILES['img_toSEND']['error'] === UPLOAD_ERR_OK) {
            // Generate a unique filename for the uploaded image
            $uniqueFilename = uniqid() . '-' . $_FILES['img_toSEND']['name'];

            // Define the full path to the saved image file
            $imagePath = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['img_toSEND']['tmp_name'], $imagePath)) {
                date_default_timezone_set('Asia/Manila');
                $date = date('F d, Y');
                $time = date('g:i A');

                    $stmt = $conn->prepare("INSERT INTO chat_system (sender_id, receiver_id, images, date_only, time_only) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$senderId, $receiverId, $imagePath, $date, $time]);

                    // Return a success message or any other response
                    echo "Image uploaded successfully.";
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Error during file upload. Error code: " . $_FILES['img_toSEND']['error'];
        }
    } else {
        echo "Invalid request or no file uploaded.";
    }


?>