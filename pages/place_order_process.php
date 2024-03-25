<?php
session_start();

if (!isset($_SESSION['id'])) {
    exit(json_encode(['success' => false, 'error' => 'User not logged in']));
}

include('../database/db_yeokart.php');

$customer_id = $_POST['customer_id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$address = $_POST['address'];
$total = $_POST['total'];
$items_ordered = $_POST['items_ordered']; // items_ordered JSON object

// Generate a random non-duplicate UUID for order_id
$order_id = uniqid();

// Insert the order details into the orders table
$insert_query = "INSERT INTO orders (order_id, customer_id, firstname, lastname, address, total, items_ordered, date_of_purchase) VALUES ('$order_id', '$customer_id', '$firstname', '$lastname', '$address', '$total', '$items_ordered', NOW())";
$result = mysqli_query($con, $insert_query);

if ($result) {
    // Delete items from the cart table
    $delete_query = "DELETE FROM cart WHERE customer_id = $customer_id";
    if (mysqli_query($con, $delete_query)) {
        exit(json_encode(['success' => true]));
    } else {
        exit(json_encode(['success' => false, 'error' => 'Failed to delete items from cart', 'mysql_error' => mysqli_error($con)]));
    }
} else {
    exit(json_encode(['success' => false, 'error' => 'Failed to insert order details', 'mysql_error' => mysqli_error($con)]));
}
