<?php
session_start();

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
    require('../database/db_yeokart.php');

    $sql = "UPDATE user_accounts SET is_accepted = 1 WHERE id = $customer_id";
    if ($con->query($sql) === TRUE) {
        // Redirect the user back to the homepage
        header("Location: customer_homepage.php");
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }

    $con->close();
} else {
    echo "User not logged in.";
}
