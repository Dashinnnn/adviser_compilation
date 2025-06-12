<?php
include '../connection/config.php';
error_reporting(E_ALL & ~E_NOTICE); // Show all errors except notices

session_start();

// Redirect if not authenticated
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header('Location: ../index.php');
    exit;
}

// Get student data
$studID = $_SESSION['auth_user']['coordinators_id'];
try {
    $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE id = ?");
    $stmt->execute([$studID]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "coordinator data not found.";
        header('Location: ../index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['alert'] = "Error";
    $_SESSION['status'] = "Database error: " . $e->getMessage();
}

// Handle update information form submission
if (isset($_POST['updateInfo'])) {
    $studID = filter_input(INPUT_POST, 'studID', FILTER_SANITIZE_NUMBER_INT);
    $fname = filter_input(INPUT_POST, 'first_NAME', FILTER_SANITIZE_STRING);
    $mname = filter_input(INPUT_POST, 'middle_NAME', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'last_NAME', FILTER_SANITIZE_STRING);
    $c_address = filter_input(INPUT_POST, 'complete_address', FILTER_SANITIZE_STRING);
    $cp_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);

    // Server-side validation
    if (empty($fname) || empty($mname) || empty($lname) || empty($c_address) || empty($cp_number)) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "All fields are required.";
    } elseif (!preg_match('/^\d{10,12}$/', $cp_number)) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "Phone number must be 10-12 digits.";
    } elseif (strlen($fname) > 70 || strlen($mname) > 70 || strlen($lname) > 70 || strlen($c_address) > 200) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "Input exceeds maximum length.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT first_name, middle_name, last_name, complete_address, phone_number FROM students_data WHERE id = ?");
            $stmt->execute([$studID]);
            $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fname !== $currentData['first_name'] ||
                $mname !== $currentData['middle_name'] ||
                $lname !== $currentData['last_name'] ||
                $c_address !== $currentData['complete_address'] ||
                $cp_number !== $currentData['phone_number']) {

                $stmt = $conn->prepare("UPDATE students_data SET first_name=?, middle_name=?, last_name=?, complete_address=?, phone_number=? WHERE id=?");
                $stmt->execute([$fname, $mname, $lname, $c_address, $cp_number, $studID]);

                if ($stmt->rowCount() > 0) {
                    date_default_timezone_set('Asia/Manila');
                    $date = date('F / d l / Y');
                    $time = date('g:i A');
                    $logs = 'You successfully updated your information.';

                    $sql2 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
                    $sql2->execute([$studID, $logs, $date, $time]);

                    $_SESSION['alert'] = "Success";
                    $_SESSION['status'] = "Update Success";
                } else {
                    $_SESSION['alert'] = "Error";
                    $_SESSION['status'] = "Update Failed";
                }
            } else {
                $_SESSION['alert'] = "Info";
                $_SESSION['status'] = "Nothing has changed.";
            }
        } catch (PDOException $e) {
            $_SESSION['alert'] = "Error";
            $_SESSION['status'] = "Database error: " . $e->getMessage();
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle password update form submission
if (isset($_POST['updatePword'])) {
    $studID = filter_input(INPUT_POST, 'studID', FILTER_SANITIZE_NUMBER_INT);
    $cpword = $_POST['Cpword'];
    $npword = $_POST['Npword'];
    $rnpword = $_POST['RNpword'];

    // Server-side validation
    if ($npword !== $rnpword) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "New Password and Repeat Password do not match.";
    } elseif (strlen($npword) > 200) {
        $_SESSION['alert'] = "Error";
        $_SESSION['status'] = "New password exceeds maximum length.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT stud_password FROM students_data WHERE id = ?");
            $stmt->execute([$studID]);
            $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (md5($cpword) === $currentData['stud_password']) { // WARNING: MD5 is insecure; consider using password_hash in production
                $hashedPassword = md5($npword);
                $stmt = $conn->prepare("UPDATE students_data SET stud_password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $studID]);

                if ($stmt->rowCount() > 0) {
                    date_default_timezone_set('Asia/Manila');
                    $date = date('F / d l / Y');
                    $time = date('g:i A');
                    $logs = 'You successfully updated your password.';

                    $sql2 = $conn->prepare("INSERT INTO system_notification(student_id, logs, logs_date, logs_time) VALUES (?, ?, ?, ?)");
                    $sql2->execute([$studID, $logs, $date, $time]);

                    $_SESSION['alert'] = "Success";
                    $_SESSION['status'] = "Password Updated!";
                } else {
                    $_SESSION['alert'] = "Error";
                    $_SESSION['status'] = "Password update failed.";
                }
            } else {
                $_SESSION['alert'] = "Error";
                $_SESSION['status'] = "Incorrect current password.";
            }
        } catch (PDOException $e) {
            $_SESSION['alert'] = "Error";
            $_SESSION['status'] = "Database error: " . $e->getMessage();
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Student Settings</title>
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="endorsement-css/endorsement-moa.css" rel="stylesheet">
    <style>
        .btn-download {
            display: block;
            margin-bottom: 1rem;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .hidden {
            display: none;
        }
        .document-area {
            width: 100%;
            height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .document-area iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        .document-area.enlarged {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            z-index: 1000;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .overlay.active {
            display: block;
        }
        .portfolio-requirements {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .portfolio-requirements h3 {
            margin-top: 0;
            color: #333;
            font-size: 1.2rem;
        }
        .portfolio-requirements ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        .portfolio-requirements li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>

    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto; position: relative;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="page-header">
                <div class="page-title">
                    <h1 style="font-size: 16px;"><b>Settings</b></h1><br>
                </div>
            </div>
            <div>
                <div style="margin-bottom: 30px;">
                    <p style="color: #666; margin-bottom: 20px;">
                        <strong>Note:</strong> You are only allowed to edit some of your basic information. If you notice any incorrect non-editable information, kindly message your OJT adviser so they can make the necessary changes.
                    </p>

                    <form id="updateInfoForm" action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>" required>

                        <!-- First row: First name, Middle Initial, Last name -->
                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label for="first_NAME" style="display: block; margin-bottom: 5px; font-weight: normal;">First Name</label>
                                <input type="text" class="form-control" name="first_NAME" id="first_NAME" value="<?php echo htmlspecialchars($data['first_name']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="middle_NAME" style="display: block; margin-bottom: 5px; font-weight: normal;">Middle Initial</label>
                                <input type="text" class="form-control" name="middle_NAME" id="middle_NAME" value="<?php echo htmlspecialchars($data['middle_name']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="last_NAME" style="display: block; margin-bottom: 5px; font-weight: normal;">Last Name</label>
                                <input type="text" class="form-control" name="last_NAME" id="last_NAME" value="<?php echo htmlspecialchars($data['last_name']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                            </div>
                        </div>

                        <!-- Second row: Course, Year and Section -->
                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 5px; font-weight: normal;">Course</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['course_handled']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background-color: #f5f5f5;" readonly>
                            </div>
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 5px; font-weight: normal;">Sections Handled</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['assigned_section'] . ', ' . $data['second_assigned_section']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background-color: #f5f5f5;" readonly>
                            </div>
                        </div>

                        <!-- Third row: Medical Condition (full width) -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: normal;">Active Student</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($data['active_student']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background-color: #f5f5f5;" readonly>
                        </div>

                        <!-- Fourth row: Complete Address, Phone Number -->
                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label for="complete_address" style="display: block; margin-bottom: 5px; font-weight: normal;">Complete Address</label>
                                <input type="text" class="form-control" name="complete_address" id="complete_address" value="<?php echo htmlspecialchars($data['complete_address']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="phone_number" style="display: block; margin-bottom: 5px; font-weight: normal;">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($data['phone_number']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required pattern="\d{10,12}">
                            </div>
                        </div>

                        <button type="submit" name="updateInfo" id="updateInfoButton" style="background-color: #0000FF; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Update changes</button>
                    </form>
                </div>

                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee;">
                    <h2 style="font-size: 18px; margin-bottom: 20px;">Update Password</h2>
                    <form id="updatePwordForm" action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>" required>

                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label for="Cpword" style="display: block; margin-bottom: 5px; font-weight: normal;">Current Password</label>
                                <input type="password" class="form-control" name="Cpword" id="Cpword" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div style="flex: 1;">
                                <label for="Npword" style="display: block; margin-bottom: 5px; font-weight: normal;">New Password</label>
                                <input type="password" class="form-control" name="Npword" id="Npword" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div style="flex: 1;">
                                <label for="RNpword" style="display: block; margin-bottom: 5px; font-weight: normal;">Repeat New Password</label>
                                <input type="password" class="form-control" name="RNpword" id="RNpword" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <button type="submit" name="updatePword" id="updatePwordButton" style=" background-color: #0000FF; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/form-validation/jquery.validate.min.js"></script>
    <script src="js/lib/form-validation/jquery.validate-init.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize form validation for Update Info
            $('#updateInfoForm').validate({
                rules: {
                    first_NAME: { required: true, maxlength: 70 },
                    middle_NAME: { required: true, maxlength: 70 },
                    last_NAME: { required: true, maxlength: 70 },
                    complete_address: { required: true, maxlength: 200 },
                    phone_number: { required: true, pattern: /^\d{10,12}$/ }
                },
                messages: {
                    first_NAME: { required: "First name is required.", maxlength: "First name cannot exceed 70 characters." },
                    middle_NAME: { required: "Middle initial is required.", maxlength: "Middle initial cannot exceed 70 characters." },
                    last_NAME: { required: "Last name is required.", maxlength: "Last name cannot exceed 70 characters." },
                    complete_address: { required: "Address is required.", maxlength: "Address cannot exceed 200 characters." },
                    phone_number: { required: "Phone number is required.", pattern: "Phone number must be 10-12 digits." }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('div').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Function to check if all required fields in Update Info form are filled
            function checkUpdateInfoFields() {
                const fields = [
                    $('#first_NAME').val(),
                    $('#middle_NAME').val(),
                    $('#last_NAME').val(),
                    $('#complete_address').val(),
                    $('#phone_number').val()
                ];
                const allFilled = fields.every(field => field && field.trim() !== '');
                const phoneValid = $('#phone_number').val().match(/^\d{10,12}$/);
                $('#updateInfoButton').css('background-color', allFilled && phoneValid ? '#007bff' : '#888');
            }

            // Function to check if all fields in Update Password form are filled
            function checkUpdatePwordFields() {
                const fields = [
                    $('#Cpword').val(),
                    $('#Npword').val(),
                    $('#RNpword').val()
                ];
                const allFilled = fields.every(field => field !== '');
                const passwordsMatch = $('#Npword').val() === $('#RNpword').val();
                $('#updatePwordButton').css('background-color', allFilled && passwordsMatch ? '#007bff' : '#888');
            }

            // Bind input events to check fields
            $('#updateInfoForm input').on('input', checkUpdateInfoFields);
            $('#updatePwordForm input').on('input', checkUpdatePwordFields);

            // Initial check on page load
            checkUpdateInfoFields();
            checkUpdatePwordFields();
        });

        <?php 
        if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
        ?>
            alert("<?php echo $_SESSION['alert'] . ': ' . $_SESSION['status']; ?>");
            <?php
            unset($_SESSION['status']);
            unset($_SESSION['alert']);
        }
        ?>
    </script>
</body>
</html>