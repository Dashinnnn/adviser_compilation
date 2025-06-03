<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if ($_SESSION['auth_user']['coordinators_id'] == 0) {
    echo "<script>window.location.href='index.php'</script>";
}

$coordinatorID = $_SESSION['auth_user']['coordinators_id'];
$stmt = $conn->prepare("SELECT assigned_section, second_assigned_section FROM coordinators_account WHERE id = ?");
$stmt->execute([$coordinatorID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data === false || (!isset($data['assigned_section']) && !isset($data['second_assigned_section']))) {
    die("Error: No assigned sections found for coordinator ID $coordinatorID");
}

$assignedSection = $data['assigned_section'] ?? null;
$secondAssignedSection = $data['second_assigned_section'] ?? null;

// Fetch students for first section
$studentsFirstSection = [];
if (!empty($assignedSection)) {
    $stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_section = ? AND is_working_student = 'yes' AND verification_status = 'accept'");
    $stmt->execute([$assignedSection]);
    $studentsFirstSection = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch students for second section
$studentsSecondSection = [];
if (!empty($secondAssignedSection)) {
    $stmt = $conn->prepare("SELECT * FROM students_data WHERE stud_section = ? AND is_working_student = 'yes' AND verification_status = 'accept'");
    $stmt->execute([$secondAssignedSection]);
    $studentsSecondSection = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>OJT Web Portal: Internship Documentations</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/lib/font-awesome.min.css" rel="stylesheet">
        <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
        <link href="css/lib/bootstrap.min.css" rel="stylesheet">
        <link href="css/lib/helper.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
        <link href="css/lib/themify-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .back-btn {
                font-size: 20px;
                margin: 20px 0 30px 0;
                color: #7f8c8d;
                cursor: pointer;
            }

            .back-btn:hover {
                color: rgb(0, 0, 0);
            }

            .search-cont { 
                display: flex;
                flex-direction: row;
                justify-content: end; 
                gap: 10px;
            }
            .search-box {
                width: 40%;
                padding: 8px; 
                border-radius: 4px; 
                border: 2px solid #8B0000; 
            }
            .search-btn {
                padding: 8px 20px; 
                background-color: #8B0000; 
                color: white; 
                border: none; 
                border-radius: 4px; 
                cursor: pointer; 
            }

            .table-container { 
                width: 100%; 
                overflow-x: auto; 
                margin-bottom: 20px; 
            }
            .trainee-table { 
                width: 100%; 
                border-collapse: collapse;
            }
            .trainee-table th { 
                background-color: #fff; 
                color: #700000; 
                text-align: center; 
                padding: 20px 50px; 
                min-width: 300px; 
                border: 2px solid #700000; 
                font-weight: 600; 
            }
            .trainee-table th[colspan="6"] { 
                background-color: #700000;
                color: #fff; 
                text-align: center; 
                padding: 20px 50px; 
                border: 2px solid #fff; 
                font-weight: 600; 
            }
            tbody tr td {
                color: #000;
                text-align: center;
                padding: 20px 50px; 
                border: 2px solid #700000;
                font-weight: 600; 
            }
            tbody tr:nth-child(odd){
                color: #000;
                text-align: center;
                padding: 20px 50px; 
                border: 2px solid #700000;
                background-color: rgb(221, 218, 218);
            }
            .view-profile {
                background-color: #8B0000;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
            }
            .section-container {
                margin-bottom: 40px;
            }
        </style>
    </head>

    <body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">

            <div class="back-btn" onclick="backBtn()">
                <i class="fa-solid fa-arrow-left"></i> Back
            </div>

            <!-- First Section -->
            <?php if (!empty($assignedSection)): ?>
                <div class="section-container">
                    <div class="page-header">
                        <div class="page-title"><br><h1 style="font-size: 16px;"><b>Internship Update (Photo Document)</b></h1><br><br></div>
                    </div>

                    <div class="search-cont">
                        <input type="text" class="search-box" placeholder="Search trainee..." id="traineeSearchFirst">
                        <button class="search-btn" data-table="first">Search</button>
                    </div>
                    <br><br>

                    <div class="table-container">
                        <table class="trainee-table" id="table-first">
                            <thead>
                                <tr>
                                    <th colspan="6">SECTION <?php echo htmlspecialchars($assignedSection); ?></th>
                                </tr>
                                <tr>
                                    <th>REF. ID</th>
                                    <th>COMPANY NAME</th>
                                    <th>SIS NO.</th>
                                    <th>FuULL NAME</th>
                                    <th>STATUS</th>
                                    <th>VIEW P.D.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($studentsFirstSection)): ?>
                                    <?php foreach ($studentsFirstSection as $trainee): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trainee['id'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['stud_hte'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['student_ID'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($trainee['first_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['middle_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['last_name'] ?? ''); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($trainee['ojt_status'] ?? 'N/A'); ?></td>
                                            <td><button class="view-profile">View </button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No students found in section <?php echo htmlspecialchars($assignedSection); ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <br><br>
            <!-- Second Section -->
            <?php if (!empty($secondAssignedSection)): ?>
                <div class="section-container">
                    <div class="search-cont">
                        <input type="text" class="search-box" placeholder="Search trainee..." id="traineeSearchSecond">
                        <button class="search-btn" data-table="second">Search</button>
                    </div>
                    <br><br>

                    <div class="table-container">
                        <table class="trainee-table" id="table-second">
                            <thead>
                                <tr>
                                    <th colspan="6">SECTION <?php echo htmlspecialchars($secondAssignedSection); ?></th>
                                </tr>
                                <tr>
                                    <th>REF. ID</th>
                                    <th>COMPANY NAME</th>
                                    <th>SIS NO.</th>
                                    <th>FULL NAME</th>
                                    <th>STATUS</th>
                                    <th>VIEW P.D</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($studentsSecondSection)): ?>
                                    <?php foreach ($studentsSecondSection as $trainee): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trainee['id'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['stud_hte'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($trainee['student_ID'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($trainee['first_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['middle_name'] ?? ''); ?>
                                                <?php echo htmlspecialchars($trainee['last_name'] ?? ''); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($trainee['ojt_status'] ?? 'N/A'); ?></td>
                                            <td><button class="view-profile">View</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No students found in section <?php echo htmlspecialchars($secondAssignedSection); ?>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>

    <script>
        $(document).ready(function() {
            $('.search-btn').on('click', function() {
                var tableId = $(this).data('table');
                var searchTerm = $('#traineeSearch' + tableId.charAt(0).toUpperCase() + tableId.slice(1)).val().toLowerCase();
                $('#table-' + tableId + ' tbody tr').each(function() {
                    var fullName = $(this).find('td:eq(3)').text().toLowerCase(); 
                    if (fullName.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('.search-box').on('input', function() {
                var tableId = $(this).attr('id').includes('First') ? 'first' : 'second';
                if ($(this).val() === '') {
                    $('#table-' + tableId + ' tbody tr').show();
                }
            });
        });

        $(".view-profile").on("click", function() {
            var studID = $(this).closest("tr").find("td:nth-child(1)").text().trim(); 
            window.location.href = "view_student_pd.php?id=" + encodeURIComponent(studID);
        });

        function backBtn() {
            window.history.back();
        }
    </script>
    </body>
</html>