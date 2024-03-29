<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart-Yeokart</title>
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

if (isset($_SESSION['selectedAddressId'])) {
    unset($_SESSION['selectedAddressId']); 
}


$sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $cartCount = $row['cart_count'];
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

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
                        <form action="" class="search-form1">
                            <input type="search" name="" placeholder="Search here..." id="search-box">
                            <label for="search-box" class="fas fa-search"></label>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="new_customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php">Contact Us</a></li>
                    <li><a href="customer_cart.php" class="active"><i class="fas fa-shopping-cart"><span id="cart-num"><?php echo $cartCount; ?></span></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="cart" id="cart">
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
                        $select_query = "SELECT c.cart_id, c.customer_id, c.item_name, c.item_image1, c.quantity, c.price, p.item_quantity FROM cart c INNER JOIN products p ON c.item_name = p.item_name WHERE c.customer_id = $customer_id";
                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) > 0) {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $subtotal = $row['quantity'] * $row['price'];
                                $total += $subtotal;
                                $item_quantity = $row['item_quantity']; // Available item quantity

                                echo "<div class='box'>";
                                echo "<img src='item_images/{$row['item_image1']}' alt='Item Image'>";
                                echo "<div class='content'>";
                                echo "<h3>{$row['item_name']}</h3>";
                                echo "<h4>Price: ₱ {$row['price']}</h4>";
                                echo "<h4 id='subtotal{$row['cart_id']}' class='subtotal' style='font-style: italic;'>Subtotal: ₱ " . number_format($subtotal, 2) . "</h4>";
                                echo "<div class='select-quantity'>";
                                echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
                                echo "<span>Edit Quantity:</span>";
                                echo "<button type='button' onclick='decrement({$row['cart_id']}, {$row['price']})'>-</button>";
                                echo "<input type='number' id='quantity{$row['cart_id']}' name='quantity' min='1' max='{$row['item_quantity']}' data-max-quantity='{$row['item_quantity']}' value='{$row['quantity']}' onchange='updateTotal()' oninput='updateQuantity({$row['cart_id']}, this.value, {$row['price']}, $item_quantity)'>";
                                $disableAdd = $row['quantity'] >= $item_quantity ? "disabled" : "";
                                echo "<button type='button' onclick='increment({$row['cart_id']}, {$row['price']}, $item_quantity)' $disableAdd>+</button>";
                                echo "<input type='hidden' name='cart_id' value='{$row['cart_id']}'>";
                                echo "<input type='submit' style='display: none;'>";
                                echo "</form>";
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
                        <?php
                        if ($total > 0) {
                            echo "<form action='update_cart_quantities.php' method='POST' id='checkoutForm'>";
                            echo "<button type='submit' class='btn' name='checkout'><i class='fa fa-cart-arrow-down'></i>Checkout</button>";
                            echo "</form>";
                        } else {
                            echo "<button class='btn' disabled style='background-color: gray; cursor: not-allowed;'><i class='fa fa-cart-arrow-down'></i>Checkout</button>";
                        }
                        ?>
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

        // JavaScript functions for increment and decrement
        function increment(cartId, price, itemQuantity) {
            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) + 1;
            if (newValue <= itemQuantity) {
                input.value = newValue;
                updateQuantity(cartId, newValue, price);
            }
        }

        function decrement(cartId, price) {
            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
                updateQuantity(cartId, newValue, price);
            }
        }

        // Update the quantity in the database and UI
        function updateQuantity(cartId, quantity, price, itemQuantity) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    updateSubtotal(cartId, quantity, price);
                    updateTotal();
                    // Enable/disable increment button based on itemQuantity
                    document.querySelector(`#quantity${cartId}`).nextElementSibling.disabled = quantity >= itemQuantity;
                } else {
                    console.error("Error updating quantity:", xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error("Network error occurred while updating quantity");
            };

            xhr.send("cart_id=" + cartId + "&quantity=" + quantity);
        }

        // Other existing JavaScript functions remain the same


        // Function to update subtotal
        function updateSubtotal(cartId, quantity, price) {
            var subtotalElement = document.getElementById('subtotal' + cartId);
            var newSubtotal = quantity * price;
            subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Function to update total
        function updateTotal() {
            var totalElements = document.querySelectorAll('.subtotal');
            var total = Array.from(totalElements).reduce(function(acc, elem) {
                var subtotalText = elem.textContent.replace('Subtotal: ₱ ', '').replace(',', '');
                var subtotalValue = parseFloat(subtotalText);
                return acc + subtotalValue;
            }, 0);
            var totalElement = document.querySelector('.total');
            totalElement.innerHTML = "Total: ₱ " + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.getElementById('click');
                const cartToHide = document.getElementById('cart');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        cartToHide.style.display = 'none';
                    } else {
                        cartToHide.style.display = 'block';
                    }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkoutForm');
            checkoutForm.addEventListener('submit', function(event) {
                let hasExceededQuantity = false;
                const cartItems = document.querySelectorAll('.box'); // Assuming each cart item is within a .box div

                cartItems.forEach(function(item) {
                    const quantityInput = item.querySelector('input[type=number]');
                    const maxQuantity = quantityInput.getAttribute('data-max-quantity'); // Assuming data-max-quantity contains the item's available stock

                    if (parseInt(quantityInput.value) > parseInt(maxQuantity)) {
                        hasExceededQuantity = true;
                        alert('One or more items exceed the available stock. Please adjust your quantities before checking out.');
                        event.preventDefault(); // Prevent form submission only if quantities are invalid
                        return;
                    }
                });
                // If no items exceed available stock, allow form to submit naturally
            });
        });

    </script>
    <iframe name="update_frame" style="display:none;"></iframe>
</body>

</html>