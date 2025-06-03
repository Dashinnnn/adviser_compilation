<?php

include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['student_id']==0){
  echo"<script>window.location.href='index.php'</script>";
}

if (isset($_POST['upload'])) {
  $studID = $_SESSION['auth_user']['student_id'];

  $uploadDirectory = '../student_file_images/';

  $uniqueFilename = uniqid() . '-' . $_FILES['img_student']['name'];

  $imagePath = $uploadDirectory . $uniqueFilename;

  $sql = $conn->prepare("SELECT profile_picture FROM students_data WHERE id = ? ");
  $sql->execute([$studID]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);
  $currentImagePath = $row['profile_picture'];

  if (file_exists($currentImagePath)) {
      unlink($currentImagePath);
  }

  if (move_uploaded_file($_FILES['img_student']['tmp_name'], $imagePath)) {

      $sql = $conn->prepare("UPDATE students_data SET profile_picture = ? WHERE id = ?");
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

$studID = $_SESSION['auth_user']['student_id'];
$stmt = $conn->prepare("SELECT * FROM students_data WHERE id = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$skillsStmt = $conn->prepare("SELECT * FROM stud_skills WHERE stud_id = ?");
$skillsStmt->execute([$studID]);
$skills = $skillsStmt->fetchAll(PDO::FETCH_ASSOC);

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
    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php
    require_once 'templates/stud_navbar.php';
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
                                  <?php if(!empty($data['profile_picture']) && file_exists($data['profile_picture'])): ?>
                                      <img src="<?php echo $data['profile_picture']; ?>" alt="Profile Image">
                                  <?php else: ?>
                                      <img src="images/placeholder.png" alt="Profile Placeholder" class="placeholder-icon">
                                  <?php endif; ?>
                              </div>
                              
                              <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
                                  <input type="file" name="img_student" id="profile-input" onchange="uploadImage(event)" required accept="image/*" style="display: none;">
                                  <input type="submit" name="upload" id="upload-submit" style="display: none;">
                              </form>
                          </div>
                    
                    <div class="student-info">
                        <div class="student-name">
                            <?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?> 
                            <?php echo isset($data['middle_name']) ? $data['middle_name'] : ''; ?> 
                            <?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>
                            <span class="student-badge">Student</span>
                        </div>
                        
                        <div class="student-id">
                            Student No.: <?php echo isset($data['student_ID']) ? $data['student_ID'] : 'N/A'; ?>
                        </div>
                        <br>
                        <div class="student-details">
                            <div class="detail-row">
                                <div class="detail-label">Course</div>
                                <div class="detail-value">: <?php echo isset($data['stud_course']) ? $data['stud_course'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Year</div>
                                <div class="detail-value">: <?php echo isset($data['year_level']) ? $data['year_level'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Section</div>
                                <div class="detail-value">: <?php echo isset($data['stud_section']) ? $data['stud_section'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">: <?php echo isset($data['age']) ? $data['age'] . ' years old' : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">OJT Adviser</div>
                                <div class="detail-value">: <?php echo isset($data['ojt_adviser']) ? $data['ojt_adviser'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">HTE</div>
                                <div class="detail-value">: <?php echo isset($data['hte']) ? $data['hte'] : 'N/A'; ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Total rendered hours</div>
                                <div class="detail-value">: <?php echo isset($data['rendered_hours']) ? $data['rendered_hours'] : 'N/A'; ?></div>
                            </div>
                            <br>
                            <div class="detail-row">
                                <div class="detail-label">Medical Condition</div>
                                <div class="detail-value <?php echo (isset($data['medical_condition']) && $data['medical_condition'] == 'Abnormal') ? 'abnormal' : ''; ?>">
                                    : <?php echo isset($data['medical_condition']) ? $data['medical_condition'] : 'N/A'; ?>
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