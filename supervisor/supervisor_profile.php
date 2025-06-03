<?php

include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if($_SESSION['auth_user']['supervisor_id']==0){
  echo"<script>window.location.href='index.php'</script>";
  
}

//REMOVE SKILLS
if (isset($_POST['removeSKILL'])) {
  $supervisor_id = $_SESSION['auth_user']['supervisor_id'];

  $selected_skill = $_POST['selected_skill'];


  $sql = $conn->prepare("DELETE FROM company_skills_requirements WHERE id = ?");
  $sql->execute([$selected_skill]);

  date_default_timezone_set('Asia/Manila');
  $date = date('F / d l / Y');
  $time = date('g:i A');
  $logs = 'You removed a skill on your profile.';

  $sql2 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
  $sql2->execute([$supervisor_id, $logs, $date, $time]);

    $_SESSION['alert'] = "Success...";
    $_SESSION['status'] = "Skill Removed";
    $_SESSION['status-code'] = "success";

  
}

//ADD SKILLS
if (isset($_POST['add_skills'])) {
  $supervisor_id = $_SESSION['auth_user']['supervisor_id'];

  $company_name = $_SESSION['auth_user']['supervisor_company'];

  $skills_NAME = $_POST['skillsNAME'];


  $sql = $conn->prepare("INSERT INTO company_skills_requirements(company_name, skills_name) VALUES (?, ?)");
  $sql->execute([$company_name, $skills_NAME]);

  date_default_timezone_set('Asia/Manila');
  $date = date('F / d l / Y');
  $time = date('g:i A');
  $logs = 'Skill added successfully.';

  $sql2 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
  $sql2->execute([$supervisor_id, $logs, $date, $time]);

    $_SESSION['alert'] = "Success...";
    $_SESSION['status'] = "Skill Inserted";
    $_SESSION['status-code'] = "success";

  
}





