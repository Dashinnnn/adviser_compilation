<?php
include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['auth_user']['student_id']) || $_SESSION['auth_user']['student_id']==0){
    echo"<script>window.location.href='index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Find your H.T.E</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <!-- Styles -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        .search-container {
            margin-bottom: 20px;
        }
        .search-box {
            width: 75%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .search-button {
            padding: 8px 20px;
            background-color: #8B0000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .option-button {
            padding: 8px 15px;
            margin-right: 10px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .working-student {
            background-color: #8B0000;
            color: white;
        }
        .new-moa {
            background-color: #8B0000;
            color: white;
        }
        .plus-icon {
            margin-right: 5px;
            font-weight: bold;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 20px;
        }
        .company-table {
            width: 100%;
            border-collapse: collapse;
        }
        .company-table th {
            background-color: #fff;
            color: #700000;
            text-align: center;
            padding: 20px 50px;
            min-width: 300px;
            border: 2px solid #700000;
            font-weight: 600;
        }
        .company-table td {
            padding: 20px 50px;
            border: 2px solid #700000;
            text-align: center;
            color: #000;
        }
        .company-table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .view-profile-btn {
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .step-description {
            margin-bottom: 20px;
            color: #000;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!---------NAVIGATION BAR-------->
    <?php require_once 'templates/stud_navbar.php'; ?>
    <!---------NAVIGATION BAR ENDS-------->

    <div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div>
                <div>
                    <div>
                        <div class="page-header">
                            <div class="page-title"><br>
                                <h1 style="font-size: 16px;"><b>FIND H.T.E</b></h1><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-description">
                    <p style="color: #000;">At this step, you're going to choose your desired H.T.E. The database provided in the table below lists those that already have an agreement (MOA) with our university. Therefore, you don't need to apply for a new <strong>Memorandum of agreement (MOA)</strong>. Simply download the MOA attached to your chosen H.T.E. and proceed to the next steps.</p>
                    
                    <p style="color: #000;">If your desired H.T.E. is not on the list, you'll need to apply for a new MOA. If so, you can click the "<strong>NEW MOA</strong>" button, and further steps will be provided for you.</p>
                    
                    <p style="color: #000;">But if you have a job related to your course, you can click the "<strong>Working Student</strong>" button.</p>
                </div>

                <br>
                
                <div class="search-container">
                    <input type="text" class="search-box" placeholder="Search Company name here...." id="companySearch">
                    <button class="search-button">Search</button>
                </div>
                
                <br>
                
                <div style="margin-bottom: 20px;">
                    <button class="option-button working-student">
                        <span class="plus-icon">+</span> Working Student
                    </button>
                    <button class="option-button new-moa">
                        <span class="plus-icon">+</span> New MOA
                    </button>
                </div>
                
                <div class="table-container">
                <table class="company-table">
                    <thead>
                        <tr>
                            <th class="table-head">Company Profile</th>
                            <th class="table-head">Company Name</th>
                            <th class="table-head">Nature of Business</th>
                            <th class="table-head">Contact Person</th>
                            <th class="table-head">Position</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Company Address</th>
                            <th class="table-head">Date Notarized and Validity</th>
                            <th class="table-head">Linked to Scanned MOA   </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $stmt = $conn->prepare("
                            SELECT 
                                supervisor.id AS supervisorID, 
                                supervisor.supervisor_email, 
                                supervisor.company_address, 
                                supervisor.link_to_moa,  
                                supervisor.company_name AS company_name, 
                                supervisor.phone_number, 
                                supervisor.position, 
                                CONCAT(date_notarized, ' ', moa_validity) AS moa_info, 
                                CONCAT(first_name, ' ', middle_name, ' ', last_name) AS contact_person, 
                                supervisor.business_nature, 
                                GROUP_CONCAT(company_skills_requirements.skills_name) AS skills 
                            FROM supervisor 
                            LEFT JOIN company_skills_requirements 
                                ON supervisor.company_name = company_skills_requirements.company_name 
                            GROUP BY supervisor.id, supervisor.position
                        ");
                        $stmt->execute();
                        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($companies as $company) {
                            echo '<tr>';
                            echo '<td><button class="view-profile-btn">View Profile</button></td>';
                            echo '<td>' . htmlspecialchars($company['company_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['business_nature']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['contact_person']) . '<br>' . 
                                htmlspecialchars($company['phone_number']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['position']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['supervisor_email']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['company_address']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['moa_info']) . '</td>';
                            echo '<td>' . htmlspecialchars($company['link_to_moa']) . '</td>';
                            echo '</tr>';
                        }

                        
                        // Example static data matching the image (in case the database query returns no results)
                        if ($stmt->rowCount() == 0) {
                            echo '<tr>';
                            echo '<td><button class="view-profile-btn">View Profile</button></td>';
                            echo '<td>Digital Services Cambridge Limited</td>';
                            echo '<td>Web Development</td>';
                            echo '<td>James Marwin Quindoza<br>9355346790</td>';
                            echo '</tr>';
                            
                            echo '<tr>';
                            echo '<td><button class="view-profile-btn">View Profile</button></td>';
                            echo '<td>Digital Services Cambridge Limited</td>';
                            echo '<td>Web Development</td>';
                            echo '<td>James Marwin Quindoza<br>9355346790</td>';
                            echo '</tr>';
                            
                            echo '<tr>';
                            echo '<td><button class="view-profile-btn">View Profile</button></td>';
                            echo '<td>Digital Services Cambridge Limited</td>';
                            echo '<td>Web Development</td>';
                            echo '<td>James Marwin Quindoza<br>9355346790</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                </div>
                
                <section id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer">
                                <p>2024 © <a href="#">Mabuhay</a></p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>
    
    <script>
        $(document).ready(function() {
            // Search functionality
            $("#companySearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".company-table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            // Button click handlers
            $(".working-student").click(function() {
                window.location.href = "working_student_form.php";
            });
            
            $(".new-moa").click(function() {
                window.location.href = "new_moa_application.php";
            });
            
            $(".view-profile-btn").click(function() {
                var companyName = $(this).closest("tr").find("td:nth-child(2)").text();
                window.location.href = "view_company_profile.php?company=" + encodeURIComponent(companyName);
            });
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