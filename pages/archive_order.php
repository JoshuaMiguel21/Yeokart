<?php
session_start();
require('../database/db_yeokart.php');

if (isset($_POST['orderId']) && isset($_SESSION['id'])) {
    $orderId = $_POST['orderId'];
    $customerId = $_SESSION['id'];

    $stmt = $con->prepare("UPDATE orders SET is_archive = 1 WHERE order_id = ? AND customer_id = ?");
    $stmt->bind_param("si", $orderId, $customerId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Order ID or Customer ID not provided']);
}

?>
