<?php
    include '../connection/config.php';

    error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

session_start(); 
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .appointment-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .appointment-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        
        .meetings-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        
        .meeting-card {
            background: white;
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .meeting-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .meeting-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .meeting-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            color: #4a90e2;
        }
        
        .meeting-type {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .meeting-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .detail-row {
            display: flex;
            align-items: flex-start;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 80px;
            margin-right: 10px;
        }
        
        .detail-value {
            color: #666;
            flex: 1;
            word-break: break-all;
        }
        
        .meeting-link {
            color: #4a90e2;
            text-decoration: none;
        }
        
        .meeting-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .meetings-grid {
                flex-direction: column;
                align-items: center;
            }
            
            .meeting-card {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>

    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto; position: relative;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="appointment-container">
                <div class="page-header">
                    <div class="page-title">
                        <h1 style="font-size: 16px;"><b>Appointment Meetings</b></h1><br>
                    </div>
                </div>
                <div class="meetings-grid">
                    <!-- Meeting Card 1 -->
                    <div class="meeting-card">
                        <div class="meeting-header">
                            <img src="images/vid-call.png" alt="" class="meeting-icon">
                            <span class="meeting-type">Zoom Meeting</span>
                        </div>
                        
                        <div class="meeting-details">
                            <div class="detail-row">
                                <span class="detail-label">Link</span>
                                <span class="detail-value">: <a href="https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1" class="meeting-link" target="_blank">https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1</a></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Passcode</span>
                                <span class="detail-value">: 8891</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Date</span>
                                <span class="detail-value">: April 22, 2025</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Time</span>
                                <span class="detail-value">: 9am - 12nn</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Agenda</span>
                                <span class="detail-value">: Lorem Ipsum</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meeting Card 2 -->
                    <div class="meeting-card">
                        <div class="meeting-header">
                            <img src="images/vid-call.png" alt="" class="meeting-icon">
                            <span class="meeting-type">Zoom Meeting</span>
                        </div>
                        
                        <div class="meeting-details">
                            <div class="detail-row">
                                <span class="detail-label">Link</span>
                                <span class="detail-value">: <a href="https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1" class="meeting-link" target="_blank">https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1</a></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Passcode</span>
                                <span class="detail-value">: 8891</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Date</span>
                                <span class="detail-value">: April 22, 2025</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Time</span>
                                <span class="detail-value">: 9am - 12nn</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Agenda</span>
                                <span class="detail-value">: Lorem Ipsum</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meeting Card 3 -->
                    <div class="meeting-card">
                        <div class="meeting-header">
                            <img src="images/vid-call.png" alt="" class="meeting-icon">
                            <span class="meeting-type">Zoom Meeting</span>
                        </div>
                        
                        <div class="meeting-details">
                            <div class="detail-row">
                                <span class="detail-label">Link</span>
                                <span class="detail-value">: <a href="https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1" class="meeting-link" target="_blank">https://us05web.zoom.us/j/89564295461?pwd=zsXcuvESXGIC71rBnuDZTiBcFeVs2.1</a></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Passcode</span>
                                <span class="detail-value">: 8891</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Date</span>
                                <span class="detail-value">: April 22, 2025</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Time</span>
                                <span class="detail-value">: 9am - 12nn</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Agenda</span>
                                <span class="detail-value">: Lorem Ipsum</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>
          
    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
            sweetAlert("<?php echo $_SESSION['alert'] ?? 'Notice'; ?>", "<?php echo $_SESSION['status']; ?>", "<?php echo $_SESSION['status-code']; ?>");
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</body>
</html>