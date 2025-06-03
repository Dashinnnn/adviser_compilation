<?php

include '../connection/config.php';
error_reporting(0);

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Focus Admin: Widget</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
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
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="bg-primary">

    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="index.html"><span>OJT Web Portal</span></a>
                        </div>
                        <div class="login-form">
                            <h4>Verify Account</h4>
                            <form action="../php/stud_verifyAccountCode.php" method="POST">
                            <?php
                                  if(isset($_GET['id']))
                                                {
                                                    $id = $_GET['id']; 
                            $stmt = $conn->prepare("SELECT * FROM students_data WHERE id= ?");
                                        $stmt->execute([$id]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                  ?>
                                <input type="hidden" name="studid" value="<?=$row['id']?>" required>
                                <div class="form-group">
                                    <label>Enter Verification Code</label>
                                    <input type="number" class="form-control" name="verification_number" placeholder="Enter 6 digit code" required>
                                </div>
                                <button name="verifyNow" class="btn btn-primary btn-flat m-b-15">Verify</button>
                                <div class="register-link text-center">
                                    <p>Back to <a href="index.php"> Home</a></p>
                                </div>
                                <?php
                                                                    
                                    } 
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

</body>

</html>