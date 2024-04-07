<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Item Catalog - Yeokart</title>
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
    ?>
    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
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
                    <a href="emp_item_homepage.php" class="active"><span class="las la-shopping-basket"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="emp_orders.php"><span class="las la-shopping-bag"></span>
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

                Manage Items
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3>Hi, <?php echo $firstname; ?></h3>
                    <small>Employee</small>
                </div>
            </div>
        </header>
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

            </div>
            <div class="head-buttons">
                <a href="emp_artist_table.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Artist Table</span>
                </a>
                <a href="emp_item_homepage.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Item Catalog</span>
                </a>
                <a href="emp_category_table.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">View Categories Table</span>
                </a>
            </div>
            <div class="head-search">
                <form method="GET" id="searchForm">
                    <input type="text" name="search" placeholder="Search items..." id="searchInput" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" name="search_button">Search</button>
                    <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                </form>

                <form method="GET">
                    <select name="category">
                        <option value="" disabled selected>Select a category</option>
                        <?php
                        include('../database/db_yeokart.php');
                        $category_query = "SELECT * FROM categories";
                        $result_category = mysqli_query($con, $category_query);
                        while ($category_row = mysqli_fetch_assoc($result_category)) {
                            $category_id = $category_row['category_id'];
                            $category_name = $category_row['category_name'];
                            $selected = isset($_GET['category']) && $_GET['category'] == $category_name ? 'selected' : '';
                            echo "<option value='$category_name' $selected>$category_name</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="filter_button">Filter</button>
                </form>
                <form method="GET">
                    <select name="artist">
                        <option value="" disabled selected>Select an artist</option>
                        <?php
                        include('../database/db_yeokart.php');
                        $artist_query = "SELECT * FROM artists";
                        $result_artist = mysqli_query($con, $artist_query);
                        while ($artist_row = mysqli_fetch_assoc($result_artist)) {
                            $artist_id = $artist_row['artist_id'];
                            $artist_name = $artist_row['artist_name'];
                            $selected = isset($_GET['artist']) && $_GET['artist'] == $artist_name ? 'selected' : '';
                            echo "<option value='$artist_name' $selected>$artist_name</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="filter_button">Filter</button>
                </form>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Artist</th>
                            <th>Category</th>
                            <th>Images</th>
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
                            $stmt = $con->prepare("DELETE FROM products WHERE item_id = ?");
                            $stmt->bind_param("i", $item_id);

                            if ($stmt->execute()) {
                                echo "<script>
                                            Swal.fire({
                                                title: 'Success!',
                                                text: 'Item deleted successfully',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = 'emp_item_homepage.php';
                                                }
                                            });
                                          </script>";
                            } else {
                                echo "<script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Failed to delete item',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = 'emp_item_homepage.php';
                                                }
                                            });
                                          </script>";
                            }

                            $stmt->close();
                        }

                        $itemsPerPage = 3;

                        // Default page number
                        $pageNumber = 1;

                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $pageNumber = $_GET['page'];
                        }

                        // Calculate the offset
                        $offset = ($pageNumber - 1) * $itemsPerPage;

                        if (!isset($_GET['search_button']) && !isset($_GET['filter_button'])) {
                            // Default query without any filters
                            $select_query = "SELECT * FROM products";
                        } else {
                            // Check for search or filter
                            if (isset($_GET['search_button'])) {
                                $search = $_GET['search'];
                                $select_query = "SELECT * FROM products WHERE item_name LIKE '%$search%'";
                            } elseif (isset($_GET['filter_button'])) {
                                $category_name = isset($_GET['category']) ? $_GET['category'] : '';
                                $artist_name = isset($_GET['artist']) ? $_GET['artist'] : '';

                                if (!empty($category_name)) {
                                    // Filter by category
                                    $select_query = "SELECT * FROM products WHERE category_name = '$category_name'";
                                } elseif (!empty($artist_name)) {
                                    // Filter by artist
                                    $select_query = "SELECT * FROM products WHERE artist_name = '$artist_name'";
                                } else {
                                    // No filter applied
                                    $select_query = "SELECT * FROM products";
                                }
                            }
                        }
                        // Get total count of items
                        $result = mysqli_query($con, $select_query);
                        $totalItems = mysqli_num_rows($result);

                        // Add LIMIT and OFFSET to the query
                        $select_query .= " LIMIT $itemsPerPage OFFSET $offset";

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
                            echo "<img src='./item_images/$item_image1' alt='' width='50' height='50'>&nbsp;";
                            if (!empty($item_image2)) {
                                echo "<img src='./item_images/$item_image2' alt='' width='50' height='50'>&nbsp;";
                            }

                            if (!empty($item_image3)) {
                                echo "<img src='./item_images/$item_image3' alt='' width='50' height='50'>&nbsp;";
                            }
                            echo "</td>";
                            // Inside your while loop
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo "<a href='edit_item.php?item_id=$item_id' class='edit-button'>Edit</a>";
                            echo "<button type='button' onclick='openDeletePopup(\"$item_id\", \"" . htmlspecialchars($item_name, ENT_QUOTES) . "\")' class='delete-button'>Delete</button>";
                            echo "<form id='deleteItemForm" . $item_id . "' method='post' style='display:none;'>
                                        <input type='hidden' name='item_id' value='" . $item_id . "'>
                                        <input type='hidden' name='delete_item' value='true'> <!-- Ensure this input is included -->
                                        <button type='submit' name='delete_item_button'>Delete</button>
                                    </form>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "<div class='pagination'>";
                        $pageQuery = '';
                        if (isset($_GET['search_button'])) {
                            $pageQuery .= 'search_button&search=' . urlencode($_GET['search']);
                        } elseif (isset($_GET['filter_button'])) {
                            if (isset($_GET['category'])) {
                                $pageQuery .= 'filter_button&category=' . urlencode($_GET['category']);
                            } elseif (isset($_GET['artist'])) {
                                $pageQuery .= 'filter_button&artist=' . urlencode($_GET['artist']);
                            }
                        }
                        for ($i = 1; $i <= ceil($totalItems / $itemsPerPage); $i++) {
                            $url = 'emp_item_homepage.php?page=' . $i;
                            if (!empty($pageQuery)) {
                                $url .= '&' . $pageQuery;
                            }
                            echo "<a href='$url'>$i</a>";
                        }
                        echo "</div>";
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