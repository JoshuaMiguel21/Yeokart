<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="../css/monthlyreport.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../res/icon.png">
    <title>Yeokart</title>
</head>
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

        $sql = "SELECT COUNT(*) AS order_count FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['order_count'];
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT item_quantity FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
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

        $sql = "SELECT SUM(total) AS total_revenue FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalRevenue = number_format($row['total_revenue'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(overall_total) AS total_income FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
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
        $sql = "SELECT COUNT(*) AS order_count FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['order_count'];
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT item_quantity FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
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

        $sql = "SELECT SUM(total) AS total_revenue FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalRevenue = number_format($row['total_revenue'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        $sql = "SELECT SUM(overall_total) AS total_income FROM orders WHERE date_of_purchase BETWEEN '$startDate' AND '$endDate' AND (status = 'shipped' OR status = 'delivered') AND proof_of_payment <> ''";
        $result = $con->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalIncome = number_format($row['total_income'], 2);
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
    ?>
    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
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
                    <i class="las la-archive"></i>
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
                    <input type="submit" value="Filter" name="filter">
                </form>
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
                        <span>Sales Revenue</span>
                    </div>
                    <div>
                        <span class="las la-hand-holding-usd"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo '₱ ' . $totalIncome; ?></h1>
                        <span>Total Income</span>
                    </div>
                    <div>
                        <span class="las la-donate"></span>
                    </div>
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

</body>

</html>