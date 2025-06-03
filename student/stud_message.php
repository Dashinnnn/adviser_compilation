<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['auth_user']['student_id'])) {
    die("Error: User is not authenticated.");
}

$studId = $_SESSION['auth_user']['student_id'];
$course = $_SESSION['auth_user']['student_course'];

if ($studId == 0) {
    header("Location: index.php");
    exit();
}

// Fetch active users
$stmt = $conn->prepare("SELECT * FROM students_data WHERE id != ? AND stud_course = ?");
$stmt->execute([$studId, $course]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get current user's profile
$sql = $conn->prepare("SELECT profile_picture FROM students_data WHERE id = ?");
$sql->execute([$studId]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
$currentImagePath = $row['profile_picture'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Chats</title>
    <!-- ================= Favicon ================== -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <!-- Common -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        height: 20rem;
        overflow: hidden;
    }

    .chat-online {
        color: #34ce57;
    }

    .chat-offline {
        color: #e4606d;
    }

    .chat-messages {
        display: flex;
        flex-direction: column;
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chat-message-left,
    .chat-message-right {
        display: flex;
        flex-shrink: 0;
        margin-bottom: 1rem;
    }

    .chat-message-left {
        margin-right: auto;
    }

    .chat-message-right {
        flex-direction: row-reverse;
        margin-left: auto;
    }

    .chat-message-text {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        max-width: 70%;
        word-wrap: break-word;
    }

    .chat-message-left .chat-message-text {
        background-color: #f1f3f5;
    }

    .chat-message-right .chat-message-text {
        background-color: #007bff;
        color: white;
    }

    .image-placeholder {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            background-color: #f8f8f8;
            display: flex;
            align-items: left;
            justify-content: center;
            overflow: hidden;
            border: 10px solid #D9D9D9;
        }
        
        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .placeholder-icon {
            width: 100px;
            height: auto;
            opacity: 0.3;
        }

    .list-group-item {
        transition: background-color 0.3s ease;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 8px 8px 0 0;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn {
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .file-category {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .file-category h6 {
            color: #6c757d;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
        }
        .document-item {
            padding: 10px;
            background: white;
            margin: 5px 0;
            border-radius: 5px;
        }
        .thumbnail-container {
            position: relative;
            margin-bottom: 10px;
        }
        .file-timestamp {
            font-size: 0.8rem;
            color: #6c757d;
        }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .content-wrap {
            margin-left: 0 !important;
        }

        .col-md-4, .col-lg-3 {
            width: 100%;
            margin-bottom: 1rem;
        }

        .chat-messages {
            max-height: 300px;
        }
    }
</style>

<body>
<?php require_once 'templates/stud_navbar.php'; ?>

<div class="content-wrap" style="height: 80%; width: 100%; margin: 0 auto;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div class="page-header">
            <h1 style="font-size: 16px;"><b>MESSAGES</b></h1>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-12 col-md-4 col-lg-3">
                <div class="px-4 d-none d-md-block">
                    <div class="profile-image">
                        <div class="image-placeholder">
                            <img src="<?= $currentImagePath ?: 'images/placeholder.png' ?>" alt="Profile">
                        </div>
                    </div>
                    <input type="text" class="form-control my-3" placeholder="Search..." id="searchInput">
                </div>
                <div id="userList">
                    <?php foreach ($results as $res) { ?>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0" onclick="loadConversation('<?= $res['uniqueID'] ?>')">
                            <div class="d-flex align-items-start">
                                <img src="<?= $res['profile_picture'] ?>" class="rounded-circle mr-1" width="40" height="40">
                                <div class="flex-grow-1 ml-3">
                                    <?= "{$res['first_name']} {$res['middle_name']} {$res['last_name']}" ?>
                                    <div class="small">
                                        <span class="fas fa-circle <?= $res['online_offlineStatus'] === 'Online' ? 'chat-online' : 'chat-offline' ?>"></span>
                                        <?= $res['online_offlineStatus'] ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <!-- Middle Column -->
            <div class="col-12 col-md-4 col-lg-6" id="LIVEchat"></div>

            <!-- Right Column (Initially Hidden) -->
            <div class="col-12 col-md-4 col-lg-3" id="documentSection" style="display: none;">
                <div style="margin-top: -5rem;">
                    <h5><b>Shared Files</b></h5>
                    <div id="sharedFilesContent">
                        <div class="file-category">
                            <h6>Images</h6>
                            <div class="row" id="imageContainer"></div>
                        </div>
                        <div class="file-category">
                            <h6>Documents</h6>
                            <div id="documentContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/lib/jquery.min.js"></script>
<script>
function loadConversation(receiverId) {
    $('#documentSection').show();
    
    $('#LIVEchat').load('stud_messageLIVECHAT.php', { userUNIQUEid_receiver: receiverId });

    $.ajax({
        url: 'load_documents.php',
        method: 'POST',
        data: { receiver_id: receiverId },
        success: function(response) {
            const files = JSON.parse(response);
            let imagesHtml = '';
            let docsHtml = '';

            files.forEach(file => {
                const date = new Date(file.timestamp).toLocaleString();
                if (file.file_type.startsWith('image/')) {
                    imagesHtml += `
                        <div class="col-6 mb-3">
                            <div class="thumbnail-container">
                                <img src="${file.file_path}" class="img-fluid rounded" alt="Shared image">
                                <small class="file-timestamp d-block">${date}</small>
                            </div>
                        </div>
                    `;
                } else {
                    docsHtml += `
                        <div class="document-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-${file.file_type === 'application/pdf' ? 'pdf' : 'word'} text-danger"></i>
                                    <a href="${file.file_path}" download class="ml-2">${file.file_name}</a>
                                </div>
                            </div>
                            <small class="file-timestamp">${date}</small>
                        </div>
                    `;
                }
            });

            $('#imageContainer').html(imagesHtml || '<p class="text-muted">No images shared</p>');
            $('#documentContainer').html(docsHtml || '<p class="text-muted">No documents shared</p>');
        }
    });
}

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const receiverId = urlParams.get('userUNIQUEid_receiver');
    if (receiverId) {
        loadConversation(receiverId);
    }
});
</script>
    
</body>

</html>