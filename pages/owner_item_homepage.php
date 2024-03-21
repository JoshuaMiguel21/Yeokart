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

    if (isset($_SESSION['lastname'])) {
        $lastname = $_SESSION['lastname'];
    } else {
        header("Location: login_page.php");
        exit();
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

                Manage Items
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
                    <h3>Item Catalog</h3>
                </div>
            </div>
            <div class="head-buttons">
                <a href="owner_artist_table.php" class="btn-employee">
                    <i class="las la-user"></i>
                    <span class="text">View Artist Table</span>
                </a>
                <a href="owner_item_homepage.php" class="btn-employee">
                    <i class="las la-archive"></i>
                    <span class="text">View Item Catalog</span>
                </a>
                <a href="owner_category_table.php" class="btn-employee">
                    <i class="las la-list"></i>
                    <span class="text">View Categories Table</span>
                </a>
                <a href="owner_item.php" class="btn-employee">
                    <i class="las la-plus"></i>
                    <span class="text">Add Item</span>
                </a>
            </div>
            <div class="head-search">
                <form method="POST" id="searchForm">
                    <input type="text" name="search" placeholder="Search items..." id="searchInput" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                    <button type="submit" name="search_button">Search</button>
                    <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                </form>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <center>Item Name</center>
                            </th>
                            <th>
                                <center>Price</center>
                            </th>
                            <th>
                                <center>Description</center>
                            </th>
                            <th>
                                <center>Quantity</center>
                            </th>
                            <th>
                                <center>Artist</center>
                            </th>
                            <th>
                                <center>Category</center>
                            </th>
                            <th>
                                <center>Images</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');
                        if (isset($_POST['delete_item'])) {
                            $item_id = $_POST['item_id'];
                            // Perform deletion query
                            $delete_query = "DELETE FROM products WHERE item_id='$item_id'";
                            $result_query = mysqli_query($con, $delete_query);
                            if ($result_query) {
                                echo "<script>alert('Item deleted successfully')</script>";
                                echo "<script>window.location.href = 'owner_item_homepage.php';</script>";
                            } else {
                                echo "<script>alert('Failed to delete item')</script>";
                                echo "<script>window.location.href = 'owner_item_homepage.php';</script>";
                            }
                        }

                        if (isset($_POST['search_button'])) {
                            $search = $_POST['search'];
                            $select_query = "SELECT * FROM products WHERE item_name LIKE '%$search%'";
                        } else {
                            $select_query = "SELECT * FROM products";
                        }
                        $result_query = mysqli_query($con, $select_query);
                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $item_id = $row['item_id'];
                            $item_name = $row['item_name'];
                            $item_price = $row['item_price'];
                            $item_description = $row['item_description'];
                            $item_quantity = $row['item_quantity'];
                            $artist_name = $row['artist_name'];
                            $category_name = $row['category_name'];
                            $item_image1 = $row['item_image1'];
                            $item_image2 = $row['item_image2'];
                            $item_image3 = $row['item_image3'];
                            echo "<tr>";
                            echo "<td>" . $row['item_name'] . "</td>";
                            echo "<td> ₱" . $row['item_price'] . "</td>";
                            echo "<td style='max-width: 350px;'>" . $row['item_description'] . "</td>";
                            echo "<td>" . $row['item_quantity'] . "</td>";
                            echo "<td>" . $row['artist_name'] . "</td>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td>";
                            echo "<img src='./item_images/$item_image1' alt='Twice Album' width='50' height='50'>&nbsp;";
                            echo "<img src='./item_images/$item_image2' alt='Twice Album' width='50' height='50'>&nbsp;";
                            echo "<img src='./item_images/$item_image3' alt='Twice Album' width='50' height='50'>&nbsp;";
                            echo "</td>";
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo "<a href='edit_item.php?item_id=$item_id' class='edit-button'>Edit</a> 
                          <form method='post' onsubmit='return confirmDelete()'>
                          <input type='hidden' name='item_id' value='$item_id'>
                          <button type='submit' name='delete_item' class='delete-button'>Delete</button>
                          </form>";
                            echo "<div class='button-class'>";
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
                    <p>Are you sure you want to logout?
                    <p>
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