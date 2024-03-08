<?php
include('../database/db_yeokart.php');

// Assuming you're retrieving the item_id from the URL or form submission
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $select_item_query = "SELECT * FROM products WHERE item_id = $item_id";
    $result_item_query = mysqli_query($con, $select_item_query);

    // Initialize $fetch_item to an empty array as a default state
    $fetch_item = array();

    if (mysqli_num_rows($result_item_query) > 0) {
        // If there are results, fetch the first row
        $fetch_item = mysqli_fetch_assoc($result_item_query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_product_details.css">
</head>


<body>
    <header class="header">
        <div class="header-1">
            <img src="../res/logo.png" alt="Yeokart Logo" class="logo">

            <form action="" class="search-form">
                <input type="search" name="" placeholder="Search here..." id="search-box">
                <label for="search-box" class="fas fa-search"></label>
            </form>
            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="#">Shop</a>
                <a href="#" class="fas fa-shopping-cart"></a>
                <div id="user-btn" class="fas fa-user"></div>
            </div>
        </div>
        <div class="header-2">
            <nav class="navbar">
                <a href="./customer_homepage.php">Home</a>
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
                <?php if (!empty($fetch_item)): ?>
                    <img src="../res/<?php echo $fetch_item['item_image1']; ?>" alt="Product Image" width="100%">
                <?php endif; ?>
            </div>
        </div>
        <div class="product-details-right">
            <?php if (!empty($fetch_item)): ?>
                <p><?php echo $fetch_item['category_name']; ?></p>
                <h2><?php echo $fetch_item['item_name']; ?></h2>
                <h4>PHP <?php echo $fetch_item['item_price']; ?></h4>
                <!-- Add more dynamic content here -->
            <?php endif; ?>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"><span>Related Products</span></h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php
                include('../database/db_yeokart.php');
                $select_query = $select_query = "SELECT * FROM products";
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
                        <a href='#' class='fas fa-eye'></a>
                    </div>
                    <div class='image'>
                    <img src='./item_images/$item_image1' alt=''>
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
    </script>
</body>

</html>