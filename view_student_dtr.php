<?php
include '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if ($_SESSION['auth_user']['coordinators_id'] == 0) {
    echo "<script>window.location.href='index.php'</script>";
}

$studID = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($studID)) {
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students_data WHERE id = ?");
$stmt->execute([$studID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Student not found!";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM stud_daily_time_records WHERE stud_id = ?");
$stmt->execute([$studID]);
$dtr = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OJT Web Portal: Student Profile</title>
    <link rel="shortcut icon" href="images/Picture1.png">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        .back-btn {
            border: none;
            margin: 10px 10px 25px 0px;
            font-size: 20px;
            background-color: #fff;
        }

        .student-info {
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

        .red-label {
            color: #8B0000;
            margin: 30px 0 30px 0;
        }

        .rendered-hrs-cont {
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
            border: 2px solid #fff;
            font-weight: 600;
            border: 2px solid #700000;
        }
        tbody tr:nth-child(odd) {
            color: #000;
            text-align: center;
            padding: 20px 50px;
            border: 2px solid #fff;
            font-weight: 600;
            background-color: rgb(221, 218, 218);
            border: 2px solid #700000;
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

        .photoOverlay {
            width: 1000px;
            height: 700px;
            border: 1px solid black;
            background-color: white;
            position: fixed;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            border-radius: 10px;
        }

        .overlayContent {
            padding: 20px;
            text-align: center;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .imageContainer {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            justify-items: center;
            margin-top: 20px;
        }

        .photoItem {
            text-align: center;
        }

        .photoLabel {
            font-size: 14px;
            color: #700000;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .overlayImage {
            width: 400px;
            height: 200px;
            object-fit: contain;
            border: 1px solid #ccc;
        }

        .no-photo-placeholder {
            width: 400px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid # intimidatingccc;
            background-color: #f9f9f9;
            color: #700000;
            font-size: 16px;
            font-weight: 600;
        }

        .photoInfo {
            font-size: 12px;
            color: #000;
            margin-top: 5px;
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .top-function {
            display: flex;
            justify-content: space-between;
            margin: 0px 0 30px 0;
        }

        .overlay-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0 20px 0;  
            gap: 10px;
        }
        
        .acceptBtn {
            color: white;
            background-color: rgb(34, 82, 203);
            width: 70%;
            padding: 8px 0 8px 0;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .rejectBtn {
            color: white;
            background-color: #8B0000;
            width: 70%;
            padding: 8px 0 8px 0;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .acceptBtn:disabled,
        .rejectBtn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .remarks-cont {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .remarks {
            width: 70%;
            height: 80px;
            border: 2px solid #8B0000;
            border-radius: .3rem;
            padding: 8px 10px 8px 10px;
        }
    </style>
</head>
<body>
    <?php require_once 'templates/coordinators_navbar.php'; ?>

    <div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
        <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
            <div class="page-header">
                <button class="back-btn" onclick="backBtn()">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </button>
            </div>
            <div class="page-title">
                <h1 style="font-size: 16px;"><b>Daily Time Record</b></h1>
            </div>
            <div class="student-info">
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
                        <?php echo htmlspecialchars(": " . ($data['stud_section'] ?? 'N/A')); ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="label">
                        <h6><b>HTE</b></h6>
                    </div>
                    <div class="studData">
                        <?php echo htmlspecialchars(": " . ($data['stud_hte'] ?? 'N/A')); ?>
                    </div>
                </div>
                <div class="rendered-label">
                    <h6 class="red-label">
                        <b>Required Internship Hours: 300</b>
                    </h6>
                </div>
                <div class="rendered-hrs-cont">
                    <p><b>Total rendered hours: 16 hrs</b></p>
                    <p><b>Total remaining hours: 286 hrs</b></p>
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
                            <th colspan="6">Daily Time Record</th>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>AM Time In</th>
                            <th>AM Time Out</th>
                            <th>PM Time In</th>
                            <th>PM Time Out</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dtr)): ?>
                            <?php foreach ($dtr as $traineeDTR): ?>
                                <?php
                                $baseUrl = 'http://localhost/capstone OJT/drills/student/';
                                $documentRoot = 'C:\\xampp\\htdocs\\capstone OJT\\drills\\student\\';

                                $photos = [
                                    [
                                        'field' => $traineeDTR['AM_time_IN_pic'],
                                        'label' => 'AM Time In',
                                        'time' => $traineeDTR['AM_time_IN'] ?? 'N/A',
                                        'date' => $traineeDTR['recordDate'] ?? 'N/A'
                                    ],
                                    [
                                        'field' => $traineeDTR['AM_time_OUT_pic'],
                                        'label' => 'AM Time Out',
                                        'time' => $traineeDTR['AM_time_OUT'] ?? 'N/A',
                                        'date' => $traineeDTR['recordDate'] ?? 'N/A'
                                    ],
                                    [
                                        'field' => $traineeDTR['PM_time_IN_pic'],
                                        'label' => 'PM Time In',
                                        'time' => $traineeDTR['PM_time_IN'] ?? 'N/A',
                                        'date' => $traineeDTR['recordDate'] ?? 'N/A'
                                    ],
                                    [
                                        'field' => $traineeDTR['PM_time_OUT_pic'],
                                        'label' => 'PM Time Out',
                                        'time' => $traineeDTR['PM_time_OUT'] ?? 'N/A',
                                        'date' => $traineeDTR['recordDate'] ?? 'N/A'
                                    ]
                                ];

                                foreach ($photos as &$photo) {
                                    $path = $photo['field'];
                                    if ($path) {
                                        if (strpos($path, $documentRoot) === 0) {
                                            $path = str_replace($documentRoot, $baseUrl, $path);
                                            $path = str_replace('\\', '/', $path);
                                        } elseif (!preg_match('/^uploads\//', $path)) {
                                            $path = 'uploads/' . $path;
                                            $path = $baseUrl . $path;
                                        } else {
                                            $path = $baseUrl . $path;
                                        }
                                        $photo['url'] = $path;
                                    } else {
                                        $photo['url'] = null;
                                    }
                                }
                                unset($photo); 
                                $photosJson = htmlspecialchars(json_encode($photos), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($traineeDTR['recordDate'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($traineeDTR['AM_time_IN'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($traineeDTR['AM_time_OUT'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($traineeDTR['PM_time_IN'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($traineeDTR['PM_time_OUT'] ?? 'N/A'); ?></td>
                                    <td>
                                        <button class="view-profile" 
                                                onclick="togglePhotoOverlay(<?php echo $photosJson; ?>, <?php echo $traineeDTR['id']; ?>, '<?php echo $traineeDTR['status']; ?>')"
                                                data-photos='<?php echo $photosJson; ?>'>
                                            View Photos
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No DTR Found <?php echo htmlspecialchars($data['assigned_section'] ?? 'N/A'); ?>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="photoOverlay" id="photoOverlay">
                <div class="overlayContent">
                    <div class="top-function">
                        <h4>Daily Time Record Photos</h4>
                        <button class="close-btn" onclick="togglePhotoOverlay([], null, null)">Close</button>
                    </div>
                    <div class="status: ">
                        <h4>Status</h4>
                        <?php echo htmlspecialchars($traineeDTR['status'] ?? "N/A")?>
                    </div>
                    <div id="imageContainer" class="imageContainer"></div>
                    <div class="overlay-btn">
                        <button class="acceptBtn" id="acceptBtn" onclick="updateStatus(currentDtrId, 'Accepted')">
                            <i class="fa-solid fa-circle-check"></i> Accept
                        </button>
                        <button class="rejectBtn" id="rejectBtn" onclick="toggleReject()">
                            <i class="fa-solid fa-square-xmark"></i> Reject
                        </button>
                    </div>
                    <div class="remarks-cont">
                        <textarea name="remarks" id="remarks" class="remarks" placeholder="Add remarks..."></textarea>
                        <button class="acceptBtn" id="remarks-btn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/lib/sweetalert/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.search-btn').on('click', function() {
                var searchTerm = $('.search-box').val().toLowerCase();
                $('.trainee-table tbody tr').each(function() {
                    var date = $(this).find('td:eq(0)').text().toLowerCase();
                    if (date.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('.search-box').on('input', function() {
                if ($(this).val() === '') {
                    $('.trainee-table tbody tr').show();
                }
            });
        });

        function backBtn() {
            window.history.back();
        }

        let overlayStatus = false;
        let currentDtrId = null;
        let currentStatus = null;

        function togglePhotoOverlay(photos, dtrId, status) {
            overlayStatus = !overlayStatus;
            const overlay = document.getElementById("photoOverlay");
            const imageContainer = document.getElementById("imageContainer");
            const acceptBtn = document.getElementById("acceptBtn");
            const rejectBtn = document.getElementById("rejectBtn");
            const remarksField = document.getElementById("remarks");
            const remarksBtn = document.getElementById("remarks-btn");

            currentDtrId = dtrId;
            currentStatus = status;

            if (overlayStatus && photos && photos.length > 0) {
                imageContainer.innerHTML = '';

                photos.forEach(photo => {
                    const photoItem = document.createElement('div');
                    photoItem.className = 'photoItem';

                    const label = document.createElement('div');
                    label.className = 'photoLabel';
                    label.textContent = photo.label;

                    if (photo.url) {
                        const img = document.createElement('img');
                        img.src = photo.url;
                        img.alt = photo.label;
                        img.className = 'overlayImage';
                        img.onerror = function() {
                            this.style.display = 'none';
                            const errorMsg = document.createElement('div');
                            errorMsg.className = 'no-photo-placeholder';
                            errorMsg.textContent = 'Failed to Load';
                            photoItem.appendChild(errorMsg);
                        };
                        photoItem.appendChild(label);
                        photoItem.appendChild(img);
                    } else {
                        const placeholder = document.createElement('div');
                        placeholder.className = 'no-photo-placeholder';
                        placeholder.textContent = 'No Photo Uploaded';
                        photoItem.appendChild(label);
                        photoItem.appendChild(placeholder);
                    }

                    const info = document.createElement('div');
                    info.className = 'photoInfo';
                    info.textContent = `Date: ${photo.date}, Time: ${photo.time}`;
                    photoItem.appendChild(info);

                    imageContainer.appendChild(photoItem);
                });

                imageContainer.style.display = 'grid';
                overlay.style.display = 'block';

                acceptBtn.disabled = (status === 'Accepted');
                rejectBtn.disabled = (status === 'Denied');
                remarksField.style.display = 'none';
                remarksBtn.style.display = 'none';
                remarksField.value = '';
            } else {
                imageContainer.innerHTML = '';
                imageContainer.style.display = 'none';
                overlay.style.display = 'none';
                currentDtrId = null;
                currentStatus = null;
            }
        }

        document.getElementById("photoOverlay").addEventListener("click", function (event) {
            if (event.target === this) {
                togglePhotoOverlay([], null, null);
            }
        });

        let rejectValue = false;

        function toggleReject() {
            rejectValue = !rejectValue;

            const remarksField = document.getElementById("remarks");
            const remarksBtn = document.getElementById("remarks-btn");

            remarksField.style.display = rejectValue ? "block" : "none";
            remarksBtn.style.display = rejectValue ? "block" : "none";
        }

        function updateStatus(dtrId, status, remarks = null) {
            if (!dtrId) {
                swal("Error", "No DTR record available to update.", "error");
                return;
            }
            swal({
                title: `Are you sure?`,
                text: `You are about to mark this DTR as ${status}.`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: status === 'Accepted' ? "#5cb85c" : "#d9534f",
                confirmButtonText: `Yes, ${status} it`,
                closeOnConfirm: false
            }, function() {
                $.post('update_dtr_status.php', { 
                    dtr_id: dtrId, 
                    status: status, 
                    remarks: remarks 
                }, function(response) {
                    if (response.message && response.message.includes("updated")) {
                        if (status === 'Accepted') {
                            swal("Success", "You approved <?php echo addslashes(trim($fullName)); ?>'s DTR", "success");
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

        document.addEventListener('DOMContentLoaded', function() {
            const remarksField = document.getElementById("remarks");
            const remarksBtn = document.getElementById("remarks-btn");

            remarksField.style.display = "none";
            remarksBtn.style.display = "none";

            remarksBtn.addEventListener('click', function() {
                const remarks = remarksField.value.trim();

                if (!remarks) {
                    swal("Error", "Please enter remarks before submitting.", "error");
                    return;
                }

                updateStatus(currentDtrId, 'Denied', remarks);
            });
        });
    </script>
</body>
</html>