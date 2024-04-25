<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/customer_cart.css">
</head>

<style>
    .swal2-custom-popup {
        font-size: 16px;
        width: 500px;
    }

    .swal2-custom-title {
        font-size: 20px;
    }
</style>
<?php
require('../database/db_yeokart.php');
session_start();

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['firstname'])) {
    $firstname = $_SESSION['firstname'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['lastname'])) {
    $lastname = $_SESSION['lastname'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['email'])) {
    $email = strtolower($_SESSION['email']);
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['selectedAddressId'])) {
    unset($_SESSION['selectedAddressId']);
}


$sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $cartCount = $row['cart_count'];
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cartId = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the cart table
    $update_query = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cartId";
    mysqli_query($con, $update_query);

    // Redirect back to the cart page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$select_query = "SELECT address FROM addresses WHERE customer_id = $customer_id AND is_default = 1";
$result_query = mysqli_query($con, $select_query);
if (mysqli_num_rows($result_query) > 0) {
    $row = mysqli_fetch_assoc($result_query);
    $address = $row['address'];

    // Display address in JavaScript
    echo "<script>";
    echo "var address = '" . $address . "';";
    echo "</script>";
} else {
    // Display default address if none found
    echo "<script>";
    echo "var address = 'No address found';";
    echo "</script>";
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
                <form action="customer_shop.php" method="GET" class="search-form">
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
                    <li><a href="customer_cart.php" class="active"><i class="fas fa-shopping-cart"></i><span id="cart-num"><?php echo $cartCount; ?></span></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                    <li><a href="#" id="notificationIcon"><i class="fas fa-bell"></i><span id="notif-num" class="notification-number"><?= $unreadCount ?></span></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="cart" id="cart">
        <div class="header-3">
            <h1>Shopping Cart</h1>
        </div>
        <hr>
        <div class="container">
            <div class="left-column">
                <p id="info">MY CART</p>
                <hr class="gradient">

                <div class="project">
                    <div class="shop">
                        <?php
                        $total = 0;
                        include('../database/db_yeokart.php');
                        $select_query = "SELECT c.cart_id, c.customer_id, c.item_name, c.item_image1, c.quantity, c.price, p.item_quantity, p.artist_name, p.category_name FROM cart c INNER JOIN products p ON c.item_name = p.item_name WHERE c.customer_id = $customer_id";
                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) > 0) {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $subtotal = $row['quantity'] * $row['price'];
                                $total += $subtotal;
                                $item_quantity = $row['item_quantity']; // Available item quantity
                                $category = $row['category_name'];
                                $artist = $row['artist_name'];

                                echo "<div class='box'>";
                                echo "<img src='item_images/{$row['item_image1']}' alt='Item Image'>";
                                echo "<div class='content'>";
                                echo "<h3>{$row['item_name']}</h3>";
                                echo "<h4>Price: ₱ {$row['price']}</h4>";
                                echo "<h4 id='subtotal{$row['cart_id']}' class='subtotal' style='font-style: italic;'>Subtotal: ₱ " . number_format($subtotal, 2) . "</h4>";
                                echo "<div class='select-quantity'>";
                                echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
                                echo "<span>Edit Quantity:</span>";
                                echo "<br/><button type='button' onclick='decrement({$row['cart_id']}, {$row['price']})'>-</button>";
                                echo "<input type='number' id='quantity{$row['cart_id']}' name='quantity' min='1' max='{$row['item_quantity']}' data-max-quantity='{$row['item_quantity']}' value='{$row['quantity']}' onchange='updateTotal()' oninput='updateQuantity({$row['cart_id']}, this.value, {$row['price']}, $item_quantity)'>";
                                $disableAdd = $row['quantity'] >= $item_quantity ? "disabled" : "";
                                echo "<button type='button' onclick='increment({$row['cart_id']}, {$row['price']}, $item_quantity)' $disableAdd>+</button>";
                                echo "<input type='hidden' name='cart_id' value='{$row['cart_id']}'>";
                                echo "<input type='submit' style='display: none;'>";
                                echo "</form>";
                                echo "</div>";
                                echo "<p class='btn-area' onclick='openDeletePopup({$row['cart_id']})'>";
                                echo "<i class='fa fa-trash'></i>";
                                echo "<span class='btn2'> Remove</span>";
                                echo "</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Your cart is empty.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <div class="account">
                    <p id="info">ORDER DETAILS
                    <p>
                        <hr class="gradient">
                    <div class="account-info">
                        <h3 class="total">Total: ₱ <?php echo number_format($total, 2); ?></h3>
                    </div>
                    <hr>
                    <div class="account-info center-btn">
                        <?php
                        if ($total > 0) {
                            echo "<form action='order_summary.php' method='POST' id='checkoutForm'>";
                            echo "<button type='submit' class='btn' name='checkout'>
                                    <i class='fa fa-cart-arrow-down'></i>
                                    <i class='fas fa-circle-notch fa-spin' style='display: none;''></i>
                                    Checkout
                                </button>";
                            echo "</form>";
                        } else {
                            echo "<button class='btn' disabled style='background-color: gray; cursor: not-allowed;'><i class='fa fa-cart-arrow-down'></i>Checkout</button>";
                        }
                        ?>
                    </div>

                </div>
            </div>
    </section>
    <div id="deletePopup" class="popup-del" style="display: none;">
        <div class="popup-del-content">
            <span class="close" onclick="closeDeletePopup()">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to remove this item from your cart?</p>
            <button class="btn-confirm" onclick="confirmDeletion()">Delete</button>
            <button class="btn-cancel" onclick="closeDeletePopup()">Cancel</button>
        </div>
    </div>
    <script>
        function openDeletePopup(cartId) {
            document.getElementById("deletePopup").style.display = "block";
            document.getElementById("deletePopup").setAttribute("data-cartId", cartId);
        }

        function closeDeletePopup() {
            document.getElementById("deletePopup").style.display = "none";
        }

        function confirmDeletion() {
            var cartId = document.getElementById("deletePopup").getAttribute("data-cartId");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_item_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the 'cart_id' in the request
            xhr.onload = function() {
                if (xhr.status == 200) {
                    closeDeletePopup();
                    location.reload();
                } else {
                    alert("Error deleting item.");
                }
            };

            // Send 'cart_id' in the request data
            xhr.send("cart_id=" + cartId);
        }

        // JavaScript functions for increment and decrement
        function increment(cartId, price, itemQuantity) {
            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) + 1;
            if (newValue <= itemQuantity) {
                input.value = newValue;
                updateQuantity(cartId, newValue, price);
            }
        }

        function decrement(cartId, price) {
            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
                updateQuantity(cartId, newValue, price);
            }
        }

        // Update the quantity in the database and UI
        function updateQuantity(cartId, quantity, price, itemQuantity) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    updateSubtotal(cartId, quantity, price);
                    updateTotal();
                    // Enable/disable increment button based on itemQuantity
                    document.querySelector(`#quantity${cartId}`).nextElementSibling.disabled = quantity >= itemQuantity;
                } else {
                    console.error("Error updating quantity:", xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error("Network error occurred while updating quantity");
            };

            xhr.send("cart_id=" + cartId + "&quantity=" + quantity);
        }

        // Other existing JavaScript functions remain the same


        // Function to update subtotal
        function updateSubtotal(cartId, quantity, price) {
            var subtotalElement = document.getElementById('subtotal' + cartId);
            var newSubtotal = quantity * price;
            subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Function to update total
        function updateTotal() {
            var totalElements = document.querySelectorAll('.subtotal');
            var total = Array.from(totalElements).reduce(function(acc, elem) {
                var subtotalText = elem.textContent.replace('Subtotal: ₱ ', '').replace(',', '');
                var subtotalValue = parseFloat(subtotalText);
                return acc + subtotalValue;
            }, 0);
            var totalElement = document.querySelector('.total');
            totalElement.innerHTML = "Total: ₱ " + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkoutForm');
            const checkoutButton = checkoutForm.querySelector('button[name="checkout"]');
            const cartIcon = checkoutButton.querySelector('.fa-cart-arrow-down');
            const loadingIcon = checkoutButton.querySelector('.fas.fa-circle-notch');

            checkoutButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the button's default action

                // Display the loading icon immediately
                if (cartIcon && loadingIcon) {
                    cartIcon.style.display = 'none';
                    loadingIcon.style.display = 'inline-block';
                }

                // Delay the check slightly to ensure UI updates are visible
                setTimeout(() => {
                    let exceededItems = [];
                    let zeroQuantityItems = [];
                    const cartItems = document.querySelectorAll('.box');

                    cartItems.forEach(function(item) {
                        const itemName = item.querySelector('.content h3').textContent;
                        const quantityInput = item.querySelector('input[type=number]');
                        const maxQuantity = quantityInput.getAttribute('data-max-quantity');

                        if (parseInt(quantityInput.value) > parseInt(maxQuantity)) {
                            exceededItems.push(itemName);
                        }

                        if (parseInt(quantityInput.value) === 0) {
                            zeroQuantityItems.push(itemName);
                        }
                    });

                    // Check conditions and display errors if any
                    if (exceededItems.length > 0 || zeroQuantityItems.length > 0) {
                        let errorMessage = '';
                        if (exceededItems.length > 0) {
                            errorMessage += `Adjust quantities for: ${exceededItems.join(", ")}.`;
                        }
                        if (zeroQuantityItems.length > 0) {
                            errorMessage += errorMessage ? ' ' : '';
                            errorMessage += `Set quantities above 0 for: ${zeroQuantityItems.join(", ")}.`;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Error',
                            text: errorMessage,
                            customClass: {
                                popup: 'swal2-custom-popup',
                                title: 'swal2-custom-title',
                                content: 'swal2-custom-text'
                            }
                        });

                        // Revert icon states due to error
                        cartIcon.style.display = 'inline-block';
                        loadingIcon.style.display = 'none';
                    } else {
                        // No errors, submit the form
                        checkoutForm.submit();
                    }
                }, 1000); // Small delay to ensure UI responsiveness
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
    </script>
    <iframe name="update_frame" style="display:none;"></iframe>
</body>

</html>