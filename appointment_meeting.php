<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Coordinator Profile</title>
    <link rel="shortcut icon" href="images/pupLogo.png">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="endorsement-css/endorsement-moa.css" rel="stylesheet">
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
                    <?php
                    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];
                    $query = "SELECT * FROM meetings WHERE portal IN (:portal1, :portal2)";
                    $stmt = $conn->prepare($query);
                    $stmt->execute(['portal1' => 'faculty', 'portal2' => 'all']);
                    
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($results) > 0) {
                        foreach ($results as $row) {
                    ?>
                        <div class="meeting-card">
                            <div class="meeting-header">
                                <img src="images/vid-call.png" alt="" class="meeting-icon">
                                <span class="meeting-type"><?php echo htmlspecialchars($row['meeting_type']); ?></span>
                            </div>
                            <div class="meeting-details">
                                <div class="detail-row">
                                    <span class="detail-label">Link</span>
                                    <span class="detail-value">: <a href="<?php echo htmlspecialchars($row['link']); ?>" class="meeting-link" target="_blank"><?php echo htmlspecialchars($row['link']); ?></a></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Passcode</span>
                                    <span class="detail-value">: <?php echo htmlspecialchars($row['passcode']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Date</span>
                                    <span class="detail-value">: <?php echo htmlspecialchars(date('F d, Y', strtotime($row['meeting_date']))); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Time</span>
                                    <span class="detail-value">: <?php echo htmlspecialchars($row['meeting_time']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Agenda</span>
                                    <span class="detail-value">: <?php echo htmlspecialchars($row['agenda']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    } else {
                        echo '<p>No meetings found.</p>';
                    }
                    $stmt = null; // Close the statement
                    ?>
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