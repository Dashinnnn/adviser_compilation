<?php
include '../connection/config.php';
session_start();

if (isset($_POST['receiver_id'])) {
    $senderId = $_SESSION['auth_user']['supervisor_uniqueID'];
    $receiverId = $_POST['receiver_id'];

    $stmt = $conn->prepare("SELECT * FROM chat_system
    LEFT JOIN supervisor ON supervisor.uniqueID = chat_system.sender_id
    LEFT JOIN students_data ON students_data.uniqueID = chat_system.sender_id
    WHERE (chat_system.sender_id = ? AND chat_system.receiver_id = ?) OR (chat_system.sender_id = ? AND chat_system.receiver_id = ?)
    ORDER BY chat_system.id ASC");

    $stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Construct HTML for all messages
    foreach ($messages as $message) {
        if ($message['sender_id'] == $senderId) {
            ?>
            <div class="chat-message-right pb-4">
                <div>
                    <img src="<?= $message['supervisor_profile_picture']; ?>" class="rounded-circle mr-1" alt="You" width="40" height="40">
                    <div class="text-muted small text-nowrap mt-2"><?= $message['time_only']; ?></div>
                    <div class="text-muted small text-nowrap mt-2"><?= $message['status']; ?></div>
                </div>
                <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                    <div class="font-weight-bold mb-1">You</div>
                    <?php if (!empty($message['messages'])) : ?>
                            <?= $message['messages']; ?>
                        <?php else : ?>
                            <img src="<?= $message['images']; ?>" alt="Image" width="200">
                        <?php endif; ?>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="chat-message-left pb-4">
                <div>
                    <img src="<?= $message['profile_picture']; ?>" class="rounded-circle mr-1" alt="<?= $message['first_name']; ?>" width="40" height="40">
                    <div class="text-muted small text-nowrap mt-2"><?= $message['time_only']; ?></div>
                    <div class="text-muted small text-nowrap mt-2"><?= $message['status']; ?></div>
                </div>
                <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                    <div class="font-weight-bold mb-1"><?= $message['first_name']; ?> <?= $message['middle_name']; ?> <?= $message['last_name']; ?></div>
                    <?php if (!empty($message['messages'])) : ?>
                            <?= $message['messages']; ?>
                        <?php else : ?>
                            <img src="<?= $message['images']; ?>" alt="Image" width="200">
                        <?php endif; ?>
                </div>
            </div>
            <?php
        }
    }
    $message_status = 'Seen';

    $stmt = $conn->prepare("UPDATE chat_system SET status = ? WHERE sender_id = ?");
    $stmt->execute([$message_status, $receiverId]);
    $messages = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
