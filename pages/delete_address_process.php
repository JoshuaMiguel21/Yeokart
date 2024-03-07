<?php
session_start();
require('../database/db_yeokart.php');

if(isset($_POST['addressId']) && isset($_SESSION['id'])) {
    $addressId = $_POST['addressId'];
    $customerId = $_SESSION['id'];

    $sql = "DELETE FROM addresses WHERE address_id = ? AND customer_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $addressId, $customerId);
    
    if ($stmt->execute()) {
        echo "Address deleted successfully.";
    } else {
        echo "An error occurred.";
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid request.";
}
?>
