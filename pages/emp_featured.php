<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Featured Section - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
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

if (!isset($_SESSION['nav_toggle'])) {
    // Set it to unchecked by default
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
?>

<body>

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
                    <a href="emp_view_customer.php"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="emp_item_homepage.php"><span class="las la-shopping-basket"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="emp_orders.php"><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href="emp_content_details.php" class="active"><span class="las la-tasks"></span>
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
                    <h3>Hi, <?php echo $firstname; ?></h3>
                    <small>Employee</small>
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
                <a href="emp_content_details.php" class="btn-employee">
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

                        $featuresPerPage = 10; // Set the default number of features per page
                        $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($pageNumber - 1) * $featuresPerPage;

                        if (isset($_POST['add_featured']) && isset($_POST['item_id'])) {
                            $item_id = $_POST['item_id'];

                            // Get the current is_featured status of the item
                            $select_query = "SELECT is_featured FROM products WHERE item_id = $item_id";
                            $result_query = mysqli_query($con, $select_query);
                            $row = mysqli_fetch_assoc($result_query);
                            $is_featured = $row['is_featured'];

                            // Count the number of featured items
                            $count_query = "SELECT COUNT(*) AS count FROM products WHERE is_featured = 1";
                            $count_result = mysqli_query($con, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $featured_count = $count_row['count'];

                            // Toggle the is_featured status based on the current status and the count of featured items
                            if ($is_featured == 1) {
                                $update_query = "UPDATE products SET is_featured = 0 WHERE item_id = $item_id";
                            } else {
                                if ($featured_count < 10) {
                                    $update_query = "UPDATE products SET is_featured = 1 WHERE item_id = $item_id";
                                } else {
                                    // Display a message or handle the limit reached case
                                    echo "You can only have 10 featured items at a time.";
                                }
                            }

                            // Update the is_featured status of the item
                            if (isset($update_query)) {
                                mysqli_query($con, $update_query);
                            }

                            // Redirect to the same page after updating the status
                            echo "<script>window.location.href = 'emp_featured.php';</script>";
                            exit();
                        }



                        $totalFeaturesQuery = "SELECT COUNT(*) AS total_features FROM products";
                        $totalFeaturesResult = mysqli_query($con, $totalFeaturesQuery);
                        $totalFeaturesRow = mysqli_fetch_assoc($totalFeaturesResult);
                        $totalFeatures = $totalFeaturesRow['total_features'];

                        if (isset($_POST['add_featured']) && isset($_POST['item_id'])) {
                            $item_id = $_POST['item_id'];

                            // Get the current is_featured status of the item
                            $select_query = "SELECT is_featured FROM products WHERE item_id = $item_id";
                            $result_query = mysqli_query($con, $select_query);
                            $row = mysqli_fetch_assoc($result_query);
                            $is_featured = $row['is_featured'];

                            // Count the number of featured items
                            $count_query = "SELECT COUNT(*) AS count FROM products WHERE is_featured = 1";
                            $count_result = mysqli_query($con, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $featured_count = $count_row['count'];

                            // Toggle the is_featured status based on the current status and the count of featured items
                            if ($is_featured == 1) {
                                $update_query = "UPDATE products SET is_featured = 0 WHERE item_id = $item_id";
                            } else {
                                if ($featured_count < 10) {
                                    $update_query = "UPDATE products SET is_featured = 1 WHERE item_id = $item_id";
                                } else {
                                    // Display a message or handle the limit reached case
                                    echo "You can only have 10 featured items at a time.";
                                }
                            }

                            // Update the is_featured status of the item
                            if (isset($update_query)) {
                                mysqli_query($con, $update_query);
                            }

                            // Redirect to the same page after updating the status
                            echo "<script>window.location.href = 'emp_featured.php';</script>";
                            exit();
                        }

                        $select_query = "SELECT * FROM products WHERE is_archive = 0 ORDER BY is_featured DESC LIMIT $featuresPerPage OFFSET $offset";
                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) == 0) {
                            echo "<tr><td colspan='11'><center><b>No items found</b></center></td></tr>";
                        } else {
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
                                echo "<td> ₱" . number_format($row['item_price'], 2) . "</td>";
                                echo "<td style='max-width: 2000px;'>" . $row['item_description'] . "</td>";
                                echo "<td>" . $row['item_quantity'] . "</td>";
                                echo "<td>" . $row['artist_name'] . "</td>";
                                echo "<td>" . $row['category_name'] . "</td>";
                                echo "<td>";
                                echo "<img src='./item_images/$item_image1' alt='' style='cursor: pointer;' width='auto' height='50' onclick='openImagePopup(\"./item_images/" . $item_image1 . "\")'>&nbsp;";
                                if (!empty($item_image2)) {
                                    echo "<img src='./item_images/$item_image2' alt='' style='cursor: pointer;' width='auto' height='50' onclick='openImagePopup(\"./item_images/" . $item_image2 . "\")'>&nbsp;";
                                }

                                if (!empty($item_image3)) {
                                    echo "<img src='./item_images/$item_image3' alt='' style='cursor: pointer;' width='auto' height='50' onclick='openImagePopup(\"./item_images/" . $item_image3 . "\")'>&nbsp;";
                                }
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
                        }
                        ?>

                    </tbody>
                </table>
                <?php
                $baseUrl = 'owner_featured.php?';

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
                $totalPages = ceil($totalFeatures / $featuresPerPage);

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

            <div id="imagePopup" class="popup-image" style="display: none; padding-top: 100px;">
                <div class="image-content">
                    <img id="popupImage" src="" alt="Proof of Payment" style="width: auto; height: 550px;">
                </div>
            </div>
            <!-- <div class="form-outline mb-4 mt-5">
        <a href="./owner_dashboard.php" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div> -->

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

                // Add event listener to checkbox change
                document.getElementById('nav-toggle').addEventListener('change', toggleSidebar);

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
            </script>
</body>

</html>