<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Manage Orders - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        overflow-x: auto;
        white-space: nowrap;
    }

    th,
    td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        /* Prevent wrapping */
    }

    td.expandable {
        cursor: pointer;
        max-width: 200px;
        /* Set the maximum width to prevent the cell from expanding too much */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Display ellipsis (...) for overflow text */
    }

    td.expandable.expanded {
        white-space: normal;
        max-width: none;
        overflow: auto;
    }
</style>
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
    document.addEventListener('DOMContentLoaded', function() {
        var expandableCells = document.querySelectorAll('.expandable');
        expandableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                this.classList.toggle('expanded');
            });
        });
    });
</script>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['nav_toggle'])) {
        $_SESSION['nav_toggle'] = false;
    }

    // Check if the nav-toggle checkbox has been toggled
    if (isset($_POST['nav_toggle'])) {
        // Update the session variable accordingly
        $_SESSION['nav_toggle'] = $_POST['nav_toggle'] === 'true' ? true : false;
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

    if (isset($_SESSION['email'])) {
        $email = strtolower($_SESSION['email']);
    } else {
        header("Location: login_page.php");
        exit();
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
                    <a href="owner_dashboard.php"><span class="las la-igloo"></span>
                        <span>Owner Dashboard</span></a>
                </li>
                <li>
                    <a href="owner_view_customers.php"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="owner_item_homepage.php"><span class="las la-shopping-basket"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="owner_orders.php" class="active"><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href="monthly_report.php"><span class="las la-chart-line"></span>
                        <span>Report</span></a>
                </li>
                <li>
                    <a href="manage_employees.php" class=""><span class="las la-user-circle"></span>
                        <span>Manage Employee</span></a>
                </li>
                <li>
                    <a href="owner_featured.php"><span class="las la-tasks"></span>
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
                    <div>
                        <h3><?php echo $firstname . " " . $lastname; ?></h3>
                        <small>Owner</small>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Order List</h3>
                </div>
            </div>

            <div class="scrollable-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Items Ordered</th>
                            <th>Item Quantity</th>
                            <th>Total</th>
                            <th>Date of Purchase</th>
                            <th>Status</th>
                            <th>Tracking Number</th>
                            <th>Proof of Payment</th>
                            <th>Activity Log</th>
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

                        if ($totalOrders == 0) {
                            echo "<tr><td colspan='12'><center><b>No orders at the moment</b></center></td></tr>";
                        } else {
                            $select_query = "SELECT orders.*, user_accounts.firstname, user_accounts.lastname FROM orders 
                            INNER JOIN user_accounts ON orders.customer_id = user_accounts.id 
                            ORDER BY CASE `status` 
                                WHEN 'INVALID' THEN 1
                                WHEN 'PENDING' THEN 2
                                WHEN 'PROCESSING' THEN 3
                                WHEN 'SHIPPED' THEN 4
                                WHEN 'DELIVERED' THEN 5
                                ELSE 6
                            END ASC, date_of_purchase ASC 
                            LIMIT $ordersPerPage OFFSET $offset";
                            $result_query = mysqli_query($con, $select_query);

                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $proof_of_payment = $row['proof_of_payment'];
                                echo '<tr id="order-row-' . $row['order_id'] . '">';
                                echo "<td>" . $row['order_id'] . "</td>";
                                echo "<td class='expandable'>" . $row['customer_id'] . "</td>";
                                echo "<td class='expandable'>" . $row['firstname'] . "</td>";
                                echo "<td class='expandable'>" . $row['lastname'] . "</td>";
                                echo "<td class='expandable'>" . $row['email'] . "</td>";
                                echo "<td class='expandable'>" . $row['address'] . "</td>";
                                echo "<td class='expandable'>" . $row['items_ordered'] . "</td>";
                                echo "<td class='expandable'>" . $row['item_quantity'] . "</td>";
                                echo "<td class='expandable'>₱" . number_format($row['total'], 2) . "</td>";
                                echo "<td>" . $row['date_of_purchase'] . "</td>";
                                echo "<td>";
                                echo '<div class="button-class">';

                                $selectDisabled = ($row['status'] == 'Delivered') ? 'disabled' : '';
                                echo '<select class="orderStatusSelect" onchange="updateOrderStatus(this.value, \'' . $row['order_id'] . '\')" ' . $selectDisabled . ' style="';
                                if ($row['status'] == 'Pending') {
                                    echo 'border: 1px solid red;';
                                } elseif ($row['status'] == 'Processing') {
                                    echo 'border: 1px solid blue;';
                                } elseif ($row['status'] == 'Shipped') {
                                    echo 'border: 1px solid #FFD700;'; // Gold
                                } elseif ($row['status'] == 'Delivered') {
                                    echo 'border: 1px solid green;';
                                } elseif ($row['status'] == 'Invalid') {
                                    echo 'border: 2px solid red;';
                                }
                                echo '">';

                                $status_query = "SHOW COLUMNS FROM `orders` LIKE 'status'";
                                $status_result = mysqli_query($con, $status_query);
                                $status_row = mysqli_fetch_assoc($status_result);
                                preg_match("/^enum\(\'(.*)\'\)$/", $status_row['Type'], $matches);
                                $status_enum_values = explode("','", $matches[1]);

                                foreach ($status_enum_values as $value) {
                                    $disabled = (in_array($value, ['Processing', 'Shipped', 'Delivered']) && empty($proof_of_payment)) ? 'disabled' : '';
                                    echo '<option value="' . $value . '" ' . ($row['status'] == $value ? 'selected' : '') . ' ' . $disabled . '>' . $value . '</option>';
                                }
                                echo '</select>';
                                echo '</div>';
                                echo "</td>";
                                echo "<td class='expandable'>";
                                echo "<div class='button-class'>";
                                echo '<center>';
                                if (!empty($row['tracking_number'])) {
                                    echo '<button onclick="openAddTrackingPopup(\'' . $row['order_id'] . '\', \'' . $row['tracking_number'] . '\')" class="edit-button featured"><i class="las la-edit"></i></button>';
                                } else {
                                    echo '<button onclick="openAddTrackingPopup(\'' . $row['order_id'] . '\')" class="edit-button"><i class="las la-map-marker"></i></button>';
                                }
                                echo '</center>';
                                echo "</td>";
                                if (!empty($proof_of_payment)) {
                                    echo '<td><center><img src="./item_images/' . $proof_of_payment . '" alt="Proof of Payment" width="auto" height="50" onclick="openImagePopup(\'./item_images/' . $proof_of_payment . '\')"></center></td>';
                                } else {
                                    echo '<td>Not yet paid</td>';
                                }
                                echo '<td><center><button onclick="viewActivityLogs(\'' . $row['order_id'] . '\')" class="edit-button"><i class="las la-eye"></i></button></center></td>';
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

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


            <div id="imagePopup" class="popup-image" style="display: none; padding-top: 100px;">
                <div class="image-content">
                    <img id="popupImage" src="" alt="Proof of Payment" style="width: auto; height: 550px;">
                </div>
            </div>
        </main>

        <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
                <p>Are you sure you want to logout?</p>
                <div class="logout-btns">
                    <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                    <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmDeliveryPopup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <span class="close-btn" onclick="closeConfirmDeliveryPopup()">&times;</span>
            <p>Are you sure that the order has been completed and delivered?</p>
            <div class="confirm-buttons">
                <button onclick="proceedWithDelivery()" class="confirm-logout-btn">Proceed</button>
                <button onclick="closeConfirmDeliveryPopup()" class="cancel-logout-btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="addTrackingPopup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <span class="close-btn" onclick="closeAddTrackingPopup()">&times;</span>
            <h2>Update Tracking Number</h2>
            <form class="add-tracking-form" method="post" enctype="multipart/form-data">
                <input type="hidden" id="orderId" name="orderId">
                <label for="tracking_number">Tracking Number:</label>
                <input type="text" id="tracking_number" name="tracking_number" class="form-control" placeholder="Enter Tracking Number" value="<?php echo $tracking_number; ?>" required>
                <button type="submit" name="insert_tracking">Update Tracking Number</button>
            </form>
        </div>
    </div>

    <div id="activityLogsPopup" class="popup-container" style="display: none;">
        <div class="popup-activity-log">
            <span class="close-btn" onclick="closeActivityLogsPopup()">&times;</span>
            <h2>Activity Logs</h2>
            <center><strong>(Order ID - <span id="orderIdPlaceholder"></span>)</strong></center>
            <div id="activityLogsContent" style="margin-top: 10px;"></div>
        </div>
    </div>


    <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="padding: 20px; background: white; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
            <div class="loader"></div>
            <span style="margin-left: 10px;">Updating...</span>
        </div>
    </div>

    <?php
    include('../database/db_yeokart.php');
    $tracking_number = '';

    if (isset($_POST['insert_tracking'])) {
        $orderId = $_POST['orderId'];
        $tracking_number = $_POST['tracking_number'];

        // Update the tracking number in the database
        $updateQuery = "UPDATE orders SET tracking_number='$tracking_number' WHERE order_id='$orderId'";
        $result = mysqli_query($con, $updateQuery);

        if ($result) {
            // Show success message using SweetAlert
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Tracking Number successfully updated',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'owner_orders.php';
                        }
                    });
                </script>";
            // Redirect or refresh the page to see the updated tracking number
        } else {
            // Show error message using SweetAlert
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error updating Tracking Number',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            echo mysqli_error($con); // Check for MySQL errors
        }
    }
    ?>

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

        function openConfirmDeliveryPopup(orderId, status) {
            var popup = document.getElementById('confirmDeliveryPopup');
            popup.style.display = 'flex';
            window.currentOrderForDelivery = orderId;
            var orderStatusSelect = document.querySelector('.orderStatusSelect');
            var selectedOption = orderStatusSelect.querySelector('option[value="Delivered"]');
            selectedOption.selected = true;
        }

        function closeConfirmDeliveryPopup() {
            var popup = document.getElementById('confirmDeliveryPopup');
            popup.style.display = 'none';
            var orderStatusSelect = document.querySelector('.orderStatusSelect');
            var selectedOption = orderStatusSelect.querySelector('option[value="Delivered"]');
            selectedOption.selected = false;
            enableAllOptions(orderStatusSelect);
            localStorage.removeItem('selectedStatus_' + window.currentOrderForDelivery);
            window.location.reload();
        }

        function proceedWithDelivery() {
            var orderId = window.currentOrderForDelivery;
            var orderStatusSelect = document.querySelector('.orderStatusSelect');
            var selectedOption = orderStatusSelect.querySelector('option[value="Delivered"]');
            selectedOption.disabled = true;
            localStorage.setItem('selectedStatus_' + orderId, "Delivered");

            // Show the loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';

            sendStatusUpdateRequest(orderId, "Delivered");
            // The closeConfirmDeliveryPopup call is now moved to the sendStatusUpdateRequest callback
        }

        function updateOrderStatus(status, orderId) {
            if (status === 'Delivered') {
                openConfirmDeliveryPopup(orderId, status);
            } else {
                sendStatusUpdateRequest(orderId, status);
            }
        }

        function sendStatusUpdateRequest(orderId, status) {
            document.getElementById('loadingOverlay').style.display = 'flex';
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "update_order_status.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Order status updated successfully");
                    setTimeout(function() {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        window.location.reload();
                    }, 1500);
                }
            };
            xhttp.send("order_id=" + orderId + "&status=" + status);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var orderRows = document.querySelectorAll('tr[id^="order-row-"]');
            orderRows.forEach(function(orderRow) {
                var orderStatusSelect = orderRow.querySelector('.orderStatusSelect');
                var orderId = orderRow.getAttribute('id').split('-')[2];
                var selectedStatus = localStorage.getItem('selectedStatus_' + orderId) || 'Pending';
                orderStatusSelect.addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var selectedValue = selectedOption.value;
                    localStorage.setItem('selectedStatus_' + orderId, selectedValue);
                });
            });
        });

        function enableAllOptions(orderStatusSelect) {
            orderStatusSelect.querySelectorAll('option').forEach(function(option) {
                option.disabled = false;
            });
        }


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

        function viewActivityLogs(orderId) {
            var orderIdPlaceholder = document.getElementById('orderIdPlaceholder');
            orderIdPlaceholder.textContent = orderId;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var activityLogs = JSON.parse(this.responseText);
                    var activityLogsContent = document.getElementById('activityLogsContent');
                    activityLogsContent.innerHTML = '';

                    if (activityLogs.length > 0) {
                        var ul = document.createElement('ul');
                        activityLogs.forEach(function(log) {
                            var li = document.createElement('li');
                            li.textContent = log.text + " - " + log.time;
                            ul.appendChild(li);
                        });
                        activityLogsContent.appendChild(ul);
                    } else {
                        activityLogsContent.textContent = 'No activity logs available.';
                    }

                    document.getElementById('activityLogsPopup').style.display = 'flex';
                }
            };
            xhttp.open("GET", "fetch_activity_logs.php?order_id=" + orderId, true);
            xhttp.send();
        }


        function closeActivityLogsPopup() {
            document.getElementById('activityLogsPopup').style.display = 'none';
        }

        function openAddTrackingPopup(orderId, trackingNumber) {
            var orderRow = document.getElementById('order-row-' + orderId);
            var orderStatus = orderRow.querySelector('.orderStatusSelect').value;

            // Enable the input field and update button if the order status is 'Shipped'
            var trackingNumberInput = document.getElementById('tracking_number');
            var updateButton = document.querySelector('.add-tracking-form button[type="submit"]');
            if (orderStatus === 'Shipped') {
                trackingNumberInput.disabled = false;
                updateButton.disabled = false;
            } else {
                trackingNumberInput.disabled = true;
                updateButton.disabled = true;
            }

            document.getElementById('orderId').value = orderId;
            if (trackingNumberInput) {
                trackingNumberInput.value = trackingNumber || ''; // Use an empty string if trackingNumber is undefined
            }
            document.getElementById('addTrackingPopup').style.display = 'flex';
        }

        function closeAddTrackingPopup() {
            document.getElementById('addTrackingPopup').style.display = 'none';
        }
    </script>
</body>

</html>