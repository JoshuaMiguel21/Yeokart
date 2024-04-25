<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart - Homepage</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
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

function getDaysDifference($date) {
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
                <p>
                    <strong><?= htmlspecialchars($notification['title']); ?></strong>
                    <?= !$notification['is_read'] ? '<span class="unread-dot"></span>' : '' ?>
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
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav" class="active">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"></i><span id="cart-num"><?php echo $cartCount; ?></span></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                    <li><a href="#" id="notificationIcon"><i class="fas fa-bell"></i><span id="notif-num" class="notification-number"><?= $unreadCount ?></span></a></li>
                </ul>
            </div>
        </div>
        <div class="header-2">
            <nav class="navbar">
                <a href="#home">Home</a>
                <a href="#best">Best Sellers</a>
                <a href="#featured">Featured</a>
            </nav>
        </div>
    </header>
    <nav class="bottom-navbar">
        <a href="#home" class="fas fa-home"></a>
        <a href="#best" class="fas fa-thumbs-up"></a>
        <a href="#featured" class="fas fa-list"></a>
    </nav>
    <section class="start vh-100 d-flex align-items-center" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h1 class="display-4 text-white">Welcome to Yeokart</h1>
                    <p class="text-white my-3">See the world of K-Pop!</p>
                    <a href="#best" class="btn me-2 btn1">Get Started</a>
                    <a href="#featured" class="btn btn-outline-light">Learn More</a>
                </div>
            </div>
        </div>
    </section>
    <section class="best" id="best">
        <h1 class="heading"><span>Best Sellers</span></h1>
        <div class="swiper best-slider">
            <div class="swiper-wrapper">
                <?php
                include('../database/db_yeokart.php');
                $select_query = "SELECT * FROM products WHERE is_archive = 0 ORDER BY times_sold DESC LIMIT 10";
                $result_query = mysqli_query($con, $select_query);
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $item_id = $row['item_id'];
                    $item_name = $row['item_name'];
                    $item_price = $row['item_price'];
                    $item_description = $row['item_description'];
                    $item_quantity = $row['item_quantity'];
                    $category_name = $row['category_name'];
                    $item_image1 = $row['item_image1'];
                    $artist_name = $row['artist_name'];
                    $times_sold = $row['times_sold'];
                ?>
                    <div class='swiper-slide box'>
                        <div class='icons'>
                            <a href='#' class='fas fa-eye' onclick='handleImageClick("<?php echo $item_id; ?>")'></a>
                        </div>
                        <div class='image'>
                            <img src='item_images/<?php echo $item_image1; ?>' alt='' onclick='handleImageClick("<?php echo $item_id; ?>")'>
                        </div>
                        <div class='content'>
                            <h3 class='artist'><?php echo $artist_name; ?></h3>
                            <h3 class='marquee'><?php echo $item_name; ?></h3>
                            <div class='price'>₱ <?php echo number_format($item_price, 2); ?></div>
                            <?php if ($item_quantity > 0) { ?>
                                <a href='product_details.php?item_id=<?php echo $item_id; ?>' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
                            <?php } else { ?>
                                <button class='btn' disabled style='cursor: not-allowed; background-color: gray; border-radius: 3px;'><i class='fa-solid fa-cart-plus'></i> Out of Stock</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <section class="featured" id="featured">
        <h1 class="heading"><span>Featured</span></h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php
                include('../database/db_yeokart.php');
                $select_query = $select_query = "SELECT * FROM products WHERE is_featured = 1 AND is_archive = 0";
                $result_query = mysqli_query($con, $select_query);
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $item_id = $row['item_id'];
                    $item_name = $row['item_name'];
                    $item_price = $row['item_price'];
                    $item_description = $row['item_description'];
                    $item_quantity = $row['item_quantity'];
                    $category_name = $row['category_name'];
                    $item_image1 = $row['item_image1'];
                    $artist_name = $row['artist_name'];
                    $times_sold = $row['times_sold'];
                ?>
                    <div class='swiper-slide box'>
                        <div class='icons'>
                            <a href='#' class='fas fa-eye' onclick='handleImageClick("<?php echo $item_id; ?>")'></a>
                        </div>
                        <div class='image'>
                            <img src='item_images/<?php echo $item_image1; ?>' alt='' onclick='handleImageClick("<?php echo $item_id; ?>")'>
                        </div>
                        <div class='content'>
                            <h3 class='artist'><?php echo $artist_name; ?></h3>
                            <h3 class='marquee'><?php echo $item_name; ?></h3>
                            <div class='price'>₱ <?php echo number_format($item_price, 2); ?></div>
                            <?php if ($item_quantity > 0) { ?>
                                <a href='product_details.php?item_id=<?php echo $item_id; ?>' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
                            <?php } else { ?>
                                <button class='btn' disabled style='cursor: not-allowed; background-color: gray; border-radius: 3px;'><i class='fa-solid fa-cart-plus'></i> Out of Stock</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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

        var swiper = new Swiper(".featured-slider", {
            spaceBetween: 10,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 9500,
                disabledOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                320: {
                    slidesPerView: 2,
                },
                347: {
                    slidesPerView: 2,
                },
                450: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                    centeredSlides: false,
                },
                1440: {
                    slidesPerView: 5,
                    centeredSlides: false,
                },
                2560: {
                    slidesPerView: 5,
                    centeredSlides: false,
                },
            },
        });
        var swiper = new Swiper(".best-slider", {
            spaceBetween: 10,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 9500,
                disabledOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                320: {
                    slidesPerView: 2,
                },
                347: {
                    slidesPerView: 2,
                },
                450: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                    centeredSlides: false,
                },
                1440: {
                    slidesPerView: 5,
                    centeredSlides: false,
                },
                2560: {
                    slidesPerView: 5,
                    centeredSlides: false,
                },
            },
        });
        document.addEventListener('DOMContentLoaded', function() {
            const itemNames = document.querySelectorAll('.marquee');

            itemNames.forEach(itemName => {
                if (itemName.scrollWidth > itemName.clientWidth) {
                    itemName.classList.add('marquee');
                } else {
                    itemName.classList.remove('marquee');
                }
            });
        });

        function handleImageClick(itemId) {
            // Construct the URL for the product details page
            var url = "product_details.php?item_id=" + itemId;
            // Redirect the user to the product details page
            window.location.href = url;
        }

        function validateSearch() {
            var searchBox = document.getElementById('search-box');
            if (searchBox.value.trim() === '') {
                return false;
            }
            return true;
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
        
    </script>
    
</body>

</html>