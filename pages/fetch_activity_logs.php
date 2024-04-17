<?php
include('../database/db_yeokart.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $select_query = "SELECT `activity_text`, `activity_time` FROM `activity_logs` WHERE `order_id` = ? ORDER BY `activity_time` ASC";
    $stmt = mysqli_prepare($con, $select_query);
    mysqli_stmt_bind_param($stmt, "s", $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $activity_text, $activity_time);

    $activity_logs = array();
    while (mysqli_stmt_fetch($stmt)) {
        $activity_logs[] = array(
            'text' => $activity_text,
            'time' => $activity_time
        );
    }

    mysqli_stmt_close($stmt);

    header('Content-Type: application/json');
    echo json_encode($activity_logs);
    exit();
} else {
    header("HTTP/1.0 400 Bad Request");
    exit();
}
?>
