<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <title>Item Catalog - Yeokart</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    function openAddCategoryPopup() {
        document.getElementById('addCategoryPopup').style.display = 'flex';
    }

    function closeAddCategoryPopup() {
        document.getElementById('addCategoryPopup').style.display = 'none';
    }

    function openEditCategoryPopup(category_id, category_name) {
        document.getElementById('edit_category_id').value = category_id;
        document.getElementById('edit_category_name').value = category_name;
        document.getElementById('editCategoryPopup').style.display = 'flex';
    }

    function closeEditCategoryPopup() {
        document.getElementById('editCategoryPopup').style.display = 'none';
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

// Redirect to login page if session variables are not set
if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname'])) {
    header("Location: login_page.php");
    exit();
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

<body>
    <input type="checkbox" id="nav-toggle" <?php echo $_SESSION['nav_toggle'] ? 'checked' : ''; ?>>
     <div class="sidebar <?php echo $_SESSION['nav_toggle'] ? 'open' : ''; ?>">
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
                    <a href="owner_orders.php"><span class="las la-shopping-bag"></span>
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

                Manage Items
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
                    <h3>Category Table</h3>
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
                <a href="#" onclick="openAddCategoryPopup()" class="btn-employee">
                    <i class="las la-plus"></i>
                    <span class="text">Add Category</span>
                </a>
            </div>
            <div class="head-search">
                <form method="POST" id="searchForm">
                    <input type="text" name="search" placeholder="Search categories..." id="searchInput" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                    <button type="submit" name="search_button">Search</button>
                    <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                </form>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Categories</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');

                        if (isset($_POST['search_button'])) {
                            $search = $_POST['search'];
                            $select_query = "SELECT * FROM categories WHERE category_name LIKE '%$search%'";
                        } else {
                            $select_query = "SELECT * FROM categories";
                        }

                        $result_query = mysqli_query($con, $select_query);

                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $category_id = $row['category_id'];
                            $category_name = $row['category_name'];
                            echo "<tr>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo '<a href="#" onclick="openEditCategoryPopup(' . $category_id . ', \'' . $category_name . '\')" class="edit-button">Edit</a>';
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
                    <p>Are you sure you want to logout?</p>
                    <div class="logout-btns">
                        <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                        <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <div id="addCategoryPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeAddCategoryPopup()">&times;</span>
                    <h2>Add New Category</h2>
                    <form class="add-artist-form" method="post" enctype="multipart/form-data">
                        <label for="category_name">Category Name:</label>
                        <input type="text" id="category_name" name="category_name" class="form-control" placeholder="Enter category name" required>
                        <button type="submit" name="insert_category">Add Category</button>
                    </form>
                </div>
            </div>

            <div id="editCategoryPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeEditCategoryPopup()">&times;</span>
                    <h2>Edit Category</h2>
                    <form class="add-artist-form" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="edit_category_id" name="category_id" value="">
                        <label for="edit_category_name">Category Name:</label>
                        <input type="text" id="edit_category_name" name="category_name" class="form-control" placeholder="Enter category name" required>
                        <input type="hidden" id="previous_page" name="previous_page">
                        <button type="submit" name="update_category">Update Category</button>
                    </form>
                </div>
            </div>

            <?php
            include('../database/db_yeokart.php');

            if (isset($_POST['insert_category'])) {
                $category_name = $_POST['category_name'];

                $select_query = "SELECT * FROM categories WHERE category_name='$category_name'";
                $result_select = mysqli_query($con, $select_query);
                $number = mysqli_num_rows($result_select);

                if ($number > 0) {
                    echo "<script>Swal.fire({
                        title: 'Error!',
                        text: 'This category already exists',
                        icon: 'error',
                        confirmButtonText: 'OK'
                      }).then(function(){
                        document.getElementById('addCategoryPopup').style.display = 'flex';
                      });</script>";
                } else {
                    if ($category_name == '') {
                        echo "<script>alert('Please fill up the field')</script>";
                        exit();
                    } else {

                        $insert_category = "INSERT INTO categories (category_name) VALUES ('$category_name')";
                        $result_query_category = mysqli_query($con, $insert_category);
                        if ($result_query_category) {
                            echo "<script>Swal.fire({
                                title: 'Success!',
                                text: 'Category successfully added',
                                icon: 'success',
                                confirmButtonText: 'OK'
                              }).then(function(){
                                window.location.href = './owner_category_table.php';
                              });</script>";
                        }
                    }
                }
            }

            
            if (isset($_POST['update_category'])) {
                $category_id = $_POST['category_id'];
                $category_name = $_POST['category_name'];

                $get_old_category_query = "SELECT category_name FROM categories WHERE category_id='$category_id'";
                $result_old_category = mysqli_query($con, $get_old_category_query);
                $row_old_category = mysqli_fetch_assoc($result_old_category);
                $old_category_name = $row_old_category['category_name'];

                $select_query = "SELECT * FROM categories WHERE category_name='$category_name' AND category_id <> '$category_id'";
                $result_select = mysqli_query($con, $select_query);
                $number = mysqli_num_rows($result_select);

                if ($number > 0) {
                    echo "<script>Swal.fire({
                        title: 'Error!',
                        text: 'This category already exists',
                        icon: 'error',
                        confirmButtonText: 'OK'
                      }).then(function(){
                        openEditCategoryPopup('$category_id', '".addslashes($old_category_name)."');
                      });</script>";
                } else {
                    if ($category_name == '') {
                        echo "<script>alert('Please fill up the field')</script>";
                        exit();
                    } else {
                        $update_category = "UPDATE categories SET category_name='$category_name' WHERE category_id='$category_id'";
                        $result_query_category = mysqli_query($con, $update_category);

                        $update_products_category = "UPDATE products SET category_name='$category_name' WHERE category_name='$old_category_name'";
                        $result_query_products_category = mysqli_query($con, $update_products_category);

                        if ($result_query_category && $result_query_products_category) {
                            echo "<script>Swal.fire({
                                title: 'Success!',
                                text: 'Category successfully updated',
                                icon: 'success',
                                confirmButtonText: 'OK'
                              }).then(function(){
                                window.location.href = 'owner_category_table.php';
                              });</script>";
                        }
                    }
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

                // Add event listener to checkbox change
                document.getElementById('nav-toggle').addEventListener('change', toggleSidebar);
            </script>
</body>

</html>