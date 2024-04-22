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
        font-size: 16px;
        width: 500px;
    }

    .swal2-custom-title {
        font-size: 20px;
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

    $cartTotal = 0;
    foreach ($cartItems as $item) {
        $cartTotal += $item['price'] * $item['quantity'];
    }
    $shippingFee = 0;
    $overallTotal = 0;

    // Calculate total quantity of items with category "Albums"
    $totalAlbumsQuantity = array_reduce($cartItems, function ($acc, $item) {
        if (preg_match('/\b(albums?|albums)\b/i', $item['category'])) {
            return $acc + $item['quantity'];
        }
        return $acc;
    }, 0);

    // Determine the province based on the selected address or default to Metro Manila
    $province = $province === 'Metro Manila' ? 'Metro Manila' : $province;

    // Check if province is empty and set shippingFee to 0
    if ($province === '') {
        $shippingFee = 0;
    } else {
        // Calculate shipping fee based on quantity and province
        if ($totalAlbumsQuantity < 3) {
            $shippingFee = $province === 'Metro Manila' ? 100 : 180;
        } else {
            $shippingFee = $province === 'Metro Manila' ? 120 : 220;
        }
    }

    // Update overall total
    $overallTotal = $cartTotal + $shippingFee;


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) {
        if ($fullAddress === "No default address found") {
            // Trigger SweetAlert error message due to lack of a default address
            echo "<script>
    Swal.fire({
        icon: 'error', 
        title: 'No Address!',
        text: 'There is no default address yet. Please update your address information before placing the order.',
        confirmButtonText: 'Add New Address',
        customClass: {
            popup: 'swal2-custom-popup',
            title: 'swal2-custom-title',
            content: 'swal2-custom-text'
        },
        backdrop: true, 
        allowOutsideClick: false 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'customer_address.php'; 
        }
    });
</script>";
        } else {
            // Your existing order processing code starts here since a default address exists
            $items_ordered = array_column($cartItems, 'item_name');
            $items_ordered_str = implode(", ", $items_ordered);
            $date_of_purchase = "NOW()";
            $order_id = uniqid();

            $item_quantities = array_column($cartItems, 'quantity');
            $item_quantities_str = implode(", ", $item_quantities);

            $categories = array_column($cartItems, 'category');
            $item_categories_str = implode(", ", $categories);

            $artists = array_column($cartItems, 'artist');
            $item_artists_str = implode(", ", $artists);

            $price = array_column($cartItems, 'price');
            $item_price_str = implode(", ", $price);

            $subtotal_array = array();
            foreach ($cartItems as $item) {
                $subtotal_array[] = $item['subtotal'];
            }
            $item_subtotal_str = implode(", ", $subtotal_array);

            $image1 = array_column($cartItems, 'item_image1');
            $item_images_str = implode(", ", $image1);

            $insert_query = $con->prepare("INSERT INTO orders (order_id, customer_id, address, items_ordered, item_quantity, items_category, items_artist, items_price, subtotal, total, shipping_fee, overall_total, date_of_purchase, items_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
            $insert_query->bind_param("sisssssssssss", $order_id, $customer_id, $fullAddress, $items_ordered_str, $item_quantities_str, $item_categories_str, $item_artists_str, $item_price_str, $item_subtotal_str, $cartTotal, $shippingFee, $overallTotal, $item_images_str);

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
                        title: 'Order Placed Successfully!',
                        text: 'Proceed with the payment and send the proof of payment to verify your order.',
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
                            window.location.href = 'customer_profile.php'; 
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
            // Your existing order processing code ends here
        }
    }

    ?>
    <header class="order-header">
        <a href="customer_homepage.php">
            <img src="../res/logo.png" alt="Yeokart Logo" class="logo1" width="200px" height="80px">
        </a>
        <a href="customer_cart.php" class="cart-dir">
            <div class="slideRight">
                Return To Cart <i class="fas fa-shopping-cart"></i>
            </div>
        </a>
    </header>

    <hr class="order-hr">

    <div class="left">
        <div class="reminders-container">
            <div class="reminders-inf">
                <h3><i class="fa-solid fa-circle-info"></i>Important Reminders</h3>
            </div>
            <h3>WARNING: Orders placed cannot be canceled. Please verify your cart before proceeding.</h3>
            <br></br>
            <p><strong class="name">Mode of Payment</strong></p>
            <p><strong>GCash</strong></p>
            <?php
            require('../database/db_yeokart.php');

            // SQL query to retrieve contacts_description from contacts table where icon_link is <i class='fa-solid fa-peso-sign'></i>
            $sql = "SELECT contacts_description FROM contacts WHERE icon_link = '<i class=\'fa-solid fa-peso-sign\'></i>'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li style='margin-left: 20px;'><span class=\"larger-font\">" . $row["contacts_description"] . "</span></li>";
                }
                echo "</ul>";
            } else {
                echo "<span style='margin-left: 20px;'> No Data Found</span>";
            }

            $con->close();
            ?>
            <p>After Placing your order, you may now proceed to pay through GCash and please <strong>don't forget to provide us the proof of payment by uploading the screenshot of the transaction</strong> in your account profile.</p>
            <p style="margin-top: 20px;"><strong class="name">Mode of Delivery</strong></p>
            <p>We will arrange the delivery through a courier service, but the customer will be responsible for the delivery fee.
                The shipping rates will be provided below.</p>
            <p>The customer can choose if they want to pay the shipping fee through GCash or Cash on Delivery.</p>
            <p style="margin-top: 20px;"><strong class="name">Shipping Fee</strong></p>
            <p><strong>These are the rates for the Shipping fee.</strong></p>
            <p>Small to Medium Items (Up to 2 albums)</p>
            <ul>
                <li style="margin-left: 20px;">Metro Manila - <strong>₱ 100</strong></li>
                <li style="margin-left: 20px;">Provinces - <strong>₱ 180</strong></li>
            </ul>
            <p>Large Items (3 albums and more)</p>
            <ul>
                <li style="margin-left: 20px;">Metro Manila - <strong>₱ 120</strong></li>
                <li style="margin-left: 20px;">Provinces - <strong>₱ 220</strong></li>
            </ul>

        </div>
        <div class="address-container">
            <div class="address-inf">
                <h3><i class="fas fa-map-marker-alt"></i>Address</h3>
                <a href="#" onclick="showPopup()">Choose another address</a>
            </div>
            <p><strong class="name"><?php echo $firstname . " " . $lastname; ?></strong></p>
            <?php if ($address !== "No default address found") : ?>
                <p><strong>Address:</strong> <?php echo $address; ?></p>
                <p><strong>Street:</strong> <?php echo $street; ?></p>
                <p><strong>City:</strong> <?php echo $city; ?></p>
                <p><strong>Province:</strong> <?php echo $province; ?></p>
                <p><strong>Zip Code:</strong> <?php echo $zipCode; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $phoneNumber; ?></p>
            <?php else : ?>
                <p><strong>No default address found</strong></p>
            <?php endif; ?>
        </div>

        <div class="my-cart">
            <h3>Your Cart<span><?php echo "(" . $cartCount . " items)"; ?></span></h3>
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
                <p><strong>Cart Total: </strong>₱ <?php echo number_format($cartTotal, 2); ?></p>
                <p><strong>Shipping Fee: </strong>₱ <?php echo number_format($shippingFee, 2); ?></p>
                <p><strong>Overall Total: </strong>₱ <?php echo number_format($overallTotal, 2); ?></p>
                <p><strong>Date of Purchase: </strong><?php echo date("F j, Y"); ?></p>
                <div class="button-order">
                    <button type="submit" class="btn-confirm" name="confirm_order" id="placeOrderButton">
                        <input type="hidden" name="confirm_order" value="1">
                        <i class='fa fa-cart-arrow-down'></i>
                        <i class='fas fa-circle-notch fa-spin' style='display: none;' id='loadingIcon'></i> Place Order
                    </button>
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

        document.addEventListener("DOMContentLoaded", function() {
            var placeOrderButton = document.getElementById("placeOrderButton");
            var loadingIcon = document.getElementById('loadingIcon');
            var cartIcon = document.querySelector('.btn-confirm i.fa-cart-arrow-down');

            placeOrderButton.addEventListener("click", function(event) {
                event.preventDefault();

                loadingIcon.style.display = 'inline-block';
                cartIcon.style.display = 'none';

                setTimeout(function() {
                    // Make sure you are targeting the correct form, especially if there are multiple forms on the page.
                    document.querySelector('form').submit();
                }, 1000);
            });
        });
    </script>
</body>

</html>