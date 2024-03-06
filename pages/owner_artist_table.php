<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Yeokart Item Catalog Page</title>
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
</script>
<?php
        session_start();

        if(isset($_SESSION['firstname'])) {
            $firstname = $_SESSION['firstname'];
        } else {
            header("Location: login_page.php");
            exit();
        }

        if(isset($_SESSION['lastname'])) {
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
                        <span>Admin Dashboard</span></a>
                </li>
                <li>
                    <a href="owner_view_customers.php"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="owner_item_homepage.php" class="active"><span class="las la-shopping-basket"></span>
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
                    <a href="manage_employees.php" class=""><span class="las la-user-circle"></span>
                        <span>Manage Employee</span></a>
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

                Manage Items
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3><?php echo $firstname ." ". $lastname;?></h3>
                    <small>Owner</small>
                </div>
            </div>
        </header>

        <!-- <div class="container mt-3">
        <h1 class="text-center mb-4">Item Catalog</h1>
    </div>
     <div class="form-outline mb-4 mt-5">
        <a href="./owner_item.php" class="btn btn-info mb-3 px-3 mx-auto">
            Add a new Item
        </a>
    </div> -->

        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Item Catalog</h3>
                </div>
                <a href="owner_artist_table.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Artist Table</span>
                </a>
                <a href="owner_item_homepage.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Item Catalog</span>
                </a>
                <a href="owner_category_table.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Categories Table</span>
                </a>
                <a href="owner_artist.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">Add Artist</span>
                </a>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Artist Name</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');
                        $select_query = "SELECT * FROM artists";
                        $result_query = mysqli_query($con, $select_query);
                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $artist_id = $row['artist_id'];
                            $artist_name = $row['artist_name'];
                            echo "<tr>";
                            echo "<td>" . $row['artist_name'] . "</td>";
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo "<a href='edit_artist.php?artist_id=$artist_id' class='edit-button'>Edit</a> ";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
                    <p>Are you sure you want to logout?<p>
                    <div class="logout-btns">
                        <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                        <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
                </div>
            </div>
            <!-- <div class="form-outline mb-4 mt-5">
        <a href="./owner_dashboard.php" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div> -->

</body>

</html>