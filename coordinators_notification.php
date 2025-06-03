<?php
include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
  echo"<script>window.location.href='index.php'</script>";
}
?>

<?php
// Handle AJAX requests
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    $studentId = $_SESSION['auth_user']['coordinators_id'] ?? null;
    
    // Check if user is logged in
    if(!$studentId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- theme meta -->
    <meta name="theme-name" content="focus" />
    <title>OJT Web Portal: Dashboard</title>
    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">
    <!-- Styles -->
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/lib/weather-icons.css" rel="stylesheet" />
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <style>

        .notifications-container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #8d8d8d;

            box-shadow: -10px 0px 20px rgba(0, 0, 0, 0.1), 
             10px 0px 20px rgba(0, 0, 0, 0.1);

        }
        
        .date-section {
            border-bottom: none;
            padding: 10px 0;
        }
        
        .date-section:last-child {
            border-bottom: none;
        }
        
        .date-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            padding: 20px 20px;
            margin: 0;
            border-bottom: 1px solid #8d8d8d;
        }
        
        .notification-card {
            padding: 15px 20px;
            border-bottom: 1px solid #8d8d8d;
            transition: background-color 0.2s ease;
        }
        
        .notification-card:hover {
            background-color: #f9f9f9;
        }
        
        
        .notification-content {
            display: flex;
            align-items: flex-start;
        }
        
        .avatar-container {
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-letter {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        
        .notification-details {
            flex-grow: 1;
        }
        
        .notification-sender {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .notification-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .notification-time {
            color: #999;
            font-size: 12px;
        }
        
        .empty-notifications, .login-required {
            text-align: center;
            padding: 40px 20px;
        }
        
        .empty-notifications i, .login-required i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
        }
        
        .empty-notifications p, .login-required p {
            font-size: 18px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        
        .empty-notifications span, .login-required span {
            font-size: 14px;
            color: #888;
        }
        
        .notification-text {
            color: #666;
            font-size: 0.9rem;
        }
        
        .notification-timestamp {
            font-size: 0.8rem;
            color: #999;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .loading i {
            animation: spin 2s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!---------NAVIGATION BAR-------->
<?php
require_once 'templates/coordinators_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->


<div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div>
            <div>
                <div>
                    <div class="page-header">
                        <div class="page-title">
                            <h1 style="font-size: 16px;"><b>NOTIFICATIONS</b></h1><br><br>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notifications-container" class="notifications-container">
                        <?php
                        // Function to determine avatar color based on letter
                        function getAvatarColor($letter) {
                            $colors = [
                                'A' => '#1e88e5', // Blue
                                'B' => '#43a047', // Green
                                'C' => '#e53935', // Red
                                'D' => '#fb8c00', // Orange
                                'E' => '#8e24aa', // Purple
                                'F' => '#00acc1', // Cyan
                                'G' => '#3949ab', // Indigo
                                'H' => '#00897b', // Teal
                                'I' => '#d81b60', // Pink
                                'J' => '#5e35b1', // Deep Purple
                                'K' => '#ff6f00', // Amber
                                'L' => '#6d4c41', // Brown
                                'M' => '#546e7a', // Blue Grey
                                'N' => '#f4511e', // Deep Orange
                                'O' => '#37474f', // Blue Grey Dark
                                'P' => '#7cb342', // Light Green
                                'Q' => '#039be5', // Light Blue
                                'R' => '#c0ca33', // Lime
                                'S' => '#757575', // Grey
                                'T' => '#ff5722', // Deep Orange
                                'U' => '#673ab7', // Deep Purple
                                'V' => '#2196f3', // Blue
                                'W' => '#009688', // Teal
                                'X' => '#f44336', // Red
                                'Y' => '#9c27b0', // Purple
                                'Z' => '#4caf50', // Green
                                // Default color for non-letter characters
                                'default' => '#757575', // Grey
                            ];
                            
                            // Default color if letter not found
                            return $colors[$letter] ?? $colors['default'];
                        }

                        if(isset($_SESSION['auth_user']['coordinators_id'])) {
                            $studID = $_SESSION['auth_user']['coordinators_id'];
                            
                            // Get all notifications for this student
                            $stmt = $conn->prepare("SELECT coordinatorsystemnotification.*, coordinators_account.coordinators_profile_picture 
                                                  FROM coordinatorsystemnotification 
                                                  LEFT JOIN coordinators_account ON coordinators_account.id = coordinatorsystemnotification.coordinator_id 
                                                  WHERE coordinatorsystemnotification.coordinator_id = ? 
                                                  ORDER BY coordinatorsystemnotification.logs_date DESC, coordinatorsystemnotification.logs_time DESC");
                            $stmt->execute([$studID]);
                            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if(count($notifications) > 0) {
                                // Group notifications by simplified date categories
                                $groupedNotifications = [
                                    'Today' => [],
                                    'Yesterday' => [],
                                    'Older' => [],
                                ];
                                
                                foreach ($notifications as $notification) {
                                    // Parse the custom date format
                                    $dbDate = $notification['logs_date'];
                                    $dateParts = explode('/', $dbDate);
                                    if (count($dateParts) >= 3) {
                                        $month = trim($dateParts[0]);
                                        $dayWithWeekday = trim($dateParts[1]);
                                        $year = trim($dateParts[2]);
                                
                                        $dayParts = explode(' ', $dayWithWeekday);
                                        $day = $dayParts[0];
                                
                                        $parsedDateStr = "$month $day $year";
                                        $timestamp = strtotime($parsedDateStr);
                                
                                        $notifDate = ($timestamp !== false) ? date('Y-m-d', $timestamp) : '1970-01-01';
                                    } else {
                                        $notifDate = '1970-01-01';
                                    }
                                
                                    $today = date('Y-m-d');
                                    $yesterday = date('Y-m-d', strtotime('-1 day'));
                                
                                    if ($notifDate == $today) {
                                        $groupedNotifications['Today'][] = $notification;
                                    } else if ($notifDate == $yesterday) {
                                        $groupedNotifications['Yesterday'][] = $notification;
                                    } else {
                                        $groupedNotifications['Older'][] = $notification;
                                    }
                                }
                                
                                // Display notifications grouped by simplified date categories
                                foreach ($groupedNotifications as $dateLabel => $dateNotifications) {
                                    // Skip empty date categories
                                    if(empty($dateNotifications)) continue;
                                    
                                    echo '<div class="date-section">';
                                    echo '<h5 class="date-label">' . $dateLabel . '</h5>';
                                    echo '<div class="notifications-group">';
                                    
                                    foreach ($dateNotifications as $notification) {
                                        // Get first letter for avatar placeholder if no image
                                        $notificationTitle = $notification['logs'] ?: 'OJT Admin';
                                        $firstLetter = strtoupper(substr($notificationTitle, 0, 1));
                                        $avatarColor = getAvatarColor($firstLetter);
                                        
                                        echo '<div class="notification-card">';
                                        echo '<div class="notification-content">';
                                        
                                        // Avatar section - either show image or letter in circle
                                        echo '<div class="avatar-container">';
                                        if (!empty($notification['profile_picture'])) {
                                            echo '<img src="' . htmlspecialchars($notification['profile_picture']) . '" alt="Profile" class="avatar-img">';
                                        } else {
                                            echo '<div class="avatar-circle" style="background-color: ' . $avatarColor . ';">';
                                            echo '<span class="avatar-letter">' . $firstLetter . '</span>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        
                                        // Notification details
                                        echo '<div class="notification-details">';
                                        echo '<div class="notification-sender">' . htmlspecialchars($notificationTitle) . '</div>';
                                        echo '<div class="notification-time">' . date('g:i A', strtotime($notification['logs_time'])) . '</div>';
                                        echo '</div>';
                                        
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="empty-notifications">
                                        <p>No notifications yet</p>
                                        <span>When you get notifications, they\'ll show up here</span>
                                      </div>';
                            }
                        } else {
                            echo '<div class="login-required">
                                    <i class="ti-lock"></i>
                                    <p>Please log in</p>
                                    <span>You need to log in to view your notifications</span>
                                  </div>';
                        }
                        ?>
                    </div>
                    
                    <div class="loading">
                        <i class="ti-reload"></i> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>