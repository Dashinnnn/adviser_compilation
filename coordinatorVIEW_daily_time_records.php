<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}

if (ISSET($_POST['approve'])) {

    $coordinator_id = $_SESSION['auth_user']['coordinators_id'];
    $dtr_id = $_POST['dtr_id'];
    $status = 'Approved';

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You successfully APPROVED students Daily Time Record.';

    $sql = $conn->prepare("UPDATE stud_daily_time_records SET recordStatus = ? WHERE id = ?");
    $sql->execute([$status, $dtr_id]);

    $sql1 = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
    $sql1->execute([$coordinator_id, $logs, $date, $time]);

    

        $_SESSION['alert'] = "Success...";
        $_SESSION['status'] = "DTR Approved";
        $_SESSION['status-code'] = "success";
}


if (ISSET($_POST['reject'])) {

    $coordinator_id = $_SESSION['auth_user']['coordinators_id'];
    $dtr_id = $_POST['dtr_id'];
    $status = 'Rejected';

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You successfully REJECTED students Daily Time Record.';

    $sql = $conn->prepare("UPDATE stud_daily_time_records SET recordStatus = ? WHERE id = ?");
    $sql->execute([$status, $dtr_id]);

    $sql1 = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
    $sql1->execute([$coordinator_id, $logs, $date, $time]);

    

        $_SESSION['alert'] = "Success...";
        $_SESSION['status'] = "DTR Rejected";
        $_SESSION['status-code'] = "success";
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
                            <?php
                            if (isset($_GET['student_ID'])) {
                                $studID = $_GET['student_ID'];
                                $stmt = $conn->prepare("SELECT * FROM students_data WHERE id = ?");
                                $stmt->execute([$studID]);
                                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                
                                <h1><?=$data['first_name']?> <?=$data['middle_name']?> <?=$data['last_name']?></h1>
                                <?php
                            }
                            ?>
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
                
<div id="Time" class="tabpane" style="overflow: auto;">
    <table id="datatablessss" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
        <thead>
            <tr>
                <th class="text-center">Date</th>
                <th class="text-center">A.M Time In</th>
                <th class="text-center">A.M Time Out</th>
                <th class="text-center">P.M Time In</th>
                <th class="text-center">P.M Time Out</th>
                <th class="text-center">Total Working Hours</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
if (isset($_GET['student_ID'])) {
    $studID = $_GET['student_ID'];

    $stmt = $conn->prepare("SELECT * FROM stud_daily_time_records WHERE stud_id = ?");
    $stmt->execute([$studID]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $result) {
?>
        <tr>
            <td class="autofit"><?= $result['recordDate'] ?></td>
            <td class="autofit"><?= $result['AM_time_IN'] . '<br>' . '<img src="../student/'. $result['AM_time_IN_pic'] . '" alt="AM Time In Picture" width="300" height="300">' ?></td>
            <td class="autofit"><?= $result['AM_time_OUT'] . '<br>' . '<img src="../student/'. $result['AM_time_OUT_pic'] . '" alt="AM Time Out Picture" width="300" height="300">'?></td>
            <td class="autofit"><?= $result['PM_time_IN'] . '<br>' . '<img src="../student/'. $result['PM_time_IN_pic'] . '" alt="PM Time In Picture" width="300" height="300">'?></td>
            <td class="autofit"><?= $result['PM_time_OUT'] . '<br>' . '<img src="../student/'. $result['PM_time_OUT_pic'] . '" alt="PM Time Out Picture" width="300" height="300">'?></td>
            <td class="autofit"><?= $result['total_working_hours'] ?></td>
            <td class="autofit"><?= $result['recordStatus'] ?></td>
            <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelIdAPPROVE_<?= $result['id'] ?>"><i class="ti-check"></i></button>
                
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modelIdREJECT_<?= $result['id'] ?>"><i class="ti-close"></i></button>
                
                <!-- Modal -->
                <div class="modal fade" id="modelIdAPPROVE_<?= $result['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Approve Time In and Out</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>

                            <form action="" method="POST">
                            <input type="hidden" name="dtr_id" value="<?= $result['id'] ?>">
                                <div class="modal-body text-center">
                                    Are you sure you want to approve this attendance?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button name="approve" class="btn btn-success">Approve</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="modelIdREJECT_<?= $result['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Time In and Out</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                            
                            <form action="" method="POST">
                            <input type="hidden" name="dtr_id" value="<?= $result['id'] ?>">
                                <div class="modal-body text-center">
                                    Are you sure you want to reject this attendance?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button name="reject" class="btn btn-danger">Reject</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


            </td>
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
                <th>Action</th>
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
                                <p>2024 ©  -
                                    <a href="#">Mabuhay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </section> -->
            </div>
        </div>
    </div>

<!-- <script>
    new DataTable('#examples');
    
</script> -->


<!-- Initialize DataTables with export buttons -->


<!-- Initialize DataTables with export buttons -->
<script>
    $(document).ready(function() {
        $('#datatablessss').DataTable({
            dom: 'Bfrtip',
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
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