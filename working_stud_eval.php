<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['auth_user']['coordinators_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Evaluations</title>
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="endorsement-css/endorsement-moa.css" rel="stylesheet">
    <style>
        .evaluation-container {
            display: flex;
            justify-content: space-between;
            padding: 20px 100px;
            margin-top: 20px;
        }
        .evaluation-card {
            flex: 1;
            margin: 0 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .evaluation-icon {
            margin-bottom: 15px;
        }
        .evaluation-icon img {
            width: 120px;
            height: auto;
        }
        .evaluation-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #000;
        }
        .evaluation-desc {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
            max-width: 300px;
        }
        .evaluate-button {
            background-color: #a00000;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            display: inline-block;
        }
        .evaluate-button:hover {
            background-color: #800000;
            color: white;
            text-decoration: none;
        }
        .page-header {
            margin-bottom: 30px;
        }
        .page-title h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>

    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto; position: relative;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">

        <div class="page-header">
            <div class="page-title">
                <h1 style="font-size: 16px;"><b>Evaluation (Working Student)</b></h1>
            </div>
        </div>
            
        <div class="evaluation-container">
                <div class="evaluation-card">
                    <div class="evaluation-icon">
                        <img src="images/sup-eval.png" alt="HTE Icon" onerror="this.src='https://via.placeholder.com/120?text=Person'" style="filter: sepia(1) saturate(10) hue-rotate(320deg) brightness(0.6) contrast(1.2);">
                    </div>
                    <div class="evaluation-title">
                        Evaluation Instrument for Host Training Establishment (HTE)
                    </div>
                    <div class="evaluation-desc">
                        Evaluate the submitted student evaluation by the student.
                    </div>
                    <a href="coordinator_hte_eval_ws.php" class="evaluate-button">Evaluate Now</a>
                </div>
                
                <div class="evaluation-card">
                    <div class="evaluation-icon">
                        <img src="images/hte-eval.png" alt="Supervisor Icon" onerror="this.src='https://via.placeholder.com/120?text=Building'" style="filter: sepia(1) saturate(10) hue-rotate(320deg) brightness(0.6) contrast(1.2);">
                    </div>
                    <div class="evaluation-title">
                        Evaluation Instrument for Training Supervisor
                    </div>
                    <div class="evaluation-desc">
                        Evaluate the submitted student evaluation by the student.
                    </div>
                    <a href="coordinator_supervisor_eval_ws.php" class="evaluate-button">Evaluate Now</a>
                </div>

                <div class="evaluation-card">
                    <div class="evaluation-icon">
                        <img src="images/hte-eval.png" alt="Supervisor Icon" onerror="this.src='https://via.placeholder.com/120?text=Building'" style="filter: sepia(1) saturate(10) hue-rotate(320deg) brightness(0.6) contrast(1.2);">
                    </div>
                    <div class="evaluation-title">
                        Evaluation Instrument for Student
                    </div>
                    <div class="evaluation-desc">
                        Evaluate the submitted student evaluation by the HTE.
                    </div>
                    <a href="eval_supervisor_ws.php" class="evaluate-button">Evaluate Now</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script src="js/lib/sweetalert/sweetalert.init.js"></script>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
        <script>
            sweetAlert("<?php echo $_SESSION['alert'] ?? 'Notice'; ?>", "<?php echo $_SESSION['status']; ?>", "<?php echo $_SESSION['status-code']; ?>");
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</body>
</html>