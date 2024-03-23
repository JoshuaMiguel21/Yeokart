<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
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
                    <li><a href="new_customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php" class="active"><i class="fas fa-shopping-cart"></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="cart">
        <div class="header-3">
            <h1>Shopping Cart</h1>
        </div>
        <hr>
        <div class="container">
            <div class="left-column">
                <p id="info">MY CART
                <p>
                    <hr class="gradient">
                </p>
                <div class="project">
                    <div class="shop">
                        <?php
                        $total = 0;
                        include('../database/db_yeokart.php');
                        $select_query = "SELECT * FROM cart WHERE customer_id = $customer_id";
                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) > 0) {

                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $subtotal = $row['quantity'] * $row['price'];
                                $total += $subtotal;

                                echo "<div class='box'>";
                                echo "<img src='item_images/{$row['item_image1']}' alt='Item Image'>";
                                echo "<div class='content'>";
                                echo "<h3>{$row['item_name']}</h3>";
                                echo "<h4>Price: ₱ {$row['price']}</h4>";
                                echo "<h4 id='subtotal{$row['cart_id']}' class='subtotal' style='font-style: italic;'>Subtotal: ₱ " . number_format($subtotal, 2) . "</h4>";
                                echo "<div class='select-quantity'>";
                                echo "<span>Edit Quantity:</span>";
                                echo "<button onclick='decrement({$row['cart_id']}, {$row['price']})'>-</button>";
                                echo "<input type='number' id='quantity{$row['cart_id']}' name='quantity' min='1' value='{$row['quantity']}' onchange='updateTotal()' oninput='updateQuantity({$row['cart_id']}, this.value, {$row['price']})'>";
                                echo "<button onclick='increment({$row['cart_id']}, {$row['price']})'>+</button>";
                                echo "</div>";
                                echo "<p class='btn-area'>";
                                echo "<i class='fa fa-trash'></i>";
                                echo "<span class='btn2'onclick='openDeletePopup({$row['cart_id']})'> Remove</span>";
                                echo "</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Your cart is empty.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <div class="account">
                    <p id="info">ORDER DETAILS
                    <p>
                        <hr class="gradient">
                    <div class="account-info">
                        <h3 class="total">Total: ₱ <?php echo number_format($total, 2); ?></h3>
                    </div>
                    <hr>
                    <div class="account-info center-btn">
                        <a href='#' class='btn'><i class="fa-solid fa-cart-arrow-down"></i>Place Order</a>
                    </div>
                </div>
            </div>
    </section>
    <div id="deletePopup" class="popup-del" style="display: none;">
        <div class="popup-del-content">
            <span class="close" onclick="closeDeletePopup()">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this item?</p>
            <button class="btn-confirm" onclick="confirmDeletion()">Delete</button>
            <button class="btn-cancel" onclick="closeDeletePopup()">Cancel</button>
        </div>
    </div>
    <script>
        function openDeletePopup(cartId) {
            document.getElementById("deletePopup").style.display = "block";
            document.getElementById("deletePopup").setAttribute("data-cartId", cartId);
        }

        function closeDeletePopup() {
            document.getElementById("deletePopup").style.display = "none";
        }

        function confirmDeletion() {
            var cartId = document.getElementById("deletePopup").getAttribute("data-cartId");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_item_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the 'cart_id' in the request
            xhr.onload = function() {
                if (xhr.status == 200) {
                    closeDeletePopup();
                    location.reload();
                } else {
                    alert("Error deleting item.");
                }
            };

            // Send 'cart_id' in the request data
            xhr.send("cart_id=" + cartId);
        }

        function increment(cartId, price) {
            var input = document.getElementById('quantity' + cartId);
            if (input.value === '') {
                input.value = 1; // Set default value to 2 if input is empty
            }
            var newValue = parseInt(input.value) + 1;
            input.value = newValue;
            updateQuantity(cartId, newValue, price);

            // Update subtotal
            var subtotalElement = document.getElementById('subtotal' + cartId);
            var newSubtotal = newValue * price;
            subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2);

            // Update total
            updateTotal();
        }

        function decrement(cartId, price) {
            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
                updateQuantity(cartId, newValue);

                // Update subtotal
                var subtotalElement = document.getElementById('subtotal' + cartId);
                var newSubtotal = newValue * price;
                subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2);

                // Update total
                updateTotal();
            }
        }

        function updateQuantity(cartId, quantity, price) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.target = 'update_frame'; // Target the hidden iframe
            form.action = '';

            var hiddenField1 = document.createElement('input');
            hiddenField1.type = 'hidden';
            hiddenField1.name = 'cart_id';
            hiddenField1.value = cartId;
            form.appendChild(hiddenField1);

            var hiddenField2 = document.createElement('input');
            hiddenField2.type = 'hidden';
            hiddenField2.name = 'quantity';
            hiddenField2.value = quantity;
            form.appendChild(hiddenField2);

            document.body.appendChild(form);
            form.submit();

            // Update subtotal and total in the frontend
            var subtotalElement = document.getElementById('subtotal' + cartId);
            var newSubtotal = quantity * price;
            subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            // Calculate new total
            updateTotal();
        }

        function updateTotal() {
            var totalElements = document.querySelectorAll('.subtotal');
            var total = Array.from(totalElements).reduce((acc, elem) => {
                var subtotalText = elem.textContent.replace('Subtotal: ₱ ', '').replace(',', ''); // Remove currency symbol and comma
                var subtotalValue = parseFloat(subtotalText);
                return acc + subtotalValue;
            }, 0);
            var totalElement = document.querySelector('.total');
            totalElement.innerHTML = "Total: ₱ " + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
    </script>
    <iframe name="update_frame" style="display:none;"></iframe>
</body>

</html>