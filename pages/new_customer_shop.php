<?php
include('../database/db_yeokart.php');

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href="../css/new_customer_shop.css" rel="stylesheet" />
    <title>Yeokart</title>
</head>

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
                <a href="new_customer_shop.php">Shop</a>
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

    <section class="best" id="best">
        <h1 class="heading"><span>Item Catalog</span></h1>
        <div class="swiper best-slider">
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
                    $artist_name = $row['artist_name'];
                ?>
                    <div class='swiper-slide box'>
                        <div class='icons'>
                            <form method='post'>
                                <input type='hidden' name='item_id' value='<?php echo $item_id; ?>'>
                                <button type='submit' name='view_item_button' class='fas fa-eye'></button>
                            </form>
                        </div>
                        <div class='image'>
                            <img src='item_images/<?php echo $item_image1; ?>' alt=''>
                        </div>
                        <div class='content'>
                            <h3 class='artist'><?php echo $artist_name; ?></h3>
                            <h3 class='marquee'><?php echo $item_name; ?></h3>
                            <div class='price'>₱ <?php echo $item_price; ?></div>
                            <a href='product_details.php?item_id=<?php echo $item_id; ?>' class='btn'>View Item</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

</body>

</html>