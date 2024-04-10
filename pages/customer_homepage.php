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

?>

<body>
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
                        <form action="customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav" class="active">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"><span id="cart-num"><?php echo $cartCount; ?></span></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
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
                $select_query = "SELECT * FROM products ORDER BY times_sold DESC LIMIT 10";
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
                            <div class='price'>₱ <?php echo $item_price; ?></div>
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
                $select_query = $select_query = "SELECT * FROM products WHERE is_featured = 1";
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
                            <div class='price'>₱ <?php echo $item_price; ?></div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('click');
            const homeToHide = document.getElementById('home'); // Change 'home' to the ID of the section you want to hide/show
            const bestToHide = document.getElementById('best');
            const featuredToHide = document.getElementById('featured');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    homeToHide.style.display = 'none';
                    bestToHide.style.display = 'none';
                    featuredToHide.style.display = 'none';
                } else {
                    homeToHide.style.display = 'block';
                    bestToHide.style.display = 'block';
                    featuredToHide.style.display = 'block';
                }
            });
        });

        function validateSearch() {
            var searchBox = document.getElementById('search-box');
            if (searchBox.value.trim() === '') {
                return false;
            }
            return true;
        }
    </script>
</body>

</html>