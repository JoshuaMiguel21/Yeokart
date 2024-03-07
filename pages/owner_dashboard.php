<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
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

    $sql = "SELECT COUNT(*) AS employee_count FROM employee_accounts";
    $result = $con->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $employeeCount = $row['employee_count'];
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    $sql = "SELECT COUNT(*) AS customer_count FROM user_accounts";
    $result = $con->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $userCount = $row['customer_count'];
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }


    $sql = "SELECT COUNT(*) AS item_count FROM products";
    $result = $con->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $itemCount = $row['item_count'];
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
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
                    <a href="owner_dashboard.php" class="active"><span class="las la-igloo"></span>
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
                    <a href=""><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-chart-line"></span>
                        <span>Report</span></a>
                </li>
                <li>
                    <a href="manage_employees.php"><span class="las la-user-circle"></span>
                        <span>Manage Employee</span></a>
                </li>
                <li>
                    <a href="owner_contact_details.php"><span class="las la-tasks"></span>
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

                Dashboard
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

            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1><?php echo $userCount; ?></h1>
                        <span>Customers</span>
                    </div>
                    <div>
                        <span class="las la-users"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo $employeeCount; ?></h1>
                        <span>Employees</span>
                    </div>
                    <div>
                        <span class="las la-user-tie"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo $itemCount; ?></h1>
                        <span>Items</span>
                    </div>
                    <div>
                        <span class="las la-shopping-bag"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>54</h1>
                        <span>Income</span>
                    </div>
                    <div>
                        <span class="lab la-google-wallet"></span>
                    </div>
                </div>
            </div>

        </main>
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