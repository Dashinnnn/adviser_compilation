<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0); 
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

session_start();
if (!isset($_SESSION['auth_user']['coordinators_id']) || $_SESSION['auth_user']['coordinators_id'] == 0) {
    header('Location: index.php');
    exit;
}

$student_id = $_GET['id'] ?? '';
if (empty($student_id)) {
    header('Location: stud_endorsement_validation.php');
    exit;
}

try {
    $stmt = $conn->prepare("SELECT student_ID, first_name, middle_name, last_name, stud_section, stud_hte FROM students_data WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        die("Student not found.");
    }
} catch (PDOException $e) {
    error_log("Student fetch error: " . $e->getMessage());
    die("Error fetching student data.");
}

try {
    $stmt = $conn->prepare("
        SELECT
            id AS document_id,
            document_name,
            uploaded_path,
            DATE_FORMAT(upload_date, '%M %d, %Y %H:%i') AS formatted_date,
            status
        FROM
            endorsement_documents
        WHERE 
            student_id = ? AND document_type = 'contract'
        ORDER BY
            upload_date DESC
        LIMIT 1
    ");
    $stmt->execute([$student_id]);
    $contract = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Contract fetch error: " . $e->getMessage());
    $contract = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Company ID Review</title>
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .back-btn { 
            border: none;
            margin: 10px 10px 25px 0px;
            font-size: 20px;
            background-color: #fff;
        }
        .back-btn:hover { color: black; }
        fieldset {
            border: 2px solid #8B0000;
            border-radius: 1.5rem;
            padding: 10px 20px;
            margin: 10px 20px;
            flex: 2;
        }
        legend {
            padding: 10px 20px 10px 30px;
            font-weight: 500;
            color: black;
        }
        .content-holder {
            display: flex;
            flex: 1;
            padding: 10px 120px;
            gap: 20px;
        }
        .info-function-cont {
            flex: 1;
            padding: 10px 20px;
        }
        .data-row {
            display: flex;
        }
        .label {
            flex: 0 0 150px;
            font-weight: 500;
        }
        .student-data { flex: 1; }
        .button-function {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .btn-style {
            background-color: #8B0000;
            color: white;
            font-weight: bold;
            border: none;
            padding: 8px 0;
            border-radius: .3rem;
            cursor: pointer;
        }
        .btn-style-submit {
            background-color: rgb(34, 82, 203);
            color: white;
            font-weight: bold;
            border: none;
            padding: 8px 0;
            border-radius: .3rem;
            cursor: pointer;
        }
        .btn-style:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .btn-style:disabled:hover {
            color: #fff;
        }
        .moa-overview { 
            margin-bottom: 15px; 
        }
        .overview-cont {
            width: 100%;
            height: 500px;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .moa-preview {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            background: #fff;
        }
        .moa-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .moa-preview img {
            max-width: 100%;
            height: auto;
        }
        .no-file-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #666;
            font-style: italic;
            text-align: center;
        }
        .remarks-cont {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 30px;
        }
        .remarks {
            width: 100%;
            height: 80px;
            border: 2px solid #8B0000;
            border-radius: .3rem;
            padding: 8px 10px 8px 10px;
        }

        .moa-preview.enlarged {
            position: fixed;
            top: 55%;
            left: 55%;
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
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>  
    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="back-btn" onclick="backBtn()">
            <i class="fa-solid fa-arrow-left"></i> Back
            </div>
            <div class="content-holder">
                <div class="file-overview-holder">
                    <fieldset>
                        <legend>Contract Job and Description</legend>
                        <div class="overview-cont">
                            <?php if ($contract): ?>
                                <div class="moa-preview" id="cjdPreview">
                                    <?php
                                    $file_ext = strtolower(pathinfo($contract['uploaded_path'], PATHINFO_EXTENSION));
                                    if ($file_ext === 'pdf'): ?>
                                        <iframe src="<?php echo $contract['uploaded_path'] . '?t=' . time(); ?>" frameborder="0"></iframe>
                                    <?php elseif (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                        <img src="<?php echo $contract['uploaded_path']; ?>" alt="MOA Image">
                                    <?php else: ?>
                                        <p class="no-file-message">Preview not available for this file type. Use the "View" button to open it.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="no-file-message">No document has been submitted.</p>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                </div>
                <div class="info-function-cont">
                    <div class="info-section">
                        <div class="data-row">
                            <div class="label"><p><b>Student Name:</b></p></div>
                            <div class="student-data">
                                <?php
                                $firstName = $student['first_name'] ?? '';
                                $middleName = $student['middle_name'] ?? '';
                                $lastName = $student['last_name'] ?? '';
                                $middleInitial = $middleName ? strtoupper(substr(trim($middleName), 0, 1)) . '.' : '';
                                $fullName = trim(": $lastName, $firstName $middleInitial");
                                echo htmlspecialchars($fullName);
                                ?>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="label"><p><b>Section:</b></p></div>
                            <div class="student-data">
                                : <?php echo htmlspecialchars($student['stud_section'] ?? ''); ?>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="label"><p><b>HTE:</b></p></div>
                            <div class="student-data">
                                : <?php echo htmlspecialchars($student['stud_hte'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="moa-overview">
                            <?php if ($contract): ?>
                                <div class="data-row">
                                    <div class="label"><p><b>File Name</b></p></div>
                                    <div class="student-data">: <?php echo htmlspecialchars($contract['document_name']); ?></div>
                                </div>
                                <div class="data-row">
                                    <div class="label"><p><b>Status</b></p></div>
                                    <div class="student-data">: <?php echo htmlspecialchars(ucfirst($contract['status'])); ?></div>
                                </div>
                            <?php else: ?>
                                <div class="data-row">
                                    <div class="label"><p><b>File Name</b></div>
                                    <div class="student-data">: N/A</div>
                                </div>
                                <div class="data-row">
                                    <div class="label"><p><b>Status</b></div>
                                    <div class="student-data">: N/A</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="button-function">
                        <button class="btn-style" onclick="viewMoa()" <?php echo $contract ? '' : 'disabled'; ?>>
                            <i class="fa-regular fa-eye"></i> View
                        </button>
                        <button class="btn-style" onclick="updateStatus(<?php echo $contract['document_id'] ?? 'null'; ?>, 'accepted')" <?php echo $contract && $contract['status'] !== 'accepted' ? '' : 'disabled'; ?>>
                            <i class="fa-solid fa-circle-check"></i> Approve
                        </button>
                        <button class="btn-style" onclick="toggleReject()" <?php echo $contract && $contract['status'] !== 'denied' ? '' : 'disabled'; ?>>
                            <i class="fa-solid fa-square-xmark"></i> Reject
                        </button>
                    </div>
                    <div class="remarks-cont">
                        <textarea name="remarks" id="remarks" class="remarks" placeholder="Add remarks..."></textarea>
                        <button class="btn-style-submit" id="remarks-btn">
                            <i class="fa-solid fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>
    <script>
        function backBtn() {
            window.history.back();
        }

        function viewMoa() {
            <?php if ($contract): ?>
                const fileExt = '<?php echo $file_ext; ?>';
                const cjdPreview = document.getElementById('cjdPreview');
                const overlay = document.getElementById('overlay');

                if (fileExt === 'pdf') {
                    cjdPreview.classList.toggle('enlarged');
                    overlay.classList.toggle('active');
                } else {
                    swal("Notice", "Preview not available for this file type.", "info");
                }
            <?php else: ?>
                swal("Notice", "No document available to view.", "info");
            <?php endif; ?>
        }

        function updateStatus(documentId, status, remarks = null) {
            if (documentId === null) {
                swal("Error", "No document available to update.", "error");
                return;
            }
            swal({
                title: `Are you sure?`,
                text: `You are about to mark this file as ${status}.`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: status === 'accepted' ? "#5cb85c" : "#d9534f",
                confirmButtonText: `Yes, ${status} it`,
                closeOnConfirm: false
            }, function() {
                $.post('update_status.php', { 
                    document_id: documentId, 
                    status: status, 
                    remarks: remarks 
                }, function(response) {
                    if (response.message && response.message.includes("updated")) {
                        if (status === 'accepted') {
                            swal("Success", "You approved <?php echo addslashes(trim("$firstName $middleInitial $lastName")); ?>'s work", "success");
                        } else {
                            swal("Success", response.message, "success");
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        swal("Error", response.message || "Unknown error occurred.", "error");
                    }
                }, 'json').fail(function() {
                    swal("Error", "Failed to update status.", "error");
                });
            });
        }

        let rejectValue = false;

        function toggleReject() {
            rejectValue = !rejectValue;

            const remarksField = document.getElementById("remarks");
            const remarksBtn = document.getElementById("remarks-btn");

            remarksField.style.display = rejectValue ? "block" : "none";
            remarksBtn.style.display = rejectValue ? "block" : "none";
        }

        document.addEventListener('DOMContentLoaded', function() {
            const remarksField = document.getElementById("remarks");
            const remarksBtn = document.getElementById("remarks-btn");
            const overlay = document.getElementById('overlay');

            remarksField.style.display = "none";
            remarksBtn.style.display = "none";

            remarksBtn.addEventListener('click', function() {
                const documentId = <?php echo $contract['document_id'] ?? 'null'; ?>;
                const remarks = remarksField.value.trim();

                if (!remarks) {
                    swal("Error", "Please enter remarks before submitting.", "error");
                    return;
                }

                updateStatus(documentId, 'denied', remarks);
            });

            
            overlay.addEventListener('click', function() {
                const cjdPreview = document.getElementById('cjdPreview');
                cjdPreview.classList.remove('enlarged');
                overlay.classList.remove('active');
            });
        });
    </script>
</body>
</html>