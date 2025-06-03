<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

session_start();
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header('Location: index.php');
    exit;
}

$studID = isset($_GET['student_ID']) ? $_GET['student_ID'] : '';
if (empty($studID)) {
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * from students_data WHERE student_ID = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$data) {
    echo "Student not found";
    exit;
}

$studentPrimaryId = $data['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <link rel="shortcut icon" href="images/Picture1.png">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        fieldset {
            border: 2px solid #8B0000;
            border-radius: 1.5rem;
            padding: 10px 20px 10px 20px;
            margin: 10px 20px 10px 20px;
        }

        legend {
            padding: 10px 20px 10px 30px;
            font-weight: 500;
            color: black;
        }

        .documents-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 100px;
            margin: 30px 0 35px 0;
            width: 100%;
        }

        .document-card {
            background-color: #8B0000;
            border-radius: 8px;
            width: 250px;
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .document-card:hover {
            transform: translateY(-5px);
        }

        .document-icon {
            width: 100px;
            height: 120px;
            margin-bottom: 15px;
            background-color: white;
            padding: 5px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .document-icon-medical {
            width: 100px;
            height: 120px;
            margin-bottom: 15px;
            background-color: white;
            padding: 5px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B0000; 
            font-size: 100px;
        }

        .document-icon img {
            max-width: 100%;
            max-height: 100%;
        }

        .back-btn { 
            border: none;
            margin: 10px 10px 25px 0px;
            font-size: 20px;
            background-color: #fff;
        }

        .back-btn:hover {
            color: black;
        }

    </style>
</head>
<body>
<?php require_once 'templates/coordinators_navbar.php'; ?>

<div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div class="back-btn" onclick="backBtn()">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </div>
        <div class="page-header">
            <div class="page-title"><br><h1 style="font-size: 16px;"><b>Documents Validation</b></h1></div>
        </div>
        <br>
        <fieldset>
            <legend><?php
                $firstName = $data['first_name'] ?? '';
                $middleName = $data['middle_name'] ?? '';
                $lastName = $data['last_name'] ?? '';

                $middleInitial = $middleName ? strtoupper(substr(trim($middleName), 0, 1)) . '.' : '';
                $fullName = trim("$lastName, $firstName $middleInitial");

                echo htmlspecialchars($fullName);
            ?></legend>

                <div class="documents-container">
                    <!--Row 1 -->
                    <div class="document-card" onclick="window.location.href='view_moa.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                        <img src="../student/templates/endorsement/moa.png" alt="MOA Document">
                        </div>
                        <div class="document-title">Memorandum of Agreement</div>
                        <div class="document-subtitle">(MOA)</div>
                    </div>  
                    
                    <div class="document-card" onclick="window.location.href='view_ia.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                        <img src="../student/templates/endorsement/ia.png" alt="Internship Agreement">
                        </div>
                        <div class="document-title">internship Agreement</div>
                    </div>

                    <div class="document-card" onclick="window.location.href='view_cf.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                            <img src="../student/templates/endorsement/consent_form.png" alt="Consent Form">
                        </div>
                        <div class="document-title">Consent Form</div>
                    </div>
                    
                    <!--Row 2 -->

                    <div class="document-card" onclick="window.location.href='view_intent_letter.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                            <img src="../student/templates/endorsement/intent_letter.png" alt="Intent Letter">
                        </div>
                        <div class="document-title">Intent Letter</div>
                    </div>  
                    
                    <div class="document-card" onclick="window.location.href='view_medical.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon-medical">
                        <i class="fa-solid fa-file-medical"></i>
                        </div>
                        <div class="document-title">Medical Certificate</div>
                    </div>

                    <div class="document-card" onclick="window.location.href='view_resume.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon-medical">
                            <i class="fa-regular fa-file"></i>
                        </div>
                        <div class="document-title">Resume</div>
                    </div>
                    <!--Row 3 -->

                    <div class="document-card" onclick="window.location.href='view_nda.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                            <div style="font-size: 40px; font-weight: bold; color: #8B0000;">NDA</div>
                        </div>
                        <div class="document-title">Non-Disclosure Agreement</div>
                        <div class="document-subtitle">(NDA)</div>
                    </div>  
                    
                    <div class="document-card" onclick="window.location.href='view_insurance.php?id=<?php echo urlencode($studentPrimaryId);?>'">
                        <div class="document-icon">
                        <div style="font-size: 15px; font-weight: bold; color: #8B0000;">INSURANCE</div>
                        </div>
                        <div class="document-title">Insurance</div>
                    </div>

                    <div class="document-card">
                        <div class="document-icon">
                        <div style="font-size: 12px; font-weight: bold; color: #8B0000;">ENDORSEMENT</div>
                        </div>
                        <div class="document-title">Endorsement</div>
                    </div>
                </div>
        </fieldset>

    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>

    <script>
        function backBtn () {
            window.history.back();
        }
    </script>
</html>