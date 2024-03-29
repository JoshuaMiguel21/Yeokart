<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Shopping Cart Summary - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>
<style>
.swal2-custom-popup {
    font-size: 20px;
    width: 600px;
}

.swal2-custom-title {
    font-size: 24px;
}

.swal2-custom-text {
    font-size: 18px;
    text-transform: none;
}
</style>

<body style="margin: 30px;">
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['address_id'])) {
    $_SESSION['selectedAddressId'] = $_POST['address_id'];
}

$selectedAddressId = isset($_SESSION['selectedAddressId']) ? $_SESSION['selectedAddressId'] : null;

$query = "";
if ($selectedAddressId) {
    $query = "SELECT `address_id`, `customer_id`, `address`, `street`, `city`, `province`, `zipCode`, `phoneNumber`, `is_default` FROM `addresses` WHERE `address_id` = $selectedAddressId";
} else {
    // If no address has been selected yet, fetch the default address
    $query = "SELECT `address_id`, `customer_id`, `address`, `street`, `city`, `province`, `zipCode`, `phoneNumber`, `is_default` FROM `addresses` WHERE `customer_id` = $customer_id AND `is_default` = 1";
}

$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $address = $row['address'];
    $street = $row['street'];
    $city = $row['city'];
    $province = $row['province'];
    $zipCode = $row['zipCode'];
    $phoneNumber = $row['phoneNumber'];
    
    $fullAddress = trim($address . " " . $street . " " . $city . " " . $province . " " . $zipCode);
} else {
    $address = "No default address found";
    $street = "";
    $city = "";
    $province = "";
    $zipCode = "";
    $phoneNumber = "";
    $fullAddress = "No default address found"; 
}

$customer_id = $_SESSION['id'];
$query = "SELECT * FROM `addresses` WHERE `customer_id` = $customer_id";
$result = mysqli_query($con, $query);

$addresses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $addresses[] = $row;
}

$customer_id = $_SESSION['id'];

$select_query_cart = "SELECT * FROM cart WHERE customer_id = $customer_id";
$result_query_cart = mysqli_query($con, $select_query_cart);

$cartItems = array();

if (mysqli_num_rows($result_query_cart) > 0) {
    while ($row = mysqli_fetch_assoc($result_query_cart)) {
        $cartItems[] = $row;
    }
}

$overallTotal = 0;
foreach ($cartItems as $item) {
    $overallTotal += $item['price'] * $item['quantity'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) { 
    $items_ordered = array_column($cartItems, 'item_name');
    $items_ordered_str = implode(", ", $items_ordered);
    $date_of_purchase = date("Y-m-d");
    $order_id = uniqid();

    $item_quantities = array_column($cartItems, 'quantity');
    $item_quantities_str = implode(", ", $item_quantities);

    $insert_query = $con->prepare("INSERT INTO orders (order_id, customer_id, firstname, lastname, address, items_ordered, item_quantity, total, date_of_purchase) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_query->bind_param("sisssssss", $order_id, $customer_id, $firstname, $lastname, $fullAddress, $items_ordered_str, $item_quantities_str, $overallTotal, $date_of_purchase);
    
    if ($insert_query->execute()) {

        $clear_cart_query = "DELETE FROM cart WHERE customer_id = ?";
        $clear_cart_stmt = $con->prepare($clear_cart_query);
        $clear_cart_stmt->bind_param("i", $customer_id);
        $clear_cart_stmt->execute();

        foreach ($cartItems as $item) {
            $item_name = $item['item_name'];
            $quantity_purchased = $item['quantity'];
        
            $product_query = $con->prepare("SELECT `item_quantity`, `times_sold` FROM `products` WHERE `item_name` = ?");
            $product_query->bind_param("s", $item_name);
            $product_query->execute();
            $product_result = $product_query->get_result();
        
            if ($product_result->num_rows > 0) {
                $product_row = $product_result->fetch_assoc();
                $current_stock = $product_row['item_quantity'];
                $current_times_sold = $product_row['times_sold'];
        
                $new_stock = $current_stock - $quantity_purchased;
                $new_times_sold = $current_times_sold + $quantity_purchased;
        
                $update_query = $con->prepare("UPDATE `products` SET `item_quantity` = ?, `times_sold` = ? WHERE `item_name` = ?");
                $update_query->bind_param("iis", $new_stock, $new_times_sold, $item_name);
        
                if (!$update_query->execute()) {
                    echo "Error updating product stock and sales data for item: " . $item_name;
                }
            } else {
                echo "Product not found for item: " . $item_name;
            }
        }
        
        unset($_SESSION['selectedAddressId']);

        echo "<script>
                Swal.fire({
                    icon: 'success', 
                    title: 'Order Placed!',
                    text: 'Your order has been placed successfully.',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-custom-popup',
                        title: 'swal2-custom-title',
                        content: 'swal2-custom-text'
                    },
                    backdrop: true, 
                    allowOutsideClick: false 
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'customer_cart.php'; 
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error', 
                    title: 'Error!',
                    text: 'There was an error placing your order. Please try again later.',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-custom-popup',
                        title: 'swal2-custom-title',
                        content: 'swal2-custom-text'
                    },
                    backdrop: true, 
                    allowOutsideClick: false 
                });
              </script>";
    }
}

