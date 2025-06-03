<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
  echo"<script>window.location.href='index.php'</script>";
}

$studID = isset($_GET['student_ID']) ? $_GET['student_ID'] : '';
if (empty($studID)) {
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students_data WHERE student_ID = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$data) {
  echo "Student not found";
  exit;
}

$stmt = $conn->prepare("
    SELECT 
        students_data.*, 
        CONCAT(coordinators_account.first_name, ' ', coordinators_account.middle_name, ' ', coordinators_account.last_name) AS ojt_adviser
    FROM students_data
    LEFT JOIN coordinators_account
        ON students_data.stud_section = coordinators_account.assigned_section
    WHERE students_data.student_ID = ?
");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Student not found";
    exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    
    <style>
      body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            color: #000;
            overflow: hidden;
        }
        
        .profile-card {
            max-width: 1500px;
            margin: 0 auto;
            background-color: white;
            overflow: hidden;
        }
        
        .profile-header {
            padding: 15px;
            font-weight: bold;
        }
        
        .profile-content {
            display: flex;
            padding: 20px;
            margin-bottom: 40px;
        }
        
        .profile-image {
            flex: 0 0 300px;
            text-align: center;
            padding: 20px;
        }
        
        .image-placeholder {
            width: 400px;
            height: 400px;
            margin: 0 auto;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            background-color: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 10px solid #D9D9D9;
        }
        
        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .placeholder-icon {
            width: 100px;
            height: auto;
            opacity: 0.3;
        }
        
        .student-info {
            flex: 1;
            padding: 10px 120px;
        }
        
        .student-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 50px;
        }
        
        .student-badge {
            background-color: #ffc107;
            color: #333;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .student-details {
            margin-top: 20px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .detail-label {
            flex: 0 0 150px;
            font-weight: 500;
        }
        
        .detail-value {
            flex: 1;
            font-weight: bold;
        }
        
        .student_info{
          margin-left: 50px;
        }

        .message-btn-cont {
          margin: 25px 0 50px 0;
        }

        .message-btn {
          border: 2px solid rgb(112, 111, 109);
          border-radius: 0.5rem;
          padding: 5px 8px 5px 8px;
        }

        .back-btn {
          border: none;
          margin: 10px 10px 25px 0px;
          font-size: 20px;
          background-color: #fff;
        }
    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php
    require_once 'templates/coordinators_navbar.php';
    ?>
    <!---------NAVIGATION BAR ENDS-------->

    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
      <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div class="page-header">
          <div>
            <button class="back-btn" onclick="backFunction()">
              <i class="fa-solid fa-arrow-left"></i>
              Back
            </button>
          </div>
          <div class="page-title">
            <h1 style="font-size: 16px;"><b>VIEW STUDENT PROFILE</b></h1>
          </div>
        </div>
        <div class="profile-card">
          <div class="profile-content">
            <div class="profile-image">
              <div class="image-placeholder">
                <?php if (!empty($data['profile_picture']) && file_exists($data['profile_picture'])): ?>
                  <img src="<?php echo htmlspecialchars($data['profile_picture']);?>" alt="Profile Image">
                <?php else: ?>
                  <img src="images/placeholder.png" alt="Profile Placeholder" class="placeholder-icon">
                <?php endif; ?>
              </div>
              <div class="message-btn-cont">
                <button class="message-btn">
                  <i class="fa-brands fa-rocketchat"></i>
                  Message
                </button>
              </div>
            </div>

            <div class="student_info">
              <div class="student-name">
                <?php
                $fullName = trim(($data['first_name'] ?? '') . ' ' .($data['middle_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
                echo htmlspecialchars($fullName ?: 'N/A');
                ?>
                <span class="student-badge">STUDENT</span>
              </div>

              <div class="student-id">
                SIS No: <?php echo htmlspecialchars($data['student_ID'] ?? 'N/A');?>
              </div>
              <br>
              <div class="student-details">
                  <div class="detail-row">
                    <div class="detail-label">Course</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['stud_course'] ?? 'N/A')?></div>
                  </div>

                    <div class="detail-row">
                      <div class="detail-label">Year</div>
                      <div class="detail-value">: <?php echo htmlspecialchars($data['year_lvl'] ?? 'N/A')?> Year</div>
                    </div>

                  <div class="detail-row">
                    <div class="detail-label">Section</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['stud_section'] ?? 'N/A')?></div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-label">OJT Adviser</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['ojt_adviser'] ?? 'N/A')?></div>
                  </div>

                  <div class="detail-row">
                    <div class="detail-label">HTE</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['stud_hte'] ?? 'N/A')?></div>
                  </div>
                  
                  <div class="detail-row">
                    <div class="detail-label">Total rendered hours</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['total_rendered_hours'] ?? 'N/A')?></div>
                  </div>
                  <br><br>
                  <div class="detail-row">
                    <div class="detail-label">Medical Condition</div>
                    <div class="detail-value">: <?php echo htmlspecialchars($data['medical_condition'] ?? 'N/A')?></div>
                  </div>

              </div><!--stud details-->
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

    <script>
      function backFunction () {
        window.history.back();
      }
    </script>
</body>
</html>