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
    <link href="../css/customer_shop.css" rel="stylesheet" />
    <title>Yeokart</title>
</head>

<body>
    <nav>
        <a href="customer_homepage.php"><i class="fas fa-home"></i></a>
        <input type="text" id="search" placeholder="Search..">
        <div class="right-side">
            <a href="#" class="active">Shop</a>
            <a href="#"><i class="fas fa-user"></i></a>
            <a href="customer_cart.html"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </nav>
    <div class="content-wrapper">
        <div class="filter-section">
            <h2>Search Filter</h2>
            <input type="text" name="filter" placeholder="Filter by name...">
            <hr>
            <h4>Filter by Category</h4>
            <div class="filter-category">
                <label>
                    <input type="checkbox" name="category" value="kpop"> K-pop
                </label>
                <label>
                    <input type="checkbox" name="category" value="pop"> J-pop
                </label>
                <label>
                    <input type="checkbox" name="category" value="rock"> Pop
                </label>
            </div>

            <h4>Filter by Artist</h4>
            <div class="filter-category">
                <label>
                    <input type="checkbox" name="category" value="twice"> TWICE
                </label>
                <label>
                    <input type="checkbox" name="category" value="bts"> BTS
                </label>
                <label>
                    <input type="checkbox" name="category" value="red"> Red Velvet
                </label>
            </div>

            <button type="button" onclick="applyFilter()">Filter</button>
        </div>

        <section class="section">
            <div class="container">
                <div class="header">
                </div>
                <hr>
                <?php
                $select_items = mysqli_query($con, "SELECT * FROM products");
                if (mysqli_num_rows($select_items) > 0) {
                    while($fetch_item = mysqli_fetch_assoc($select_items)){
                        ?>
                    <form method="post" action="">
                    <div class="card">
                        <div class="product-image">
                            <!-- Assuming you have a field in products table for item image -->
                            <img src="/res/<?php echo $fetch_item['item_image1']; ?>" alt="<?php echo $fetch_item['item_name']; ?>">
                        </div>
                        <div class="product-info">
                            <h4><?php echo $fetch_item['item_name']; ?></h4>
                            <h4><?php echo $fetch_item['item_price']; ?></h4>
                            <!-- Hidden fields to store item details -->
                            <input type="hidden" name="item_id" value="<?php echo $fetch_item['item_id']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $fetch_item['item_name']; ?>">
                            <input type="hidden" name="item_price" value="<?php echo $fetch_item['item_price']; ?>">
                            <!-- You can add more hidden fields for other item details -->
                        </div>
                        <div class="button">
                            <button type="submit" name="view_item_button">View Item</button>
                        </div>
                    </div>
                </form>
                <?php
                    }
                } else {
                    echo "No products";
                }
                ?>

            </div>
        </section>
    </div>

    <footer>
        <div class="footer-info">
            <div>
                <p><img src="/res/logo.png" alt="Yeokart Logo" class="footer-logo">&copy; 2024 Yeokart. All rights
                    reserved.</p>
            </div>

            <div class="footer-links">
                <a href="customer_homepage.html">Home</a>
                <a href="#">Shop</a>
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