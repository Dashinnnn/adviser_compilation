<?php

include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if ($_SESSION['auth_user']['student_id'] == 0) {
    echo "<script>window.location.href='index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Student Profile</title>
    <!-- ================= Favicon ================== -->
    <link rel="shortcut icon" href="images/Picture1.png">

    <!-- Common -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">

    <style>
        body {
            font-family: source sans pro, sans-serif;
        }

         .box {
            border: 1px solid #8B0000;
            border-radius: 20px;
            max-width: 900px;
            margin: 0 auto; 
            display: block; 
            padding: 20px;
            min-height: 600px;
        }

        .header {
            display: flex;
            align-items: center;
        }
        
        .back-button {
            display: flex;
            align-items: center;
            color: #666;
            text-decoration: none;
            font-size: 16px;
        }
        
        .back-icon {
            margin-right: 5px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }
        
        .title {
            color: #8B0000;
            font-size: 20px;
            font-weight: 400;
            padding: 0px 25px;
            max-width:300px;
            margin-left: 200px;
            margin-top: -10px;
            position: absolute;
            display: flex;
            background-color: white;
        }
        
        .content {
            display: flex;
            padding: 20px;
        }
        
        .document-area {
            flex: 1;
            min-height: 500px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            border-radius: 5px;
            margin-right: 50px;
            margin-left: 50px;
            margin-top: 30px;
        }
        
        .action-buttons {
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-icon {
            margin-right: 10px;
        }
        
        .btn-upload {
            background-color: #8B0000;
            color: white;
        }
        
        .btn-upload:hover {
            background-color: #6b0000;
        }
        
        .btn-view, .btn-edit, .btn-print {
            background-color: #c0c0c0;
            color: #333;
        }
        
        .btn-view:hover, .btn-edit:hover, .btn-print:hover {
            background-color: #a9a9a9;
        }
        
        .hidden {
            display: none;
        }
        
        #fileInput {
            display: none;
        }
        
        #documentImage {
            max-width: 100%;
            max-height: 400px;
        }
        
        .file-name {
            font-size: 14px;
            color: #333;
            margin-top: 5px;
            text-align: center;
        }
        .back-button {
            display: flex;
            align-items: center;
            background:none;
            margin-left: 10rem;
            color: rgba(128, 128, 128, 0.5);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: 0.3s;
            position: relative;
            right: 185px;
            bottom: 15px;
        }

        .back-button img {
            height: 40px;
            filter: grayscale(100%);
            opacity: 0.3;
            transition: filter 0.3s ease, opacity 0.3s ease;
        }
      
        .back-button:hover,
        .back-button:hover img {
            color: #9B0C0C;
            filter: grayscale(0%) sepia(100%) hue-rotate(330deg) saturate(500%);
            opacity: 1;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 50px;
        }
    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php
    require_once 'templates/stud_navbar.php';
    ?>
    <!---------NAVIGATION BAR ENDS-------->

    
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto; position: relative;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div>
            <div>
                <div>
                    <a href="#" class="back-button">
                        <span class="back-icon"><img src="images/less-than.png" alt="Back"></span>
                        Back
                    </a>
                </div>
                
                <div>
                    <h1 class="title">Memorandum of Agreement</h1>
                </div>

                <div class="box">
                    <div class="content">
                        <div class="document-area" id="documentArea">
                            <div id="placeholderText">No uploads to show</div>
                            <img id="documentImage" class="hidden">
                            <div id="fileName" class="file-name hidden"></div>
                        </div>
                        
                        <div class="action-buttons">
                            <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button class="btn btn-upload" id="uploadBtn">
                                Upload
                            </button>
                            <button class="btn btn-view" id="viewBtn" disabled>
                                View
                            </button>
                            <button class="btn btn-edit" id="editBtn" disabled>
                                Edit file
                            </button>
                            <button class="btn btn-print" id="printBtn" disabled>
                                Print
                            </button>
                        </div>
                    </div>
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
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>

    <?php 
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
        sweetAlert("<?php echo $_SESSION['alert']; ?>", "<?php echo $_SESSION['status']; ?>", "<?php echo $_SESSION['status-code']; ?>");
        </script>
    <?php
    unset($_SESSION['status']);
    }
    ?>
</body>

</html>
