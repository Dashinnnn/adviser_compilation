<?php

include '../connection/config.php';
error_reporting(0);

session_start();

if($_SESSION['auth_user']['coordinators_id']==0){
    echo"<script>window.location.href='index.php'</script>"; 
}else {
    // Assuming you have a variable $conn which is your database connection
    $faculty_id = $_SESSION['auth_user']['coordinators_id'];
    $query = "SELECT first_name FROM coordinators_account WHERE id = :faculty_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $first_name = isset($result['first_name']) ? $result['first_name'] : "Guest";
}

// Fetch Announcements for Faculty or All portals
$announcement_query = "SELECT title, content, created_at FROM announcements WHERE portal IN ('adviser', 'all') ORDER BY created_at DESC";
$announcement_stmt = $conn->prepare($announcement_query);
$announcement_stmt->execute();
$announcements = $announcement_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch FAQs
$faq_query = "SELECT question, answer FROM faqs ORDER BY id DESC";
$faq_stmt = $conn->prepare($faq_query);
$faq_stmt->execute();
$faqs = $faq_stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE id = ?");
$stmt->execute([$faculty_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

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
require_once 'templates/coordinators_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->


    <div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div>
                <div>
                    <div>
                        <div class="page-header">
                            <div class="page-title">
                                <h1 style="font-size: 16px;"><b>HOME</b></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <section id="main-content">
                    <!-- Slideshow container -->
                    <br><br>
                     <div class="slideshow-container" style="position: relative;">
                        <div class="mySlides fade" style="position: relative;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #700000; opacity: 0.5; z-index: 1; border-radius: 40px;"></div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FABC3F; font-size: 48px; z-index: 2; text-align: center; font-family: 'Source Serif 4', serif">
                                Iskolar ng Bayan!
                            </div>
                            <img src="images/pup-carousel.jpg" style="height: 50%; width: 100%; position: relative; z-index: 0; border-radius: 40px;">
                        </div>

                        <div class="mySlides fade" style="position: relative;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #700000; opacity: 0.5; z-index: 1; border-radius: 40px;"></div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FABC3F; font-size: 48px; z-index: 2; text-align: center; font-family: 'Source Serif 4', serif">
                                Iskolar ng Bayan!
                            </div>
                            <img src="images/pup-carousel.jpg" style="height: 50%; width: 100%; position: relative; z-index: 0; border-radius: 40px;">
                        </div>

                        <div class="mySlides fade" style="position: relative;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #700000; opacity: 0.5; z-index: 1; border-radius: 40px;"></div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FABC3F; font-size: 48px; z-index: 2; text-align: center; font-family: 'Source Serif 4', serif">
                                Iskolar ng Bayan!
                            </div>
                            <img src="images/pup-carousel.jpg" style="height: 50%; width: 100%; position: relative; z-index: 0; border-radius: 40px;">
                        </div>
                        
                        <!-- Dots below the center of the image -->
                        <div style="position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); text-align: center;">
                            <span class="dot" onclick="currentSlide(1)"></span>
                            <span class="dot" onclick="currentSlide(2)"></span>
                            <span class="dot" onclick="currentSlide(3)"></span>
                        </div>
                    </div>

                    <br><br>

                    <div class="page-title">
                        <h1 style="font-size: 16px; color: #700000; margin-left: 5rem;"><b>DASHBOARD</b></h1>
                    </div>
                        <br><br>


                        <div class="row dashboard">


                            <div>
                                <div>
                                    <div class="dashboard-content">
                                        <div>
                                            <img src="images/user.png" alt="Trainee icon">
                                        </div>
                                        <div>
                                            <div>Trainee</div>
                                            <?php
                                            $course_handled = $data['course_handled'];
                                            $first_section = $data['assigned_section'];
                                            $second_section = $data['second_assigned_section'];
                                            
                                            try {
                                                $query = "SELECT COUNT(*) FROM students_data WHERE stud_course = ? AND (stud_section = ?";
                                                $params = [$course_handled, $first_section];

                                                if(!empty($second_section)) {
                                                    $query .= " OR stud_section = ?)";
                                                    $params[] = $second_section;
                                                } else {
                                                    $query .= ")";
                                                }

                                                $stmt = $conn->prepare($query);
                                                $stmt->execute($params);
                                                $count = $stmt->fetchColumn();
                                            } catch (PDOException $e) {
                                                $count = 0;
                                                echo "Error: " . $e->getMessage();
                                            }
                                            ?>

                                            <div class="stat"><?php echo $count; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <div class="dashboard-content">
                                        <div>
                                            <img src="images/user.png" alt="Deployed icon">
                                        </div>
                                        <div>
                                            <div>Deployed</div>
                                            <?php
                                            $course_handled = isset($data['course_handled']) ? $data['course_handled'] : '';
                                            $first_section = isset($data['assigned_section']) ? $data['assigned_section'] : '';
                                            $second_section = isset($data['second_assigned_section']) ? $data['second_assigned_section'] : '';
                                            $ojt_status = 'Deployed';

                                            try {
                                                $query = "SELECT COUNT(*) FROM students_data WHERE stud_course = ? AND ojt_status = ? AND (stud_section = ?";
                                                $params = [$course_handled, $ojt_status, $first_section];
                                            
                                                if (!empty($second_section)) {
                                                    $query .= " OR stud_section = ?)";
                                                    $params[] = $second_section;
                                                } else {
                                                    $query .= ")";
                                                }
                                            
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute($params);
                                                $count = $stmt->fetchColumn();
                                            } catch (PDOException $e) {
                                                $count = 0;
                                                                        echo "Error: " . $e->getMessage();
                                            }
                                            ?>
                                            <div class="stat"><?php echo $count; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <div>
                                        <div class="dashboard-content">
                                            <div>
                                                <img src="images/user.png" alt="Completed icon">
                                            </div>
                                            <div>
                                                <div>Completed</div>
                                                <?php
                                                $course_handled = isset($data['course_handled']) ? $data['course_handled'] : '';
                                                $first_section = isset($data['assigned_section']) ? $data['assigned_section'] : '';
                                                $second_section = isset($data['second_assigned_section']) ? $data['second_assigned_section'] : '';
                                                $ojt_status = 'Completed';

                                                try {
                                                    $query = "SELECT COUNT(*) FROM students_data WHERE stud_course = ? AND ojt_status = ? AND (stud_section = ?";
                                                    $params = [$course_handled, $ojt_status, $first_section];
                                                
                                                    if (!empty($second_section)) {
                                                        $query .= " OR stud_section = ?)";
                                                        $params[] = $second_section;
                                                    } else {
                                                        $query .= ")";
                                                    }
                                                
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->execute($params);
                                                    $count = $stmt->fetchColumn();
                                                } catch (PDOException $e) {
                                                    $count = 0;
                                                    echo "Error: " . $e->getMessage();
                                                }
                                                ?>
                                                <div class="stat"><?php echo $count; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <div>
                                        <div class="dashboard-content">
                                            <div>
                                                <img src="images/user.png" alt="Completed icon">
                                            </div>
                                            <div>
                                                <div>Dropped</div>
                                                <?php
                                                $course_handled = isset($data['course_handled']) ? $data['course_handled'] : '';
                                                $first_section = isset($data['assigned_section']) ? $data['assigned_section'] : '';
                                                $second_section = isset($data['second_assigned_section']) ? $data['second_assigned_section'] : '';
                                                $ojt_status = 'Dropped';

                                                try {
                                                    $query = "SELECT COUNT(*) FROM students_data WHERE stud_course = ? AND ojt_status = ? AND (stud_section = ?";
                                                    $params = [$course_handled, $ojt_status, $first_section];

                                                    if (!empty($second_section)) {
                                                        $query .= " OR stud_section = ?)";
                                                        $params[] = $second_section;
                                                    } else {
                                                        $query .= ")";
                                                    }

                                                    $stmt = $conn->prepare($query);
                                                    $stmt->execute($params);
                                                    $count = $stmt->fetchColumn();
                                                } catch (PDOException $e) {
                                                    $count = 0;
                                                    echo "Error: " . $e->getMessage();
                                                }
                                                ?>
                                                <div class="stat"><?php echo $count; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--End -->
                        <br><br><br><br>
                        <!--Chart -->
                        <div class="chart">
                            <div class="col-lg-8">
                                <div class="card">
                                <div class="card-title">
                                    <h4>Student per Section </h4>

                                </div>
                                <div class="sales-chart">
                                    <canvas id="pieChart"></canvas>
                                </div>
                                </div>
                                <!-- /# card -->
                            </div>
                            </div>
                            <br><br>
                                                <!-- Announcements Section -->
                    <div class="page-title">
                        <h1 style="font-size: 16px; color: #700000; margin-left: 5rem;"><b>ANNOUNCEMENTS</b></h1>
                    </div>
                    <br><br>
                    <div style="max-width: 62.5rem; margin: 0 auto; display: flex; flex-direction: row; align-items: center; justify-content: center; gap: 80px;">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="deadline-container">
                                    <div class="header">
                                        <?php echo htmlspecialchars($announcement['title']); ?>
                                    </div>
                                    <div class="deadline-content">
                                        <?php echo htmlspecialchars($announcement['content']); ?>
                                        <div style="font-size: 14px; color: #555; margin-top: 10px;">
                                            <?php echo htmlspecialchars(date('F d, Y, H:i', strtotime($announcement['created_at']))); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; color: #700000;">No announcements available at this time.</div>
                        <?php endif; ?>
                    </div>
                    <br><br>
                    <!-- FAQs Section -->
                    <div class="page-title" style="display: flex; align-items: center; margin-left: 5rem;">
                        <img src="../student/images/faqs.png" alt="faqs" style="margin-right: 10px;">
                        <h1 style="font-size: 16px; color: #700000; margin: 0;"><b>FAQs</b></h1>
                    </div>
                    <div class="faq-container">
                        <?php if (!empty($faqs)): ?>
                            <?php foreach ($faqs as $faq): ?>
                                <div class="faq-item">
                                    <div class="faq-header"><?php echo htmlspecialchars($faq['question']); ?><span>v</span></div>
                                    <div class="faq-content">
                                        <?php echo htmlspecialchars($faq['answer']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; color: #700000;">No FAQs available at this time.</div>
                        <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/customAlert.js"></script>


    <?php
$course_handled = $data['course_handled'];
$stmt = $conn->prepare("SELECT stud_section, COUNT(*) as count FROM students_data WHERE stud_course = ? GROUP BY stud_section");
$stmt->execute([$course_handled]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stud_sections = $rows ? array_column($rows, 'stud_section') : [];
$section_counts = $rows ? array_column($rows, 'count') : [];
?>

<script>
    (function($) {
        "use strict";

        
        var stud_sections = <?= json_encode($stud_sections) ?>;
        var section_counts = <?= json_encode($section_counts) ?>;


        //pie chart
	var ctx = document.getElementById( "pieChart" );
	ctx.height = 300;
	var myChart = new Chart( ctx, {
		type: 'pie',
		data: {
			datasets: [ {
				data: section_counts,
				backgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "rgba(0, 123, 255,0.7)",
                                    "rgba(0, 123, 255,0.5)",
                                    "rgba(0,0,0,0.07)",
                                    "rgba(255, 165, 0, 0.5)",
                                    "rgba(255, 206, 86, 0.5)",
                                    "rgba(255, 99, 132, 0.5)",
                                    "rgba(128, 0, 128, 0.5)"
                                ],
				hoverBackgroundColor: [
                                    "rgba(0, 123, 255,0.9)",
                                    "rgba(0, 123, 255,0.7)",
                                    "rgba(0, 123, 255,0.5)",
                                    "rgba(0,0,0,0.07)",
                                    "rgba(255, 165, 0, 0.5)",
                                    "rgba(255, 206, 86, 0.5)",
                                    "rgba(255, 99, 132, 0.5)",
                                    "rgba(128, 0, 128, 0.5)"

                                ]

                            } ],
			labels: stud_sections,
		},
		options: {
			responsive: true
		}
	} );

    <?php
    $course_handled = $data['course_handled'];

    $stmt = $conn->prepare("SELECT week, AVG(job_knowledge) as job_knowledgeAVG, AVG(dependability) as dependabilityAVG,
    AVG(communication_skills) as communication_skillsAVG, AVG(conduct) as conductAVG, AVG(initiative_and_creativity) as initiative_and_creativityAVG, 
    AVG(cooperatives_and_relationship) as cooperatives_and_relationshipAVG, 
    AVG(attendance_and_punctuality) as attendance_and_punctualityAVG FROM stud_evaluation LEFT JOIN students_data ON students_data.id = stud_evaluation.stud_id
    WHERE students_data.stud_course = ? GROUP BY week");
    $stmt->execute([$course_handled]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $weeks = array_map(function($week) {
        return "Week $week";
    }, array_column($rows, 'week'));


    $job_knowledgeAVG = array_column($rows, 'job_knowledgeAVG');

    $dependabilityAVG = array_column($rows, 'dependabilityAVG');

    $communication_skillsAVG = array_column($rows, 'communication_skillsAVG');

    $conductAVG = array_column($rows, 'conductAVG');

    $initiative_and_creativityAVG = array_column($rows, 'initiative_and_creativityAVG');

    $cooperatives_and_relationshipAVG = array_column($rows, 'cooperatives_and_relationshipAVG');

    $attendance_and_punctualityAVG = array_column($rows, 'attendance_and_punctualityAVG');
    ?>


        // Extract PHP arrays for JavaScript use
        var weekData = <?= json_encode($weeks) ?>;
        var job_knowledgeAVG = <?= json_encode($job_knowledgeAVG) ?>;
        var dependabilityAVG = <?= json_encode($dependabilityAVG) ?>;
        var communication_skillsAVG = <?= json_encode($communication_skillsAVG) ?>;
        var conductAVG = <?= json_encode($conductAVG) ?>;
        var initiative_and_creativityAVG = <?= json_encode($initiative_and_creativityAVG) ?>;
        var cooperatives_and_relationshipAVG = <?= json_encode($cooperatives_and_relationshipAVG) ?>;
        var attendance_and_punctualityAVG = <?= json_encode($attendance_and_punctualityAVG) ?>;

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

    <?php
    $course_handled = $data['course_handled'];

    $stmt = $conn->prepare("SELECT AVG(total_points) as total_pointsAVG FROM stud_evaluation
    LEFT JOIN students_data ON students_data.id = stud_evaluation.stud_id WHERE students_data.stud_course = ?");
    $stmt->execute([$course_handled]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



    $total_pointsAVG = array_column($rows, 'total_pointsAVG');

    ?>


        var total_pointsAVG = <?= json_encode($total_pointsAVG) ?>;

    // single bar chart
	var ctx = document.getElementById( "singelBarChart" );
	ctx.height = 150;
	var myChart = new Chart( ctx, {
		type: 'bar',
		data: {
			datasets: [
				{
                    label: "Performance Rating Percentage",
					data: total_pointsAVG,
					borderColor: "rgba(50, 205, 50, 1)",
                    borderWidth: "0",
                    backgroundColor: "rgba(50, 205, 50, 0.5)"
                            }
                        ]
		},
		options: {
			scales: {
				yAxes: [ {
					ticks: {
						beginAtZero: true
					}
                                } ]
			}
		}
	} );



    <?php
    $course_handled = $data['course_handled'];

    $stmt = $conn->prepare("SELECT total_points FROM stud_evaluation
        LEFT JOIN students_data ON students_data.id = stud_evaluation.stud_id WHERE students_data.stud_course = ?");
    $stmt->execute([$course_handled]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract total points
    $total_points = array_column($rows, 'total_points');

    // Count occurrences of each total point value
    $total_points_count = array_count_values($total_points);

    // Prepare data for chart
    $labels = array_keys($total_points_count); // Unique total points as labels
    $counts = array_values($total_points_count); // Frequency of each total point value
    ?>

var labels = <?= json_encode($labels) ?>;
    var counts = <?= json_encode($counts) ?>;

    var ctx = document.getElementById("singelBarChart2");
    ctx.height = 150;
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Performance Rating Frequency",
                data: counts,
                borderColor: "rgba(255, 165, 0, 1)", // Orange color
                borderWidth: "0",
                backgroundColor: "rgba(255, 165, 0, 0.5)"
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });


    <?php
    $course_handled = $data['course_handled'];
    $task_status = 'Finished';

    $stmt = $conn->prepare("SELECT COUNT(task_status) AS finished_tasks FROM stud_task_list
    LEFT JOIN students_data ON students_data.id = stud_task_list.stud_id WHERE students_data.stud_course = ? AND stud_task_list.task_status = ?");
    $stmt->execute([$course_handled, $task_status]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



    $finished_tasks = array_column($rows, 'finished_tasks');

    ?>


        var finished_tasks = <?= json_encode($finished_tasks) ?>;

    // single bar chart
	var ctx = document.getElementById( "singelBarChart3" );
	ctx.height = 150;
	var myChart = new Chart( ctx, {
		type: 'bar',
		data: {
			datasets: [
				{
                    label: "Task Accomplishments",
					data: finished_tasks,
					borderColor: "rgba(0, 123, 255, 0.9)",
					borderWidth: "0",
					backgroundColor: "rgba(0, 123, 255, 0.5)"
                            }
                        ]
		},
		options: {
			scales: {
				yAxes: [ {
					ticks: {
						beginAtZero: true
					}
                                } ]
			}
		}
	} );


    

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

<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1 }
        
        slides[slideIndex - 1].style.display = "block";
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        dots[slideIndex - 1].className += " active";
        
        setTimeout(showSlides, 3000); 
    }

    function plusSlides(n) {
        slideIndex += n - 1;
        showSlides();
    }

    function currentSlide(n) {
        slideIndex = n - 1; 
        showSlides();
    }

    setInterval(showSlides, 3000);
</script>

<style>
    * {box-sizing:border-box}

    .slideshow-container {
    max-width: 1000px;
    position: relative;
    margin: auto;
    }

    .mySlides {
    display: none;
    }

    .prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    margin-top: -22px;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    }

    .dot {
    cursor: pointer;
    height: 10px;
    width: 10px;
    margin: 0 2px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
    }

    .chart {
        display: flex;
        justify-content: center;
    }

    .active, .dot:hover {
    background-color: #717171;
    }

    .fade {
    animation-name: fade;
    animation-duration: 1.5s;
    }

    @keyframes fade {
    from {opacity: .4}
    to {opacity: 1}
    }

    .faq-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .faq-item {
      background-color: white;
      margin: 20px 0;
      border-radius: 5px;
      overflow: hidden;
      transition: all 0.8s ease;
    }

    .faq-header {
        padding: 15px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        background-color: rgb(104, 104, 104);
        color: white;
        display: flex;
        justify-content: space-between; 
        align-items: center; 
        transition: background-color 0.3s ease;
    }

    .faq-header:hover {
        background-color: rgb(152, 152, 152);
        color: #000;
    }

    .faq-header span {
        font-size: 24px; 
        font-weight: 200;
        transition: transform 0.3s ease;
    }

    .faq-item.active .faq-header span {
        transform: rotate(180deg); 
    }

    .faq-content {
        padding: 15px;
        display: block;
        background-color: rgb(255, 255, 255);
        font-size: 16px;
        color: #000;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.8s ease, opacity 0.8s ease;
        }

    .faq-item.active .faq-content {
        max-height: 1000px; 
        opacity: 1;
        }


    .dashboard {
        max-width: 62.5rem;
        margin: 0 auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center; 
        gap: 100px;
    }

    .dashboard-content {
        min-width: 150px;
        min-height: 150px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .dashboard-content img {
        height: 50px;
        display: block;
        margin: 0 auto;
    }


    .dashboard > :nth-child(1) {
        border: 3px solid #0054B2; /* First child border */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8); /* Shadow on right and bottom */
    }

    .dashboard > :nth-child(2) {
        border: 3px solid #DA7700; 
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8); /* Shadow on right and bottom */
    }

    .dashboard > :nth-child(3) {
        border: 3px solid #EAE100; 
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8); /* Shadow on right and bottom */
    }

    .dashboard > :nth-child(4) {
        border: 3px solid #419D00; 
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.8); /* Shadow on right and bottom */
    }

    .stat {
        align-self: center;
        justify-self: center;
    }

    .deadline-container {
    width: 300px;
    border-radius: 10px;
    overflow: hidden;
    font-family: relative ;
    box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
    }

    .header {
    background-color: #8B0000;
    color: #ffffff;;
    padding: 15px 20px;
    font-size: 18px;
    position: relative;
    margin: 0 auto;
    text-align: center;
    }

    .header::before {
    content: '';
    position: absolute;
    top: 10px;
    left: 10px;
    width: 8px;
    height: 8px;
    background-color: black;
    border-radius: 50%;
    }

    .content {
    background-color: #f0f0f0;   
    color: #000000; 
    padding: 30px 20px;
    text-align: center;
    font-size: 24px;
    }
</style>

</body>

</html>
