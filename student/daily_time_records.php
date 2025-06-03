<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if($_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
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
    <title>OJT Web Portal: Student DTR</title>
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

    <!---------------------DATATABLES------------------------->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <!----------------------CAMERA---------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
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
                                <h1>Daily Time Records</h1>
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
                                    <li class="breadcrumb-item active">Daily Time Records</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <form action="save_daily_time_records.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Date & Time</label>
                    <?php
                    date_default_timezone_set('Asia/Manila');
                    $currentDate = date('Y-m-d');
                    $currentTime = date('H:i');
                    ?>
                    <input type="date" name="date_record" min="<?php echo $currentDate; ?>" max="<?php echo $currentDate; ?>" value="<?php echo $currentDate; ?>" readonly>
                    <input type="time" name="time_record" value="<?php echo $currentTime; ?>">
                    <div class="form-group">
                      <label for="">Select Time In or Time Out</label>
                      <select class="form-inline" name="selectTIMEinTIMEout" id="" required>
                        <option value="">Select Here</option>
                        <option value="AM Time In">AM Time In</option>
                        <option value="AM Time Out">AM Time Out</option>
                        <option value="PM Time In">PM Time In</option>
                        <option value="PM Time Out">PM Time Out</option>
                      </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div id="my_camera"></div>
                        </div>
                    </div>
                </div>
                    <button name="timeINtimeOUT" onClick="take_snapshot()" class="btn btn-success">Save Time</button>
                    <input type="hidden" name="image" class="image-tag">
                </form>
<br><br>
<div id="Time" class="tabpane" style="overflow: auto;">
    <table id="datatablessss" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>A.M Time In</th>
                <th>A.M Time Out</th>
                <th>P.M Time In</th>
                <th>P.M Time Out</th>
                <th>Total Working Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
if (isset($_SESSION['auth_user']['student_id'])) {
    $studID = $_SESSION['auth_user']['student_id'];

    $stmt = $conn->prepare("SELECT * FROM stud_daily_time_records LEFT JOIN students_data
    ON students_data.id = stud_daily_time_records.stud_id  WHERE stud_daily_time_records.stud_id = ?");
    $stmt->execute([$studID]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $result) {
?>
        <tr>
            <td><?= $result['recordDate'] ?></td>
            <td><?= $result['AM_time_IN'] ?></td>
            <td><?= $result['AM_time_OUT'] ?></td>
            <td><?= $result['PM_time_IN'] ?></td>
            <td><?= $result['PM_time_OUT'] ?></td>
            <td><?= $result['total_working_hours'] ?> Hrs</td>
            <td><?= $result['recordStatus'] ?></td>
        </tr>
<?php
    }
}
?>

        </tbody>
        <tfoot>
            <tr>
                <th>Date</th>
                <th>A.M Time In</th>
                <th>A.M Time Out</th>
                <th>P.M Time In</th>
                <th>P.M Time Out</th>
                <th>Total Working Hours</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
</div>
                <!-- /# row -->
                <!-- <section id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="extra-area-chart"></div>
                            <div id="morris-line-chart"></div>
                            <div class="footer">
                                <p>2024 © -
                                    <a href="#">Mabuhay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </section> -->
            </div>
        </div>
    </div>

<!-- Initialize DataTables with export buttons -->
<script>
    $(document).ready(function() {
        $('#datatablessss').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

<script language="JavaScript">
        Webcam.set({
            width:  490,
            height:  390,
            image_format: 'jpeg',
            jpeg_quality:  90
        });
        Webcam.attach('#my_camera');
        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            });
        }
</script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


    <!-- Common -->
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <!-- Sweet Alert -->
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