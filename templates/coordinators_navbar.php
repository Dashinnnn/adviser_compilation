<?php
$result = null;
$profileImage = 'student/images/profile.png';

if (isset($_SESSION['auth_user']['coordinators_id']) && !empty($_SESSION['auth_user']['coordinators_id'])) {
    $studID = $_SESSION['auth_user']['coordinators_id'];
    $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE id = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->errorInfo()[2]);
    }
    $stmt->execute([$studID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $profileImage = $result['coordinators_profile_picture'] ?: 'student/images/profile.png';
    } else {
        error_log("No coordinator found for ID: $studID");
    }
}
?>

<nav class="nav-1">
    <img src="images/pupLogo.png" alt="PUP Logo" class="nav-logo">
    <div class="nav-title-caption-container">
        <div class="nav-title">Polytechnic University of the Philippines-ITECH</div>
    </div>
    <div class="user">
        <div class="header-icon">
            <div class="avatar-trigger" data-toggle="dropdown">
                <div class="user-info">
                    <span class="user-name">
                        <?php echo $result ? htmlspecialchars($result['first_name'] ?? 'N/A') : 'Guest'; ?>
                    </span>
                    <span class="schoolID">
                        <?php echo $result ? htmlspecialchars($result['faculty_id'] ?? 'N/A') : 'N/A'; ?>
                    </span>
                </div>
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Avatar" class="avatar-img">
            </div>
            <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right">
                <div class="dropdown-content-body">
                    <ul>
                        <li>
                            <a href="#" onclick="profile();">
                                <i class="ti-user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="settings();">
                                <i class="ti-settings"></i>
                                <span>Setting</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="logout();">
                                <i class="ti-power-off"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>		
