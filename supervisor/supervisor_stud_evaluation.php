<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
if($_SESSION['auth_user']['supervisor_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}

if (ISSET($_POST['add_evaluation'])) {

    $supervisor_id = $_SESSION['auth_user']['supervisor_id'];

    $student_ID = $_POST['student_ID'];
    $week = $_POST['week'];
    $job_knowledge = $_POST['job_knowledge'];
    $dependability = $_POST['dependability'];
    $communication_skills = $_POST['communication_skills'];
    $conduct = $_POST['conduct'];
    $initiative_and_creativity = $_POST['initiative_and_creativity'];
    $cooperatives_and_relationship = $_POST['cooperatives_and_relationship'];
    $attendance_and_punctuality = $_POST['attendance_and_punctuality'];
    $comments_suggestions = $_POST['comments_suggestions'];

    $total_points = $job_knowledge + $dependability + $communication_skills + $conduct + $initiative_and_creativity + $cooperatives_and_relationship + $attendance_and_punctuality;

    date_default_timezone_set('Asia/Manila');
    $date = date('F / d l / Y');
    $time = date('g:i A');
    $logs = 'You successfully evaluate a student this week';

    $sql = $conn->prepare("INSERT INTO  stud_evaluation(stud_id, week, job_knowledge, dependability, communication_skills, conduct, initiative_and_creativity, cooperatives_and_relationship, attendance_and_punctuality, total_points, comments_suggestions)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->execute([$student_ID, $week, $job_knowledge, $dependability, $communication_skills, $conduct, $initiative_and_creativity, $cooperatives_and_relationship, $attendance_and_punctuality, $total_points, $comments_suggestions]);

    $sql1 = $conn->prepare("INSERT INTO supervisor_system_notification(supervisor_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
    $sql1->execute([$supervisor_id, $logs, $date, $time]);

    

        $_SESSION['alert'] = "Success...";
        $_SESSION['status'] = "Student evaluated successfully";
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
    <title>OJT Web Portal: Student Evaluation</title>
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

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId_AddTask" title="Evaluate the student"><i class="ti-plus"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modelId_AddTask" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Student Evaluation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>

                                    <form action="" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="student_ID" value="<?php echo $data['id'] ?>">
                                          <label for="">Week</label>
                                          <input type="number" class="form-control" name="week" placeholder="Enter Week..." required>
                                          <br>
                                          <div class="form-inline">  
                                          <label>Job Knowledge</label>
                                          <input type="number" class="form-control" name="job_knowledge" style="width: 30%; margin-right: 40px;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          
                                          <label>Dependability</label>
                                          <input type="number" class="form-control" name="dependability" style="width: 30%;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          </div>
                                          <br>
                                          <div class="form-inline"> 
                                          <label>Communication Skills</label>
                                          <input type="number" class="form-control" name="communication_skills" style="width: 30%; margin-right: 40px;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          
                                          <label>Conduct</label>
                                          <input type="number" class="form-control" name="conduct" style="width: 30%;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          </div>
                                          <br>
                                          <div class="form-inline">
                                          <label>Initiative & Creativity</label>
                                          <input type="number" class="form-control" name="initiative_and_creativity" style="width: 30%; margin-right: 40px;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          
                                          <label>Cooperatives & Relationship</label>
                                          <input type="number" class="form-control" name="cooperatives_and_relationship" style="width: 20%;" placeholder="Enter points from 1 - 15" min="1" max="15" required>
                                          </div>
                                          <br>
                                          <label>Attendance & Punctuality</label>
                                          <input type="number" class="form-control" name="attendance_and_punctuality" placeholder="Enter points from 1 - 10" min="1" max="10" required>
                                          <br>
                                          <label>Comments / Suggestions</label>
                                          <textarea class="form-control" name="comments_suggestions" placeholder="Enter Comments..." required></textarea>
                                          

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button name="add_evaluation" class="btn btn-success">Submit</button>
                                    </div>
                                    </form>

                                </div>
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
                                    <li class="breadcrumb-item active">Student Evaluation</li>
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
                <th>Week</th>
                <th>Job Knowledge</th>
                <th>Dependability</th>
                <th>Communication Skills</th>
                <th>Conduct</th>
                <th>Initiative & Creativity</th>
                <th>Cooperatives & Relationship</th>
                <th>Attendance & Punctuality</th>
                <th>Total Evaluation Points</th>
                <th>Comments/Suggestions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_GET['student_ID'])) {
            $studID = $_GET['student_ID'];
            $stmt = $conn->prepare("SELECT * FROM stud_evaluation WHERE stud_id = ?");
            $stmt->execute([$studID]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as $res) {

            ?>
        <tr>
            <td><?= $res['week'] ?></td>
            <td><?= $res['job_knowledge'] ?></td>
            <td><?= $res['dependability'] ?></td>
            <td><?= $res['communication_skills'] ?></td>
            <td><?= $res['conduct'] ?></td>
            <td><?= $res['initiative_and_creativity'] ?></td>
            <td><?= $res['cooperatives_and_relationship'] ?></td>
            <td><?= $res['attendance_and_punctuality'] ?></td>
            <td><?= $res['total_points'] ?></td>
            <td><?= $res['comments_suggestions'] ?></td>
        </tr>
        <?php
        }
        }
        ?>

        </tbody>
        <tfoot>
            <tr>
                <th>Week</th>
                <th>Job Knowledge</th>
                <th>Dependability</th>
                <th>Communication Skills</th>
                <th>Conduct</th>
                <th>Initiative & Creativity</th>
                <th>Cooperatives & Relationship</th>
                <th>Attendance & Punctuality</th>
                <th>Total Evaluation Points</th>
                <th>Comments/Suggestions</th>
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
                // 'copy', 'csv', 'excel', 
                'pdf', 'print'
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