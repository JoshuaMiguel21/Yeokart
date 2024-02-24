<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart - Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
</head>

<body>
    <nav>
        <a href="#" class="active"><i class="fas fa-home"></i></a>
        <input type="text" name="search" placeholder="Search..">
        <div class="right-side">
            <a href="customer_item.html">Shop</a>
            <a href="#"><i class="fas fa-user"></i></a>
            <a href="customer_cart.html"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </nav>
    <div class="start vh-100 d-flex align-items-center" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h1 class="display-4 text-white">Welcome to Yeokart</h1>
                    <p class="text-white my-3">See the world of K-Pop!</p>
                    <a href="#bestseller" class="btn me-2 btn1">Get Started</a>
                    <a href="#discover" class="btn btn-outline-light">Learn More</a>
                </div>
            </div>
        </div>
    </div>
    <section class="section" id="bestseller">
        <div class="container">
            <?php
            include('../database/db_items.php');
            $select_query = $select_query = "SELECT * FROM products";
            $result_query = mysqli_query($con, $select_query);
            //$row = mysqli_fetch_assoc($result_query);
            //echo $row['item_name']
            while ($row = mysqli_fetch_assoc($result_query)) {
                $item_id = $row['item_id'];
                $item_name = $row['item_name'];
                $item_price = $row['item_price'];
                $item_description = $row['item_description'];
                $item_quantity = $row['item_quantity'];
                $category_id = $row['category_id'];
                $item_image1 = $row['item_image1'];
                echo "<div class='card'>
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
            <div class="header">
                <h2>Best Seller</h2>
            </div>

        </div>
    </section>
    <section class="section" id="discover">
        <div class="container">
            <div class="header">
                <h2>Discover</h2>
            </div>
            <div class="card">
                <div class="product-image">
                    <img src="/res/twice_album_1.png" alt="Twice Album">
                </div>
                <div class="product-info">
                    <h4>TWICE 7th Mini Album - Fancy You</h4>
                    <h4>₱900.00</h4>
                </div>
                <div class="button">
                    <button type="button">View Item</button>
                </div>
            </div>
            <div class="card">
                <div class="product-image">
                    <img src="/res/twice_album_2.jpg" alt="Twice Album">
                </div>
                <div class="product-info">
                    <h4>TWICE 4th Mini Album - Signal</h4>
                    <h4>₱800.00</h4>
                </div>
                <div class="button">
                    <button type="button">View Item</button>
                </div>
            </div>
            <div class="card">
                <div class="product-image">
                    <img src="/res/twice_album_3.jfif" alt="Twice Album">
                </div>
                <div class="product-info">
                    <h4>TWICE Special Album - Twicecoaster: Lane 2</h4>
                    <h4>₱800.00</h4>
                </div>
                <div class="button">
                    <button type="button">View Item</button>
                </div>
            </div>
            <div class="card">
                <div class="product-image">
                    <img src="/res/twice_album_4.jpg" alt="Twice Album">
                </div>
                <div class="product-info">
                    <h4>TWICE 5th Mini Album - What is Love?</h4>
                    <h4>₱900.00</h4>
                </div>
                <div class="button">
                    <button type="button">View Item</button>
                </div>
            </div>
            <div class="card">
                <div class="product-image">
                    <img src="/res/twice_album_5.jpeg" alt="Twice Album">
                </div>
                <div class="product-info">
                    <h4>TWICE 9th Mini Album - More & More</h4>
                    <h4>₱950.00</h4>
                </div>
                <div class="button">
                    <button type="button">View Item</button>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-info">
            <div>
                <p><img src="/res/logo.png" alt="Yeokart Logo" class="footer-logo">&copy; 2024 Yeokart. All rights
                    reserved.</p>
            </div>

            <div class="footer-links">
                <a href="#">Home</a>
                <a href="customer_item.html">Shop</a>
                <a href="#">Contact</a>
                <a href="#">About Us</a>
            </div>

            <div class="social-links">
                <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>