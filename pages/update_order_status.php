<?php
include('../database/db_yeokart.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update order status in the database using prepared statements to prevent SQL injection
    $update_query = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "ss", $status, $order_id);
    mysqli_stmt_execute($stmt);

    // Close the prepared statement
    mysqli_stmt_close($stmt);
    exit(); // Exit after updating the status to prevent further execution of the script
}
