<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
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

    function openPopup() {
        document.getElementById('editProfilePopup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('editProfilePopup').style.display = 'none';
    }

    document.querySelector('.nav-user .logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        openPopup();
    });

    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }
</script>

<?php
require('../database/db_yeokart.php');
session_start();

if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $username = $_SESSION['username'];
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

$sql = "SELECT customer_id, COUNT(*) AS address_count FROM addresses WHERE customer_id = $customer_id";
$result = $con->query($sql);

if ($result) {
    $addressCount = 0;
    while ($row = $result->fetch_assoc()) {
        $addressCount += $row['address_count'];
    }
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$defaultAddressQuery = "SELECT * FROM addresses WHERE customer_id = $customer_id AND is_default = 1 LIMIT 1";
$defaultAddressResult = $con->query($defaultAddressQuery);

if ($defaultAddressResult->num_rows > 0) {
    $defaultAddress = $defaultAddressResult->fetch_assoc();
    $displayAddress = htmlspecialchars($defaultAddress['address']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['street']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['city']);
    $displayAddress .= ", " . htmlspecialchars($defaultAddress['province']);
} else {
    $displayAddress = "No default address set.";
}

$phoneQuery = "SELECT phoneNumber FROM addresses WHERE customer_id = $customer_id AND is_default = 1 LIMIT 1";
$phoneResult = $con->query($phoneQuery);

if ($phoneResult->num_rows > 0) {
    $phoneRow = $phoneResult->fetch_assoc();
    $displayPhone = htmlspecialchars($phoneRow['phoneNumber']);
} else {
    $displayPhone = "No default phone number set.";
}

// Close the database connection
$con->close();
?>

<?php
require('../database/db_yeokart.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $sessionUsername = $_SESSION['username'];

    $checkExistingUser = "SELECT username FROM user_accounts WHERE username = ? UNION SELECT username FROM employee_accounts WHERE username = ?";
    
    if ($checkStmt = $con->prepare($checkExistingUser)) {
        $checkStmt->bind_param("ss", $param_username, $param_username);
        $param_username = $username;
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
            echo "<script>window.location.href = 'customer_profile.php';</script>";
            exit();
        }

        $checkStmt->close();
    }

    // Proceed with the update query
    $sql = "UPDATE user_accounts SET firstname = ?, lastname = ?, username = ? WHERE username = ?";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ssss", $param_firstname, $param_lastname, $param_username, $param_sessionUsername);

        $param_firstname = $firstname;
        $param_lastname = $lastname;
        $param_username = $username;
        $param_sessionUsername = $sessionUsername;

        if ($stmt->execute()) {
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['username'] = $username;

            header("Location: customer_profile.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$con->close();
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
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"><span id="cart-num"><?php echo $cartCount; ?></span></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn" class="active"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="user-profile">
        <div id="editProfilePopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closePopup()">&times;</span>
                <h3>Edit Profile</h3>
                <form id="editProfileForm" action="customer_profile.php" method="POST">
                    <div class="form-group">
                        <label for="editFirstname">First Name</label>
                        <input type="text" id="editFirstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editLastname">Last Name</label>
                        <input type="text" id="editLastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editUsername">Username</label>
                        <input type="text" id="editUsername" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <button type="submit" class="update-btn">Save Changes</button>
                </form>
            </div>
        </div>

        <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
                <p>Are you sure you want to logout?
                <p>
                <div class="logout-btns">
                    <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                    <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                </div>
            </div>
        </div>

        <div class="header-3">
            <h1>My Account</h1>
            <div class="nav-user">
                <a href="#" onclick="openPopup(); return false;" class="logout-link">EDIT PROFILE</a>
                <a href="#" onclick="openLogoutPopup(); return false;" class="logout-link">LOG OUT<i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="left-column">
                <p id="info">ORDER HISTORY</p>
                <hr class="gradient">

                <?php
                require('../database/db_yeokart.php');

                if (isset($_SESSION['id'])) {
                    $customer_id = $_SESSION['id'];
                } else {
                    header("Location: login_page.php");
                    exit();
                }

                // Query to fetch orders
                $sql = "SELECT `order_id`, `customer_id`, `firstname`, `lastname`, `address`, `items_ordered`, `total`, `shipping_fee`, `overall_total`, `date_of_purchase`, `item_quantity`, `status`, `proof_of_payment`  FROM `orders` WHERE `customer_id` = $customer_id";

                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    echo '<table class="order-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Order ID</th>';
                    echo '<th>Date</th>';
                    echo '<th>Status</th>';
                    echo '<th><center>Proof of Payment</center></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $order_id = $row['order_id'];
                        $status = strtoupper($row['status']);
                        echo '<tr class="order-row">';
                        echo '<td>' . $row['order_id'] . '</td>';
                        echo '<td>' . $row['date_of_purchase'] . '</td>';
                        echo '<td>' . strtoupper($row['status']) . '</td>';
                        if ($status == "PENDING") {
                            echo '<td><center><a href="#" class="upload-proof-cell" data-order-id="' . $order_id . '" data-proof="' . $row['proof_of_payment'] . '">Upload Proof of Payment</a></center></td>';
                            
                        } else {
                            echo '<td><center><button class="payment-done" disabled>Payment Done <i class="fas fa-check"></i></button></center></td>';
                        }
                        echo '</tr>';
                        echo '<tr class="hidden-row">';
                        echo '<td colspan="4">';
                        echo '<div class="order-details-card">';
                        echo '<div class="alert';
                        switch ($status) {
                            case "DELIVERED":
                                echo ' alert-success';
                                break;
                            case "PROCESSING":
                                echo ' alert-info';
                                break;
                            case "PENDING":
                                echo ' alert-danger';
                                break;
                            case "SHIPPED":
                                echo ' alert-warning';
                                break;
                            default:
                                echo ' alert-danger';
                                break;
                        }
                        echo '" role="alert">';
                        echo '<strong>Status:</strong> ' . $status . '<br>';
                        if ($status == "DELIVERED") {
                            echo 'Your order has been delivered. Thank you for shopping with us!';
                        } elseif ($status == "PROCESSING") {
                            echo 'Your order is currently being processed. Please wait for further updates.';
                        } elseif ($status == "PENDING") {
                            echo 'Your order is pending. We are waiting for payment confirmation.';
                        } elseif ($status == "SHIPPED") {
                            echo 'Your order has been shipped. You will receive it soon.';
                        } else {
                            echo 'Your order status is ' . $status . '. For more information, please contact customer support.';
                        }

                        echo '</div>'; // Close alert div

                        $items = explode(",", $row['items_ordered']);
                        $quantities = explode(",", $row['item_quantity']);

                        foreach ($items as $key => $item_name) {
                            $item_name = trim($item_name);
                            $item_query = "SELECT * FROM products WHERE item_name = '$item_name'";
                            $item_result = $con->query($item_query);

                            if ($item_result->num_rows > 0) {
                                $item_row = $item_result->fetch_assoc();

                                echo "<div class='cart-item' style='background-color: #fff;'>";
                                echo "<img src='item_images/{$item_row['item_image1']}' alt='Item Image' class='cart-item-image'>";
                                echo "<div class='item-details'>";
                                echo "<p><b>Name: </b>{$item_row['item_name']}</p>";
                                echo "<p>Price: ₱" . $item_row['item_price'] . "</p>";
                                echo "</div>";
                                echo "<p>Quantity: {$quantities[$key]}</p>";
                                echo "<p>Total: ₱" . ($item_row['item_price'] * $quantities[$key]) . "</p>";
                                echo "</div>";
                            } else {
                                echo "Item not found";
                            }
                        }

                        echo '<div class="total-for-order">';
                        echo '<p class="total-label">Subtotal: </p>';
                        echo '<p class="total-price">&nbsp;₱' . $row['total'] . '</p>';
                        echo '<p class="total-label">Shipping Fee: </p>';
                        echo '<p class="total-price">&nbsp;₱' . $row['shipping_fee'] . '</p>';
                        echo '<p class="total-label">Overall Total: </p>';
                        echo '<p class="total-price">&nbsp;₱' . $row['overall_total'] . '</p>';
                        echo '</div>'; // Close total-for-order div

                        echo '</div>'; // Close order-details-card div
                        echo '</div>'; // Close hidden-row div
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "0 results";
                }
                $con->close();
                ?>
            </div>

            <div class="right-column">
                <div class="account">
                    <p id="info">ACCOUNT DETAILS
                    <p>
                        <hr class="gradient">
                    <div class="account-info">
                        <p><?php echo $username; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-user-circle"></i>
                        <p><?php echo $firstname . " " . $lastname; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-envelope"></i>
                        <p><?php echo $email; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><?php echo $displayAddress; ?></p>
                    </div>
                    <div class="account-info">
                        <i class="fas fa-phone"></i>
                        <p><?php echo $displayPhone; ?></p>
                    </div>

                </div>
                <div class="address">
                    <a href="customer_address.php" class="address-link">VIEW ADDRESSES (<?php echo $addressCount ?>)<i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <div id="uploadProofPopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closeUploadProofPopup()">&times;</span>
                <h3>Upload Proof of Payment</h3>
                <?php
                require('../database/db_yeokart.php');

                // SQL query to retrieve contacts_description from contacts table where icon_link matches
                $sql = "SELECT contacts_description FROM contacts WHERE icon_link = '<i class=\'fa-solid fa-peso-sign\'></i>'";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='gcash_info'><strong>GCash:&nbsp;</strong>" . $row["contacts_description"] . "</div>";
                    }
                } else {
                    echo "<p>No additional payment instructions found.</p>";
                }

                $con->close();
                ?>
                <form class="add-artist-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="proof_of_payment" class="form-label">Select Image for Proof of Payment:</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/jpeg, image/png, image/gif" style="display:none;">
                        <div class="file_upload">
                            <button type="button" class="file-upload-btn" onclick="document.getElementById('proof_of_payment').click();">Choose File</button>
                            <span id="file-chosen">No file chosen</span>
                        </div>
                    </div>
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="proof_image" id="proof_image">
                        <center><img src="" alt="Proof of Payment" id="proof_of_payment_image"></center>
                        <button type="submit" name="upload_proof" class="upload-btn">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        require('../database/db_yeokart.php');

        if (isset($_POST['upload_proof'])) {
            $order_id = $_POST['order_id'];
            if (isset($_FILES['proof_of_payment']['name']) && !empty($_FILES['proof_of_payment']['name'])) {
                $upload_proof = $_FILES['proof_of_payment']['name'];
                $temp_proof = $_FILES['proof_of_payment']['tmp_name'];
        
                if (move_uploaded_file($temp_proof, "./item_images/$upload_proof")) {
                    $stmt = $con->prepare("UPDATE orders SET proof_of_payment = ? WHERE order_id = ?");
                    $stmt->bind_param("ss", $upload_proof, $order_id);
                    $stmt->execute();
                    $stmt->close();
        
                    // SweetAlert message for successful upload with redirection
                    echo "<script>
                        Swal.fire({
                            icon: 'success', 
                            title: 'Success!',
                            text: 'Proof of payment uploaded successfully.',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-custom-popup',
                                title: 'swal2-custom-title',
                                content: 'swal2-custom-text'
                            },
                            backdrop: true, 
                            allowOutsideClick: false 
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = 'customer_profile.php';
                            }
                        });
                    </script>";

                } else {
                    // SweetAlert message for failed file move with redirection
                    echo "<script>
                        Swal.fire({
                            icon: 'error', 
                            title: 'Failed!',
                            text: 'Failed to move uploaded file.',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-custom-popup',
                                title: 'swal2-custom-title',
                                content: 'swal2-custom-text'
                            },
                            backdrop: true, 
                            allowOutsideClick: false 
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = 'customer_profile.php';
                            }
                        });
                    </script>";
                }
            } else {
                // SweetAlert message for no file uploaded with redirection
                echo "<script>
                    Swal.fire({
                        icon: 'info', 
                        title: 'Notice!',
                        text: 'No file uploaded.',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-custom-popup',
                            title: 'swal2-custom-title',
                            content: 'swal2-custom-text'
                        },
                        backdrop: true, 
                        allowOutsideClick: false 
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = 'customer_profile.php';
                        }
                    });
                </script>";
            }
        }
        

        $con->close();
        ?>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderRows = document.querySelectorAll('.order-row');

            let currentlyOpenRow = null;

            orderRows.forEach(function(row) {
                row.addEventListener('click', function() {
                    const hiddenRow = row.nextElementSibling;
                    if (currentlyOpenRow && currentlyOpenRow !== hiddenRow) {
                        currentlyOpenRow.classList.remove('hidden-row-visible');
                    }
                    hiddenRow.classList.toggle('hidden-row-visible');
                    currentlyOpenRow = hiddenRow.classList.contains('hidden-row-visible') ? hiddenRow : null;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const uploadProofCells = document.querySelectorAll('.upload-proof-cell');

            uploadProofCells.forEach(function(cell) {
                cell.addEventListener('click', function(event) {
                    event.preventDefault();
                    const orderId = cell.getAttribute('data-order-id');
                    openUploadProofPopup(orderId);
                });
            });
        });

        function openUploadProofPopup(orderId) {
            document.getElementById('order_id').value = orderId;
            document.getElementById('uploadProofPopup').style.display = 'flex';
            // Check if the proof_of_payment field is not empty
            const proofOfPayment = document.querySelector('.upload-proof-cell[data-order-id="' + orderId + '"]').getAttribute('data-proof');
            if (proofOfPayment !== "") {
                // Display the image in the popup
                const proofOfPaymentImage = document.getElementById('proof_of_payment_image');
                proofOfPaymentImage.src = "./item_images/" + proofOfPayment;
                proofOfPaymentImage.style.display = 'block';
            }
        }

        function closeUploadProofPopup() {
            document.getElementById('uploadProofPopup').style.display = 'none';
            // Hide the image when closing the popup
            document.getElementById('proof_of_payment_image').style.display = 'none';
        }

        document.getElementById('proof_of_payment').addEventListener('change', function() {
            var fileName = document.getElementById('proof_of_payment').files[0].name;
            document.getElementById('file-chosen').textContent = fileName;
        });

    </script>
</body>

</html>