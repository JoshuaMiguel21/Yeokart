<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Manage Orders - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this item?");
    }

    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').submit();
    }
</script>

<body>
    <?php
    session_start();

    if (isset($_SESSION['firstname'])) {
        $firstname = $_SESSION['firstname'];
    } else {
        header("Location: login_page.php");
        exit();
    }
    if (!isset($_SESSION['nav_toggle'])) {
        $_SESSION['nav_toggle'] = false;
    }
    if (isset($_POST['nav_toggle'])) {
        // Update the session variable accordingly
        $_SESSION['nav_toggle'] = $_POST['nav_toggle'] === 'true' ? true : false;
    }
    ?>
    <input type="checkbox" id="nav-toggle" <?php echo $_SESSION['nav_toggle'] ? 'checked' : ''; ?>>
    <div class="sidebar <?php echo $_SESSION['nav_toggle'] ? 'open' : ''; ?>">
        <div class="sidebar-brand">
            <h2><span>Yeokart</span></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="emp_dashboard.php"><span class="las la-igloo"></span>
                        <span>Employee Dashboard</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="emp_item_homepage.php"><span class="las la-shopping-basket"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="emp_orders.php" class="active"><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href="emp_featured.php"><span class="las la-tasks"></span>
                        <span>Manage Content</span></a>
                </li>
                <li>
                    <a href="#" onclick="openLogoutPopup(); return false;"><span class="las la-sign-out-alt"></span>
                        <span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-content">
        <header>
            <h3>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                </label>

                Manage Orders
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3>Hi, <?php echo $firstname; ?></h3>
                    <small>Employee</small>
                </div>
            </div>
        </header>

        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Order List</h3>
                </div>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Items Ordered</th>
                            <th>Item Quantity</th>
                            <th>Total</th>
                            <th>Date of Purchase</th>
                            <th>Status</th>
                            <th>Proof of Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');

                        $ordersPerPage = 10;
                        $pageNumber = 1;

                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $pageNumber = $_GET['page'];
                        }

                        $offset = ($pageNumber - 1) * $ordersPerPage;

                        $totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
                        $totalOrdersResult = mysqli_query($con, $totalOrdersQuery);
                        $totalOrdersRow = mysqli_fetch_assoc($totalOrdersResult);
                        $totalOrders = $totalOrdersRow['total_orders'];

                        $select_query = "SELECT * FROM orders LIMIT $ordersPerPage OFFSET $offset";
                        $result_query = mysqli_query($con, $select_query);

                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $proof_of_payment = $row['proof_of_payment'];
                            echo '<tr id="order-row-' . $row['order_id'] . '">';
                            echo "<td>" . $row['order_id'] . "</td>";
                            echo "<td>" . $row['customer_id'] . "</td>";
                            echo "<td>" . $row['firstname'] . "</td>";
                            echo "<td>" . $row['lastname'] . "</td>";
                            echo "<td>" . $row['address'] . "</td>";
                            echo "<td>" . $row['items_ordered'] . "</td>";
                            echo "<td>" . $row['item_quantity'] . "</td>";
                            echo "<td>₱" . $row['total'] . "</td>";
                            echo "<td>" . $row['date_of_purchase'] . "</td>";
                            echo "<td>";
                            echo '<div class="button-class">';
                            echo '<select class="orderStatusSelect" onchange="updateOrderStatus(this.value, \'' . $row['order_id'] . '\')">';
                            include('../database/db_yeokart.php');

                            // Fetch ENUM values for order status from the database
                            $status_query = "SHOW COLUMNS FROM `orders` LIKE 'status'";
                            $status_result = mysqli_query($con, $status_query);
                            $status_row = mysqli_fetch_assoc($status_result);
                            preg_match("/^enum\(\'(.*)\'\)$/", $status_row['Type'], $matches);
                            $status_enum_values = explode("','", $matches[1]);
                            foreach ($status_enum_values as $value) {
                                echo '<option value="' . $value . '" ' . ($row['status'] == $value ? 'selected' : '') . '>' . $value . '</option>';
                            }
                            echo '</select>';
                            echo '</div>';
                            if (!empty($proof_of_payment)) {
                                echo '<td><center><img src="./item_images/' . $proof_of_payment . '" alt="Proof of Payment" width="auto" height="50" onclick="openImagePopup(\'./item_images/' . $proof_of_payment . '\')"></center></td>';
                            } else {
                                echo '<td>Not yet paid</td>';
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                $baseUrl = 'owner_orders.php?';

                $pageQuery = '';
                if (isset($_GET['search_button'])) {
                    $pageQuery = 'search_button&search=' . urlencode($_GET['search']);
                } elseif (isset($_GET['filter_button'])) {
                    if (isset($_GET['category'])) {
                        $pageQuery = 'filter_button&category=' . urlencode($_GET['category']);
                    } elseif (isset($_GET['artist'])) {
                        $pageQuery = 'filter_button&artist=' . urlencode($_GET['artist']);
                    }
                }

                $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalOrders / $ordersPerPage);

                $startPage = max(1, $pageNumber - 1);
                $endPage = min($totalPages, $pageNumber + 1);

                if ($pageNumber == 1) {
                    $startPage = 1;
                    $endPage = min(3, $totalPages);
                } elseif ($pageNumber == $totalPages) {
                    $startPage = max(1, $totalPages - 2);
                    $endPage = $totalPages;
                }

                echo "<div class='pagination'>";

                $prevPage = max(1, $pageNumber - 1);
                echo "<a href='{$baseUrl}page=$prevPage&$pageQuery' class='pagination-link' " . ($pageNumber <= 1 ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">&laquo; Previous</a>";

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $linkClass = $i == $pageNumber ? 'pagination-link current-page' : 'pagination-link';
                    echo "<a href='{$baseUrl}page=$i&$pageQuery' class='$linkClass'>$i</a>";
                }

                $nextPage = min($totalPages, $pageNumber + 1);
                echo "<a href='{$baseUrl}page=$nextPage&$pageQuery' class='pagination-link' " . ($pageNumber >= $totalPages ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">Next &raquo;</a>";

                echo "</div>";
                ?>
            </div>
            <div id="imagePopup" class="popup-image" style="display: none; padding-top: 100px;">
                <div class="image-content">
                    <img id="popupImage" src="" alt="Proof of Payment" style="width: auto; height: 550px;">
                </div>
            </div>
        </main>

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
    </div>
    <script>
        // Function to toggle the sidebar and update session variable
        function toggleSidebar() {
            var isChecked = document.getElementById('nav-toggle').checked;
            var newState = isChecked ? 'true' : 'false';

            // Update session variable using AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("nav_toggle=" + newState);
        }

        function updateOrderStatus(status, orderId) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "update_order_status.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Order status updated successfully");
                }
            };
            xhttp.send("order_id=" + orderId + "&status=" + status);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var orderRows = document.querySelectorAll('tr[id^="order-row-"]');

            orderRows.forEach(function(orderRow) {
                var orderStatusSelect = orderRow.querySelector('.orderStatusSelect');
                var orderId = orderRow.getAttribute('id').split('-')[2]; // Extract order ID from row ID
                var selectedStatus = localStorage.getItem('selectedStatus_' + orderId) || 'Pending';

                // Set initial border color based on the stored status
                orderStatusSelect.style.border = getBorderStyle(selectedStatus);

                // Add event listener to update border color on status change
                orderStatusSelect.addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var selectedValue = selectedOption.value;
                    this.style.border = getBorderStyle(selectedValue);

                    // Store selected status in local storage
                    localStorage.setItem('selectedStatus_' + orderId, selectedValue);
                });
            });

            // Function to get border style based on status
            function getBorderStyle(status) {
                switch (status) {
                    case 'Pending':
                        return '1px solid red';
                    case 'Processing':
                        return '1px solid blue';
                    case 'Shipped':
                        return '1px solid #FFD700';
                    case 'Delivered':
                        return '1px solid green';
                    default:
                        return '';
                }
            }
        });

        function openImagePopup(imageUrl) {
            var popup = document.getElementById('imagePopup');
            var image = document.getElementById('popupImage');
            image.src = imageUrl;
            popup.style.display = 'flex';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var popup = document.getElementById('imagePopup');

            popup.addEventListener('click', function(event) {
                if (event.target === popup) {
                    popup.style.display = 'none';
                }
            });
        });
        // Function to toggle the sidebar and update session variable
        function toggleSidebar() {
            var isChecked = document.getElementById('nav-toggle').checked;
            var newState = isChecked ? 'true' : 'false';

            // Update session variable using AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("nav_toggle=" + newState);
        }

        // Add event listener to checkbox change
        document.getElementById('nav-toggle').addEventListener('change', toggleSidebar);
    </script>
</body>

</html>