<?php

include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
  echo"<script>window.location.href='index.php'</script>";
}

if (isset($_POST['upload'])) {
  $studID = $_SESSION['auth_user']['coordinators_id'];

  $uploadDirectory = '../student_file_images/';

  $uniqueFilename = uniqid() . '-' . $_FILES['img_student']['name'];

  $imagePath = $uploadDirectory . $uniqueFilename;

  $sql = $conn->prepare("SELECT coordinators_profile_picture FROM coordinators_account WHERE id = ? ");
  $sql->execute([$studID]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);
  $currentImagePath = $row['coordinators_profile_picture'];

  if (file_exists($currentImagePath)) {
      unlink($currentImagePath);
  }

  if (move_uploaded_file($_FILES['img_student']['tmp_name'], $imagePath)) {

      $sql = $conn->prepare("UPDATE coordinators_account SET coordinators_profile_picture = ? WHERE id = ?");
      if ($sql->execute([$imagePath, $studID])) {

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logs = 'Profile picture updated successfully.';

        $sql2 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql2->execute([$studID, $logs, $date, $time]);

          $_SESSION['alert'] = "Success...";
          $_SESSION['status'] = "Image Updated";
          $_SESSION['status-code'] = "success";
      } else {
          $_SESSION['alert'] = "Failed!";
          $_SESSION['status'] = "Database update failed";
          $_SESSION['status-code'] = "error";
      }
  } else {
      $_SESSION['status'] = "Failed to move the uploaded image";
      $_SESSION['status-code'] = "error";
  }
}

$studID = $_SESSION['auth_user']['coordinators_id'];
$stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE id = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$assignedSection = $data['assigned_section'];
$secondAssignedSection = $data['second_assigned_section'];

$studentCount = ['student_count' => 0];

if (!empty($assignedSection) || !empty($secondAssignedSection)) {
    $sections = array_filter([$assignedSection, $secondAssignedSection]);
    if (!empty($sections)) {
        $placeholders = rtrim(str_repeat('?,', count($sections)), ',');
        $query = "SELECT COUNT(*) AS student_count FROM students_data WHERE stud_section IN ($placeholders)";

        $stmt = $conn->prepare($query);
        $stmt->execute($sections);
        $studentCount = $stmt->fetch(PDO::FETCH_ASSOC);
    }
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
    
    <!-- Common -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
            margin-top: 40px;
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
            align-items: left;
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
        
        .choose-text {
            margin-top: 10px;
            color: #888;
            font-size: 14px;
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
        
        .abnormal {
            color: #dc3545;
            font-weight: bold;
        }
        
        .upload-form {
            margin-top: 15px;
        }
        
        .upload-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .edit-profile-btn {
            border: none;
            background-color: transparent;
            color: #700000;
            font-weight: bold;
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
    <div class="page-header">
                            <div class="page-title">
                                <h1 style="font-size: 16px;"><b>MY PROFILE</b></h1>
                            </div>
                        </div>
                  <div class="profile-card">
                      <div class="profile-content">
                      <div class="profile-image">
                              <div class="image-placeholder" onclick="document.getElementById('profile-input').click();">
                                  <?php if(!empty($data['coordinators_profile_picture']) && file_exists($data['coordinators_profile_picture'])): ?>
                                      <img src="<?php echo $data['coordinators_profile_picture']; ?>" alt="Profile Image">
                                  <?php else: ?>
                                      <img src="images/placeholder.png" alt="Profile Placeholder" class="placeholder-icon">
                                  <?php endif; ?>
                              </div>
                              
                              <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
                                  <input type="file" name="img_student" id="profile-input" onchange="uploadImage(event)" required accept="image/*" style="display: none;">
                                  <input type="submit" name="upload" id="upload-submit" style="display: none;">
                              </form>

                              <button class="edit-profile-btn" onclick="toSettings()">
                                <i class="fa-solid fa-pencil"></i>
                                Edit Profile Information
                            </button>
                          </div>
                    
                    <div class="student-info">
                        <div class="student-name">
                            <?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?> 
                            <?php echo isset($data['middle_name']) ? $data['middle_name'] : ''; ?> 
                            <?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>
                            <span class="student-badge">Professor</span>
                        </div>
                        
                        <div class="student-id">
                            Faculty No.: <?php echo isset($data['faculty_id']) ? $data['faculty_id'] : 'N/A'; ?>
                        </div>
                        <br>
                        <div class="student-details">
                            <div class="detail-row">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">: <?php echo isset($data['coordinators_email']) ? $data['coordinators_email'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Department</div>
                                <div class="detail-value">: <?php echo isset($data['coor_dept']) ? $data['coor_dept'] : 'N/A'; ?></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">First Assigned Section</div>
                                <div class="detail-value">: <?php echo isset($data['assigned_section']) ? $data['assigned_section'] : 'N/A'; ?></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Second Assigned Section</div>
                                <div class="detail-value">: <?php echo isset($data['second_assigned_section']) ? $data['second_assigned_section'] : 'N/A'; ?></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Active Student</div>
                                <div class="detail-value">: <?php echo isset($studentCount['student_count']) ? $studentCount['student_count'] : 'N/A'; ?></div>
                            </div>
                            <br><br><br><br><br><br><br><br><br><br><br><br>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- JavaScript for image preview -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.querySelector('.image-placeholder img');
                output.src = reader.result;
                output.classList.remove('placeholder-icon');
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script>
      function uploadImage(event) {
          // Preview the image
          previewImage(event);
          
          // Automatically submit the form when a file is selected
          document.getElementById('upload-submit').click();
      }

      function previewImage(event) {
          const file = event.target.files[0];
          if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                  const placeholder = document.querySelector('.image-placeholder img');
                  placeholder.src = e.target.result;
              }
              reader.readAsDataURL(file);
          }
      }

      function toSettings () {
        window.location.href = "coordinators_settings.php";
      }
    </script>

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