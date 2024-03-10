<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
</head>
<?php
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

include('../database/db_yeokart.php');

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $select_item_query = "SELECT * FROM products WHERE item_id = $item_id";
    $result_item_query = mysqli_query($con, $select_item_query);

    $fetch_item = array();

    if (mysqli_num_rows($result_item_query) > 0) {
        $fetch_item = mysqli_fetch_assoc($result_item_query);
    }
}

if (isset($_POST['view_item_button'])) {
    // Fetching item details based on item ID
    $item_id = $_POST['item_id'];
    $select_item_query = "SELECT * FROM products WHERE item_id = $item_id";
    $result_item_query = mysqli_query($con, $select_item_query);
    $fetch_item = mysqli_fetch_assoc($result_item_query);

    // Redirecting to product_details.php with item details
    if ($fetch_item) {
        header("Location: product_details.php?item_id=$item_id");
        exit();
    }
}
?>

<body>
    <header class="header">
        <div class="header-1">
            <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>

            <form action="" class="search-form">
                <input type="search" name="" placeholder="Search here..." id="search-box">
                <label for="search-box" class="fas fa-search"></label>
            </form>
            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="customer_shop.php">Shop</a>
                <a href="contact_page.php">Contact Us</a>
                <a href="#" class="fas fa-shopping-cart"></a>
                <a href="customer_profile.php" id="user-btn" class="fas fa-user"></a>
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

    <section class="product-details" id="product-details">
        <div class="product-details-left">
            <div class="main-image">
                <!-- Check if $fetch_item is not empty before accessing its elements -->
                <?php if (!empty($fetch_item)) : ?>
                    <img src="item_images/<?php echo $fetch_item['item_image1']; ?>" alt="Product Image" width="100%">
                <?php endif; ?>
            </div>
        </div>
        <div class="product-details-right">
            <?php if (!empty($fetch_item)) : ?>

                <div class="category-name">
                    <p><?php echo $fetch_item['category_name']; ?></p>
                </div>

                <div class="item-name">
                    <h2><?php echo $fetch_item['item_name']; ?></h2>
                </div>

                <div class="item-price">
                    <h4>&#8369;<?php echo $fetch_item['item_price']; ?></h4>
                </div>

                <!--<div class="select-version">
                    <select id="version-dropdown">
                        <option value="option1">Select Version</option>
                        <option value="option1">Version A</option>
                        <option value="option2">Version B</option>
                        <option value="option3">Version C</option>
                    </select>
                </div>-->

                <div class="select-quantity">
                    <span>Select Quantity:</span>
                    <button onclick="decrement()">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" value="1">
                    <button onclick="increment()">+</button>
                </div>

                <div class="item-stock">
                    <p>Stock: <?php echo $fetch_item['item_quantity']; ?></p>
                </div>

                <div class="add-to-cart">
                    <button type="submit" name="add-to-cart-btn">Add To Cart</button>
                </div>

                <div class="product-description">
                    <h4>Product Description</h4>
                    <p><?php echo $fetch_item['item_description']; ?>
                    <p>
                </div>

            <?php endif; ?>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"><span>Related Products</span></h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php
                include('../database/db_yeokart.php');
                $current_item_id = $fetch_item['item_id'];
                $current_category = $fetch_item['category_name'];
                $select_query = "SELECT * FROM products WHERE category_name = '$current_category' AND item_id != $current_item_id";
                $result_query = mysqli_query($con, $select_query);
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $item_id = $row['item_id'];
                    $item_name = $row['item_name'];
                    $item_price = $row['item_price'];
                    $item_description = $row['item_description'];
                    $item_quantity = $row['item_quantity'];
                    $category_name = $row['category_name'];
                    $item_image1 = $row['item_image1'];
                    echo "<div class='swiper-slide box'>
                    <div class='icons'>
                        <form method='post'>
                            <input type='hidden' name='item_id' value='" . $item_id . "'>
                            <button type='submit' name='view_item_button' class='fas fa-eye'></button>
                        </form>
                    </div>
                    <div class='image'>
                    <img src='item_images/$item_image1' alt=''>
                    </div>
                    <div class='content'>
                    <h3 class='marquee'>$item_name</h3>
                    <div class='price'>₱$item_price</div>
                    <a href='#' class='btn'>Add to Cart</a>
                    </div>
                </div>";
                }
                ?>
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

        function increment() {
            var input = document.getElementById('quantity');
            input.stepUp();
        }

        function decrement() {
            var input = document.getElementById('quantity');
            input.stepDown();
        }

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
    </script>
</body>

</html>