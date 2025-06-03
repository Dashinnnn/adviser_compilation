<?php

include '../connection/config.php';
//display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if($_SESSION['auth_user']['student_id']==0){
  echo"<script>window.location.href='index.php'</script>";
  
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
    <!-- Standard -->
    <link rel="shortcut icon" href="images/Picture1.png">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
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
      .chat-online {
    color: #34ce57
}

.chat-offline {
    color: #e4606d
}

.chat-messages {
    display: flex;
    flex-direction: column;
    max-height: 800px;
    overflow-y: scroll
}

.chat-message-left,
.chat-message-right {
    display: flex;
    flex-shrink: 0
}

.chat-message-left {
    margin-right: auto
}

.chat-message-right {
    flex-direction: row-reverse;
    margin-left: auto
}
.py-3 {
    padding-top: 1rem!important;
    padding-bottom: 1rem!important;
}
.px-4 {
    padding-right: 1.5rem!important;
    padding-left: 1.5rem!important;
}
.flex-grow-0 {
    flex-grow: 0!important;
}
.border-top {
    border-top: 1px solid #dee2e6!important;
}
    </style>
</head>

<body>
<!---------NAVIGATION BAR-------->
<?php
require_once 'templates/stud_navbar.php';
?>
<!---------NAVIGATION BAR ENDS-------->



<div class="content-wrap" style="height: 80%; width: 100%;margin: 0 auto;">
<div style="background-color: white; margin-top: 6rem; margin-left: 16rem; padding: 2rem;">
        <div>
          
          <!-- /# row -->
          <div>
            
            <div>

            <div class="page-header">
                            <div class="page-title">
                                <h1 style="font-size: 16px;"><b>Messages</b></h1>
                            </div>
                        </div>
          
              <div>
                <div>
                <div>
                    <div>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <input type="text" class="form-control my-3" placeholder="Search...">
                        </div>
                      </div>
                    </div>

                      <?php

                      $course = $_SESSION['auth_user']['student_course'];

                        $stmt = $conn->prepare("SELECT * FROM coordinators_account WHERE course_handled = ?");
                        $stmt->execute([$course]);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($results as $res) {
                        ?>
                          <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0" onclick="loadConversation('<?php echo $res['uniqueID']; ?>');">
                            <div class="badge bg-success float-right count_RECEIVEDmessages_<?php echo $res['uniqueID']; ?>">n/a</div>
                            <div class="d-flex align-items-start">
                              <img src="<?php echo $res['coordinators_profile_picture']; ?>" class="rounded-circle mr-1" alt="<?php echo $res['first_name'] . ' ' . $res['middle_name'] . ' ' . $res['last_name']; ?>" width="40" height="40">
                              <div class="flex-grow-1 ml-3">
                                <?php echo $res['first_name'] . ' ' . $res['middle_name'] . ' ' . $res['last_name']; ?>
                                <div class="small">
                                  <span class="fas fa-circle <?php echo $res['online_offlineStatus'] === 'Online' ? 'chat-online' : 'chat-offline'; ?>"></span> <?php echo $res['online_offlineStatus']; ?>
                                </div>
                              </div>
                            </div>
                          </a>
                      <?php } ?>

                    <hr class="d-block d-lg-none mt-1 mb-0">
                  </div>

                  <div class="col-12 col-lg-7 col-xl-9" id="LIVEchat">    
          
                  </div>

                </div>
              </div>
            </div>


            
          </div>
        </div>
      </div>
    </div>



    



    <!-- Common -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
  $(function(){
    <?php foreach ($results as $res) { ?>
      var receiverId_<?php echo $res['uniqueID']; ?> = <?php echo json_encode($res['uniqueID']); ?>;
      var countElement_<?php echo $res['uniqueID']; ?> = $(".count_RECEIVEDmessages_<?php echo $res['uniqueID']; ?>");

      function updateMessageCount_<?php echo $res['uniqueID']; ?>() {
        $.ajax({
          type: "POST",
          url: "load_receivedNewMessage.php",
          data: { receiver_id: receiverId_<?php echo $res['uniqueID']; ?> },
          success: function(data) {
            countElement_<?php echo $res['uniqueID']; ?>.html(data);
          }
        });
      }

      // Call the updateMessageCount function initially and set up a polling interval to check for new messages
      updateMessageCount_<?php echo $res['uniqueID']; ?>();
      setInterval(updateMessageCount_<?php echo $res['uniqueID']; ?>, 1000); // Update every 1 second, adjust as needed
    <?php } ?>
  });
});
</script>

<script>
  function loadConversation(user_uniqueId_receiver) {
    // Reload the page with a query parameter to indicate the selected user
    window.location.href = 'coordinator_studMESSAGE.php?userUNIQUEid_receiver=' + user_uniqueId_receiver;
  }

  // This function retrieves the conversation for the selected user using AJAX
  function loadSelectedUserConversation() {
    // Check if the user's unique ID is present in the URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const user_uniqueId_receiver = urlParams.get('userUNIQUEid_receiver');
    
    if (user_uniqueId_receiver) {
      // Send an AJAX request to load the conversation data
      $.ajax({
        url: 'coordinator_messageLIVECHAT.php', // Replace with the actual URL to load conversation data
        method: 'POST',
        data: {
          userUNIQUEid_receiver: user_uniqueId_receiver
        },
        success: function(response) {
          // Load the conversation data into the #LIVEchat div
          $('#LIVEchat').html(response);
        },
        error: function() {
          alert('Error loading conversation');
        }
      });
    }
  }

  // Call the function to load conversation on page load
  $(document).ready(function() {
    loadSelectedUserConversation();
  });
</script>


</body>


</html>