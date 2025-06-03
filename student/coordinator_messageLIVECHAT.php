<?php
include '../connection/config.php';

//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();

if($_SESSION['auth_user']['student_id']==0){
  echo"<script>window.location.href='index.php'</script>";
  
}

if (isset($_POST['userUNIQUEid_receiver'])) {

    // $studId_sender = $_SESSION['auth_user']['student_id'];

    $stud_uniqueId_receiver = $_POST['userUNIQUEid_receiver'];
    
    $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE uniqueID = ?");
    $stmt->execute([$stud_uniqueId_receiver]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
    <div class="py-2 px-4 border-bottom d-none d-lg-block">
        <div class="d-flex align-items-center py-1">
        <div class="position-relative">
            <img src="<?php echo $results['coordinators_profile_picture']; ?>" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
        </div>
        <div class="flex-grow-1 pl-3">
            <strong><?php echo $results['first_name']; ?> <?php echo $results['middle_name']; ?> <?php echo $results['last_name']; ?></strong>
            <div class="text-muted small"><em>Typing...</em></div>
        </div>
        </div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">
          <i class="ti-gallery"></i> <i class="ti-plus"></i>
        </button>
        
        <!-- Modal -->
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
                                <label for=""></label>
                                <input type="hidden" name="receiver_id" value="<?php echo $results['uniqueID']; ?>">
                                <input type="file" class="form-control-file" name="img_toSEND" id="imgtoSEND" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" name="send_img" class="btn btn-primary" id="sendButton">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    <?php
   if (isset($_POST['userUNIQUEid_receiver'])) {
    // Fetch messages between sender and receiver
    $senderId = $_SESSION['auth_user']['student_uniqueID'];
    $receiverId = $_POST['userUNIQUEid_receiver'];

    // Modify your SQL query to fetch messages between sender and receiver
    $stmt = $conn->prepare("SELECT * FROM chat_system
        LEFT JOIN students_data ON students_data.uniqueID = chat_system.sender_id
        LEFT JOIN coordinators_account ON coordinators_account.uniqueID = chat_system.sender_id
        WHERE (chat_system.sender_id = ? AND chat_system.receiver_id = ?) OR (chat_system.sender_id = ? AND chat_system.receiver_id = ?)
        ORDER BY chat_system.id ASC");
    $stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="position-relative">
        <div class="chat-messages p-4" id="chatMessages">
            <?php foreach ($messages as $message) : ?>
                <?php if ($message['sender_id'] == $senderId) : ?>
                    <div class="chat-message-right pb-4">
                        <div>
                            <img src="<?= $message['profile_picture']; ?>" class="rounded-circle mr-1" alt="You" width="40" height="40">
                            <div class="text-muted small text-nowrap mt-2"><?= $message['time_only']; ?></div>
                            <div class="text-muted small text-nowrap mt-2"><?= $message['status']; ?></div>
                        </div>
                        <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                            <div class="font-weight-bold mb-1">You</div>
                            <?php if (!empty($message['messages'])) : ?>
                                <?= $message['messages']; ?>
                            <?php elseif (!empty($message['images'])) : ?>
                                <img src="<?= $message['images']; ?>" alt="Image" width="200">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="chat-message-left pb-4">
                        <div>
                            <img src="<?= $message['coordinators_profile_picture']; ?>" class="rounded-circle mr-1" alt="<?= $message['first_name']; ?>" width="40" height="40">
                            <div class="text-muted small text-nowrap mt-2"><?= $message['time_only']; ?></div>
                            <div class="text-muted small text-nowrap mt-2"><?= $message['status']; ?></div>
                        </div>
                        <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                            <div class="font-weight-bold mb-1"><?= $message['first_name']; ?> <?= $message['middle_name']; ?> <?= $message['last_name']; ?></div>
                            <?php if (!empty($message['messages'])) : ?>
                                <?= $message['messages']; ?>
                            <?php elseif (!empty($message['images'])) : ?>
                                <img src="<?= $message['images']; ?>" alt="Image" width="200">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php
} else {
    // Handle the case where 'user_id_receiver' is not set
    echo "No receiver specified.";
}
?>

    <form action="" method="POST" id="messageForm">
    <div class="flex-grow-0 py-3 px-4 border-top">
        <div class="input-group">
        <textarea class="form-control" name="message" placeholder="Type your message"></textarea>
        <button class="btn btn-primary" name="sendMessage">Send</button>
        </div>
    </div>
    </form>
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
$(document).ready(function() {
    
    $(document).ready(function () {
    $("#sendButton").click(function () {
        var formData = new FormData($("#imageForm")[0]);
        var receiverId = $("#imageForm input[name='receiver_id']").val();

        // Add the receiver_id to the FormData object
        formData.append("receiver_id", receiverId);

        $.ajax({
            type: "POST",
            url: "send_image_to_coordinator.php",
            data: formData,
            processData: false, // Prevent jQuery from processing data
            contentType: false, // Prevent jQuery from setting content type
            success: function (response) {
                // Handle the response from the server
                alert(response); // You can replace this with your own logic
            },
            error: function (xhr, status, error) {
                // Handle errors, if any
                console.error(xhr.responseText);
            }
        });
    });
});

    // Submitting a new message
    $("#messageForm").submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally
        var message = $("textarea[name='message']").val();
        var receiverId = <?php echo json_encode($receiverId); ?>;

        $.ajax({
            type: "POST",
            url: "send_user_chat.php",
            data: { message: message, receiver_id: receiverId },
            success: function(data) {
                
                    // Clear the textarea
                    $("textarea[name='message']").val("");
            }
        });
    });

           $(function(){
            var receiverId = <?php echo json_encode($receiverId); ?>;
             setInterval(function(){
              $.ajax({
                type: "POST",
                url: "coordinatorLOAD_NewMessage.php", // Create a separate PHP file to check for new messages
                data: { receiver_id: receiverId },
                success:function(data){
                    $("#chatMessages").html(data);
                }
              });   
             }, 100);
           });

           
    
});


</script>


    <script>
  // JavaScript to scroll to the bottom of the chat messages div
  var chatMessages = document.getElementById("chatMessages");
  chatMessages.scrollTop = chatMessages.scrollHeight;
</script>