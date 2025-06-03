<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['auth_user']['coordinators_id'])) {
    die("Error: User is not authenticated.");
}

$studId = $_SESSION['auth_user']['coordinators_id'];
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
$sql = $conn->prepare("SELECT coordinators_profile_picture FROM coordinators_account WHERE id = ?");
$sql->execute([$studId]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
$currentImagePath = $row['coordinators_profile_picture'] ?? '';
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
        margin: 0;
        height: 100vh;
        overflow: auto;
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

    .profile-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 900px;
        width: 100%;
        height: calc(100vh - 150px);
        overflow: hidden;
    }

    .left-column {
        border: solid 1px black;
        width: 40%;
    }

    .add-contact {
        display: flex;
        align-items: center;
        gap: 10px;  
    }

    .add-contact button {
        all: unset;
    }

    .image-placeholder {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        border-radius: 50%;
        border: 2px solid #e0e0e0;
        background-color: #f8f8f8;
        display: flex;
        align-items: center; /* Fixed from 'left' to 'center' */
        justify-content: center;
        overflow: hidden;
        border: 10px solid #D9D9D9;
        box-sizing: border-box; /* Ensure border is included in dimensions */
    }

    .image-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensure aspect ratio is maintained */
        display: block; /* Remove any inline spacing */
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

    .main-message-cont {
        display: flex;
        justify-content: center;
    }

    .user-contact {
        display: flex;
        justify-content: space-between;
    }

        .user-contact1 {
        display: flex;
        justify-content: space-between;
        background-color: rgb(221, 221, 217);
        padding: 15px 10px;
    }

    .profile1 {
        color: white;
        font-weight: bold;
        background-color: rgb(234, 204, 10);
        width: 50px;
        height: auto;
        border-radius: 3.5rem;
        text-align: center;
        padding-top: 10px;
    }

    .profile2 {
        color: white;
        font-weight: bold;
        background-color: rgb(20, 161, 7);
        width: 50px;
        height: auto;
        text-align: center;
        padding-top: 10px;
        border-radius: 3.5rem;
        margin-right: 30px;
    }

    .profile3 {
        color: white;
        font-weight: bold;
        background-color: rgb(11, 146, 209);
        width: 50px;
        height: auto;
        text-align: center;
        padding-top: 10px;
        border-radius: 3.5rem;
        margin-right: 30px;
    }

    .profile4 {
        color: white;
        font-weight: bold;
        background-color: rgb(118, 16, 5);
        width: 50px;
        height: auto;
        text-align: center;
        padding-top: 10px;
        border-radius: 3.5rem;
        margin-right: 30px;
    }

        .profile5 {
        color: white;
        font-weight: bold;
        background-color: rgb(45, 204, 103);
        width: 50px;
        height: auto;
        text-align: center;
        padding-top: 10px;
        border-radius: 3.5rem;
        margin-right: 30px;
    }

    .contacts-indiv {
        margin-bottom: 10px;
    }

    .scrollable-cont {
        max-height: 100px;
        overflow-y: auto;
        padding: 0.5rem;
        border-radius: 4px;
        width: 100%;
    }

    .content-wrap {
        height: calc(100vh - 6rem);
        overflow: auto;
    }

    .search {
        border: 2px solid #700000;
        border-radius: 2rem;
        padding: 3px 3px 3px 10px;
    }

    .middle-message{
        background-color:rgb(199, 198, 198);
        width: 500px;
        border-radius: 5px;
        margin-left: 20px;
        margin-right: 10px;
    }

    .top-column {
        color: white;
        font-weight: bold;
        display: flex;
        padding: 10px 10px 10px 10px;
        border: 1px solid white;
    }

    .chat-pfp {
        background-color: rgb(234, 204, 10);
        width: 50px;
        height: auto;
        text-align: center;
        padding: 10px;
        border-radius: 3.5rem;
        margin-right: 30px;
    }

    .chat-name {
        padding-top: 6px;
    }

    .middle {
        display: flex;
        justify-content: center;
        padding-top: 20px;

    }

    .main-pfp {

        background-color: rgb(234, 204, 10) ;
        font-size: 30px;
        padding: 30px 40px;
        border-radius: 3.5rem;
        text-align: center;
    }

    .main-pfp > h1{
        color: white;
    }

    .chat-name-in{ 
        color: black;
        font-weight: bold;
        margin-top: 10px;
    }

    .title {
        font-size: 10px;
    }

    .chat-cont{
        display: flex;
        justify-content: end;
        margin-right: 10px;
    }

    .chat-bubble {
        background-color: black;
        color: white;
        width: 150px;
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 20px; 
        border-radius: 2rem;
        font-size: 11px;
    }

    .message-box {
        display: flex;
        justify-content:center;
    }

    .chat-message-in {
        border: 2px solid #700000;
        border-radius: 2rem;
        width: 350px;
        margin-right: 10px;
        padding-top: 10px;
        padding-bottom: 10px; 
        padding-left: 10px;
        padding-right: 10px;
    }

    .send {
        border: none;
        background-color: #700000;
        color: white;
        border-radius: 2rem;
        margin-left: 10px;
        padding-top: 10px;
        padding-bottom: 10px; 
        padding-left: 10px;
        padding-right: 10px;
    }

    .right-message {
        background-color:rgb(227, 226, 226);
    }


    .files-box{
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .files {
        background-color: #700000;
        color: white;
        width: 100px;
        padding-top:20px;
        padding-bottom:20px;
        padding-left:20px;
        padding-right:20px;
        display:flex;
        justify-content: center;
        text-align:center;
        margin-right: 10px;
        border-radius: 6px;
    }

    .images {
        background-color:rgb(172, 168, 168);
        color: white;
        width: 100px;
        padding-top:20px;
        padding-bottom:20px;
        padding-left:20px;
        padding-right:20px;
        display:flex;
        justify-content: center;
        text-align:center;
        margin-left: 10px;
        margin-right: 10px;
        border-radius: 6px;
    }

    .content-holder {
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px;
    }

 hr {
        background-color: black;
    }

    .no-docs {
        margin-top: 20px;
        margin-left: 60px;
    }

    .file-name {
        margin-top: 5px;
    }
</style>

<body>
<?php require_once 'templates/coordinators_navbar.php'; ?>

<div class="content-wrap" style="width: 100%; margin: 0 auto;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div class="main-message-cont">
            <div class="left-message">
                <div class="page-title">
                    <h6><b>MESSAGE</b></h6>
                    <br><br>
                    <div class="profile-image">
                        <div class="image-placeholder">
                            <img src="<?= $currentImagePath ?: 'images/placeholder.png' ?>" alt="Profile">
                        </div>
                        
                        <h1>
                            <b><?php echo $result['first_name'];?></b>
                        </h1>
                        <br>
                        <div class="search-filter">
                            <input type="text" placeholder="Search..." class="search">
                        </div>
                        <br>
                        <div class="contacts">
                            Contacts
                        </div>
                        <br>

                        <div class="scrollable-cont">
                            <div class="contacts-indiv">
                                <div class="user-contact1">
                                    <div class="profile1">
                                        A
                                    </div>
                                    <div class="userData">
                                        <div class="name">
                                            <b>Alen Jeru Ganotice</b>
                                        </div>
                                        <div class="message">You: Good morning student!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="contacts-indiv">
                                <div class="user-contact">
                                    <div class="profile2">
                                        W
                                    </div>
                                    <div class="userData">
                                        <div class="name">
                                            <b>warren Baugbog</b>
                                        </div>
                                        <div class="message">You: Good morning student!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="contacts-indiv">
                                <div class="user-contact">
                                    <div class="profile3">
                                        B
                                    </div>
                                    <div class="userData">
                                        <div class="name">
                                            <b>Bryan Batumbakal</b>
                                        </div>
                                        <div class="message">You: Good morning student!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="contacts-indiv">
                                <div class="user-contact">
                                    <div class="profile4">
                                        C
                                    </div>
                                    <div class="userData">
                                        <div class="name">
                                            <b>Chiana Karina</b>
                                        </div>
                                        <div class="message">You: Good morning student!</div>
                                    </div>
                                </div>
                            </div>
                            <div class="contacts-indiv">
                                <div class="user-contact">
                                    <div class="profile5">
                                        D
                                    </div>
                                    <div class="userData">
                                        <div class="name">
                                            <b>Danica Labanan</b>
                                        </div>
                                        <div class="message">You: Good morning student!</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="middle-message">
                <div class="top-column">
                    <div class="chat-pfp">
                        A
                    </div>
                    <div class="chat-name">Alen Jeru Ganotice</div>
                </div>

                <div class="middle">
                    <div class="middle-pfp">
                        <div class="main-pfp">
                            <h1>A</h1>
                        </div>
                            <div class="chat-name-in">
                                Alen Jeru Ganotice
                            </div>
                            <div class="title">
                                PUP ITECH | STUDENT
                            </div>
                    </div>

                </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="chat-cont">
                        <div class="chat-bubble">
                            Good morning student!
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="message-box">
                        <div class="chat-message">
                            <input type="text" class="chat-message-in" placeholder="Send a message...">
                        </div>

                        <button class="send">
                            <b>Send</b>
                        </button>
                    </div>
            </div>

            <div class="right-message">
                <div class="content-holder">
                 <div class="right-header">
                    <h6><b>Files</b></h6>
                </div>

                <div class="files-box">
                    <div class="files">
                        <i class="fa-solid fa-folder">
                    </i></div>
                    <div class="images">
                        <i class="fa-solid fa-images"></i>
                    </div>
                </div>
                <div class="line"><hr></div>
                </div>

                <div class="no-docs">No documents yet</div>
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