<?php
require('../database/db_yeokart.php');
session_start();

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_POST['id'])) {
    $notification_id = $_POST['id'];
    // Delete the notification from the database
    $delete_query = "DELETE FROM notifications WHERE id = $notification_id AND customer_id = $customer_id";
    if ($con->query($delete_query) === TRUE) {
        // Notification deleted successfully
    } else {
        // Error deleting notification
    }
} else {
    // Invalid request
}
