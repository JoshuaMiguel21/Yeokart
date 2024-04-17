<?php
include('../database/db_yeokart.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if (isset($_SESSION['email']) && isset($_SESSION['firstname'])) {
        $session_email = strtolower($_SESSION['email']);
        $firstname = $_SESSION['firstname'];

        // Check if the session belongs to an admin
        $check_admin_query = "SELECT * FROM admin_account WHERE email = ? AND firstname = ?";
        $stmt = mysqli_prepare($con, $check_admin_query);
        mysqli_stmt_bind_param($stmt, "ss", $session_email, $firstname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $activity_text = "Owner $firstname updated order status to $status";
        } else {
            // Check if the session belongs to an employee
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

        // Update order status and log activity
        $update_query = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $status, $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Format current date and time
        date_default_timezone_set('Asia/Manila'); // Set the time zone to Philippine time
        $formatted_datetime = date("F d, Y") . ", " . date("h:i:s A");

        $insert_query = "INSERT INTO `activity_logs` (`order_id`, `activity_text`, `activity_time`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "sss", $order_id, $activity_text, $formatted_datetime);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        exit();
    }
    echo "Unauthorized access.";
    exit();
}
?>
