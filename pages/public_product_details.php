<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
    <link rel="icon" type="image/png" href="../res/icon.png">
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

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $select_item_query = "SELECT *, category_name, artist_name FROM products WHERE item_id = $item_id";
    $result_item_query = mysqli_query($con, $select_item_query);

    if (mysqli_num_rows($result_item_query) > 0) {
        $fetch_item = mysqli_fetch_assoc($result_item_query);
    }
}
?>

<body>
    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="index.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="public_customer_shop.php" method="GET" class="search-form">
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
                        <form action="public_customer_shop.php" method="GET" class="search-form">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="index
                    .php" id="home-nav">Home</a></li>
                    <li><a href="public_customer_shop.php" class="active">Shop</a></li>
                    <li><a href="public_faq.php">FAQ</a></li>
                    <li><a href="public_contact_page.php">Contact Us</a></li>
                    <button class="sign-in-button" onclick="window.location.href='login_page.php';">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
                        </svg>


                        <div class="sign-in-btn-text">
                            Sign In
                        </div>

                    </button>
                </ul>
            </div>
        </div>
    </header>
    <div class="btn-return2">
        <a href="public_customer_shop.php" class="btn-prod">
            <i class="fas fa-arrow-left"></i>
            <span class="text">RETURN TO SHOP</span>
        </a>
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="cart-access-popup" id="cart-access-popup">
        <a href="#" class="close" id="close-popup">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="10px" viewBox="215.186 215.671 80.802 80.8" enable-background="new 215.186 215.671 80.802 80.8" xml:space="preserve">
                <polygon fill="#FFFFFF" points="280.486,296.466 255.586,271.566 230.686,296.471 215.19,280.964 240.086,256.066 215.186,231.17 
        230.69,215.674 255.586,240.566 280.475,215.671 295.985,231.169 271.987,256.064 295.987,280.96 " />
            </svg>
        </a>

        <div class="valid">
            <!-- Shopping cart GIF icon -->
            <img src="../res/shopping-cart-gif.gif" alt="Shopping Cart" width="40px" height="40px">
        </div>

        <h1>Ready to add this item to cart?</h1>
        <p>Please log in or sign up to continue.</p>
        <div class="bottom-popup">
            <a class="start" href="login_page.php">Login / Sign Up</a>
        </div>
    </div>

    <section class="product-details" id="product-details">
        <div class="product-details-left">
            <?php if (!empty($fetch_item['item_image1']) || !empty($fetch_item['item_image2']) || !empty($fetch_item['item_image3'])) : ?>
                <div class="main-image fade">
                    <!-- Check if $fetch_item is not empty before accessing its elements -->
                    <?php if (!empty($fetch_item['item_image1'])) : ?>
                        <img src="item_images/<?php echo $fetch_item['item_image1']; ?>" alt="Product Image" width="100%" height="100%">
                    <?php endif; ?>
                </div>
                <?php if (!empty($fetch_item['item_image2'])) : ?>
                    <div class="main-image fade">
                        <!-- Check if $fetch_item is not empty before accessing its elements -->
                        <img src="item_images/<?php echo $fetch_item['item_image2']; ?>" alt="Product Image" width="100%" height="100%">
                    </div>
                <?php endif; ?>
                <?php if (!empty($fetch_item['item_image3'])) : ?>
                    <div class="main-image fade">
                        <!-- Check if $fetch_item is not empty before accessing its elements -->
                        <img src="item_images/<?php echo $fetch_item['item_image3']; ?>" alt="Product Image" width="100%" height="100%">
                    </div>
                <?php endif; ?>
                <?php if (!empty($fetch_item['item_image2']) || !empty($fetch_item['item_image3'])) : ?>
                    <a class="prev" onClick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onClick="plusSlides(1)">&#10095;</a>
                <?php endif; ?>

                <div style="text-align: center">
                    <span class="dot" onClick="currentSlides(1)"></span>
                    <?php if (!empty($fetch_item['item_image2'])) : ?>
                        <span class="dot" onClick="currentSlides(2)"></span>
                    <?php endif; ?>
                    <?php if (!empty($fetch_item['item_image3'])) : ?>
                        <span class="dot" onClick="currentSlides(3)"></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
                    <h4>&#8369; <?php echo number_format($fetch_item['item_price'], 2); ?></h4>
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
                        <input type="hidden" name="item_image1" value="<?php echo $fetch_item['item_image1']; ?>">
                        <input type="hidden" name="category_name" value="<?php echo $fetch_item['category_name']; ?>">
                        <input type="hidden" name="artist_name" value="<?php echo $fetch_item['artist_name']; ?>">
                        <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                        <input type="hidden" id="hidden-quantity" name="quantity" value="1"> <!-- Updated the ID -->

                        <?php if ($fetch_item['item_quantity'] > 0) : ?>
                            <button type="submit" name="add-to-cart-btn" href=""><i class='fa-solid fa-cart-plus'></i> Add to Cart</button>
                        <?php else : ?>
                            <button type="button" disabled style="cursor: not-allowed; background-color: gray; border-radius: 3px;"><i class='fa-solid fa-cart-plus'></i> Out of Stock</button>
                        <?php endif; ?>
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
                                    <div class='price'>₱ " . number_format($item_price, 2) . "</div>
                                    <a href='public_product_details.php?item_id=$item_id' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
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
            var url = "public_product_details.php?item_id=" + itemId;
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

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('click');
            const featuredToHide = document.getElementById('featured');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    featuredToHide.style.display = 'none';
                } else {
                    featuredToHide.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const closeNotification = document.getElementById('close-notification');
            const cartNotification = document.getElementById('cart-notification');

            closeNotification.addEventListener('click', function() {
                cartNotification.style.display = 'none';
            });

            <?php if (isset($_POST['add-to-cart-btn'])) : ?>

                setTimeout(function() {
                    document.getElementById('cart-notification').style.display = 'block';
                    document.getElementById('cart-item-name').innerText = "<?php echo $fetch_item['item_name']; ?>";
                    document.getElementById('cart-item-image').src = "item_images/<?php echo $fetch_item['item_image1']; ?>";
                });

            <?php endif; ?>
        });
    </script>


    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("main-image");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) {
                slideIndex = slides.length;
            }
            if (n < 1) {
                slideIndex = 1;
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartBtn = document.querySelector('button[name="add-to-cart-btn"]');
            const popup = document.getElementById('cart-access-popup');
            const closePopup = document.getElementById('close-popup');
            const overlay = document.getElementById('overlay'); // Get the overlay element

            addToCartBtn.addEventListener('click', function(event) {
                event.preventDefault();
                popup.style.display = 'block';
                overlay.style.display = 'block'; // Display the overlay
            });

            closePopup.addEventListener('click', function(event) {
                event.preventDefault();
                popup.style.display = 'none';
                overlay.style.display = 'none'; // Hide the overlay
            });

            // Optional: Hide popup and overlay when clicking outside of the popup
            overlay.addEventListener('click', function() {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            });
        });
    </script>



</body>

</html>