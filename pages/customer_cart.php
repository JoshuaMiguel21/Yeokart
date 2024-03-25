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

<body onload="restoreScrollPosition()">
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
                        <?php
                        if ($total > 0) {
                            echo "<a href='#' class='btn' onClick='openSummaryPopup()'><i class='fa fa-cart-arrow-down'></i>Place Order</a>";
                        } else {
                            echo "<button class='btn' disabled style='background-color: gray; cursor: not-allowed;'><i class='fa fa-cart-arrow-down'></i>Place Order</button>";
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
    <div id="summaryPopup" class="popup-add" style="display: none;">
        <div class="popup-add-content">
            <span class="close" onclick="closeSummaryPopup()">&times;</span>
            <h2>Order Summary</h2>
            <p><strong>First name: </strong><span id="summaryFirstname"></span></p>
            <p><strong>Last name: </strong><span id="summaryLastname"></span></p>
            <label for="addressDropdown">Select Address: <select id="addressDropdown"></select></label>
            <ul id="cartList"></ul>
            <p><strong>Total: </strong>₱ <span id="summaryTotal"></span></p>
            <p><strong>Date of Purchase: </strong><span id="summaryDate"></span></p>
            <button class="btn-confirm" onclick="placeOrder()">Confirm Order</button>
            <button class="btn-cancel" onclick="closeSummaryPopup()">Cancel</button>
        </div>
    </div>
    <script>
        function storeScrollPosition() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        }

        function restoreScrollPosition() {
            var scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
                sessionStorage.removeItem('scrollPosition');
            }
        }

        function openDeletePopup(cartId) {
            document.getElementById("deletePopup").style.display = "block";
            document.getElementById("deletePopup").setAttribute("data-cartId", cartId);
        }

        function closeDeletePopup() {
            document.getElementById("deletePopup").style.display = "none";
        }

        function openSummaryPopup() {
            // Fetch other information
            var firstname = "<?php echo $firstname; ?>";
            var lastname = "<?php echo $lastname; ?>";
            var total = "<?php echo $total; ?>";
            var currentDate = new Date().toLocaleDateString();

            // Retrieve all addresses associated with the customer ID
            var addresses = [];
            <?php
            $select_query = "SELECT * FROM addresses WHERE customer_id = $customer_id";
            $result_query = mysqli_query($con, $select_query);
            if (mysqli_num_rows($result_query) > 0) {
                while ($row = mysqli_fetch_assoc($result_query)) {
                    echo "addresses.push('{$row['address']}');";
                }
            }
            ?>

            var cartItems = [];
            <?php
            $select_query_cart = "SELECT item_name, quantity, (quantity * price) AS subtotal FROM cart WHERE customer_id = $customer_id";
            $result_query_cart = mysqli_query($con, $select_query_cart);
            $cartItems = array();
            if (mysqli_num_rows($result_query_cart) > 0) {
                while ($row = mysqli_fetch_assoc($result_query_cart)) {
                    $cartItems[] = $row;
                }
            }

            $itemsOrdered = "";
            foreach ($cartItems as $item) {
                $itemsOrdered .= "{$item['item_name']} ({$item['quantity']}) (₱ {$item['subtotal']}), ";
            }
            $itemsOrdered = rtrim($itemsOrdered, ', '); // Remove trailing comma and space
            ?>
            var cartItems = <?php echo json_encode($cartItems); ?>;

            // Display information in the popup
            document.getElementById("summaryPopup").style.display = "block";
            document.getElementById("summaryFirstname").innerText = firstname;
            document.getElementById("summaryLastname").innerText = lastname;
            document.getElementById("summaryTotal").innerText = Number(total).toLocaleString('en-US', {
                maximumFractionDigits: 2
            });
            document.getElementById("summaryDate").innerText = currentDate;

            // Populate the address selection dropdown
            var dropdown = document.getElementById("addressDropdown");
            dropdown.innerHTML = ""; // Clear previous options
            addresses.forEach(address => {
                var option = document.createElement("option");
                option.text = address;
                dropdown.add(option);
            });

            var cartList = document.getElementById("cartList");
            cartList.innerHTML = ""; // Clear previous items
            var itemsOrderedLabel = document.createElement("label");
            itemsOrderedLabel.textContent = "Items Ordered:";
            cartList.appendChild(itemsOrderedLabel);
            cartItems.forEach(item => {
                var listItem = document.createElement("li");
                listItem.innerText = `${item.item_name} (${item.quantity}) (₱ ${Number(item.subtotal).toLocaleString('en-US', {maximumFractionDigits: 2})})`;
                cartList.appendChild(listItem);
            });
        }

        function closeSummaryPopup() {
            document.getElementById("summaryPopup").style.display = "none";
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
            storeScrollPosition();

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
            subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");;

            // Update total
            updateTotal();
        }

        function decrement(cartId, price) {
            storeScrollPosition();

            var input = document.getElementById('quantity' + cartId);
            var newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
                updateQuantity(cartId, newValue, price);

                // Update subtotal
                var subtotalElement = document.getElementById('subtotal' + cartId);
                var newSubtotal = newValue * price;
                subtotalElement.textContent = "Subtotal: ₱ " + newSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");;

                // Update total
                updateTotal();
            }
        }

        function updateQuantity(cartId, quantity, price) {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = '<?php echo $_SERVER['PHP_SELF']; ?>';
            var inputCartId = document.createElement('input');
            inputCartId.type = 'hidden';
            inputCartId.name = 'cart_id';
            inputCartId.value = cartId;
            form.appendChild(inputCartId);
            var inputQuantity = document.createElement('input');
            inputQuantity.type = 'hidden';
            inputQuantity.name = 'quantity';
            inputQuantity.value = quantity;
            form.appendChild(inputQuantity);
            document.body.appendChild(form);
            form.submit();
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

        function placeOrder() {
            var addressDropdown = document.getElementById("addressDropdown");
            var selectedAddress = addressDropdown.options[addressDropdown.selectedIndex].text;

            var cartItems = <?php echo json_encode($cartItems); ?>;

            var itemsOrdered = cartItems.map(item => `${item.item_name} (${item.quantity}) (₱ ${item.subtotal})`).join(', ');

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "place_order_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Order placed successfully!");
                        window.location.reload();
                    } else {
                        alert("Failed to place order. Please try again.");
                    }
                } else {
                    alert("Error placing order. Please try again.");
                }
            };

            var data = "customer_id=<?php echo $customer_id; ?>&firstname=<?php echo $firstname; ?>&lastname=<?php echo $lastname; ?>&address=" + selectedAddress + "&total=<?php echo $total; ?>&items_ordered=" + encodeURIComponent(itemsOrdered);
            xhr.send(data);
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
    </script>
    <iframe name="update_frame" style="display:none;"></iframe>
</body>

</html>