<?php
include '../connection/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['auth_user']['coordinators_id'])) {
    die("Error: User is not authenticated.");
}

$coordinatorId = $_SESSION['auth_user']['coordinators_id'];

// Fetch current user's profile
$sql = $conn->prepare("SELECT coordinators_profile_picture, first_name FROM coordinators_account WHERE id = ?");
$sql->execute([$coordinatorId]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
$currentImagePath = $row['coordinators_profile_picture'] ?? 'images/profile.png';
$coordinatorName = $row['first_name'];

// Fetch active students
$stmt = $conn->prepare("SELECT id, uniqueID, first_name, last_name, profile_picture, online_offlineStatus FROM students_data WHERE verify_status = 'Verified'");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $stmt = $conn->prepare("INSERT INTO chat_system (sender_id, receiver_id, messages, date_only, time_only, status) VALUES (?, ?, ?, ?, ?, 'Sent')");
    $stmt->execute([$coordinatorId, $receiverId, $message, $date, $time]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OJT Web Portal: Chats</title>
    <!-- ================= Favicon ================== -->   
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
        align-items: center; 
        justify-content: center;
        overflow: hidden;
        border: 10px solid #D9D9D9;
        box-sizing: border-box; 
    }

    .image-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        display: block; 
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

    .chat-messages {
            max-height: 400px;
            overflow-y: auto;
            padding: 1rem;
        }
        .message-item {
            margin-bottom: 1rem;
            max-width: 70%;
        }
        .sent {
            background-color: #007bff;
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-left: auto;
        }
        .received {
            background-color: #f1f3f5;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
        .resource-panel {
            border-left: 1px solid #ddd;
            padding: 1rem;
        }
</style>

<body>
<?php require_once 'templates/coordinators_navbar.php'; ?>

<div class="content-wrap" style="width: 100%; margin: 0 auto;">
    <div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem; display: flex;">
        <!-- Left Panel: Student List -->
        <div class="left-panel" style="width: 30%; border-right: 1px solid #ddd; padding-right: 1rem;">
            <h6><b>MESSAGE</b></h6>
            <br>
            <div class="search-filter">
                <input type="text" id="searchStudent" placeholder="Search..." class="search">
            </div>
            <br>
            <div id="studentList">
                <?php foreach ($students as $student): ?>
                    <div class="contacts-indiv" onclick="loadChat('<?php echo $student['uniqueID']; ?>', '<?php echo $student['first_name'] . ' ' . $student['last_name']; ?>', '<?php echo $student['profile_picture']; ?>')">
                        <div class="user-contact1">
                            <div class="profile1" style="background-color: <?php echo $student['online_offlineStatus'] === 'Online' ? '#34ce57' : '#e4606d'; ?>">
                                <?php echo strtoupper(substr($student['first_name'], 0, 1)); ?>
                            </div>
                            <div class="userData">
                                <div class="name"><b><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></b></div>
                                <div class="message">Last message...</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Middle Panel: Chat Container -->
        <div class="middle-panel" style="width: 40%; padding: 0 1rem;">
            <div class="top-column" id="chatHeader" style="display: none;">
                <div class="chat-pfp" id="chatPfp"></div>
                <div class="chat-name" id="chatName"></div>
            </div>
            <div class="chat-messages" id="chatMessages"></div>
            <div class="message-box">
                <input type="text" id="messageInput" class="chat-message-in" placeholder="Send a message...">
                <button class="send" onclick="sendMessage()">Send</button>
            </div>
        </div>

        <!-- Right Panel: Resource Panel -->
        <div class="resource-panel" style="width: 30%; padding-left: 1rem;">
            <h6><b>Files</b></h6>
            <div id="resourcePanel">
                <p>No documents yet</p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadChat(receiverId, name, profilePic) {
    $('#chatHeader').show().data('receiver-id', receiverId);
    $('#chatPfp').text(name.charAt(0).toUpperCase());
    $('#chatName').text(name);
    loadChatHistory(receiverId);
    loadDocuments(receiverId);
}

function loadChatHistory(receiverId) {
    $.ajax({
        url: 'load_chat_history.php',
        method: 'POST',
        data: { sender_id: <?php echo $coordinatorId; ?>, receiver_id: receiverId },
        success: function(response) {
            $('#chatMessages').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading chat history:', error);
        }
    });
}

function sendMessage() {
    const message = $('#messageInput').val().trim();
    const receiverId = $('#chatHeader').data('receiver-id');
    if (message && receiverId) {
        $.ajax({
            url: 'send_message.php',
            method: 'POST',
            dataType: 'json', // Expect JSON response
            data: { sender_id: <?php echo $coordinatorId; ?>, receiver_id: receiverId, message: message },
            success: function(response) {
                console.log('Response:', response); // Debug the response
                if (response.status === 'success') {
                    $('#messageInput').val('');
                    loadChatHistory(receiverId);
                } else {
                    console.error('Send message failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                if (xhr.responseText) {
                    console.log('Raw response:', xhr.responseText); // Log raw response
                }
            }
        });
    } else {
        console.log('Message or receiver ID is missing');
    }
}

function loadDocuments(receiverId) {
    $.ajax({
        url: 'load_documents.php',
        method: 'POST',
        data: { receiver_id: receiverId },
        success: function(response) {
            $('#resourcePanel').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading documents:', error);
        }
    });
}

// Handle Enter key press
$('#messageInput').on('keypress', function(e) {
    if (e.which === 13) {
        e.preventDefault();
        sendMessage();
    }
});

// Auto-refresh chat
setInterval(function() {
    const receiverId = $('#chatHeader').data('receiver-id');
    if (receiverId) {
        loadChatHistory(receiverId);
        loadDocuments(receiverId);
    }
}, 5000);

// Search functionality
$('#searchStudent').on('keyup', function() {
    const query = $(this).val().toLowerCase();
    $('#studentList .contacts-indiv').each(function() {
        const name = $(this).find('.name b').text().toLowerCase();
        $(this).toggle(name.includes(query));
    });
});
</script>