<?php
include('../database/db_yeokart.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if (isset($_SESSION['email']) && isset($_SESSION['firstname'])) {
        $session_email = strtolower($_SESSION['email']);
        $firstname = $_SESSION['firstname'];

        $check_admin_query = "SELECT * FROM admin_account WHERE email = ? AND firstname = ?";
        $stmt = mysqli_prepare($con, $check_admin_query);
        mysqli_stmt_bind_param($stmt, "ss", $session_email, $firstname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $activity_text = "Owner $firstname updated order status to $status";
        } else {
            $check_employee_query = "SELECT * FROM employee_accounts WHERE email = ? AND firstname = ?";
            $stmt = mysqli_prepare($con, $check_employee_query);
            mysqli_stmt_bind_param($stmt, "ss", $session_email, $firstname);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $activity_text = "Employee $firstname updated order status to $status";
            } else {
                echo "Unauthorized access.";
                exit();
            }
        }

        $update_query = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $status, $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        switch ($status) {
            case "Shipped":
                $notification_message = "Your order (ID: $order_id) has been shipped and is on its way to you.";
                break;
            case "Pending":
                $notification_message = "Your order (ID: $order_id) is still pending. We will update you once it progresses.";
                break;
            case "Delivered":
                $notification_message = "Your order (ID: $order_id) has been delivered. Thank you for shopping with us!";
                break;
            case "Processing":
                $notification_message = "Your order (ID: $order_id) is currently being processed.";
                break;
            case "Invalid":
                $notification_message = "The proof of payment for your order (ID: $order_id) has been found to be invalid. Please upload a valid proof of payment or contact support.";
                break;
            default:
                $notification_message = "There has been an update to your order (ID: $order_id). Please check your order details for the current status.";
                break;
        }

        date_default_timezone_set('Asia/Manila');
        $formatted_datetime = date("F d, Y") . ", " . date("h:i:s A");

        $insert_query = "INSERT INTO `activity_logs` (`order_id`, `activity_text`, `activity_time`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "sss", $order_id, $activity_text, $formatted_datetime);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $customer_id_query = "SELECT customer_id FROM orders WHERE order_id = ?";
        $stmt = mysqli_prepare($con, $customer_id_query);
        mysqli_stmt_bind_param($stmt, "s", $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $customer_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $notification_title = "Order Status Update";

        // Updated to include order_id in the insert statement
        $insert_notification_query = "INSERT INTO notifications (customer_id, title, message, order_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_notification_query);
        mysqli_stmt_bind_param($stmt, "isss", $customer_id, $notification_title, $notification_message, $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        exit();
    }
    echo "Unauthorized access.";
    exit();
}
?>
