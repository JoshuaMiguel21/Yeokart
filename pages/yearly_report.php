<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="../css/monthlyreport.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../res/icon.png">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <title>Yearly Report - Yeokart</title>
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
        /* Set a fixed width for the columns */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Use ellipsis to indicate truncated text */
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
    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
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

    ?>
    <?php
    require('../database/db_yeokart.php');

    $orderCount = 0; // Initialize the variable
    $totalItemsSold = 0;
    $totalRevenue = 0;
    $totalIncome = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
        $selectedYear = $_POST['year'];
        $startDate = date("$selectedYear-01-01");
        $endDate = date("$selectedYear-12-31");

        $sql = "SELECT COUNT(*) AS order_count FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['order_count'];
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT item_quantity FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {

            while ($row = $result->fetch_assoc()) {
                $quantities = explode(',', $row['item_quantity']);
                foreach ($quantities as $quantity) {
                    $totalItemsSold += (int)$quantity;
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(total) AS total_revenue FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalRevenue = number_format($row['total_revenue'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(overall_total) AS total_income FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalIncome = number_format($row['total_income'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    } else {
        // Default to current year
        $selectedYear = date("Y");

        $startDate = date("$selectedYear-01-01");
        $endDate = date("$selectedYear-12-31");

        // Retrieve data for current year
        $sql = "SELECT COUNT(*) AS order_count FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['order_count'];
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT item_quantity FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {

            while ($row = $result->fetch_assoc()) {
                $quantities = explode(',', $row['item_quantity']);
                foreach ($quantities as $quantity) {
                    $totalItemsSold += (int)$quantity;
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(total) AS total_revenue FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalRevenue = number_format($row['total_revenue'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(overall_total) AS total_income FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalIncome = number_format($row['total_income'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
    $dataPoints = array();

    // Create an array of month names
    $months = array(
        1 => "January", 2 => "February", 3 => "March", 4 => "April",
        5 => "May", 6 => "June", 7 => "July", 8 => "August",
        9 => "September", 10 => "October", 11 => "November", 12 => "December"
    );

    // Query to get the total for each month
    $sql = "SELECT MONTH(date_of_purchase) AS month, SUM(total) AS total_monthly FROM orders WHERE YEAR(date_of_purchase) = $selectedYear AND (status = 'delivered') AND proof_of_payment <> '' GROUP BY MONTH(date_of_purchase)";
    $result = $con->query($sql);

    // Initialize an array to store the total for each month
    $totalByMonth = array_fill(1, 12, 0);

    if ($result->num_rows > 0) {
        // Loop through each row of the result set
        while ($row = $result->fetch_assoc()) {
            // Update the total for the corresponding month
            $totalByMonth[$row["month"]] = $row["total_monthly"];
        }
    }

    // Populate the $dataPoints array with data for each month
    $currentMonth = date("n");
    foreach ($months as $monthNumber => $monthName) {
        if ($monthNumber <= $currentMonth) {
            $dataPoints[] = array("y" => $totalByMonth[$monthNumber], "label" => $monthName);
        } else {
            $dataPoints[] = array("y" => null, "label" => $monthName);
        }
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
                    <a href="owner_orders.php"><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href="monthly_report.php" class="active"><span class="las la-chart-line"></span>
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

                Reports Page
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
                    <h3>Yearly Report</h3>
                </div>
            </div>

            <div class="head-buttons">
                <a href="monthly_report.php" class="btn-employee">
                    <i class="las la-chart-line"></i>
                    <span class="text">View Monthly Report</span>
                </a>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="year">Select Year:</label>
                    <select id="year" name="year">
                        <?php
                        $currentYear = date("Y");
                        for ($i = $currentYear; $i >= 2000; $i--) {
                            $selected = ($i == $selectedYear) ? 'selected' : '';
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" class="filter-btn" value="Filter" name="filter">
                </form>
                <form id="reportForm" method="post" action="generate_yearly_report.php" target="_blank">
                    <input type="hidden" name="pdf_creater" value="PDF">
                    <input type="hidden" name="selected_year" value="<?php echo $selectedYear; ?>">
                    <input type="hidden" name="startDate" value="<?php echo $startDate; ?>">
                    <input type="hidden" name="endDate" value="<?php echo $endDate; ?>">
                    <input type="hidden" name="order_count" value="<?php echo $orderCount; ?>">
                    <input type="hidden" name="item_quantity" value="<?php echo  $totalItemsSold; ?>">
                    <input type="hidden" name="total_revenue" value="<?php echo  $totalRevenue; ?>">
                    <input type="hidden" name="total_income" value="<?php echo  $totalIncome; ?>"> <!-- Add this line -->
                </form>
                <a href="#" class="btn-main" style="<?php echo $orderCount == 0 ? 'background-color: gray; cursor: not-allowed;' : ''; ?>" onclick="submitReportForm(); return false;" <?php echo $orderCount == 0 ? 'disabled' : ''; ?> onclick="document.getElementById('reportForm').submit(); return false;">
                    <i class="las la-file-invoice-dollar"></i>
                    <span class="text">Generate Report</span>
                </a>

            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;
            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1><?php echo $orderCount; ?></h1>
                        <span>Orders Recieved</span>
                    </div>
                    <div>
                        <span class="las la-shopping-bag"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo $totalItemsSold; ?></h1>
                        <span>Items Sold</span>
                    </div>
                    <div>
                        <span class="las la-shopping-basket"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo '₱ ' . $totalRevenue; ?></h1>
                        <span>Gross Sales</span>
                    </div>
                    <div>
                        <span class="las la-hand-holding-usd"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo '₱ ' . $totalIncome; ?></h1>
                        <span>Gross Sales + Shipping Fee</span>
                    </div>
                    <div>
                        <span class="las la-donate"></span>
                    </div>
                </div>
            </div>
            <br></br>
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            <br></br>
            <div class="head-title">
                <div class="left">
                    <h3>Most Sold Artists</h3>
                </div>
            </div>
            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <center>Artist Name</center>
                            </th>
                            <th>
                                <center>Sold this Month</center>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');

                        // Query to get the top 10 best-selling artists in the selected month and year
                        $select_query = "
                            SELECT
                                TRIM(artist) AS artist_name,
                                SUM(item_quantity) AS total_sold
                            FROM (
                                SELECT
                                    TRIM(LEADING ', ' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(items_artist, ', ', n.digit+1), ', ', -1)) AS artist,
                                    TRIM(LEADING ', ' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(item_quantity, ', ', n.digit+1), ', ', -1)) AS item_quantity
                                FROM orders
                                JOIN (
                                    SELECT 0 AS digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
                                    SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                ) AS n
                                WHERE n.digit < LENGTH(items_artist) - LENGTH(REPLACE(items_artist, ',', '')) + 1
                                    AND date_of_purchase BETWEEN '$startDate' AND '$endDate'
                                    AND (status = 'delivered')
                                    AND proof_of_payment <> ''
                            ) AS o
                            GROUP BY artist
                            ORDER BY total_sold DESC
                            LIMIT 10";

                        $result_query = mysqli_query($con, $select_query);

                        if (mysqli_num_rows($result_query) == 0) {
                            echo "<tr><td colspan='11'><center><b>No data available</b></center></td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $artist_name = $row['artist_name'];
                                $total_sold = $row['total_sold'];
                                echo "<tr>";
                                echo "<td><center>" . $artist_name . "</center></td>";
                                echo "<td><center>" . $total_sold . "</center></td>";
                                echo "</tr>";
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
            <div class="head-title">
                <div class="left">
                    <h3>Most Sold Items</h3>
                </div>
            </div>
            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <center>Item Name</center>
                            </th>
                            <th>
                                <center>Category</center>
                            </th>
                            <th>
                                <center>Artist</center>
                            </th>
                            <th>
                                <center>Sold this Month</center>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');

                        // Query to get the top 10 best-selling items with category and artist name
                        $select_query = "
                            SELECT
                                o.item_name,
                                o.items_artist,
                                o.items_category,
                                SUM(o.item_quantity) AS total_sold
                            FROM (
                                SELECT
                                    TRIM(LEADING ',' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(items_ordered, ', ', n.digit+1), ', ', -1)) AS item_name,
                                    TRIM(LEADING ',' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(item_quantity, ', ', n.digit+1), ', ', -1)) AS item_quantity,
                                    TRIM(LEADING ',' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(items_artist, ', ', n.digit+1), ', ', -1)) AS items_artist,
                                    TRIM(LEADING ',' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(items_category, ', ', n.digit+1), ', ', -1)) AS items_category
                                FROM orders
                                JOIN (
                                    SELECT 0 AS digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
                                    SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                ) AS n
                                WHERE n.digit < LENGTH(items_ordered) - LENGTH(REPLACE(items_ordered, ',', '')) + 1
                                    AND date_of_purchase BETWEEN '$startDate' AND '$endDate'
                                    AND (status = 'delivered')
                                    AND proof_of_payment <> ''
                            ) AS o
                            GROUP BY o.item_name
                            ORDER BY total_sold DESC
                            LIMIT 10";

                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) == 0) {
                            echo "<tr><td colspan='11'><center><b>No orders at the moment</b></center></td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $item_name = $row['item_name'];
                                $category_name = $row['items_category'];
                                $artist_name = $row['items_artist'];
                                $total_sold = $row['total_sold'];
                                echo "<tr>";
                                echo "<td class='expandable'>" . $item_name . "</td>";
                                echo "<td class='expandable' style='text-align: center;'>" . $category_name . "</td>";
                                echo "<td class='expandable' style='text-align: center;'>" . $artist_name . "</td>";
                                echo "<td class='expandable' style='text-align: center;'>" . $total_sold . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>

                    </tbody>
                </table>
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
            <script>
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

                function submitReportForm() {
                    var button = document.querySelector('.btn-main');
                    if (!button.hasAttribute('disabled')) {
                        document.getElementById('reportForm').submit();
                    }
                }
                window.onload = function() {
                    var chart = new CanvasJS.Chart("chartContainer", {
                        title: {
                            text: "Total Sales Over Months"
                        },
                        axisY: {
                            title: "Total Sales"
                        },
                        data: [{
                            type: "line",
                            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                        }]
                    });
                    chart.render();
                }
            </script>
</body>

</html>