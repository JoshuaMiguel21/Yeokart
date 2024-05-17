<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
    <style>
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: 30px 0;
        }

        .header .logo {
            width: 200px;
        }

        .button-container {
            margin: 30px 0px;
        }

        @media (max-width: 768px) {
            .header .logo {
                width: 200px;
            }
        }

        @media (max-width: 480px) {
            .header .logo {
                width: 150px;
            }
        }
    </style>
</head>

<?php
require('../database/db_yeokart.php');
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login_page.php");
    exit();
}

$customer_id = $_SESSION['id'];
$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
$username = $_SESSION['username'] ?? '';
$email = strtolower($_SESSION['email'] ?? '');

// Fetch order details
$order_id = $_GET['order_id'] ?? '';
if ($order_id == '') {
    echo '<p>Order ID not specified.</p>';
    exit();
}

$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo '<header class="header">';
    echo '<a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>';
    echo '</header>';
    echo '<div class="notification-container">';
    echo '<h1>Order Status</h1>';
    echo '<div class="status-bar">';
    echo '<div class="status-circle"><i class="fa fa-check"></i></div>';
    echo '<div class="status-circle"><i class="fa fa-truck"></i></div>';
    echo '<div class="status-circle"><i class="fa fa-box"></i></div>';
    echo '<div class="status-line"></div>';
    echo '</div>';
    echo '<div class="notification-status">';
    echo '<div class="status-text">Payment Done</div>';
    echo '<div class="status-text">Shipped</div>';
    echo '<div class="status-text">Delivered</div>';
    echo '</div>';
    echo '<div style="text-align: center; margin: 50px 0px; font-size: 2rem; color: red;">';
    echo '<strong>Sorry, this order has been cancelled and does not exist anymore.</strong>';
    echo '</div>';
    echo '</div>';
    echo '<center>';
    echo '<div class="button-container">';
    echo '<a href="customer_homepage.php" class="btn-address">';
    echo '<span class="text">Back to Homepage</span>';
    echo '</a>';
    echo '</div>';
    echo '</center>';
    exit();
}
$row = $result->fetch_assoc();

$items = explode(", ", $row['items_ordered']);
$quantities = explode(", ", $row['item_quantity']);
$itemPrices = explode(", ", $row['items_price'] ?? []);
$itemImages = explode(", ", $row['items_image'] ?? []);

$status = strtoupper($row['status']);

$line_width = '0%';
$completed_class = '';
$current_class = '';
$next_class = '';
$status_message = '';

if ($status == 'PROCESSING') {
    $status_message = 'Your payment has been successfully confirmed.';
    $line_width = '0%';
    $completed_class = 'current';
} elseif ($status == 'SHIPPED') {
    $status_message = 'Your order is on the way!';
    $line_width = '50%';
    $completed_class = 'completed';
    $current_class = 'current';
} elseif ($status == 'DELIVERED') {
    $status_message = 'Your order has arrived!';
    $line_width = '100%';
    $completed_class = 'completed';
    $current_class = 'completed';
    $next_class = 'completed';
}
?>

<body>
    <header class="header">
        <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
    </header>
    <div class="notification-container">
        <h1>Order Status (<?php echo $order_id; ?>)</h1>
        <div class="status-bar">
            <div class="status-circle <?php echo $completed_class; ?>"><i class="fa fa-check"></i></div>
            <div class="status-circle <?php echo $current_class; ?>"><i class="fa fa-truck"></i></div>
            <div class="status-circle <?php echo $next_class; ?>"><i class="fa fa-box"></i></div>
            <div class="status-line"></div>
            <div class="status-line active" style="width: <?php echo $line_width; ?>;"></div>
        </div>
        <div class="notification-status">
            <div class="status-text">Payment Done</div>
            <div class="status-text">Shipped</div>
            <div class="status-text">Delivered</div>
        </div>

        <div style="text-align: center; margin: 50px 0px; font-size: 2rem; color: #DD2F6E;">
            <strong><?php echo $status_message; ?></strong>
        </div>
        <?php
        $tracking_number = $row['tracking_number'] ?? '';
        if (!empty($tracking_number)) {
            echo "<h2 class='tracking-number'>Tracking Number: <u>{$tracking_number}</u></h2>";
        }
        ?>
        <div class="order-details">
            <h2>Your Orders</h2>
            <?php
            $total_paid = 0.00; // Initialize total paid variable
            foreach ($items as $key => $itemName) {
                $itemName = trim($itemName);
                $itemPrice = floatval($itemPrices[$key] ?? 0);
                $itemQuantity = intval($quantities[$key] ?? 0);
                $total = $itemPrice * $itemQuantity;
                $itemImage = trim($itemImages[$key] ?? '');
                $total_paid += $total; // Sum up the total paid

                echo "<div class='cart-item'>";
                echo "<img src='item_images/{$itemImage}' alt='Item Image' class='cart-item-image'>";
                echo "<div class='order-details'>";
                echo "<p><b>Name:</b> {$itemName}</p>";
                echo "<p><b>Price:</b> ₱" . number_format($itemPrice, 2) . "</p>";
                echo "<p><b>Quantity:</b> {$itemQuantity}</p>";
                echo "</div>";
                echo "<p class='order-total'><b>Total:</b> ₱" . number_format($total, 2) . "</p>";
                echo "</div>";
            }
            ?>
            <div class='total-paid-alert'>
                Overall Total Paid: Php. <?php echo number_format($total_paid, 2); ?>
            </div>
        </div>
    </div>

    <center>
        <div class="button-container">
            <a href="customer_homepage.php" class="btn-address">
                <span class="text">Back to Homepage</span>
            </a>
        </div>
    </center>

</html>

</html>