</nav>
<div>
    <div>
        <div class="sidenav">
            <?php
            // Get the current page filename
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>
            <ul>
                <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><img src="images/home.png"> Home </a></li>
                <li><a href="coordinators_profile.php" class="<?php echo ($current_page == 'coordinators_profile.php') ? 'active' : ''; ?>"><img src="images/profile.png"> Profile </a></li>
                <li><a href="coordinators_notification.php" class="<?php echo ($current_page == 'coordinators_notification.php') ? 'active' : ''; ?>"><img src="images/notification.png"> Notifications </a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle"><img src="images/message.png"> Messages</a>
                    <div class="dropdown-content" <?php echo (in_array($current_page, ['stud_message.php', 'chat_supervisor.php', 'chat_faculty.php', 'chat_admin.php'])) ? 'style="display: block;"' : ''; ?>>
                        <a href="stud_message.php" class="<?php echo ($current_page == 'stud_message.php') ? 'active' : ''; ?>"><img src="../student/images/student.png"> Student</a>
                        <a href="chat_faculty.php" class="<?php echo ($current_page == 'chat_faculty.php') ? 'active' : ''; ?>"><img src="../student/images/faculty.png"> Faculty</a>
                        <a href="chat_supervisor.php" class="<?php echo ($current_page == 'chat_supervisor.php') ? 'active' : ''; ?>"><img src="../student/images/supervisor.png"> Supervisor</a>
                        <a href="chat_admin.php" class="<?php echo ($current_page == 'chat_admin.php') ? 'active' : ''; ?>"><img src="../student/images/admin.png"> Admin</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo (in_array($current_page, ['appointment_meeting.php', 'stud_verification.php'])) ? 'active' : ''; ?>"><img src="../student/images/todo.png"> To do</a>
                    <div class="dropdown-content" <?php echo (in_array($current_page, ['appointment_meeting.php', 'stud_verification.php'])) ? 'style="display: block;"' : ''; ?>>
                        <a href="appointment_meeting.php" class="<?php echo ($current_page == 'appointment_meeting.php') ? 'active' : ''; ?>"><img src="../student/images/search.png"> Appointment Meetings</a>
                        <a href="stud_verification.php" class="<?php echo ($current_page == 'stud_verification.php') ? 'active' : ''; ?>"><img src="images/badge.png">Student's Verification</a>

                    </div>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo (in_array($current_page, ['Trainees.php', 'stud_endorsement_validation.php', 'internship_update.php', 'list_of_student_portfolio.php', 'coordinator_eval.php', 'list_coc.php'])) ? 'active' : '';?>">Students</a>
                    <div class="dropdown-content <?php echo(in_array($current_page, ['Trainees.php', 'stud_endorsement_validation.php', 'internship_update.php', 'list_of_student_portfolio.php', 'coordinator_eval.php', 'list_coc.php'])) ? 'style="display: block;"' : '';?>">
                        <a href="Trainees.php" class="<?php echo ($current_page == 'Trainees.php') ? 'active' : '';?>"> <img src="images/placeholder.png">Trainees</a>
                        <a href="stud_endorsement_validation.php" class="<?php echo ($current_page == 'stud_endorsement_validation.php') ? 'active' : '';?>"><img src="images/document.png">Endorsement Paper</a>
                        <a href="internship_update.php" class="<?php echo ($current_page == 'internship_update.php') ? 'active' : '';?>"><img src="../student/images/internship_docs.png">Internship Update</a>
                        <a href="coordinator_eval.php" class="<?php echo ($current_page == 'coordinator_eval.php') ? 'active' : '';?>"><img src="../student/images/evaluation.png">Evaluation</a>
                        <a href="list_of_student_portfolio.php" class="<?php echo ($current_page == 'list_of_student_portfolio.php') ? 'active' : '';?>"><img src="../student/images/portfolio.png">Portfolio</a>
                        <a href="list_coc.php" class="<?php echo ($current_page == 'list_coc.php') ? 'active' : '';?>"><img src="../student/images/certificate.png">Certificate of Completion</a>
                    </div>
                    
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo (in_array($current_page, ['pre_internship_papers.php', 'working_stud_updates.php', 'working_stud_portfolio.php', 'list_of_working_students.php', 'working_stud_eval.php', 'working_stud_list_coc.php'])) ? 'active' : ''; ?>">Working Students</a>
                    <div class="dropdown-content" <?php echo (in_array($current_page, ['pre_internship_papers.php', 'working_stud_updates.php', 'working_stud_portfolio.php', 'list_of_working_students.php', 'working_stud_eval.php', 'working_stud_list_coc.php'])) ? 'style="display: block;"' : ''; ?>>
                        <a href="list_of_working_students.php" class="<?php echo  ($current_page == 'list_of_working_students.php') ? 'active' : ''; ?>"> <img src="images/placeholder.png">Working Students</a>
                        <a href="pre_internship_papers.php" class="<?php echo  ($current_page == 'pre_internship_papers.php') ? 'active' : ''; ?>"><img src="images/document.png">Pre-internship Paper</a>
                        <!-- <a href="working_stud_eval.php" class="<?php echo ($current_page == 'working_stud_eval.php') ? 'active' : '';?>">Evaluation</a> -->
                        <a href="working_stud_updates.php" class="<?php echo ($current_page == 'working_stud_updates.php') ? 'active' : ''; ?>"><img src="../student/images/internship_docs.png">Internship Update</a>
                        <a href="working_stud_portfolio.php" class="<?php echo ($current_page == 'working_stud_portfolio.php') ? 'active' : '';?>"><img src="../student/images/portfolio.png">Portfolio</a>
                        <a href="working_stud_list_coc.php" class="<?php echo ($current_page == 'working_stud_list_coc.php') ? 'active' : '';?>"><img src="../student/images/certificate.png">Certificate of Completion</a>
                    </div>
                </li>


            </ul>
        </div>
    </div>
</div>

