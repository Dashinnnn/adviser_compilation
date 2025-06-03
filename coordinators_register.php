<?php

include '../connection/config.php';
error_reporting(0);

session_start();
?>


<!DOCTYPE html>
<html style="font-size: 16px;" lang="en"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Register - OJT Web Portal</title>
    <link rel="stylesheet" href="css/nicepage.css" media="screen">
<link rel="stylesheet" href="css/Page-3.css" media="screen">
    <script class="u-script" type="text/javascript" src="js/jquery.js" defer=""></script>
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: left;
            padding: 2rem;
            height: 100%;
            width: 100%;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            overflow-y: scroll;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h5 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 50px;  
            align-items: left;
            justify-content: left;
            display: flex;
            line-height: 0.5;
            color: #9B0C0C;
        }

        input,
        select{
            width: 300px;
            height: 50px;
            padding: 10px;
            margin: 10px 10px;
            border: 1px solid #000;
            border-radius: 5px;
            outline: none;
            color: #000;
            font-size: 14px;
        }

        label {
            font-size: 14px;
            font-weight: 400;
            color: #000;
            margin-left: 10px;
        }

        .nav-1 {
            font-family: 'Source Serif 4', serif;
            background: linear-gradient(to left, rgba(155, 12, 12, 1), rgba(255, 255, 255, 1));
            color: #D11010;
            padding: 15px 0;
            text-align: left;
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
            flex-direction: column;
            margin-left: 20px;
        }

        .nav-title {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-caption {
            font-size: 16px;
            color: #000;
            font-weight: normal;
        }
        
        .login {
          width: 1000px;
          margin: 0 auto;
        }

        .register-button {
            background: #9B0C0C;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
            transition: 0.3s;
            width: 100%;
            height: 50px;
        }

        .register-section {
            background: #fff;
            padding: 20px;
            display: grid;
            grid-template-columns: 3fr 8fr;
            gap: 50px;
            line-height: 2;
            margin-top: 100px;
            
            /* Centering */
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
        }


        .register-here {
            font-size: 16px;
            color:#0C0C9B;
        }

        .header {
            position: absolute; 
            top: 100px; 
            left: 20px; 
            display: flex;
            flex-direction: column;
            align-items: left;
            margin-left: 20px;
            margin-bottom: 20px;
            gap: 10px; 
        }

        .back-button {
            display: flex;
            align-items: center;
            background: none;
            color: rgba(128, 128, 128, 0.5);
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: 0.3s;
        }

        .back-button img {
            height: 40px;
            filter: grayscale(100%);
            opacity: 0.3;
            transition: filter 0.3s ease, opacity 0.3s ease;
        }

        h5 {
            display: flex;
            align-items: center;
            margin-left: 18px;
            font-size: 18px;
            font-weight: bold;
        }

        h5 img {
            margin-right: 10px;
            filter: invert(10%) sepia(88%) saturate(5144%) hue-rotate(356deg) brightness(97%) contrast(106%);
        }


      
        .back-button:hover,
        .back-button:hover img {
            color: #9B0C0C;
            filter: grayscale(0%) sepia(100%) hue-rotate(330deg) saturate(500%);
            opacity: 1;
        }

        .row-1, 
        .row-2,
        .row-3,
        .row-4,
        .row-5 {
          display: flex;
          gap: 10px;
        }

        .required {
            color: red;
            font-weight: bold;
            margin-left: 5px;
        }

    </style>
</head>
  <body>
  <nav class="nav-1">
          <img src="images/pupLogo copy.png" alt="PUP Logo" class="nav-logo">
          <div class="nav-title-caption-container">
              <div class="nav-title">Polytechnic University of the Philippines-ITECH</div>
              <div class="nav-caption">On the Job Training Portal</div>
          </div>
      </nav>
    
      <header style="z-index: 10;" class="header">
    <a href="index.php" class="back-button"><img src="images/less-than.png" alt="Back">Back</a>
              <h5 style="align-items:center;">
                <img src="images/pencil.svg" alt="Edit Icon" height="27">
                REGISTRATION
              </h5>
    </header>

    <section id="sec-113a" class="register-section">
      <div>
        <div>
          <div>
            <div class="u-form u-form-1">
            <form action="../php/coordinator_registerCode.php" method="POST" enctype="multipart/form-data">

                <div class="row-1">
                    <div>
                      <label for="name-3b9a">First Name <span class="required">*</span></label>
                      <input type="text" placeholder="First Name" id="name-3b9a" name="f_name" required="true">
                      </div>

                      <div>
                        <label for="email-3b9a">Middle Name <span class="required">*</span></label>
                        <input type="text" placeholder="Middle Name" id="email-3b9a" name="m_name" required="true">
                      </div>

                      <div>
                        <label for="text-743c">Last Name<span class="required">*</span></label>
                        <input type="text" placeholder="Last Name" id="text-743c" name="l_name" required="true">
                    </div>
                  </div>

                <div class="row-2">
                    <div>
                      <label for="text-7a3c">Faculty ID<span class="required">*</span></label>
                      <input type="text" placeholder="Faculty ID" id="text-7a3c" name="faculty_ID"  required="true">
                    </div>

                    <div >
                      <label for="select-f65c">Department <span class="required">*</span></label>
                      <div>
                        <select id="select-f65c" name="coor_dept"  required="true">
                          <option value="">Department</option>
                          <option value="College of Engineering">College of Engineering</option>
                          <option value="College of Education">College of Education</option>
                          <option value="College of Arts">College of Arts</option>
                          <option value="College of Science">College of Science</option>
                          <option value="College of Technology">College of Technology</option>
                        </select>
                        </div>
                    </div>

                    <div>
                    <label for="select-f65c">Course handled<span class="required">*</span></label>
                      <div>
                        <select id="select-f65c" name="course_handled"  required="true">
                        <option value="">Course Handled</option>
                        <option value="MS Excel">MS Excel</option>
                        </select>
                        </div>
                    </div>
                </div>

                <div class="row-3">
                    <div>
                      <label for="text-63ba">Address<span class="required">*</span></label>
                      <input type="text" placeholder="Address" id="text-63ba" name="C_address" required="true">
                    </div>
                    
                    <div >
                    <label for="text-fd88">Contact Number<span class="required">*</span></label>
                    <input type="number" placeholder="Contact No." id="text-fd88" name="cpNum" required="true">
                    </div>

                    <div>
                      <label for="text-bb89">Email<span class="required">*</span></label>
                      <input type="text" placeholder="Email" id="text-bb89" name="eMail" required="true">
                    </div>

                </div>

                <div class="row-4">
                    <div>
                      <label for="text-124c">Password<span class="required">*</span></label>
                      <input type="password" placeholder="Password" id="text-124c" name="pword" required="true">
                    </div>
                    
                    <div>
                      <label for="text-da7f">Repeat Password <span class="required">*</span></label>
                      <input type="password" placeholder="Repeat Password" id="text-da7f" name="cpword" required="true">
                    </div>

                    <div>
                      <label for="text-df1c">Image File<span class="required">*</span></label>
                      <input type="file" accept="image/*" id="text-df1c" name="coordinators_pic" required="true">
                    </div>
                </div>

                <br>

                <div>
                  <button name="register" class="register-button">Register</button>
                </div>
                
              </form>
              <br>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
	<script src="js/scripts.js"></script>

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
$(document).ready(function() {
    $('#select-f65c').change(function() {
        var selectedDepartment = $(this).val();
        var courseSelect = $('select[name="course_handled"]');

        // Clear the course options
        courseSelect.empty();

        // Add a default option
        courseSelect.append('<option value="">Course</option>');

        if (selectedDepartment === 'College of Education') {
            courseSelect.append('<option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>');
            courseSelect.append('<option value="Bachelor of Early Childhood Education">Bachelor of Early Childhood Education</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in English minor in Mandarin">Bachelor of Secondary Education Major in English minor in Mandarin</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in Sciences">Bachelor of Secondary Education Major in Sciences</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>');
            courseSelect.append('<option value="Bachelor of Secondary Education Major in Values Education">Bachelor of Secondary Education Major in Values Education</option>');
            courseSelect.append('<option value="Bachelor of Physical Education">Bachelor of Physical Education</option>');
            courseSelect.append('<option value="Bachelor of Technology and Livelihood Education Major in Industrial Arts">Bachelor of Technology and Livelihood Education Major in Industrial Arts</option>');
            courseSelect.append('<option value="Bachelor of Technology and Livelihood Education Major in Information and Communication Technology">Bachelor of Technology and Livelihood Education Major in Information and Communication Technology</option>');
            courseSelect.append('<option value="Bachelor of Technology and Livelihood Education Major in Home Economics">Bachelor of Technology and Livelihood Education Major in Home Economics</option>');
        } else if (selectedDepartment === 'College of Engineering') {
            courseSelect.append('<option value="Bachelor of Science in Civil Engineering">Bachelor of Science in Civil Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Computer Engineering">Bachelor of Science in Computer Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Electrical Engineering">Bachelor of Science in Electrical Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Electronics Engineering">Bachelor of Science in Electronics Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Industrial Engineering">Bachelor of Science in Industrial Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Manufacturing Engineering">Bachelor of Science in Manufacturing Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Mechanical Engineering">Bachelor of Science in Mechanical Engineering</option>');
            courseSelect.append('<option value="Bachelor of Science in Mechatronics Engineering">Bachelor of Science in Mechatronics Engineering</option>');
        } else if (selectedDepartment === 'College of Science') {
            courseSelect.append('<option value="Bachelor of Science in Environmental Science">Bachelor of Science in Environmental Science</option>');
            courseSelect.append('<option value="Bachelor of Science in Food Technology">Bachelor of Science in Food Technology</option>');
            courseSelect.append('<option value="Bachelor of Science in Math with Specialization in Computer Science">Bachelor of Science in Math with Specialization in Computer Science</option>');
            courseSelect.append('<option value="Bachelor of Science in Math with Specialization in Applied Statistics">Bachelor of Science in Math with Specialization in Applied Statistics</option>');
            courseSelect.append('<option value="Bachelor of Science in Math with Specialization in Business Applications">Bachelor of Science in Math with Specialization in Business Applications</option>');
        } else if (selectedDepartment === 'College of Arts') {
            courseSelect.append('<option value="Bachelor of Arts in Broadcasting">Bachelor of Arts in Broadcasting</option>');
            courseSelect.append('<option value="Bachelor of Arts in Journalism">Bachelor of Arts in Journalism</option>');
            courseSelect.append('<option value="Bachelor of Performing Arts (Theater Track)">Bachelor of Performing Arts (Theater Track)</option>');
            courseSelect.append('<option value="Batsilyer ng Sining sa Malikhaing Pagsulat">Batsilyer ng Sining sa Malikhaing Pagsulat</option>');
        } else if (selectedDepartment === 'College of Technology') {
          courseSelect.append('<option value="Diploma in Information Technology (DIT)">Diploma in Information Technology (DIT)</option>');
          courseSelect.append('<option value="Civil Engineering Technology (DCvET)">Civil Engineering Technology (DCvET)</option>');
          courseSelect.append('<option value="Diploma in Computer Engineering Technology (DCET)">Diploma in Computer Engineering Technology (DCET)</option>');
          courseSelect.append('<option value="Diploma in Electrical Engineering Technology (DEET)">Diploma in Electrical Engineering Technology (DEET)</option>');
          courseSelect.append('<option value="Diploma in Electronics Engineering Technology (DECET)">Diploma in Electronics Engineering Technology (DECET)</option>');
          courseSelect.append('<option value="Diploma in Office Management Technology (DOMT)">Diploma in Office Management Technology (DOMT)</option>');
          courseSelect.append('<option value="Diploma in Railway Engineering Technology (DRET)">Diploma in Railway Engineering Technology (DRET)</option>');
        } else {
        }
    });
});
</script>
  
</body></html>