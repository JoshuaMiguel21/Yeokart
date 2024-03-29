<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <a href="monthly_report.php"><span class="las la-chart-line"></span>
                        <span>Report</span></a>
                </li>
                <li>
                    <a href="manage_employees.php"><span class="las la-user-circle"></span>
                        <span>Manage Employee</span></a>
                </li>
                <li>
                    <a href="owner_featured.php" class="active"><span class="las la-tasks"></span>
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

                Manage Content
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
                    <h3>Manage Featured Section</h3>
                </div>

            </div>
            <div class="head-buttons">
                <a href="owner_content_details.php" class="btn-employee">
                    <i class="las la-edit"></i>
                    <span class="text">Edit Contact Details</span>
                </a>
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

                        if (isset($_POST['add_featured']) && isset($_POST['item_id'])) {
                            $item_id = $_POST['item_id'];
                            $select_query = "SELECT is_featured FROM products WHERE item_id = $item_id";
                            $result_query = mysqli_query($con, $select_query);
                            $row = mysqli_fetch_assoc($result_query);
                            $is_featured = $row['is_featured'];

                            // Count the number of featured items
                            $count_query = "SELECT COUNT(*) AS count FROM products WHERE is_featured = 1";
                            $count_result = mysqli_query($con, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $featured_count = $count_row['count'];

                            if ($is_featured == 1) {
                                $update_query = "UPDATE products SET is_featured = 0 WHERE item_id = $item_id";
                            } else {
                                if ($featured_count < 10) { // Change 3 to 5 for 5 featured items
                                    $update_query = "UPDATE products SET is_featured = 1 WHERE item_id = $item_id";
                                } else {
                                    // Display a message or handle the limit reached case
                                    echo "You can only have 10 featured items at a time."; // Change 3 to 5 for 5 featured items
                                }
                            }

                            if (isset($update_query)) {
                                mysqli_query($con, $update_query);
                            }
                            echo "<script>window.location.href = 'owner_featured.php';</script>";
                            exit();
                        }

                        $select_query = "SELECT * FROM products ORDER BY is_featured DESC";
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
                            $is_featured = $row['is_featured'];
                            echo "<tr>";
                            echo "<td>" . $row['item_name'] . "</td>";
                            echo "<td> ₱" . $row['item_price'] . "</td>";
                            echo "<td style='max-width: 2000px;'>" . $row['item_description'] . "</td>";
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
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='item_id' value='$item_id'>";
                            if ($is_featured == 1) {
                                echo "<button type='submit' name='add_featured' class='edit-button featured'><i class='las la-check'></i></button>";
                            } else {
                                echo "<button type='submit' name='add_featured' class='edit-button'><i class='las la-plus'></i></button>";
                            }
                            echo "</form>";
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