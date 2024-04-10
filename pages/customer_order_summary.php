<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cartId = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the cart table
    $update_query = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cartId";
    mysqli_query($con, $update_query);

    // Redirect back to the cart page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$select_query = "SELECT address FROM addresses WHERE customer_id = $customer_id AND is_default = 1";
$result_query = mysqli_query($con, $select_query);
if (mysqli_num_rows($result_query) > 0) {
    $row = mysqli_fetch_assoc($result_query);
    $address = $row['address'];

    // Display address in JavaScript
    echo "<script>";
    echo "var address = '" . $address . "';";
    echo "</script>";
} else {
    // Display default address if none found
    echo "<script>";
    echo "var address = 'No address found';";
    echo "</script>";
}

$total = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart'])) {
    // Generate UUID for order_id
    $order_id = uniqid();

    // Get customer details
    $address = $_POST['address'];
    $total = $_POST['total'];
    $date = $_POST['date'];

    // Insert order details into orders table
    $insert_query = "INSERT INTO orders (order_id, customer_id, firstname, lastname, address, items_ordered, total, date_of_purchase) VALUES ('$order_id', $customer_id, '$firstname', '$lastname', '$address', '', $total, '$date')";
    mysqli_query($con, $insert_query);

    // Retrieve and insert items_ordered into the orders table
    foreach ($_POST['cart'] as $item) {
        $item_data = explode(',', $item);
        $item_name = $item_data[0];
        $quantity = $item_data[1];
        $subtotal = $item_data[2];

        $items_ordered .= "{$item_name} ({$quantity}) ({$subtotal}), ";
    }

    $update_query = "UPDATE orders SET items_ordered = '$items_ordered' WHERE order_id = '$order_id'";
    mysqli_query($con, $update_query);

    // Redirect or display success message
    header("Location: customer_order_summary.php");
    exit();
} else {
    header("Location: customer_cart.php");
    exit();
}
?>

<body>
    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="" class="search-form">
                    <input type="search" name="" placeholder="Search here..." id="search-box">
                    <label for="search-box" class="fas fa-search"></label>
                </form>
                <label for="click" class="menu-btn">
                    <i class="fas fa-bars"></i>
                </label>
            </div>
            <div class="icons">
                <ul>
                    <li class="search-ul">
                        <form action="" class="search-form">
                            <input type="search" name="" placeholder="Search here..." id="search-box">
                            <label for="search-box" class="fas fa-search"></label>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php" class="active"><i class="fas fa-shopping-cart"></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="summary">
        <div class="header-3">
            <h1>Order Summary</h1>
        </div>
        <hr>
        <div class="container">
            <h2>Customer Details</h2>
            <p>Name: <?php echo $firstname . ' ' . $lastname; ?></p>
            <!-- Assuming you've already retrieved and stored the address in a variable called $address -->
            <p>Address: <?php echo $address; ?></p>
            <h2>Order Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the cart items and display them in the table
                    foreach ($_POST['cart'] as $item) {
                        $item_data = explode(',', $item);
                        $total += floatval($item_data[2]); // Update total by adding the subtotal of each item
                        echo "<tr>";
                        echo "<td>{$item_data[0]}</td>";
                        echo "<td>{$item_data[1]}</td>";
                        echo "<td>₱ {$item_data[2]}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <h2>Total: ₱ <?php echo number_format($total, 2); ?></h2>
            <p>Date of Purchase: <?php echo $_POST['date']; ?></p>
            <div class="center-btn">
                <a href='#' class='btn' onclick="confirmOrder()"><i class="fa-solid fa-cart-arrow-down"></i>Confirm Order</a>
            </div>
        </div>
    </section>
    <script>
        function confirmOrder() {
            // Submit the form containing the order details
            document.getElementById('orderForm').submit();
        }
    </script>
</body>

</html>