<style>
    .header-icon {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    
    .avatar-trigger {
        display: flex;
        align-items: center;
    }
    
    .user-name {
        font-weight: 500;
        color: #000;
    }
    
    .avatar-img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .nav-1 {
        font-family: 'Source Serif 4', serif;
        background: #fff;
        border-bottom: 2px solid rgba(68, 68, 68, 0.66);
        color: #D11010;
        text-align: left;
        align-items: center;
        font-size: 20px;
        font-weight: 400;
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        display: flex;
        align-items: left;
        margin-bottom: 20px;
        background-clip: padding-box;
        z-index: 1000;
    }

    .nav-logo {
        height: 50px;
        margin-left: 20px;
    }

    .nav-title-caption-container {
        display: flex;
        margin-left: 20px;
    }

    .nav-title {
        font-size: 24px;
        font-weight: bold;
    }

    .sidenav {
        width: 15%;
        background: #fff;
        border-right: 2px solid rgba(68, 68, 68, 0.66);
        height: 100%;
        top: 0;
        position: fixed; 
        padding-top: 20px;
        padding-right: 20px;
        margin-top: 75px;
        z-index: 1;
        overflow-y: scroll;
    }

    .sidenav img {
        height: 20px;
        margin-right: 10px;
        filter: brightness(0) invert(0);
    }
    
    .sidenav ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    
    .sidenav a {
        padding: 10px 16px;
        text-decoration: none;
        font-size: 14px;
        color: rgb(0, 0, 0);
        display: flex;
        align-items: center;
        border-top-left-radius: 0px;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        border-bottom-left-radius: 0px;
    }

    .sidenav a:hover {
        color: #f1f1f1;
        background-color: #700000;
    }

    .sidenav a:not(.dropdown-content a):active {
        color: #f1f1f1;
        background-color: #700000;
    }

    .sidenav a.active {
        color: #f1f1f1;
        background-color: #700000;
    }

    .sidenav a:hover img,
    .sidenav a.active img {
        filter: brightness(0) invert(1);
    }
    
    .dropdown-toggle {
        align-items: left;
        width: 100%;
    }

    .sidenav .dropdown-toggle.active {
        color: #f1f1f1 !important;
        background-color: #700000 !important;
    }
    
    .sidenav .dropdown-toggle.active img {
        filter: brightness(0) invert(1) !important;
    }
    
    .dropdown-arrow {
        margin-left: 5px;
    }

    .user {
        margin-right: 20px;
        margin-left: auto;
    }
    
    .main {
        margin-left: 160px; 
        padding: 0px 10px;
    }   

    .user-info {
        display: flex; 
        flex-direction: column; 
        color: #000; 
        align-items: center; 
        justify-content: center; 
        margin-right: 20px; 
        font-family: 'Arial', sans-serif; 
        font-size: 14px; 
    }

    .user-name {
        margin-bottom: 10px;
    }

    .dropdown-content {
        display: none;
        min-width: 100%;
        padding: 0;
        z-index: 1;
        background-color: #fff;
    }
    
    .dropdown-content a {
        padding: 8px 16px 8px 24px;
        text-decoration: none;
        font-size: 13px;
        color: rgb(0, 0, 0);
        display: flex;
        align-items: center;
        background-color: #fff;
    }

    .dropdown-content a:hover {
        color: #f1f1f1;
        background-color: #700000;
    }

    .dropdown-content a:active,
    .dropdown-content a.active {
        background-color: #fff;
        color: #000;
    }

    @media screen and (max-width: 768px) {
        .sidenav {
            width: 100%;
            height: auto;
            position: relative;
            margin-top: 60px;
            padding-top: 10px;
            border-right: none;
            border-bottom: 2px solid rgba(68, 68, 68, 0.66);
        }
        
        .main {
            margin-left: 0;
            padding-top: 20px;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const $dropdownToggles = $(".dropdown-toggle");
        
        $dropdownToggles.click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $parent = $(this).parent();
            const $dropdownContent = $(this).siblings(".dropdown-content");
            const $arrow = $(this).find(".dropdown-arrow");
            const isActive = $parent.hasClass("active");
            
            if (isActive) {
                $parent.removeClass("active");
                $dropdownContent.slideUp(200);
                $arrow.text("▼");
            } else {
                $parent.addClass("active");
                $dropdownContent.slideDown(200);
                $arrow.text("▲");
            }
        });
        
        // Keep dropdowns open if they contain active items
        $(".dropdown-content").each(function() {
            if ($(this).find("a.active").length > 0) {
                $(this).css("display", "block");
                $(this).parent().addClass("active");
            }
        });
    });
</script>

<!-----------AUTO LOGOUT INACTIVITY--------------->
<script>
    var userId = <?php echo $_SESSION['auth_user']['coordinators_id'] ?? 0; ?>;
    var logoutTimeout;

    function startLogoutTimer() {
        logoutTimeout = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: 'update_status_AutoLogOut.php',
                data: { userId: userId },
                success: function (response) {
                    window.location.href = 'index.php';
                },
                error: function (xhr, status, error) {
                    console.error('Auto logout failed:', error);
                }
            });
        }, 360000);
    }

    function resetLogoutTimer() {
        clearTimeout(logoutTimeout);
        startLogoutTimer();
    }

    startLogoutTimer();

    document.addEventListener('mousemove', resetLogoutTimer);
    document.addEventListener('keydown', resetLogoutTimer);
</script>

<script>    
    function profile() {
        window.location.href = 'coordinators_profile.php';
    }
    function settings() {
        window.location.href = 'coordinators_settings.php';
    }
    function logout() {
        window.location.href = 'coordinators_logout.php';
    }
</script>