if (isset($_POST['upload'])) {
  $supervisor_id = $_SESSION['auth_user']['supervisor_id'];

  // Define the directory where you want to save the images
  $uploadDirectory = '../student_file_images/'; // Change this to your desired directory

  // Generate a unique filename for the updated image
  $uniqueFilename = uniqid() . '-' . $_FILES['img_supervisor']['name'];

  // Define the full path to the saved image file
  $imagePath = $uploadDirectory . $uniqueFilename;

  // Retrieve the current image path from the database
  $sql = $conn->prepare("SELECT supervisor_profile_picture FROM supervisor WHERE id = ? ");
  $sql->execute([$supervisor_id]);
  $row = $sql->fetch(PDO::FETCH_ASSOC);
  $currentImagePath = $row['supervisor_profile_picture'];

  // Delete the current image from the file system
  if (file_exists($currentImagePath)) {
      unlink($currentImagePath);
  }

  // Move the updated image to the specified directory
  if (move_uploaded_file($_FILES['img_supervisor']['tmp_name'], $imagePath)) {
      // Image has been successfully updated in the file system

      // Update the database with the new image path
      $sql = $conn->prepare("UPDATE supervisor SET supervisor_profile_picture = ? WHERE id = ?");
      if ($sql->execute([$imagePath, $supervisor_id])) {

        date_default_timezone_set('Asia/Manila');
        $date = date('F / d l / Y');
        $time = date('g:i A');
        $logs = 'Profile picture updated successfully.';

        $sql2 = $conn->prepare("INSERT INTO supervisor_system_notification(supervisor_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql2->execute([$supervisor_id, $logs, $date, $time]);

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


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Profile</title>
    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">   

    <!-- Common -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
</head>

<body>
<!---------NAVIGATION BAR-------->
<?php
require_once 'templates/supervisor_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->



  <div class="content-wrap">
    <div class="main">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8 p-r-0 title-margin-right">
            <div class="page-header">
              <div class="page-title">
                <h1>Company Profile
                </h1>
              </div>
            </div>
          </div>
          <!-- /# column -->
          <div class="col-lg-4 p-l-0 title-margin-left">
            <div class="page-header">
              <div class="page-title">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <a href="dashboard.php">Dashboard</a>
                  </li>
                  <li class="breadcrumb-item active">App-Profile</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /# column -->
        </div>
        <!-- /# row -->
        <section id="main-content">
          <!-- Begin Page Content -->
          <div class="container-fluid">

<!-- Page Heading -->

<div class="container bootstrap snippets bootdey">


<div class="panel-body inf-content">
<div class="row">
  <?php
if(isset($_SESSION['auth_user']['supervisor_id'])){

  $supervisorID = $_SESSION['auth_user']['supervisor_id'];

  $stmt = $conn->prepare("SELECT * FROM supervisor WHERE id = ? ");
	$stmt->execute([$supervisorID]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
}
?>

<div class="col-md-4">
<form action="" method="POST" enctype="multipart/form-data">
<img alt="" id="myImage" style="width:600px;" title="" class="img-circle img-thumbnail isTooltip" src="<?php echo $data['supervisor_profile_picture']; ?>" data-original-title="Usuario">
<br><br>



<label>Update/Upload Image</label>

<input type="file" name="img_supervisor" onchange="previewImage(event)" required accept="image/*"><br><br>

<button name="upload" class="btn btn-primary">Upload</button>


</form>

</div>
<div class="col-md-6">
<strong>Information</strong><br>
<div class="table-responsive">
<table class="table table-user-information">
<tbody>

<tr>    
    <td>
        <strong>
            <span class="ti-home"></span>    
            Company Name                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['company_name']; ?>  
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-location-pin"></span> 
            Company Address                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['company_address']; ?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-user"></span> 
            User Account Name                                               
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['first_name']; ?> <?php echo $data['last_name']; ?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-mobile"></span> 
            Phone Number                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['phone_number']; ?>
    </td>
</tr>


<tr>        
    <td>
        <strong>
            <span class="ti-email"></span> 
            Email Address                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['supervisor_email']; ?>
    </td>
</tr>


<tr>        
    <td>
        <strong>
            <span class="ti-files"></span> 
            Skills Requirements                                             
        </strong>
    </td>
    <td class="text-primary">
    <?php
if(isset($_SESSION['auth_user']['supervisor_company'])){
    $company_name = $_SESSION['auth_user']['supervisor_company'];

    $stmt = $conn->prepare("SELECT * FROM company_skills_requirements WHERE company_name = ?");
    $stmt->execute([$company_name]);

    // Fetch all skills associated with the student
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($skills)) {
        foreach ($skills as $skill) {
            // Access individual skill attributes within the loop
            $skillName = $skill['skills_name'];

            // Use the skill information as needed
            echo "$skillName<br>";
        }
    } else {
        echo "No skills found for this Company.";
    }
}
?>

    <form action="" method="post">
      <input type="text" name="skillsNAME" required>
      <br><br>
      <button name="add_skills" class="btn btn-primary">Add Skills</button>
    </form>
<br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modelId_<?php echo $_SESSION['auth_user']['supervisor_id']; ?>">
          Remove Skill
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="modelId_<?php echo $_SESSION['auth_user']['supervisor_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Remove Skill</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
                    <form action="" method="post">
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="selectskills"></label>
                            <?php
                                if(isset($_SESSION['auth_user']['supervisor_company'])){
                                    $studID = $_SESSION['auth_user']['supervisor_company'];

                                    $stmt = $conn->prepare("SELECT * FROM company_skills_requirements WHERE company_name = ?");
                                    $stmt->execute([$studID]);

                                    // Fetch all skills associated with the student
                                    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if (!empty($skills)) {
                                        echo '<select class="form-control" name="selected_skill" id="selectskills">';
                                        echo '<option value="">Select a Skill</option>';
                                        
                                        foreach ($skills as $skill) {
                                            $skillName = $skill['skills_name'];
                                            $skillID = $skill['id'];

                                            echo "<option value='$skillID'>$skillName</option>";
                                        }

                                        echo '</select>';
                                    } else {
                                        echo '<p>No skills found for this student.</p>';
                                    }
                                }
                            ?>

                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button name="removeSKILL" class="btn btn-danger">Remove</button>
                        </div>
                    </form>
            </div>
          </div>
        </div>
    </td>
</tr>

                                    
</tbody>
</table>
</div>
</div>
</div>
</div>



</div>                                        

</div>
<!-- /.container-fluid -->
          <!-- /# row -->
          
          <div class="row">
            <div class="col-lg-12">
              <div class="footer">
                <p>2024 © -
                  <a href="#">Mabuhay</a>
                </p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>



<!----------------UPLOAD OR UPDATE AN IMAGE AND DISPLAYS THE SELECTED IMAGE FIRST BEFORE UPDATING OR UPLOADING--------------->
<script>
    function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function () {
    var output = document.getElementById('myImage');
    output.src = reader.result;
  }
  reader.readAsDataURL(event.target.files[0]);
}
</script>



    <!-- Common -->
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