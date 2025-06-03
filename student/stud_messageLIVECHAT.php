<?php
include '../connection/config.php';

// Display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SESSION['auth_user']['student_id'] == 0) {
    echo "<script>window.location.href='index.php'</script>";
}

if (isset($_POST['userUNIQUEid_receiver'])) {
    $stud_uniqueId_receiver = $_POST['userUNIQUEid_receiver'];

    $stmt = $conn->prepare("SELECT * FROM students_data WHERE uniqueID = ?");
    $stmt->execute([$stud_uniqueId_receiver]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="py-2 px-4 d-lg-block"  style="background-color: rgb(212, 212, 212); margin-top: -6.8rem;">
    <div class="d-flex align-items-center py-1">
        <div class="position-relative">
            <img src="<?php echo $results['profile_picture']; ?>" class="rounded-circle mr-1" alt="User" width="40" height="40">
        </div>
        <div class="flex-grow-1 pl-3">
            <strong><?php echo $results['first_name'] . " " . $results['middle_name'] . " " . $results['last_name']; ?></strong>
            <div class="text-muted small"><em>Typing...</em></div>
        </div>
    </div>

    <!-- Modal for image file -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Image File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="imageForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="receiver_id" value="<?php echo $results['uniqueID']; ?>">
                            <input type="file" class="form-control-file" name="img_toSEND" id="imgtoSEND" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="sendButton">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for document file -->
    <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="documentForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="receiver_id" value="<?php echo $results['uniqueID']; ?>">
                            <input type="file" class="form-control-file" name="doc_toSEND" id="docToSEND" accept=".pdf,.doc,.docx,.ppt,.txt,.xls,.xlsx">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="sendDocumentButton">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="position-relative">
    <div id="chatMessages" style="padding: 20px; background-color:rgb(212, 212, 212); overflow-y: scroll; scrollbar-width: none; -ms-overflow-style: none; height: 30rem;">
        <?php
        if (isset($_POST['userUNIQUEid_receiver'])) {
            $senderId = $_SESSION['auth_user']['student_uniqueID'];
            $receiverId = $_POST['userUNIQUEid_receiver'];

            $stmt = $conn->prepare("SELECT * FROM chat_system 
                LEFT JOIN students_data ON students_data.uniqueID = chat_system.sender_id 
                WHERE (chat_system.sender_id = ? AND chat_system.receiver_id = ?) 
                OR (chat_system.sender_id = ? AND chat_system.receiver_id = ?) 
                ORDER BY chat_system.id ASC");
            $stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($messages as $message) {
                if ($message['sender_id'] == $senderId) {
                    echo '<div class="chat-message-right pb-4">
                            <div>
                                <img src="' . $message['profile_picture'] . '" class="rounded-circle mr-1" width="40" height="40">
                                <div class="text-muted small text-nowrap mt-2">' . $message['time_only'] . '</div>
                            </div>
                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                <div class="font-weight-bold mb-1">You</div>';
                    // Display text message
                    if (!empty($message['messages'])) {
                        echo $message['messages'];
                    } 
                    // Display image if available
                    if (!empty($message['images'])) {
                        echo '<img src="' . $message['images'] . '" alt="Image" width="200">';
                    }
                    // Display document if available
                    if (!empty($message['documents'])) {
                        echo '<a href="' . $message['documents'] . '" target="_blank" class="btn btn-link">Download Document</a>';
                    }
                    echo '</div></div>';
                } else {
                    echo '<div class="chat-message-left pb-4">
                            <div>
                                <img src="' . $message['profile_picture'] . '" class="rounded-circle mr-1" width="40" height="40">
                                <div class="text-muted small text-nowrap mt-2">' . $message['time_only'] . '</div>
                            </div>
                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                                <div class="font-weight-bold mb-1">' . $message['first_name'] . '</div>';
                    // Display text message
                    if (!empty($message['messages'])) {
                        echo $message['messages'];
                    } 
                    // Display image if available
                    if (!empty($message['images'])) {
                        echo '<img src="' . $message['images'] . '" alt="Image" width="200">';
                    }
                    // Display document if available
                    if (!empty($message['documents'])) {
                        echo '<a href="' . $message['documents'] . '" target="_blank" class="btn btn-link">Download Document</a>';
                    }
                    echo '</div></div>';
                }
            }
        }
        ?>
    </div>
</div>


<form action="" method="POST" id="messageForm" style="padding: 20px; background-color:rgb(212, 212, 212);">
    <div class="flex-grow-0">
        <div class="input-group">
            <!-- Button trigger modal for image -->
            <button type="button" data-toggle="modal" data-target="#modelId" style="background: none; border: none;">
                <img style="height: 30px;" src="images/attach.png">
            </button>

            <!-- Button trigger modal for document -->
            <button type="button" data-toggle="modal" data-target="#documentModal" style="background: none; border: none;">
                <img style="height: 30px;" src="images/document.png">
            </button>

            <textarea class="form-control" name="message" placeholder="Aa" 
            style="border: 2px solid #ccc; 
                 border-radius: 8px; font-size: 16px; resize: none; 
                 outline: none; transition: 0.3s;
            "></textarea>

            <button style="background:none; color: white; border: none;" name="sendMessage">
               <img style=" height: 30px;" src="images/send.png" alt="">
            </button>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let chatMessages = $("#chatMessages");

    // Send image using AJAX
    $("#sendButton").click(function() {
        let formData = new FormData($("#imageForm")[0]);

        $.ajax({
            type: "POST",
            url: "send_image_to_user.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                fetchMessages();
            }
        });
    });

    // Send document using AJAX
    $("#sendDocumentButton").click(function() {
        let formData = new FormData($("#documentForm")[0]);

        $.ajax({
            type: "POST",
            url: "send_document_to_user.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                fetchMessages();
            }
        });
    });

    // Send text message using AJAX
    $("#messageForm").submit(function(e) {
        e.preventDefault();
        let message = $("textarea[name='message']").val();

        $.ajax({
            type: "POST",
            url: "send_user_chat.php",
            data: { message: message, receiver_id: <?= json_encode($receiverId) ?> },
            success: function() {
                $("textarea[name='message']").val("");
                fetchMessages();
            }
        });
    });

    // Load new messages and always scroll to bottom
    function fetchMessages() {
        $.ajax({
            type: "POST",
            url: "load_NewMessage.php",
            data: { receiver_id: <?= json_encode($receiverId) ?> },
            success: function(data) {
                chatMessages.html(data);
                // Always scroll to the bottom of the chat
                chatMessages.scrollTop(chatMessages[0].scrollHeight);
            }
        });
    }

    // Initial scroll to bottom when page loads
    chatMessages.scrollTop(chatMessages[0].scrollHeight);

    // Refresh messages every 3 seconds and scroll to bottom
    setInterval(fetchMessages, 3000);
});
</script>
