<?php
include '../connection/config.php';

session_start();

if (!isset($_SESSION['auth_user'])) {
    echo 'You are not logged in!';
    exit();
}

if (isset($_FILES['doc_toSEND'])) {
    // Get the file details
    $file = $_FILES['doc_toSEND'];
    
    if ($file['error'] !== 0) {
        echo 'Error uploading the document!';
        exit();
    }

    $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'txt', 'xls', 'xlsx'];
    
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo 'Invalid file type! Please upload a valid document.';
        exit();
    }

    $uploadDir = 'uploads/documents/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uniqueFileName = uniqid('doc_', true) . '.' . $fileExtension;

    $fileDestination = $uploadDir . $uniqueFileName;

    if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
        $receiverId = $_POST['receiver_id'];

        $senderId = $_SESSION['auth_user']['student_uniqueID'];

        $fileName = basename($file['name']);

        $uploadedAt = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO shared_documents (sender_id, receiver_id, file_name, file_path, uploaded_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$senderId, $receiverId, $fileName, $fileDestination, $uploadedAt]);

        echo 'Document uploaded successfully!';
    } else {
        echo 'Error moving the uploaded file!';
    }
} else {
    echo 'No document uploaded!';
}
?>
