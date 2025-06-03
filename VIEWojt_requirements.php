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
    <!-- theme meta -->
    <meta name="theme-name" content="focus" />
    <title>OJT Web Portal: OJT Requirements</title>
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
                            
                                $stmt = $conn->prepare("SELECT * FROM ojt_requirements LEFT JOIN students_data
                                ON students_data.id = ojt_requirements.student_id WHERE ojt_requirements.student_id = ?");
                            
                                $stmt->execute([$studID]);
                            
                                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                                if ($data) {
                                    echo '<h1>'.$data['first_name'].' '.$data['middle_name'].' '.$data['last_name'].'</h1>';
                                } else {
                                    echo 'No Document Submitted Yet.';
                                }
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
                                    <li class="breadcrumb-item active">OJT Requirements</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>

<br><br>
<div id="Time" class="tabpane" style="overflow: auto;">
    <table id="datatablessss" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Documents</th>
                <th>File Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
if (isset($_GET['student_ID'])) {
    $studID = $_GET['student_ID'];

    $stmt = $conn->prepare("SELECT *, ojt_requirements.id AS ojt_docID, students_data.id AS stud_ID FROM ojt_requirements LEFT JOIN students_data
    ON students_data.id = ojt_requirements.student_id  WHERE ojt_requirements.student_id = ?");
    $stmt->execute([$studID]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $result) {
?>
        <tr>
            <td><?= $result['document_name'] ?></td>
            <td><a href="<?= $result['document_location'] ?>" class="btn btn-link" target="_blank"><?= $result['document_fileName'] ?></a></td>
            <td><?= $result['status'] ?></td>
            <td>
                <button class="btn btn-success btn-circle" data-toggle="modal" data-target="#confimrModal<?= $result['ojt_docID'] ?>">
                   Confirm<i class="ti-check"></i>
                </button>

                <button class="btn btn-danger btn-circle" data-toggle="modal" data-target="#rejectModal<?= $result['ojt_docID'] ?>">
                   Reject<i class="ti-red-cross"></i>
                </button>

            </td>
        </tr>

        <!-- Modal for Confirmation -->
        <div class="modal fade" id="confimrModal<?= $result['ojt_docID'] ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to confirm this document?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="confirm_reject_OJT_requirements.php?confirmID=<?= $result['ojt_docID'] ?>&&studentID=<?= $result['stud_ID'] ?>" class="btn btn-success">Confirm</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Reject -->
        <div class="modal fade" id="rejectModal<?= $result['ojt_docID'] ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to reject this document?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="confirm_reject_OJT_requirements.php?rejectID=<?= $result['ojt_docID'] ?>&&studentID=<?= $result['stud_ID'] ?>" class="btn btn-danger">Reject</a>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

        </tbody>
        <tfoot>
            <tr>
                <th>Documents</th>
                <th>File Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>



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
                'copy', 'csv', 'excel', 'pdf', 'print'
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