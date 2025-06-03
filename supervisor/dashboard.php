<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if($_SESSION['auth_user']['supervisor_id']==0){
    echo"<script>window.location.href='index.php'</script>";
    
}else {
    // Assuming you have a variable $conn which is your database connection
    $supervisor_id = $_SESSION['auth_user']['supervisor_id'];
    $query = "SELECT first_name FROM supervisor WHERE id = :supervisor_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':supervisor_id', $supervisor_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $first_name = isset($result['first_name']) ? $result['first_name'] : "Guest";
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
    <title>OJT Web Portal: Dashboard</title>
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
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/lib/weather-icons.css" rel="stylesheet" />
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
                                <h1 style="color: blue;">Hello, <span style="font-size: 18px; color: black;"><?php echo htmlspecialchars($first_name); ?></span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Home</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-user color-primary border-primary"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Trainees</div>
                                        <?php
                                        $supervisor_company = $_SESSION['auth_user']['supervisor_company'];
            
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM students_data LEFT JOIN deployed_students ON students_data.id = deployed_students.student_id
                                        WHERE deployed_students.company_name = ?");
                                        $stmt->execute([$supervisor_company]);
                                        
                                        $count = $stmt->fetchColumn(); // Fetch the count
                                        ?>

                                        <div class="stat-digit"><?php echo $count; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-alarm-clock color-success border-success"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Remaining Hours</div>
                                        <?php

                                        $supervisor_company = $_SESSION['auth_user']['supervisor_company'];

                                        $stmt = $conn->prepare("SELECT * FROM ojt_hours");
                                        $stmt->execute();
                                        
                                        $hours = $stmt->fetch(PDO::FETCH_ASSOC);

                                        

                                        $stmt = $conn->prepare("SELECT AVG(total_working_hours) AS completed_hours FROM stud_daily_time_records
                                        LEFT JOIN students_data ON students_data.id = stud_daily_time_records.stud_id 
                                        LEFT JOIN deployed_students ON students_data.id = deployed_students.student_id
                                        WHERE deployed_students.company_name = ? ");
                                        $stmt->execute([$supervisor_company]);
                                        $completedHOURS = $stmt->fetch(PDO::FETCH_ASSOC);  
                                        
                                        $hoursLEFT = $hours['total_hours'] - $completedHOURS['completed_hours'];

                                        ?>

                                        <div class="stat-digit"><?php echo number_format($hoursLEFT, 2); ?> Hrs</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-files color-primary border-primary"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Task Completed</div>
                                        <?php
                                        $supervisor_company = $_SESSION['auth_user']['supervisor_company'];
                                        $task_status = 'Finished';
            
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM students_data LEFT JOIN deployed_students ON students_data.id = deployed_students.student_id
                                        LEFT JOIN stud_task_list ON stud_task_list.stud_id = students_data.id 
                                         WHERE deployed_students.company_name = ? AND stud_task_list.task_status = ?");
                                        $stmt->execute([$supervisor_company, $task_status]);
                                        
                                        $count = $stmt->fetchColumn(); // Fetch the count
                                        ?>

                                        <div class="stat-digit"><?php echo $count; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="ti-files color-warning border-warning"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text">Task Remaining</div>
                                        <?php
                                        $supervisor_company = $_SESSION['auth_user']['supervisor_company'];
                                        $task_status = 'Pending';
            
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM students_data LEFT JOIN deployed_students ON students_data.id = deployed_students.student_id
                                        LEFT JOIN stud_task_list ON stud_task_list.stud_id = students_data.id 
                                         WHERE deployed_students.company_name = ? AND stud_task_list.task_status = ?");
                                        $stmt->execute([$supervisor_company, $task_status]);
                                        
                                        $count = $stmt->fetchColumn(); // Fetch the count
                                        ?>

                                        <div class="stat-digit"><?php echo $count; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-----------CHARTS-------------->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                            <div class="card-title">
                                <h4>Student Weekly Performance </h4>
            
                            </div>
                            <div class="sales-chart">
                                <canvas id="team-chart"></canvas>
                            </div>
                            </div>
                            <!-- /# card -->
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                            <div class="card-title">
                                <h4>Performance Rating per Criteria </h4>
            
                            </div>
                            <div class="sales-chart">
                                <canvas id="barChart"></canvas>
                            </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        
                    </div>
                    


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer">
                                <p>2024 ©  - <a href="#">Mabuhay</a></p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- jquery vendor -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->

    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <!-- bootstrap -->


    <script src="js/lib/chart-js/Chart.bundle.js"></script>
    <!-- <script src="js/lib/chart-js/chartjs-init.js"></script> -->

    <!-- scripit init-->
    <script src="js/dashboard2.js"></script>

    
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>


    <?php

    $supervisor_company = $_SESSION['auth_user']['supervisor_company'];

    $stmt = $conn->prepare("SELECT week,  AVG(total_points) AS total_pointsAVG, AVG(job_knowledge) as job_knowledgeAVG, AVG(dependability) as dependabilityAVG,
    AVG(communication_skills) as communication_skillsAVG, AVG(conduct) as conductAVG, AVG(initiative_and_creativity) as initiative_and_creativityAVG, 
    AVG(cooperatives_and_relationship) as cooperatives_and_relationshipAVG, 
    AVG(attendance_and_punctuality) as attendance_and_punctualityAVG FROM stud_evaluation 
    LEFT JOIN students_data ON students_data.id = stud_evaluation.stud_id 
    LEFT JOIN deployed_students ON students_data.id = deployed_students.student_id 
    WHERE deployed_students.company_name = ? GROUP BY stud_evaluation.week");
    $stmt->execute([$supervisor_company]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $weeks = array_map(function($week) {
        return "Week $week";
    }, array_column($rows, 'week'));

    $totalPoints = array_column($rows, 'total_pointsAVG');

    $job_knowledgeAVG = array_column($rows, 'job_knowledgeAVG');

    $dependabilityAVG = array_column($rows, 'dependabilityAVG');

    $communication_skillsAVG = array_column($rows, 'communication_skillsAVG');

    $conductAVG = array_column($rows, 'conductAVG');

    $initiative_and_creativityAVG = array_column($rows, 'initiative_and_creativityAVG');

    $cooperatives_and_relationshipAVG = array_column($rows, 'cooperatives_and_relationshipAVG');

    $attendance_and_punctualityAVG = array_column($rows, 'attendance_and_punctualityAVG');
    ?>


    <script>
    (function($) {
        "use strict";

        // Extract PHP arrays for JavaScript use
        var weekData = <?= json_encode($weeks) ?>;
        var totalPointsData = <?= json_encode($totalPoints) ?>;

        var job_knowledgeAVG = <?= json_encode($job_knowledgeAVG) ?>;
        var dependabilityAVG = <?= json_encode($dependabilityAVG) ?>;
        var communication_skillsAVG = <?= json_encode($communication_skillsAVG) ?>;
        var conductAVG = <?= json_encode($conductAVG) ?>;
        var initiative_and_creativityAVG = <?= json_encode($initiative_and_creativityAVG) ?>;
        var cooperatives_and_relationshipAVG = <?= json_encode($cooperatives_and_relationshipAVG) ?>;
        var attendance_and_punctualityAVG = <?= json_encode($attendance_and_punctualityAVG) ?>;

        //Team chart
        var ctx = document.getElementById("team-chart");
        ctx.height = 150;
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: weekData,
                type: 'line',
                defaultFontFamily: 'Montserrat',
                datasets: [{
                    data: totalPointsData,
                    label: "Ratings",
                    backgroundColor: 'rgba(0,103,255,.15)',
                    borderColor: 'rgba(0,103,255,0.5)',
                    borderWidth: 3.5,
                    pointStyle: 'circle',
                    pointRadius: 5,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'rgba(0,103,255,0.5)',
                }, ]
            },
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    titleFontSize: 12,
                    titleFontColor: '#000',
                    bodyFontColor: '#000',
                    backgroundColor: '#fff',
                    titleFontFamily: 'Montserrat',
                    bodyFontFamily: 'Montserrat',
                    cornerRadius: 3,
                    intersect: false,
                },
                legend: {
                    display: false,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        fontFamily: 'Montserrat',
                    },
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                },
                title: {
                    display: false,
                }
            }
        });

            //bar chart
	var ctx = document.getElementById( "barChart" );
	//    ctx.height = 200;
	var myChart = new Chart( ctx, {
		type: 'bar',
		data: {
			labels: weekData,
			datasets: [
				{
					label: "Job Knowledge",
					data: job_knowledgeAVG,
                    borderColor: "rgba(255, 99, 132, 1)", // Red color
					borderWidth: "0",
					backgroundColor: "rgba(255, 99, 132, 0.5)"
                            },
                {
					label: "Dependability",
					data: dependabilityAVG,
                    borderColor: "rgba(54, 162, 235, 1)", // Blue color
                    borderWidth: "0",
                    backgroundColor: "rgba(54, 162, 235, 0.5)"
                            },
                {
					label: "Communication Skills",
					data: communication_skillsAVG,
                    borderColor: "rgba(255, 206, 86, 1)", // Yellow color
                    borderWidth: "0",
                    backgroundColor: "rgba(255, 206, 86, 0.5)"
                            },
                            {
					label: "Conduct",
					data: conductAVG,
					borderColor: "rgba(255, 165, 0, 1)", // Orange color
                    borderWidth: "0",
                    backgroundColor: "rgba(255, 165, 0, 0.5)"
                            },
                {
					label: "Initiative & Creativity",
					data: initiative_and_creativityAVG,
					borderColor: "rgba(0, 255, 255, 1)", // Cyan color
                    borderWidth: "0",
                    backgroundColor: "rgba(0, 255, 255, 0.5)"

                            },
                {
					label: "Cooperatives & Relationship",
					data: cooperatives_and_relationshipAVG,
					borderColor: "rgba(128, 0, 128, 1)", // Purple color
                    borderWidth: "0",
                    backgroundColor: "rgba(128, 0, 128, 0.5)"


                            },
                {
					label: "Attendance & Punctuality",
					data: attendance_and_punctualityAVG,
					borderColor: "rgba(50, 205, 50, 1)", // Purple color
                    borderWidth: "0",
                    backgroundColor: "rgba(50, 205, 50, 0.5)"


                            }


                        ]
		},
        options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                ticks: {
                    autoSkip: false,
                    maxRotation: 90,
                    minRotation: 45,
                    callback: function(value, index, values) {
                        // Change font size based on window width
                        var fontSize = window.innerWidth < 512 ? 10 : 14;
                        return Chart.helpers.isArray(value) ? value.join(' ') : value;
                    }
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});




    })(jQuery);
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
