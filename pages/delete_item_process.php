<?php
session_start();
require('../database/db_yeokart.php');

if (isset($_POST['cart_id']) && isset($_SESSION['id'])) {
    $cartId = $_POST['cart_id'];
    $customerId = $_SESSION['id'];

    $sql = "DELETE FROM cart WHERE cart_id = ? AND customer_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $cartId, $customerId);

    if ($stmt->execute()) {
    } else {
        echo "An error occurred.";
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid request.";
}
