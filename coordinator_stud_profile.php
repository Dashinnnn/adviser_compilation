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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Student Profile</title>
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
require_once 'templates/coordinators_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->



  <div class="content-wrap">
    <div class="main">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8 p-r-0 title-margin-right">
            <div class="page-header">
              <div class="page-title">
                <h1>Student Profile
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
if(isset($_GET['student_ID'])){

  $studID = $_GET['student_ID'];

  $stmt = $conn->prepare("SELECT * FROM students_data WHERE id = ? ");
	$stmt->execute([$studID]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
}
?>

<div class="col-md-4">

<img alt="" id="myImage" style="width:600px;" title="" class="img-circle img-thumbnail isTooltip" src="<?php echo $data['profile_picture']; ?>" data-original-title="Usuario">
<br><br>


</div>
<div class="col-md-6">
<strong>Information</strong><br>
<div class="table-responsive">
<table class="table table-user-information">
<tbody>

<tr>    
    <td>
        <strong>
            <span class="ti-id-badge"></span>    
            ID Number                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['student_ID']; ?>  
    </td>
</tr>

<tr>    
    <td>
        <strong>
            <span class="ti-user"></span>    
            Full Name                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['first_name']; ?> <?php echo $data['middle_name']; ?> <?php echo $data['last_name']; ?>  
    </td>
</tr>

<tr>    
    <td>
        <strong>
            <span class="ti-file"></span>    
            Student Course                                               
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['stud_course']; ?>
    </td>
</tr>

<tr>    
    <td>
        <strong>
            <span class="ti-file"></span>    
            Section                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['stud_section']; ?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-home"></span> 
            Complete Address                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['complete_address']; ?>
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
    <?php echo $data['stud_email']; ?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-files"></span> 
            Skills                                              
        </strong>
    </td>
    <td class="text-primary">
    <?php
if(isset($_GET['student_ID'])){
    $studID = $_GET['student_ID'];

    $stmt = $conn->prepare("SELECT * FROM stud_skills WHERE stud_id = ?");
    $stmt->execute([$studID]);

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
        echo "No skills found for this student.";
    }
}
?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-file"></span> 
            OJT Status                                                
        </strong>
    </td>
    <td class="text-primary">
    <?php echo $data['ojt_status']; ?>
    </td>
</tr>

<tr>        
    <td>
        <strong>
            <span class="ti-files"></span> 
            Deployed Company                                             
        </strong>
    </td>
    <td class="text-primary">
    <?php
if(isset($_GET['student_ID'])){
    $studID = $_GET['student_ID'];

    $stmt = $conn->prepare("SELECT *, supervisor.company_name AS partnerCompanyName FROM deployed_students LEFT JOIN students_data ON deployed_students.student_id = students_data.id
    LEFT JOIN supervisor ON supervisor.company_name = deployed_students.company_name WHERE deployed_students.student_id = ?");
    $stmt->execute([$studID]);

    // Fetch all skills associated with the student
    $company = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($company)) {
        foreach ($company as $res) {
            // Access individual skill attributes within the loop
            $CompanyName = $res['partnerCompanyName'];

            // Use the Company information as needed
            echo "$CompanyName<br>";
        }
    } else {
        echo "No Company found for this student.";
    }
}
?>
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
          
          <!-- <div class="row">
            <div class="col-lg-12">
              <div class="footer">
                <p>2024 ©  -
                  <a href="#">Mabuhay</a>
                </p>
              </div>
            </div>
          </div> -->
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