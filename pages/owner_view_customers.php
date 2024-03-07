<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Admin Dashboard</title>
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

<body>

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
                    <a href="owner_view_customers.php" class="active"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="./owner_item_homepage.php"><span class="las la-shopping-basket"></span>
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

                Customers
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3><?php echo $firstname . " " . $lastname; ?></h3>
                    <small>Owner</small>
                </div>
            </div>
        </header>

        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Customer List</h3>
                </div>
            </div>
            <?php
            require('../database/db_yeokart.php');

            $sql = "SELECT id, firstname, lastname, username, email FROM `user_accounts` WHERE is_verified = 1";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {

                echo "<form method='post' action='#'>
                            <table border='1'>
                                <tr>
                                    <th>Customer Number</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                </tr>";


                $counter = 1;

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                                <td>" . $counter . "</td>
                                <td>" . $row["firstname"] . "</td>
                                <td>" . $row["lastname"] . "</td>
                                <td>" . $row["username"] . "</td>
                                <td>" . $row["email"] . "</td>
                            </tr>";
                    $counter++;
                }

                echo "</table></form>";
            } else {
                echo "<form method='post' action='#'>
                            <table border='1'>
                                <tr>
                                    <th>Customer Number</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                </tr>
                                <tr>
                                    <td colspan='5'><center><b>No customer found</b></center></td>
                                </tr>
                            </table>
                        </form>";
            }

            $con->close();
            ?>
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