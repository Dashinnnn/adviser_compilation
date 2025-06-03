<?php
include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['auth_user']['student_id']) || $_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <!-- Styles -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .instructions {
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .documents-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 100px;
            margin-bottom: 30px;
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
            justify-content: center;
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
        
        .document-icon img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .document-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .document-subtitle {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        
        .icon-large {
            font-size: 40px;
            color: #8B0000;
        }
        
        .document-preview {
            flex: 1;
            background-color: #f5f5f5;
            border-radius: 5px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            text-align: center;
        }
        
        .no-document-message {
            color: #888;
            font-style: italic;
        }
        
        .action-buttons {
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            padding: 12px 15px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            width: 100%;
        }

    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php require_once 'templates/stud_navbar.php'; ?>
    <!---------NAVIGATION BAR ENDS-------->

    <div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div>
                <div>
                    <div>
                        <div class="page-header">
                            <div class="page-title"><br>
                                <h1 style="font-size: 16px;"><b>ENDORSEMENT PROCESS</b></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-wrap">
    
                <div class="instructions">
                    <p>Download the necessary templates first. After filling in all the information, upload it as a Word document.</p>
                    <p>Note: Prioritize the medical process to get a schedule as soon as possible if you are having your medical exam at ITECH.</p>
                </div>
                
                <div class="documents-container">
                    <!-- First row -->
                    <div class="document-card" onclick="window.location.href='moa.php'">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="MOA Document">
                        </div>
                        <div class="document-title">Memorandum of Agreement</div>
                        <div class="document-subtitle">(MOA)</div>
                    </div>
                    
                    <div class="document-card" onclick="window.location.href='internship_agreement.php'">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Internship Agreement">
                        </div>
                        <div class="document-title">Internship Agreement</div>
                    </div>
                    
                    <div class="document-card" onclick="window.location.href='consent_form.php'">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Consent Form">
                        </div>
                        <div class="document-title">Consent Form</div>
                    </div>
                    
                    <!-- Second row -->
                    <div class="document-card" onclick="window.location.href='intent_letter.php'">
                        <div class="document-icon">
                            <img src="/api/placeholder/80/100" alt="Intent Letter">
                        </div>
                        <div class="document-title">Intent Letter</div>
                    </div>
                    
                    <div class="document-card" onclick="window.location.href='medical.php'">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="12" y1="18" x2="12" y2="12"></line>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Medical Certificate</div>
                    </div>
                    
                    <div class="document-card" onclick="window.location.href='resume.php'">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <circle cx="12" cy="13" r="3"></circle>
                                    <path d="M7 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Resume</div>
                    </div>
                    
                    <!-- Third row -->
                    <div class="document-card" onclick="window.location.href='nda.php'">
                        <div class="document-icon">
                            <div class="icon-container">
                                <div style="font-size: 40px; font-weight: bold; color: #8B0000;">NDA</div>
                            </div>
                        </div>
                        <div class="document-title">Non-Disclosure Agreement</div>
                    </div>
                    
                    <div class="document-card" onclick="window.location.href='insurance.php'">
                        <div class="document-icon">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#8B0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="17" y1="11" x2="23" y2="11"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="document-title">Insurance</div>
                    </div>
                </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>