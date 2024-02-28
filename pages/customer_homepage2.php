<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/css/style_customer_homepage.css">
</head>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchForm = document.querySelector('.search-form');
        var searchBtn = document.querySelector('#search-btn');

        searchBtn.addEventListener('click', function() {
            searchForm.classList.toggle('active');
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 786) {
                searchForm.classList.remove('active');
            }
        });

        window.addEventListener('scroll', function() {
            if (window.scrollY > 80) {
                document.querySelector('.header .header-2').classList.add('active');
            } else {
                document.querySelector('.header .header-2').classList.remove('active');
            }
        });

        if (window.scrollY > 80) {
            document.querySelector('.header .header-2').classList.add('active');
        }
    });

    var swiper = new Swiper(".best-slider", {
        spaceBetween: 20,
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
                slidesPerView: 3,
            },
            786: {
                slidesPerView: 4,
            },
            991: {
                slidesPerView: 4,
            },
            1024: {
                slidesPerView: 5,
            },
        },
    });
    var swiper = new Swiper(".featured-slider", {
        spaceBetween: 20,
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
                slidesPerView: 3,
            },
            786: {
                slidesPerView: 4,
            },
            991: {
                slidesPerView: 4,
            },
            1024: {
                slidesPerView: 5,
            },
        },
    });
</script>

<body>
    <header class="header">
        <div class="header-1">
            <img src="/res/logo.png" alt="Yeokart Logo" class="logo">

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
                include('../database/db_items.php');
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
                    echo "<div class='swiper-slide card'>
                <div class='product-image'>
                    <img src='./item_images/$item_image1' alt='Twice Album'>
                </div>
                <div class='product-info'>
                    <h4>$item_name</h4>
                    <h4>₱$item_price</h4>
                </div>
                <div class='button'>
                    <button type='button'>View Item</button>
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

</body>

</html>