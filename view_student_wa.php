<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if($_SESSION['auth_user']['coordinators_id']==0) {
    echo"<script>window.location.href='index.php'</script>";
}

$studID = isset($_GET['student_ID']) ? $_GET['student_ID'] : '';
if (empty($studID)) {
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students_data WHERE student_ID = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Student not found";
    exit;
}
?>

<DOCTYPE! html>

<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Student Profile</title>
    <!-- ================= Favicon ================== -->
    <link rel="shortcut icon" href="images/Picture1.png">
    
    <!-- Common -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    .back-btn {
        border: none;
        margin: 10px 10px 25px 0px;
        font-size: 20px;
        background-color: #fff;
    }

    .student_info {
        margin: 50px 0 30px 0;
    }

    .detail-row {
        display: flex;
    }

    .label {
        flex: 0 0 150px;
        font-weight: 500;
        color: black;
    }

    .studData {
        flex: 1;
        color: black;
    }

    .search-cont {
        display: flex;
        justify-content: end;
        gap: 5px;
    }

    .search-box {
        width: 30%;
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
        margin: 30px 0 30px 0; 
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
    .trainee-table th[colspan="5"] { 
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
            border: 2px solid #fff; 
            font-weight: 600; 
            border: 2px solid #700000;
        }
        tbody tr:nth-child(odd){
            color: #000;
            text-align: center;
            padding: 20px 50px; 
            border: 2px solid #fff; 
            font-weight: 600; 
            background-color:rgb(221, 218, 218);
            border: 2px solid #700000;
        }
        
    
</style>

<body>
    <!---------NAVIGATION BAR-------->
    <?php
    require_once 'templates/coordinators_navbar.php';
    ?>
    <!---------NAVIGATION BAR ENDS--------> 
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="page-header">
            <button class="back-btn" onclick="backBtn()">
                <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>
            </div>
            <div class="page-title">
                <h1 style="font-size: 16px;"><b>Weekly Accomplishment</b></h1>
            </div>
            <div class="student_info">
                <div class="detail-row">
                    <div class="label">
                        <h6><b>Name</b></h6>
                    </div>
                    <div class="studData">
                        <?php
                        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['middle_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
                        echo htmlspecialchars(": $fullName" ?: 'N/A');
                        ?>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label">
                        <h6><b>Section</b></h6>
                    </div>
                    <div class="studData">
                        <?php 
                            echo htmlspecialchars(": " . $data['stud_section'] ?: 'N/A');
                        ?>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label">
                        <h6><b>HTE</b></h6>
                    </div>
                    <div class="studData">
                        <?php 
                            echo htmlspecialchars(" : " . $data['stud_hte'] ?: 'N/A');
                        ?>
                    </div>
                </div>
            </div>

            <div class="search-cont">
                <input type="text" class="search-box" placeholder="Search...">
                <button class="search-btn">Search</button>
            </div>
            
            <div class="table-container">
                <table class="trainee-table">
                    <thead>
                        <tr>
                            <th colspan="5">Weekly Accomplishment Report</th>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>Week #</th>
                            <th>Daily Accomplishment Report</th>
                            <th>Number of Co-workers in activity</th>
                            <th>No. of Working hrs</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>04/15/2025</td>
                        <td>1</td>
                        <td>Build System Units</td>
                        <td>2</td>
                        <td>8 hrs</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
        $(document).ready(function() {
            $('.search-btn').on('click', function() {
                var searchTerm = $('#traineeSearch').val().toLowerCase(); 
                $('.trainee-table tbody tr').each(function() {
                    var fullName = $(this).find('td:eq(2)').text().toLowerCase(); 
                    if (fullName.includes(searchTerm)) {
                        $(this).show(); 
                    } else {
                        $(this).hide(); 
                    }
                });
            });
 
            $('#traineeSearch').on('input', function() {
                if ($(this).val() === '') {
                    $('.trainee-table tbody tr').show(); 
                }
            });
        });

            $(".view-profile").on("click", function() {
            var studID = $(this).closest("tr").find("td:nth-child(2)").text().trim();
            window.location.href = "view_student_wa.php?student_ID=" + encodeURIComponent(studID)
        });

        function backBtn() {
            window.history.back();
        }
    </script>
</html>