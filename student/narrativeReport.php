<?php

include '../connection/config.php';
error_reporting(0);

session_start();
if($_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}


if (isset($_POST['SaveDailyTime_Journal'])) {
  $STUDid = $_POST['studID'];
  $dateOFsubmit = $_POST['dateofSUBMIT'];
  $Objectives = $_POST['objectives'];
  $Accomplishments = $_POST['accomplishments'];
  $Reflection = $_POST['reflection'];
  $Realization = $_POST['realization'];
  $Knowledge = $_POST['knowledge'];

  // Validate and sanitize user inputs here

  date_default_timezone_set('Asia/Manila');
  $date = date('F / d l / Y');
  $time = date('g:i A');
  $logs = 'Daily time journal has been inserted.';

  // Insert into the system_notification table
  $sql = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
  $sql->execute([$STUDid, $logs, $date, $time]);

  // Insert into the narrative_reports table
  $sql2 = $conn->prepare("INSERT INTO narrative_reports(student_id, dateOFSubmit, objectives, accomplishments, reflections, realizations, knowledge) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $sql2->execute([$STUDid, $dateOFsubmit, $Objectives, $Accomplishments, $Reflection, $Realization, $Knowledge]);

  if ($sql && $sql2) {
      $_SESSION['alert'] = "Success";
      $_SESSION['status'] = "Daily time journal saved successfully";
      $_SESSION['status-code'] = "success";
      
  } else {
      $_SESSION['alert'] = "Error";
      $_SESSION['status'] = "Failed to save the journal";
      $_SESSION['status-code'] = "error";
      // Handle the error gracefully (e.g., log the error, show a user-friendly message)
  }
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
    <title>OJT Web Portal: Journal Report</title>
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
    <!-- Styles -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
</head>

<body>
<!---------NAVIGATION BAR-------->
<?php
require_once 'templates/stud_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->



    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Journal Report</h1>
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
                                    <li class="breadcrumb-item active">Journal Report</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>

                <?php
if(isset($_SESSION['auth_user']['student_id'])){

  $studID = $_SESSION['auth_user']['student_id'];

  $stmt = $conn->prepare("SELECT * FROM students_data WHERE id = ? ");
	$stmt->execute([$studID]);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
}
?>



<form action="" method="POST" class="row g-3 needs-validation" novalidate="">
  <div class="col-md-4 position-relative">
    <label for="validationTooltip01" class="form-label">Date</label>
    <input type="hidden"name="studID"value="<?php echo $data['id']; ?>" required="">
    <input type="date" class="form-control" name="dateofSUBMIT" id="validationTooltip01" required="">
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Date Required
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="validationTooltip02" class="form-label">Objectives</label>
    <textarea class="form-control" name="objectives" id="validationTooltip02" required></textarea>
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Objectives Required
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="validationTooltip02" class="form-label">Accomplishments</label>
    <textarea class="form-control" name="accomplishments" id="validationTooltip02" required></textarea>
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Accomplishment Required
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="validationTooltip02" class="form-label">Reflection</label>
    <textarea class="form-control" name="reflection" id="validationTooltip02" required></textarea>
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Reflection Required
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="validationTooltip02" class="form-label">Realization</label>
    <textarea class="form-control" name="realization" id="validationTooltip02" required></textarea>
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Realization Required
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="validationTooltip02" class="form-label">Knowledge</label>
    <textarea class="form-control" name="knowledge" id="validationTooltip02" required></textarea>
    <div class="valid-tooltip">
      Looks good!
    </div>
    <div class="invalid-tooltip">
      Knowledge Required
    </div>
  </div>

  <div class="col-12" style="margin-top: 50px;">
    <button class="btn btn-primary" name="SaveDailyTime_Journal">Save</button>
  </div>
</form>


                <!-- /# row -->
                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="extra-area-chart"></div>
                            <div id="morris-line-chart"></div>
                            <div class="footer">
                                <p>2024 ©  -
                                    <a href="#">Mabuhay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>




    <!-- Common -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <!--  Peity -->
    <script src="js/lib/peitychart/jquery.peity.min.js"></script>
    <script src="js/lib/peitychart/peitychart.init.js"></script>

    <!--  Sparkline -->
    <script src="js/lib/sparklinechart/jquery.sparkline.min.js"></script>
    <script src="js/lib/sparklinechart/sparkline.init.js"></script>

    <!-- Select2 -->
    <script src="js/lib/select2/select2.full.min.js"></script>

    <!--  Validation -->
    <script src="js/lib/form-validation/jquery.validate.min.js"></script>
    <script src="js/lib/form-validation/jquery.validate-init.js"></script>

    <!--  Owl Carousel -->
    <script src="js/lib/owl-carousel/owl.carousel.min.js"></script>
    <script src="js/lib/owl-carousel/owl.carousel-init.js"></script>

    <!-- JS Grid -->
    <script src="js/lib/jsgrid/db.js"></script>
    <script src="js/lib/jsgrid/jsgrid.core.js"></script>
    <script src="js/lib/jsgrid/jsgrid.load-indicator.js"></script>
    <script src="js/lib/jsgrid/jsgrid.load-strategies.js"></script>
    <script src="js/lib/jsgrid/jsgrid.sort-strategies.js"></script>
    <script src="js/lib/jsgrid/jsgrid.field.js"></script>
    <script src="js/lib/jsgrid/fields/jsgrid.field.text.js"></script>
    <script src="js/lib/jsgrid/fields/jsgrid.field.number.js"></script>
    <script src="js/lib/jsgrid/fields/jsgrid.field.select.js"></script>
    <script src="js/lib/jsgrid/fields/jsgrid.field.checkbox.js"></script>
    <script src="js/lib/jsgrid/fields/jsgrid.field.control.js"></script>
    <script src="js/lib/jsgrid/jsgrid-init.js"></script>

    <!--  Nestable -->
    <script src="js/lib/nestable/jquery.nestable.js"></script>
    <script src="js/lib/nestable/nestable.init.js"></script>

    <!-- Sweet Alert -->
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>



    <script>
   var forms = document.querySelectorAll('.needs-validation')
Array.prototype.slice.call(forms)
  .forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  }); 
</script>

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