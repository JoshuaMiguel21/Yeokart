<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
</head>
<style>
    .swal2-custom-popup {
        font-size: 16px;
        width: 500px;
    }

    .swal2-custom-title {
        font-size: 20px;
    }

    .status-title{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('.search-form');
        const searchBtn = document.querySelector('#search-btn');

        searchBtn.addEventListener('click', function() {
            searchForm.classList.toggle('active');
        });

        window.addEventListener('scroll', function() {
            searchForm.classList.remove('active');
            const header2 = document.querySelector('.header .header-2');
            if (window.scrollY > 80) {
                header2.classList.add('active');
            } else {
                header2.classList.remove('active');
            }
        });

        if (window.scrollY > 80) {
            document.querySelector('.header .header-2').classList.add('active');
        }
    });

    function openPopup() {
        document.getElementById('editProfilePopup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('editProfilePopup').style.display = 'none';
    }

    document.querySelector('.nav-user .logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        openPopup();
    });

    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }
</script>

<?php
require('../database/db_yeokart.php');
session_start();

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $username = $_SESSION['username'];
    $email = strtolower($_SESSION['email']);
} else {
    header("Location: login_page.php");
    exit();
}

$sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $cartCount = $row['cart_count'];
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$sql = "SELECT customer_id, COUNT(*) AS address_count FROM addresses WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $addressCount = 0;
    while ($row = $result->fetch_assoc()) {
        $addressCount += $row['address_count'];
    }
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$defaultAddressQuery = "SELECT * FROM addresses WHERE customer_id = $customer_id AND is_default = 1 LIMIT 1";
$defaultAddressResult = $con->query($defaultAddressQuery);

if ($defaultAddressResult->num_rows > 0) {
    $defaultAddress = $defaultAddressResult->fetch_assoc();
    $displayAddress = htmlspecialchars($defaultAddress['address']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['street']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['city']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['province']);
} else {
    $displayAddress = "No default address set.";
}

$phoneQuery = "SELECT phoneNumber FROM addresses WHERE customer_id = $customer_id AND is_default = 1 LIMIT 1";
$phoneResult = $con->query($phoneQuery);

if ($phoneResult->num_rows > 0) {
    $phoneRow = $phoneResult->fetch_assoc();
    $displayPhone = htmlspecialchars($phoneRow['phoneNumber']);
} else {
    $displayPhone = "No default phone number set.";
}

function getDaysDifference($date)
{
    $now = new DateTime();
    $notificationDate = new DateTime($date);
    $interval = $now->diff($notificationDate);
    $days = $interval->days;

    if ($days === 0) {
        return 'Today';
    } elseif ($days === 1) {
        return '1 day ago';
    } else {
        return $days . ' days ago';
    }
}

$unread_notifications_query = "SELECT COUNT(*) as unread_count FROM notifications WHERE customer_id = $customer_id AND read_status = 0";
$unread_notifications_result = $con->query($unread_notifications_query);

$unreadCount = 0;  // Default value if no result
if ($unread_notifications_result) {
    $row = $unread_notifications_result->fetch_assoc();
    $unreadCount = $row['unread_count'];
}


// Query to get notifications for the logged-in customer
$notification_query = "
    SELECT notifications.*, orders.status AS order_status 
    FROM notifications 
    LEFT JOIN orders ON notifications.order_id = orders.order_id
    WHERE notifications.customer_id = $customer_id 
    ORDER BY notifications.created_at DESC";
$notifications_result = $con->query($notification_query);

$notifications = array();
if ($notifications_result->num_rows > 0) {
    while ($notification = $notifications_result->fetch_assoc()) {
        $notification['days_difference'] = getDaysDifference($notification['created_at']);
        $notification['is_read'] = $notification['read_status'] == 1 ? true : false;
        $notifications[] = $notification;
    }
}

