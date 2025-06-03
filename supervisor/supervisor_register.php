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
    <link rel="stylesheet" href="css/Page-4.css" media="screen">
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
        
        h5{
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 50px;  
            align-items: left;
            justify-content: left;
            display: flex;
            line-height: 0.5;
            color: #9B0C0C;
        }

        input{
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
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
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
            color: rgba(48, 48, 48, 0.7);
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: color 0.3s ease;
        }

        .back-button img {
            height: 40px;
            filter: grayscale(100%);
            opacity: 0.6;
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
        .row-3 {
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
          <img src="images/pupLogo.png" alt="PUP Logo" class="nav-logo">
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
            <form action="../php/supervisor_registerCode.php" method="POST" enctype="multipart/form-data">

                 <div class="row-1">
                    <div>
                      <label for="text-1a97">Email <span class="required">*</span></label>
                      <input type="text" placeholder="Email" id="text-1a97" name="eMail" required="true">
                      </div>

                      <div>
                        <label for="text-124c">Password <span class="required">*</span></label>
                        <input type="password" placeholder="Password" id="text-124c" name="pword" required="true">
                      </div>

                      <div>
                        <label for="text-da7f">Repeat Password <span class="required">*</span></label>
                        <input type="password" placeholder="Repeat Password" id="text-da7f" name="cpword" required="true">
                    </div>
                  </div>

                  <div class="row-2">
                    <div>
                      <label for="name-3b9a">First Name <span class="required">*</span></label>
                      <input type="text" placeholder="First Name" id="name-3b9a" name="f_name"  required="true">
                    </div>

                    <div>
                      <label for="email-3b9a">Middle Name <span class="required">*</span></label>
                      <input type="text" placeholder="Middle Name" id="email-3b9a" name="m_name" required="true">
                    </div>

                    <div>
                      <label for="text-743c">Last Name <span class="required">*</span></label>
                      <input type="text" placeholder="Last Name" id="text-743c" name="l_name"  required="true">
                    </div>
                </div>

                <div class="row-3">
                    <div>
                      <label for="text-7a3c">Company Name <span class="required">*</span></label>
                      <input type="text" placeholder="Company Name" id="text-7a3c" name="companyNAME" required="true">
                    </div>
                    
                    <div>
                      <label for="text-bb89">Position <span class="required">*</span></label>
                      <input type="text" placeholder="Contact Number" id="text-bb89" name="position" required="true">
                    </div>

                    <div>
                      <label for="text-fd88">Contact Number <span class="required">*</span></label>
                      <input type="number" placeholder="Contact Number" id="text-fd88" name="cpNum" required="true">
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


</body></html>