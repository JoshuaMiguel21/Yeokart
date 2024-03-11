<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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

    function openEditCategoryPopup(category_id, category_name) {
        document.getElementById('edit_category_id').value = category_id;
        document.getElementById('edit_category_name').value = category_name;
        document.getElementById('editCategoryPopup').style.display = 'flex';
    }

    function closeEditCategoryPopup() {
        document.getElementById('editCategoryPopup').style.display = 'none';
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
                    <a href=""><span class="las la-shopping-bag"></span>
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
        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Category Table</h3>
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
                        $select_query = "SELECT * FROM categories";
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
                    <p>Are you sure you want to logout?
                    <p>
                    <div class="logout-btns">
                        <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                        <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
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
                    echo "<script>alert('This category already exists')</script>";
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
                            echo "<script>alert('Category successfully updated')</script>";
                            echo "<script>window.location.href = 'emp_category_table.php';</script>";
                        }
                    }
                }
            }

            ?>
</body>

</html>