<?php

include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}





if (isset($_POST['deploy'])) {
    $student_id = $_POST['student_id'];
    $partner_companies = $_POST['partner_companies'];

    $ojt_status = 'Deployed';

    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You deployed a student to the ' . $partner_companies;

    // Check if the student is already deployed
    $check_deployed = $conn->prepare("SELECT * FROM deployed_students WHERE student_id = ?");
    $check_deployed->execute([$student_id]);
    $student_exists = $check_deployed->fetch();

    if ($student_exists) {
        // Student is already deployed, update the company name
        $sql2 = $conn->prepare("UPDATE deployed_students SET company_name = ? WHERE student_id = ?");
        $sql2->execute([$partner_companies, $student_id]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "Student Company Updated";
        $_SESSION['status-code'] = "success";
    } else {
        // Student is not deployed, insert a new record
        $sql2 = $conn->prepare("INSERT INTO deployed_students(student_id, company_name) VALUES (?, ?)");
        $sql2->execute([$student_id, $partner_companies]);

        $sql3 = $conn->prepare("UPDATE students_data SET ojt_status = ? WHERE id = ?");
        $sql3->execute([$ojt_status, $student_id]);

        // Insert notification into coordinatorsystemnotification table
        $sql = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
        $sql->execute([$coordinators_id, $logs, $date, $time]);

        $_SESSION['alert'] = "Success";
        $_SESSION['status'] = "Student is Deployed";
        $_SESSION['status-code'] = "success";
    }

    
}






if (isset($_POST['drop_student'])) {
    $student_id = $_POST['student_ID'];

    $ojt_status = 'Dropped';

    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You DROPPED a student';
    
    $sql = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
    $sql->execute([$coordinators_id, $logs, $date, $time]);

    $sql3 = $conn->prepare("UPDATE students_data SET ojt_status = ? WHERE id = ?");
    $sql3->execute([$ojt_status, $student_id]);

    $_SESSION['alert'] = "Success";
    $_SESSION['status'] = "Student is Dropped";
    $_SESSION['status-code'] = "success";
}

if (isset($_POST['complete_student'])) {
    $student_id = $_POST['student_ID'];

    $ojt_status = 'Completed';

    $coordinators_id = $_SESSION['auth_user']['coordinators_id'];

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You confirmed that a student completed his/her hours in OJT';
    
    $sql = $conn->prepare("INSERT INTO coordinatorsystemnotification(coordinator_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
    $sql->execute([$coordinators_id, $logs, $date, $time]);

    $sql3 = $conn->prepare("UPDATE students_data SET ojt_status = ? WHERE id = ?");
    $sql3->execute([$ojt_status, $student_id]);

    $_SESSION['alert'] = "Success";
    $_SESSION['status'] = "Student completed his hours";
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
    <title>OJT Web Portal: Student List</title>
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
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

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
                                <h1>Class List</h1>
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
                                    <li class="breadcrumb-item active">Class List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <div id="Time" class="tabpane" style="overflow: auto;">
                <table id="datatablesss" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $course_handled = $_SESSION['auth_user']['coordinator_courseHANDLED'];
            
            $stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_course = ?");
            $stmt->execute([$course_handled]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $result) {
            ?>
            <tr>
                <td><?= $result['student_ID'] ?></td>
                <td><?= $result['first_name'] ?></td>
                <td><?= $result['last_name'] ?></td>
                <td><?= $result['stud_section'] ?></td>
                <td><?= $result['ojt_status'] ?></td>
                <td>
                    <a href="coordinator_stud_profile.php?student_ID=<?= $result['id'] ?>" class="btn btn-primary">View Profile</a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId_<?= $result['id'] ?>">Deploy Student</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modelIDdropstudent_<?= $result['id'] ?>">Drop Student</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelIDcompletestudent_<?= $result['id'] ?>">Complete</button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="modelIDcompletestudent_<?= $result['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Complete Student</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form action="" method="POST">
                                    <input type="hidden" name="student_ID" value="<?= $result['id'] ?>">
                                <div class="modal-body text-center">
                                    <p>Are you sure the student completed his/her hours?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button name="complete_student" class="btn btn-success">Yes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="modelIDdropstudent_<?= $result['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Drop Student</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form action="" method="POST">
                                    <input type="hidden" name="student_ID" value="<?= $result['id'] ?>">
                                <div class="modal-body text-center">
                                    Are you sure you want to drop the student?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button name="drop_student" class="btn btn-danger">Drop</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modelId_<?= $result['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Choose Company to Deploy the Student</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>

                                <form action="" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="hidden" name="student_id" value="<?= $result['id'] ?>">
                                      <label>Partner Companies</label>
                                      <select class="form-control" name="partner_companies" >
                                        <?php
                                        $stmt = $conn->prepare("SELECT company_name FROM supervisor");
                                        $stmt->execute();
                                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                                        foreach ($data as $result) {
                                        ?>
                                        <option value="<?=$result['company_name']?>"><?=$result['company_name']?></option>
                                        <?php
                                        }
                                        ?>
                                      </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button name="deploy" class="btn btn-success">Deploy</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Section</th>
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


<script>
    new DataTable('#datatablesss');
</script>





    <!-- Common -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>


    <!--  Nestable -->
    <script src="js/lib/nestable/jquery.nestable.js"></script>
    <script src="js/lib/nestable/nestable.init.js"></script>

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