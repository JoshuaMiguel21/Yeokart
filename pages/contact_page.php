<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/contacts.css">
</head>
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

$sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $cartCount = $row['cart_count'];
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
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
            <div class="notification-item <?= !$notification['is_read'] ? 'unread' : '' ?>" style="padding: 10px; border-bottom: 1px solid #eee; <?= !$notification['is_read'] ? 'background-color: #f9f9f9;' : '' ?>" data-order-id="<?= $notification['order_id']; ?>" data-order-status="<?= $notification['order_status']; ?>" onclick="markAsRead(<?= $notification['id']; ?>)">
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
                    <li><a href="contact_page.php" class="active">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"></i><span id="cart-num"><?php echo $cartCount; ?></span></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                    <li><a href="#" id="notificationIcon"><i class="fas fa-bell"></i><span id="notif-num" class="notification-number"><?= $unreadCount ?></span></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="contacts" id="contacts">
        <div class="container">
            <h1 class="heading"><span>Contact Us</span></h1>
            <br></br>

            <div class="box-container">
                <?php
                include('../database/db_yeokart.php');
                $select_query = "SELECT * FROM contacts";
                $result_query = mysqli_query($con, $select_query);

                if (mysqli_num_rows($result_query) > 0) {
                    while ($row = mysqli_fetch_assoc($result_query)) {
                        $contacts_id = $row['contacts_id'];
                        $contacts_name = $row['contacts_name'];
                        $icon_link = $row['icon_link'];
                        $contacts_description = $row['contacts_description'];

                        if ($icon_link !== "<i class='fa-solid fa-peso-sign'></i>") {
                            $contacts_link = $contacts_description;
                            if (filter_var($contacts_description, FILTER_VALIDATE_URL)) {
                                $contacts_link = "<a href='$contacts_description' target='_blank'>$contacts_description</a>";
                            }

                            echo "<div class='box' onclick='redirectToURL(\"$contacts_description\")'>
                                    <div class='iconbox'>
                                        $icon_link
                                    </div>
                                    <h3>$contacts_name</h3>
                                    <p>$contacts_link</p>
                                </div>";
                        }
                    }
                } else {
                    echo "<h1>No contacts found.</h1>";
                }
                ?>
            </div>
        </div>
    </section>
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

        function validateSearch() {
            var searchBox = document.getElementById('search-box');
            if (searchBox.value.trim() === '') {
                return false;
            }
            return true;
        }

        function redirectToURL(url) {
            if (isValidURL(url)) {
                window.open(url, '_blank');
            }
        }

        function isValidURL(url) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!pattern.test(url);
        }

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
</body>

</html>