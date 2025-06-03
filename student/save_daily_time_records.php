<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();

function calculateTotalHours($amTimeIn, $amTimeOut, $pmTimeIn, $pmTimeOut) {
    $totalHours = 0;

    // Calculate AM hours if both time in and time out are provided
    if (!empty($amTimeIn) && !empty($amTimeOut)) {
        $totalHours += (strtotime($amTimeOut) - strtotime($amTimeIn)) / 3600;
    }

    // Calculate PM hours if both time in and time out are provided
    if (!empty($pmTimeIn) && !empty($pmTimeOut)) {
        $totalHours += (strtotime($pmTimeOut) - strtotime($pmTimeIn)) / 3600;
    }

    return $totalHours;
}


if (isset($_POST['timeINtimeOUT'])) {
    date_default_timezone_set('Asia/Manila');
    $date_record = $_POST['date_record'];
    $time_record = $_POST['time_record'];
    $selectTIMEinTIMEout = $_POST['selectTIMEinTIMEout'];

    // Your existing image storing code
    $img = $_POST['image'];
    $folderPath = "upload/";
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);
    $filePath = $file;

    $studID = $_SESSION['auth_user']['student_id'];

    $stmt = $conn->prepare("SELECT * FROM stud_daily_time_records WHERE stud_id = ? AND recordDate = ?");
    $stmt->execute([$studID, $date_record]);
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingRecord) {
        if ($selectTIMEinTIMEout == "AM Time In") {
            $insertStatement = "INSERT INTO stud_daily_time_records (stud_id, recordDate, AM_time_IN, AM_time_IN_pic, total_working_hours) VALUES (?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($insertStatement);
            $stmt->execute([$studID, $date_record, $time_record, $filePath]);
        }
    } else {
        if ($selectTIMEinTIMEout == "AM Time Out") {
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET AM_time_OUT_pic = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$filePath, $studID, $date_record]);
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET AM_time_OUT = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$time_record, $studID, $date_record]);

            // Calculate total working hours and update the database
            $totalHours = calculateTotalHours(
                $existingRecord['AM_time_IN'],
                $time_record,
                $existingRecord['PM_time_IN'],
                $existingRecord['PM_time_OUT']
            );

            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET total_working_hours = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$totalHours, $studID, $date_record]);
        } elseif ($selectTIMEinTIMEout == "PM Time In") {
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET PM_time_IN_pic = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$filePath, $studID, $date_record]);
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET PM_time_IN = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$time_record, $studID, $date_record]);

            // Calculate total working hours and update the database
            $totalHours = calculateTotalHours(
                $existingRecord['AM_time_IN'],
                $existingRecord['AM_time_OUT'],
                $time_record,
                $existingRecord['PM_time_OUT']
            );

            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET total_working_hours = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$totalHours, $studID, $date_record]);
        } elseif ($selectTIMEinTIMEout == "PM Time Out") {
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET PM_time_OUT_pic = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$filePath, $studID, $date_record]);
            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET PM_time_OUT = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$time_record, $studID, $date_record]);

            // Calculate total working hours and update the database
            $totalHours = calculateTotalHours(
                $existingRecord['AM_time_IN'],
                $existingRecord['AM_time_OUT'],
                $existingRecord['PM_time_IN'],
                $time_record
            );

            $stmt = $conn->prepare("UPDATE stud_daily_time_records SET total_working_hours = ? WHERE stud_id = ? AND recordDate = ?");
            $stmt->execute([$totalHours, $studID, $date_record]);
        }
    }

    header('Location: daily_time_records.php');
    exit();
}

?>