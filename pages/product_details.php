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

/*if (isset($_POST['add-to-cart-btn'])) {
    $customer_id = $_SESSION['id'];
    $item_name = $fetch_item['item_name'];
    $price = $fetch_item['item_price'];
    $item_image = $fetch_item['item_image1'];
    $quantity = $_POST['quantity'];

    $insert_cart_query = "INSERT INTO cart (customer_id, item_name, price, item_image1, quantity) VALUES ('$customer_id', '$item_name', '$price', '$item_image', '$quantity')";
    mysqli_query($con, $insert_cart_query);
}*/
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $select_item_query = "SELECT * FROM products WHERE item_id = $item_id";
    $result_item_query = mysqli_query($con, $select_item_query);

    $fetch_item = array();

    if (mysqli_num_rows($result_item_query) > 0) {
        $fetch_item = mysqli_fetch_assoc($result_item_query);
    }
}

if (isset($_POST['add-to-cart-btn'])) {
    $customer_id = $_SESSION['id'];
    $item_name = $fetch_item['item_name'];
    $price = $fetch_item['item_price'];
    $item_image = $fetch_item['item_image1'];
    $quantity = $_POST['quantity'];
    $item_stock = $fetch_item['item_quantity'];

    // Check if the quantity exceeds the stock
    if ($quantity > $item_stock) {
        echo '<script>alert("You cannot add more than the available stock.")</script>';
    } else {
        // Check if the item already exists in the cart for the same customer ID
        $select_cart_query = "SELECT * FROM cart WHERE customer_id = '$customer_id' AND item_name = '$item_name'";
        $result_cart_query = mysqli_query($con, $select_cart_query);

        if (mysqli_num_rows($result_cart_query) > 0) {
            // Update the quantity of the existing item in the cart
            $row = mysqli_fetch_assoc($result_cart_query);
            $existing_quantity = $row['quantity'];
            $new_quantity = $existing_quantity + $quantity;

            $update_cart_query = "UPDATE cart SET quantity = $new_quantity WHERE customer_id = '$customer_id' AND item_name = '$item_name'";
            mysqli_query($con, $update_cart_query);
        } else {
            // Insert a new row for the item in the cart
            $insert_cart_query = "INSERT INTO cart (customer_id, item_name, price, item_image1, quantity) VALUES ('$customer_id', '$item_name', '$price', '$item_image', '$quantity')";
            mysqli_query($con, $insert_cart_query);
        }
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
                <ul>
                    <li class="search-ul">
                        <form action="" class="search-form">
                            <input type="search" name="" placeholder="Search here..." id="search-box">
                            <label for="search-box" class="fas fa-search"></label>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="new_customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
        <section class="product-details" id="product-details">
            <div class="product-details-left">
                <div class="main-image">
                    <!-- Check if $fetch_item is not empty before accessing its elements -->
                    <?php if (!empty($fetch_item)) : ?>
                        <img src="item_images/<?php echo $fetch_item['item_image1']; ?>" alt="Product Image" width="100%" height="100%">
                    <?php endif; ?>
                </div>
            </div>
            <div class="product-details-right">
                <?php if (!empty($fetch_item)) : ?>

                    <div class="category-name">
                        <p><?php echo $fetch_item['category_name'], ' / ', $fetch_item['artist_name']; ?></p>
                    </div>

                    <div class="item-name">
                        <h2><?php echo $fetch_item['item_name']; ?></h2>
                    </div>

                    <div class="item-price">
                        <h4>&#8369; <?php echo $fetch_item['item_price']; ?></h4>
                    </div>
                    <div class="select-quantity">
                        <span>Select Quantity:</span>
                        <button onclick="decrement()">-</button>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $fetch_item['item_quantity']; ?>" value="1" onchange="updateHiddenQuantity(this.value)">
                        <button onclick="increment()">+</button>
                    </div>

                    <div class="item-stock">
                        <p>Stock: <?php echo $fetch_item['item_quantity']; ?></p>
                    </div>

                    <div class="add-to-cart">
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo $fetch_item['item_id']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $fetch_item['item_name']; ?>">
                            <input type="hidden" name="item_price" value="<?php echo $fetch_item['item_price']; ?>">
                            <input type="hidden" name="item_image" value="<?php echo $fetch_item['item_image1']; ?>">
                            <input type="hidden" id="hidden-quantity" name="quantity" value="1"> <!-- Updated the ID -->
                            <button type="submit" onclick="return checkStock(<?php echo $fetch_item['item_quantity']; ?>);" name="add-to-cart-btn"><i class='fa-solid fa-cart-plus'></i> Add to Cart</button>
                        </form>
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
                        $artist_name = $row['artist_name'];
                        echo "<div class='swiper-slide box'>
                                <div class='icons'>
                                    <a href='#' class='fas fa-eye'onclick='handleImageClick(\"$item_id\")'></a>
                                </div>
                                <div class='image'>
                                    <img src='item_images/$item_image1' alt='' onclick='handleImageClick(\"$item_id\")'>
                                </div>
                                <div class='content'>
                                    <h3 class='artist'>$artist_name</h3>
                                    <h3 class='marquee'>$item_name</h3>
                                    <div class='price'>₱ $item_price</div>
                                    <a href='product_details.php?item_id=$item_id' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
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
                updateHiddenQuantity(input.value);
            }

            function decrement() {
                var input = document.getElementById('quantity');
                input.stepDown();
                updateHiddenQuantity(input.value);
            }

            function updateHiddenQuantity(value) {
                var hiddenInput = document.getElementById('hidden-quantity');
                hiddenInput.value = value;
            }

            function checkStock(stock) {
                var quantity = document.getElementById('quantity').value;
                if (parseInt(quantity) > parseInt(stock)) {
                    alert("You cannot add more than the available stock.");
                    return false;
                }
                return true;
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

            function handleImageClick(itemId) {
                // Construct the URL for the product details page
                var url = "product_details.php?item_id=" + itemId;
                // Redirect the user to the product details page
                window.location.href = url;
            }

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