function deleteOldOrders($con)
{
    $timestamp24HoursAgo = strtotime('-24 hours');
    $timestampOneMinuteToDeadline = strtotime('-23 hours');

    $notifyQuery = "SELECT `order_id`, `customer_id` FROM orders WHERE proof_of_payment = '' AND date_of_purchase < FROM_UNIXTIME($timestampOneMinuteToDeadline)";
    $notifyResult = $con->query($notifyQuery);

    if ($notifyResult) {
        while ($row = $notifyResult->fetch_assoc()) {
            $orderId = $row['order_id'];
            $customerId = $row['customer_id'];
            $title = "Urgent Payment Reminder";
            $message = "Your payment for Order ID $orderId is due within the next hour. Please upload proof of payment immediately to avoid order cancellation.";

            if (!notificationExists($con, $customerId, $title, $orderId)) {
                if ($stmt = $con->prepare("INSERT INTO notifications (customer_id, title, message, order_id) VALUES (?, ?, ?, ?)")) {
                    $stmt->bind_param("isss", $customerId, $title, $message, $orderId);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }

    $selectQuery = "SELECT `order_id`, `customer_id`, `items_ordered`, `item_quantity` FROM orders WHERE proof_of_payment = '' AND date_of_purchase < FROM_UNIXTIME($timestamp24HoursAgo)";
    $result = $con->query($selectQuery);

    if ($result === false) {
        echo "Error: " . $con->error;
        return;
    }

    while ($row = $result->fetch_assoc()) {
        $orderId = $row['order_id'];
        $customerId = $row['customer_id'];
        $items = explode(", ", $row['items_ordered']);
        $quantities = explode(", ", $row['item_quantity']);

        foreach ($items as $key => $item) {
            $item = trim($item);
            $quantity = intval($quantities[$key]);

            $updateQuery = "UPDATE products SET item_quantity = item_quantity + ? WHERE item_name = ?";
            if ($stmt = $con->prepare($updateQuery)) {
                $stmt->bind_param("is", $quantity, $item);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error updating products table: " . $con->error;
                return;
            }
        }

        $title = "Order Cancelled";
        $message = "Your Order ID $orderId has been cancelled due to non-receipt of payment.";

        if ($stmt = $con->prepare("INSERT INTO notifications (customer_id, title, message, order_id) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("isss", $customerId, $title, $message, $orderId);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error inserting notification: " . $con->error;
            return;
        }
    }

    $deleteQuery = "DELETE FROM orders WHERE proof_of_payment = '' AND date_of_purchase < FROM_UNIXTIME($timestamp24HoursAgo)";
    if (!$con->query($deleteQuery)) {
        echo "Error deleting old orders: " . $con->error;
        return;
    }
}

function notificationExists($con, $customerId, $title, $orderId)
{
    $checkQuery = "SELECT COUNT(*) AS num_notifications FROM notifications WHERE customer_id = ? AND title = ? AND order_id = ?";
    
    if ($stmt = $con->prepare($checkQuery)) {
        $stmt->bind_param("iss", $customerId, $title, $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            $numNotifications = $row['num_notifications'];
        } else {
            echo "Error: " . $checkQuery . "<br>" . $con->error;
            $stmt->close();
            return false;
        }

        $stmt->close();
        return $numNotifications > 0;
    } else {
        echo "Error preparing statement: " . $con->error;
        return false;
    }
}




// Close the database connection
?>
<style>
    .notification-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .notification-item.unread {
        background-color: #f9f9f9;
    }

    .notification-item .unread-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #DD2F6E;
        border-radius: 50%;
        margin-left: 5px;
    }

    .notification-number {
        display: flex;
        width: 23px;
        height: 23px;
        font-size: 14px;
        background-color: #DD2F6E;
        justify-content: center;
        align-items: center;
        color: #fff;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        right: -20px;
    }

    .notif-button {
        padding: 5px 10px;
        margin-right: 5px;
        border: none;
        background-color: #f0f0f0;
        cursor: pointer;
        border-radius: 20px;
    }

    .notif-button.active {
        background-color: #DD2F6E;
        color: white;
    }

    .delete-button {
        display: none;
        cursor: pointer;
        color: #DD2F6E;
        margin-left: 10px;
        font-size: 13px;
    }

    .notification-item:hover .delete-button {
        display: inline-block;
    }

</style>

<body>
    <div id="notificationPopup" style="display: none; position: absolute; right: 10px; top: 60px; background-color: white; border: 1px solid #ccc; padding: 10px; width: 300px; z-index: 100;">
        <h2 style="margin: 10px 0">Notifications</h2>
        <div style="padding-bottom: 10px;">
            <button id="allButton" class="notif-button active">All</button>
            <button id="unreadButton" class="notif-button">Unread</button>
        </div>
        <hr class="notif">
        <?php foreach ($notifications as $notification) :
            $orderStatus = isset($notification['order_status']) ? $notification['order_status'] : 'Order Deleted/Not Available';
        ?>
            <div class="notification-item <?= !$notification['is_read'] ? 'unread' : '' ?>"
                style="padding: 10px; border-bottom: 1px solid #eee; <?= !$notification['is_read'] ? 'background-color: #f9f9f9;' : '' ?>"
                data-order-id="<?= $notification['order_id']; ?>"
                data-order-status="<?= $notification['order_status']; ?>"
                onclick="markAsRead(<?= $notification['id']; ?>)">
                <p style="font-size: 1.2rem">
                    <strong><?= htmlspecialchars($notification['title']); ?></strong>
                    <?= !$notification['is_read'] ? '<span class="unread-dot"></span>' : '' ?>
                    <span class="delete-button" onclick="deleteNotification(<?= $notification['id']; ?>);" style="display: none; position: absolute; right: 0; top: 50%; margin-right: 5px; transform: translateY(-50%); cursor: pointer;"><i class="fas fa-trash-alt"></i></span>
                </p>
                <p><?= htmlspecialchars($notification['message']); ?></p>
                <p style="font-size: 0.8em; color: <?= !$notification['is_read'] ? '#DD2F6E' : '#666'; ?>;">
                    <?= $notification['days_difference']; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
                    <input type="search" name="search" placeholder="Search here..." id="search-box">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <label for="click" class="menu-btn">
                    <i class="fas fa-bars"></i>
                </label>
            </div>
            <div class="icons">
                <ul>
                    <li class="search-ul">
                        <form action="customer_shop.php" method="GET" class="search-form1">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"></i><span id="cart-num"><?php echo $cartCount; ?></span></a></li>
                    <li><a href="customer_profile.php" id="user-btn" class="active"><i class="fas fa-user"></i></a></li>
                    <li><a href="#" id="notificationIcon"><i class="fas fa-bell"></i><span id="notif-num" class="notification-number"><?= $unreadCount ?></span></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="user-profile">
        <div id="editProfilePopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closePopup()">&times;</span>
                <h3>Edit Profile</h3>
                <form id="editProfileForm" action="update_profile.php" method="POST">
                    <div class="form-group">
                        <label for="editFirstname">First Name</label>
                        <input type="text" id="editFirstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editLastname">Last Name</label>
                        <input type="text" id="editLastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editUsername">Username</label>
                        <input type="text" id="editUsername" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <button type="submit" class="update-btn">Save Changes</button>
                </form>
            </div>
        </div>
        <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
                <p>Are you sure you want to logout?
                <p>
                <div class="logout-btns">
                    <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                    <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                </div>
            </div>
        </div>
        <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <div style="padding: 20px; background: white; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
                <div class="loader"></div>
                <span style="margin-left: 10px; font-size: 15px;">Updating...</span>
            </div>
        </div>
        <div class="header-3">
            <h1>My Account</h1>
            <div class="nav-user">
                <a href="#" onclick="openPopup(); return false;" class="logout-link">EDIT PROFILE</a>
                <a href="#" onclick="openLogoutPopup(); return false;" class="logout-btn">LOG OUT<i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="left-column">
                <p id="info">ORDER HISTORY</p>
                <hr class="gradient">

                <?php
                require('../database/db_yeokart.php');

                if (isset($_SESSION['id'])) {
                    $customer_id = $_SESSION['id'];
                } else {
                    header("Location: login_page.php");
                    exit();
                }

                $ordersPerPage = 10;
                $pageNumber = 1;

                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $pageNumber = $_GET['page'];
                }

                $offset = ($pageNumber - 1) * $ordersPerPage;

                $totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders WHERE `customer_id` = $customer_id AND is_archive = 0";
                $totalOrdersResult = mysqli_query($con, $totalOrdersQuery);
                $totalOrdersRow = mysqli_fetch_assoc($totalOrdersResult);
                $totalOrders = $totalOrdersRow['total_orders'];

                deleteOldOrders($con);

                $sql = "SELECT `order_id`, `customer_id`, `address`, `items_ordered`, `item_quantity`, `items_category`,  `items_artist`, `items_price`, `subtotal`, `total`, `shipping_fee`, `overall_total`, `date_of_purchase`, `status`, `items_image`, `proof_of_payment`, `is_archive`, 
                CASE `status`
                    WHEN 'INVALID' THEN 1
                    WHEN 'PENDING' THEN 2
                    WHEN 'PROCESSING' THEN 3
                    WHEN 'SHIPPED' THEN 4
                    WHEN 'DELIVERED' THEN 5
                    ELSE 6
                END AS status_order
                FROM `orders`
                WHERE `customer_id` = $customer_id AND is_archive = 0
                ORDER BY status_order ASC
                LIMIT $ordersPerPage OFFSET $offset";

                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="order-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Order ID</th>';
                    echo '<th>Date</th>';
                    echo '<th>Status</th>';
                    echo '<th><center>Proof of Payment</center></th>';
                    echo '<th></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $order_id = $row['order_id'];
                        $status = strtoupper($row['status']);
                        $proof_of_payment = $row['proof_of_payment'];
                        $button_class = "upload-proof-cell"; 
                        $button_text = "";
                        $button_disabled = false;

                        if ($status == "PENDING") {
                            if (empty($proof_of_payment)) {
                                $button_text = "Upload Proof of Payment";
                                $button_icon = "fas fa-cloud-upload-alt";
                            } else {
                                $button_text = "Proof of Payment Uploaded";
                                $button_disabled = true;
                                $button_icon = "fa fa-check-square-o";
                            }
                        } elseif (in_array($status, ["PROCESSING", "SHIPPED", "DELIVERED"])) {
                            $button_text = "Payment Done";
                            $button_disabled = true;
                            $button_icon = "fas fa-check";
                            $button_class = "payment-done";
                        } else {
                            $button_text = "Upload Proof of Payment";
                            $button_icon = "fas fa-cloud-upload-alt";
                        }

                        echo '<tr class="order-row" data-order-id="' . $row['order_id'] . '">';
                        echo '<td>' . $row['order_id'] . '</td>';
                        echo '<td>' . $row['date_of_purchase'] . '</td>';
                        echo '<td>' . strtoupper($row['status']) . '</td>';
                        echo '<td><center>';
                        if ($status != "INVALID") {
                            echo '<a href="#" class="' . $button_class . ' status-button" data-order-id="' . $order_id . '" data-proof="' . $proof_of_payment . '" style="';
                            if ($button_disabled) {
                                echo 'cursor: not-allowed; pointer-events: none; ';
                            }
                            echo '"><i class="' . $button_icon . '"></i><span class="button-text">' . $button_text . '</span></a>';
                        } else {
                            echo '<a href="#" class="' . $button_class . ' status-button" data-order-id="' . $order_id . '" data-proof="' . $proof_of_payment . '"><i class="' . $button_icon . '"></i><span class="button-text">' . $button_text . '</span></a>';
                        }
                        echo '</center></td>';
                        echo '<td class="toggle-row">';
                        echo '<i class="fas fa-chevron-down toggle-icon"></i>';
                        if ($status == "DELIVERED") {
                            echo '<i class="fas fa-archive archive-icon" data-order-id="' . $order_id . '"></i>';
                        }
                        echo '</td>';
                        echo '</tr>';
                        echo '<tr class="hidden-row">';
                        echo '<td colspan="5">';
                        echo '<div class="order-details-card">';
                        echo '<div class="alert ';
                        switch ($status) {
                            case "DELIVERED":
                                echo 'alert-success';
                                break;
                            case "PROCESSING":
                                echo 'alert-info';
                                break;
                            case "PENDING":
                                echo 'alert-danger';
                                break;
                            case "INVALID":
                                echo 'alert-danger';
                                break;
                            case "SHIPPED":
                                echo 'alert-warning';
                                break;
                            default:
                                echo 'alert-danger';
                                break;
                        }
                        echo '" role="alert">';
                        echo '<div class="status-title">';
                        echo '<strong>Status:' . ' ' . $status . '</strong>';
                        if (in_array($row['status'], ['Processing', 'Shipped', 'Delivered'])) {
                            echo '<a href="customer_orderstatus.php?order_id=' . $order_id . '" class="view-order-link" style>View Order</a>';
                        }
                        echo '</div>';
                        if ($status == "DELIVERED") {
                            echo 'Your order has been delivered. Thank you for shopping with us!';
                        } elseif ($status == "PROCESSING") {
                            echo 'Your order is currently being processed. Please wait for further updates.';
                        } elseif ($status == "PENDING") {
                            echo 'Your order is pending and we are waiting for your payment. You only have <b>24 hours</b> to complete your payment.';
                        } elseif ($status == "SHIPPED") {
                            echo 'Your order has been shipped. You will receive it soon.';
                        } elseif ($status == "INVALID") {
                            echo 'The image sent as proof of payment is invalid. Please provide valid proof of payment.';
                        } else {
                            echo 'Your order status is ' . $status . '. For more information, please contact customer support.';
                        }
                        echo '</div>'; 

                        $items = explode(", ", $row['items_ordered']);
                        $quantities = explode(", ", $row['item_quantity']);
                        $itemPrices = isset($row['items_price']) ? explode(", ", $row['items_price']) : [];
                        $itemImages = isset($row['items_image']) ? explode(", ", $row['items_image']) : [];

                        foreach ($items as $key => $itemName) {
                            $itemName = trim($itemName);
                            $itemPrice = isset($itemPrices[$key]) ? floatval($itemPrices[$key]) : 0;
                            $itemQuantity = isset($quantities[$key]) ? intval($quantities[$key]) : 0;
                            $total = $itemPrice * $itemQuantity;
                            $itemImage = isset($itemImages[$key]) ? trim($itemImages[$key]) : '';

                            echo "<div class='cart-item' style='background-color: #fff;'>";
                            echo "<img src='item_images/{$itemImage}' alt='Item Image' class='cart-item-image'>";
                            echo "<div class='item-details'>";
                            echo "<p><b>Name: </b>{$itemName}</p>";
                            echo "<p>Price: ₱" . number_format($itemPrice, 2) . "</p>";
                            echo "<p>Quantity: {$itemQuantity}</p>";
                            echo "</div>";
                            echo "<p>Total: ₱" . number_format($total, 2) . "</p>";
                            echo "</div>";
                        }

                        echo '<div class="total-for-order">';
                        echo '<p class="total-label">Subtotal: </p>';
                        echo '<p class="total-price">&nbsp;₱' . number_format($row['total'], 2) . '</p>';
                        echo '<p class="total-label">Shipping Fee: </p>';
                        echo '<p class="total-price">&nbsp;₱' . number_format($row['shipping_fee'], 2) . '</p>';
                        echo '<p class="total-label">Overall Total: </p>';
                        echo '<p class="total-price">&nbsp;₱' . number_format($row['overall_total'], 2) . '</p>';
                        echo '</div>';

                        echo '</div>'; // Close order-details-card div
                        echo '</div>'; // Close hidden-row div
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<table class="order-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Order ID</th>';
                    echo '<th>Date</th>';
                    echo '<th>Status</th>';
                    echo '<th><center>Proof of Payment</center></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td colspan="4" style="text-align:center;">No orders made yet.</td>';
                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table>';
                }
                $con->close();
                ?>

                <?php
                $baseUrl = 'customer_profile.php?';

                $pageQuery = '';
                if (isset($_GET['search_button'])) {
                    $pageQuery = 'search_button&search=' . urlencode($_GET['search']);
                } elseif (isset($_GET['filter_button'])) {
                    if (isset($_GET['category'])) {
                        $pageQuery = 'filter_button&category=' . urlencode($_GET['category']);
                    } elseif (isset($_GET['artist'])) {
                        $pageQuery = 'filter_button&artist=' . urlencode($_GET['artist']);
                    }
                }

                $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalOrders / $ordersPerPage);

                $startPage = max(1, $pageNumber - 1);
                $endPage = min($totalPages, $pageNumber + 1);

                if ($pageNumber == 1) {
                    $startPage = 1;
                    $endPage = min(3, $totalPages);
                } elseif ($pageNumber == $totalPages) {
                    $startPage = max(1, $totalPages - 2);
                    $endPage = $totalPages;
                }

                echo "<div class='pagination'>";

                $prevPage = max(1, $pageNumber - 1);
                echo "<a href='{$baseUrl}page=$prevPage&$pageQuery' class='pagination-link' " . ($pageNumber <= 1 ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">&laquo; Previous</a>";

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $linkClass = $i == $pageNumber ? 'pagination-link current-page' : 'pagination-link';
                    echo "<a href='{$baseUrl}page=$i&$pageQuery' class='$linkClass'>$i</a>";
                }

                $nextPage = min($totalPages, $pageNumber + 1);
                echo "<a href='{$baseUrl}page=$nextPage&$pageQuery' class='pagination-link' " . ($pageNumber >= $totalPages ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">Next &raquo;</a>";

                echo "</div>";
                ?>
            </div>

            <div class="right-column">
                <div class="account">
                    <p id="info">ACCOUNT DETAILS
                    <p>
                        <hr class="gradient">
                    <div class="account-info">
                        <p><?php echo $username; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-user-circle"></i>
                        <p><?php echo $firstname . " " . $lastname; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-envelope"></i>
                        <p><?php echo $email; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><?php echo $displayAddress; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-phone"></i>
                        <p><?php echo $displayPhone; ?></p>
                    </div>

                </div>
                <div class="address">
                    <a href="customer_address.php" class="address-link">VIEW ADDRESSES (<?php echo $addressCount ?>)<i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <div id="uploadProofPopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closeUploadProofPopup()">&times;</span>
                <h3>Upload Proof of Payment</h3>
                <?php
                require('../database/db_yeokart.php');

                // SQL query to retrieve contacts_description from contacts table where icon_link matches
                $sql = "SELECT contacts_description FROM contacts WHERE icon_link = '<i class=\'fa-solid fa-peso-sign\'></i>'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='gcash_info'><strong>GCash:&nbsp;</strong>" . $row["contacts_description"] . "</div>";
                    }
                } else {
                    echo "<p>No additional payment instructions found.</p>";
                }

                $con->close();
                ?>
                <form class="add-artist-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="proof_of_payment" class="form-label">Select Image for Proof of Payment:</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*" style="display:none;">
                        <div class="file_upload">
                            <button type="button" class="file-upload-btn" onclick="document.getElementById('proof_of_payment').click();">Choose File</button>
                            <span id="file-chosen">No file chosen</span>
                        </div>
                    </div>
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="proof_image" id="proof_image">
                        <center><img src="" alt="Proof of Payment" id="proof_of_payment_image"></center>
                        <button type="submit" name="upload_proof" class="upload-btn">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        require('../database/db_yeokart.php');

        if (isset($_POST['upload_proof'])) {
            $order_id = $_POST['order_id'];
            if (isset($_FILES['proof_of_payment']['name']) && !empty($_FILES['proof_of_payment']['name'])) {
                $upload_proof = $_FILES['proof_of_payment']['name'];
                $temp_proof = $_FILES['proof_of_payment']['tmp_name'];

                if (move_uploaded_file($temp_proof, "./item_images/$upload_proof")) {
                    $stmt = $con->prepare("UPDATE orders SET proof_of_payment = ? WHERE order_id = ?");
                    $stmt->bind_param("ss", $upload_proof, $order_id);
                    $stmt->execute();
                    $stmt->close();

                    // SweetAlert message for successful upload with redirection
                    echo "<script>
                        Swal.fire({
                            icon: 'success', 
                            title: 'Success!',
                            text: 'Proof of payment uploaded successfully.',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-custom-popup',
                                title: 'swal2-custom-title',
                                content: 'swal2-custom-text'
                            },
                            backdrop: true, 
                            allowOutsideClick: false 
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = 'customer_profile.php';
                            }
                        });
                    </script>";
                } else {
                    // SweetAlert message for failed file move with redirection
                    echo "<script>
                        Swal.fire({
                            icon: 'error', 
                            title: 'Failed!',
                            text: 'Failed to move uploaded file.',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-custom-popup',
                                title: 'swal2-custom-title',
                                content: 'swal2-custom-text'
                            },
                            backdrop: true, 
                            allowOutsideClick: false 
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = 'customer_profile.php';
                            }
                        });
                    </script>";
                }
            } else {
                // SweetAlert message for no file uploaded with redirection
                echo "<script>
                    Swal.fire({
                        icon: 'info', 
                        title: 'Notice!',
                        text: 'No file uploaded.',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-custom-popup',
                            title: 'swal2-custom-title',
                            content: 'swal2-custom-text'
                        },
                        backdrop: true, 
                        allowOutsideClick: false 
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = 'customer_profile.php';
                        }
                    });
                </script>";
            }
        }
        $con->close();
        ?>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleIcons = document.querySelectorAll('.fa-chevron-down');

            let currentlyOpenRow = null;

            toggleIcons.forEach(function(icon) {
                icon.style.transition = 'transform 0.3s ease';

                icon.addEventListener('click', function(event) {
                    event.stopPropagation();

                    const row = icon.closest('.order-row');
                    const hiddenRow = row.nextElementSibling;

                    hiddenRow.classList.toggle('hidden-row-visible');
                    icon.classList.toggle('active');

                    if (hiddenRow.classList.contains('hidden-row-visible')) {
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        icon.style.transform = 'rotate(0deg)';
                        icon.classList.remove('active');
                    }

                    if (currentlyOpenRow && currentlyOpenRow !== hiddenRow) {
                        currentlyOpenRow.classList.remove('hidden-row-visible');
                        const previousRowIcon = currentlyOpenRow.previousElementSibling.querySelector('.fa-chevron-down');
                        previousRowIcon.style.transform = 'rotate(0deg)';
                        previousRowIcon.classList.remove('active');
                    }

                    currentlyOpenRow = hiddenRow.classList.contains('hidden-row-visible') ? hiddenRow : null;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const uploadProofCells = document.querySelectorAll('.upload-proof-cell');

            uploadProofCells.forEach(function(cell) {
                cell.addEventListener('click', function(event) {
                    event.preventDefault();
                    const orderId = cell.getAttribute('data-order-id');
                    openUploadProofPopup(orderId);
                });
            });
        });

        function openUploadProofPopup(orderId) {
            document.getElementById('order_id').value = orderId;
            document.getElementById('uploadProofPopup').style.display = 'flex';
            // Check if the proof_of_payment field is not empty
            const proofOfPayment = document.querySelector('.upload-proof-cell[data-order-id="' + orderId + '"]').getAttribute('data-proof');
            if (proofOfPayment !== "") {
                // Display the image in the popup
                const proofOfPaymentImage = document.getElementById('proof_of_payment_image');
                proofOfPaymentImage.src = "./item_images/" + proofOfPayment;
                proofOfPaymentImage.style.display = 'block';
            }
        }

        function closeUploadProofPopup() {
            document.getElementById('uploadProofPopup').style.display = 'none';
            // Hide the image when closing the popup
            document.getElementById('proof_of_payment_image').style.display = 'none';
        }

        document.getElementById('editProfileForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = this;
            document.getElementById('loadingOverlay').style.display = 'flex';
            setTimeout(function() {
                form.submit();
            }, 1000);
        });


        document.getElementById('proof_of_payment').addEventListener('change', function() {
            var fileName = document.getElementById('proof_of_payment').files[0].name;
            document.getElementById('file-chosen').textContent = fileName;
        });

        function validateSearch() {
            var searchBox = document.getElementById('search-box');
            if (searchBox.value.trim() === '') {
                return false;
            }
            return true;
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('archive-icon')) {
                const orderId = e.target.getAttribute('data-order-id');
                archiveOrder(orderId);
            }
        });

        function archiveOrder(orderId) {
            const archiveIcon = document.querySelector(`.archive-icon[data-order-id="${orderId}"]`);
            const originalClassList = archiveIcon.className;
            const originalStyle = archiveIcon.style.cssText;
            archiveIcon.className = 'fas fa-spinner fa-spin';
            archiveIcon.style.cssText = originalStyle + '; float: right; ';

            setTimeout(() => {
                fetch('archive_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `orderId=${orderId}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            archiveIcon.className = originalClassList;
                            archiveIcon.style.cssText = originalStyle;
                            alert('Failed to archive the order. Please try again.');
                        }
                    })
                    .catch(error => {
                        archiveIcon.className = originalClassList;
                        archiveIcon.style.cssText = originalStyle;
                        alert('Failed to archive the order. Please try again.');
                    });
            }, 1000); // Delay of 2 seconds
        }

        function markAsRead(notificationId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_notification.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (this.status == 200) {
                    // Optionally reload the notifications to reflect the changes
                    console.log("Notification marked as read.");
                }
            };
            xhr.send("id=" + notificationId);
        }


        document.addEventListener('DOMContentLoaded', function() {
            const allButton = document.getElementById('allButton');
            const unreadButton = document.getElementById('unreadButton');
            const notifications = document.querySelectorAll('.notification-item');

            allButton.addEventListener('click', function() {
                notifications.forEach(notification => {
                    notification.style.display = '';
                });
                allButton.classList.add('active');
                unreadButton.classList.remove('active');
            });

            unreadButton.addEventListener('click', function() {
                notifications.forEach(notification => {
                    if (notification.classList.contains('unread')) {
                        notification.style.display = '';
                    } else {
                        notification.style.display = 'none';
                    }
                });
                unreadButton.classList.add('active');
                allButton.classList.remove('active');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const notificationItems = document.querySelectorAll('.notification-item');

            notificationItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const orderStatus = this.dataset.orderStatus;

                    if (orderStatus === 'Pending' || orderStatus === 'Invalid') {
                        window.location.href = `customer_profile.php?highlight_order=${orderId}`;
                    } else {
                        window.location.href = `customer_orderstatus.php?order_id=${orderId}`;
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevents the default behavior of the click event
                    event.stopPropagation(); // Prevents the event from bubbling up the DOM tree

                    const notificationId = button.closest('.notification-item').dataset.id;
                    deleteNotification(notificationId, button);
                });
            });
        });

        function deleteNotification(notificationId, button) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_notification.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (this.status == 200) {
                    // Notification deleted successfully
                    console.log("Notification deleted successfully.");
                    // Remove the deleted notification from the UI
                    const notificationItem = button.closest('.notification-item');
                    if (notificationItem) {
                        notificationItem.remove();
                    }
                } else {
                    // Error deleting notification
                    console.error("Error deleting notification.");
                }
            };
            xhr.send("id=" + notificationId);
        }

        function showDeleteButton(element) {
            element.querySelector('.delete-button').style.display = 'inline-block';
        }

        function hideDeleteButton(element) {
            element.querySelector('.delete-button').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            function getURLParameter(name) {
                return new URLSearchParams(window.location.search).get(name);
            }

            const highlightOrderId = getURLParameter('highlight_order');
            let highlightedRow = null;

            if (highlightOrderId) {
                highlightedRow = document.querySelector(`.order-row[data-order-id='${highlightOrderId}']`);
                if (highlightedRow) {
                    highlightedRow.style.backgroundColor = '#FFD8D8';
                    highlightedRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            document.addEventListener('click', function(event) {
                if (highlightedRow && (!event.target.closest('.order-row') || event.target.closest('.order-row') !== highlightedRow)) {
                    highlightedRow.style.backgroundColor = '';
                    highlightedRow = null;
                    history.replaceState(null, '', 'customer_profile.php');
                }
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationPopup = document.getElementById('notificationPopup');

            function handleNotificationClick(event) {
                if (window.matchMedia('(max-width: 768px)').matches) {
                    window.location.href = 'notification_page.php';
                } else {
                    event.preventDefault();
                    if (notificationPopup.style.display === 'none' || !notificationPopup.style.display) {
                        notificationPopup.style.display = 'block';
                        notificationIcon.classList.add('active');
                    } else {
                        notificationPopup.style.display = 'none';
                        notificationIcon.classList.remove('active');
                    }
                }
            }

            notificationIcon.addEventListener('click', handleNotificationClick);

            window.addEventListener('resize', function() {
                notificationIcon.removeEventListener('click', handleNotificationClick);
                notificationIcon.addEventListener('click', handleNotificationClick);
            });
        });
    </script>
</body>

</html>