?>
    <header class="order-header">
        <img src="../res/logo.png" alt="Yeokart Logo" class="logo1" width="200px" height="80px">
        <a href="customer_cart.php" class="cart-dir"><i class="fas fa-shopping-cart"></i></a>
    </header>
    <hr class="order-hr">

    <div class="left">
        <div class="address-container">
            <div class="address-inf">
                <h3><i class="fas fa-map-marker-alt"></i>Address</h3>
                <a href="#" onclick="showPopup()">Choose another address</a>
            </div>
            <p><strong class="name"><?php echo $firstname ." ". $lastname; ?></strong></p>
            <?php if($address !== "No default address found"): ?>
                <p><strong>Address:</strong> <?php echo $address; ?></p>
                <p><strong>Street:</strong> <?php echo $street; ?></p>
                <p><strong>City:</strong> <?php echo $city; ?></p>
                <p><strong>Province:</strong> <?php echo $province; ?></p>
                <p><strong>Zip Code:</strong> <?php echo $zipCode; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $phoneNumber; ?></p>
            <?php else: ?>
                <p><strong>No default address found</strong></p>
            <?php endif; ?>
        </div>


        <div class="my-cart">
            <h3>Your Cart<span><?php echo "(".$cartCount ." items)"; ?></span></h3>
            <?php
                foreach ($cartItems as $item) {
                    echo "<div class='cart-item'>";
                    echo "<img src='item_images/{$item['item_image1']}' alt='Item Image' class='cart-item-image'>";
                    echo "<div class='item-details'>";
                    echo "<p><b>Name: </b>{$item['item_name']}</p>";
                    echo "<p>Price: ₱ " . number_format($item['price'], 2) . "</p>";
                    echo "</div>";
                    echo "<p>Quantity: {$item['quantity']}</p>";
                    echo "<p>Total: ₱ " . number_format($item['price'] * $item['quantity'], 2) . "</p>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
    
    <form method="POST" action="">
        <div class="right">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <p><strong>Overall Total: </strong>₱ <?php echo number_format($overallTotal, 2); ?></p>
                <p><strong>Date Purchased: </strong><?php echo date("F j, Y"); ?></p>
                <div class="button-order">
                    <button type="submit" class="btn-confirm" name="confirm_order"><i class='fa fa-cart-arrow-down'></i>Place Order</button>
                </div>     
            </div>
        </div>
    </form>


    <div id="chooseAddressPopup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h3>Choose Address</h3>
            <form id="chooseAddressForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <?php foreach ($addresses as $addr) : ?>
                    <label>
                        <input type="radio" name="address_id" value="<?php echo htmlspecialchars($addr['address_id']); ?>" <?php if ($selectedAddressId == $addr['address_id']) echo "checked"; ?>>
                        <?php echo htmlspecialchars($addr['address'] . ", " . $addr['street'] . ", " . $addr['city'] . ", " . $addr['province'] . ", " . $addr['zipCode']); ?>
                    </label><br>
                <?php endforeach; ?>
                <button type="submit" class="update-btn">Save Address</button>
            </form>
        </div>
    </div>


    <script>
        function closePopup() {
            document.getElementById("chooseAddressPopup").style.display = "none";
        }

        function showPopup() {
            document.getElementById('chooseAddressPopup').style.display = 'block';
        }

    </script>
</body>

</html>