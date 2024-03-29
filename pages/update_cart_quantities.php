<?php
session_start();

require('../database/db_yeokart.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!isset($_SESSION['id'])) {
        header("Location: login_page.php");
        exit();
    }

    $customer_id = $_SESSION['id'];

    foreach ($_POST['quantity'] as $cartId => $quantity) {
        // Update the quantity for each cart item
        $update_query = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cartId";
        mysqli_query($con, $update_query);
    }

    // Redirect to order summary page
    header("Location: order_summary.php");
    exit();
} else {
    // Redirect back to cart page if checkout button is not pressed
    header("Location: customer_cart.php");
    exit(); 
}
?>
