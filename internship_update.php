<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Validate session to prevent undefined index errors
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OJT Web Portal: Internship Documentations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->

    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <!-- Common CSS -->
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            color: #000;
        }
        .note {
            text-align: center;
            color: gray;
            margin-bottom: 20px;
        }
        .internship-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px dotted #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .section {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            height: 100%;
            transition: transform 0.2s;
        }
        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .icon-container {
            margin-right: 20px;
        }
        .icon-container i {
            font-size: 50px;
            color: rgb(150, 49, 37);
            padding-left: 20px;
        }
        .text-container {
            flex: 1;
            text-align: left;
        }
        .section p {
            color: #7f8c8d;
            margin: 0;
        }
        .section-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        .dtr-section i {
            color: rgb(150, 49, 37);
        }
        .dtr-label {
            font-size: 24px;
            font-weight: bold;
            color: #c0392b;
            margin-bottom: 5px;
        }
        .row {
            margin-bottom: 20px;
        }
        .title-container {
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 30px;
        }
        .back-btn {
            font-size: 20px;
            margin: 20px 0 30px 0;
            color: #7f8c8d;
            cursor: pointer;
        }
        .back-btn:hover {
            color: rgb(0, 0, 0);
        }
    </style>
</head>

<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="back-btn" onclick="backBtn()">
                <i class="fa-solid fa-arrow-left"></i> Back
            </div>
            <div class="page-header">
                <div class="page-title">
                    <h1 style="font-size: 16px;"><b>Student Internship Update</b></h1>
                </div>
            </div>
            <div style="padding: 50px;">
                <!-- 2x2 Grid Layout -->
                <div class="row">
                    <!-- Daily Time Record (DTR) -->
                    <div class="col-md-6 mb-4">
                        <a href="list_of_student_dtr.php" class="section-link">
                            <div class="section dtr-section">
                                <div class="icon-container">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div class="text-container">
                                    <p style="color: #000; font-size: 16px; margin-bottom: 10px;">Daily Time Record</p>
                                    <p>Check the student’s Daily Time Record (DTR) to monitor the intern’s rendered hours.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Internship Experience -->
                    <div class="col-md-6 mb-4">
                        <a href="list_of_student_ie.php" class="section-link">
                            <div class="section inactive-section">
                                <div class="icon-container">
                                    <i class="fa-solid fa-note-sticky"></i>
                                </div>
                                <div class="text-container">
                                    <p style="color: #000; font-size: 16px; margin-bottom: 10px;">Internship Experience</p>
                                    <p>Check the student’s internship experience to monitor their developments.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Weekly Accomplishment -->
                    <div class="col-md-6 mb-4">
                        <a href="list_of_student_wa.php" class="section-link">
                            <div class="section weekly-section">
                                <div class="icon-container">
                                    <i class="fa-regular fa-calendar"></i>
                                </div>
                                <div class="text-container">
                                    <p style="color: #000; font-size: 16px; margin-bottom: 10px;">Weekly Accomplishment</p>
                                    <p>Check the student’s weekly report to monitor the intern’s accomplishments and progress.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Photo Documentation -->
                    <div class="col-md-6 mb-4">
                        <a href="list_of_student_pd.php" class="section-link">
                            <div class="section inactive-section">
                                <div class="icon-container">
                                    <i class="fa-solid fa-images"></i>
                                </div>
                                <div class="text-container">
                                    <p style="color: #000; font-size: 16px; margin-bottom: 10px;">Photo Documentation</p>
                                    <p>Review the photo documentation to assess the intern’s activities.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Scripts -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <!-- <script src="js/lib/sweetalert/sweetalert.min.js"></script> -->
    <!-- <script src="js/lib/sweetalert/sweetalert.init.js"></script> -->

    <script>
        function backBtn() {
            window.history.back();
        }
    </script>
</body>
</html>