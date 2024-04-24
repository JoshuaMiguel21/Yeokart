<?php
session_start();
require('../database/db_yeokart.php');

if (isset($_POST['id'])) {
    $notificationId = $_POST['id'];
    $sql = "UPDATE notifications SET read_status = 1 WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Notification updated.";
    } else {
        echo "No changes made or error occurred.";
    }
    $stmt->close();
}
$con->close();
?>
