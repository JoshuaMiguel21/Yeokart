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
                    <a href="logout.php"><span class="las la-sign-out-alt"></span>
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
                        $select_query = "SELECT * FROM products";
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

            <!-- <div class="form-outline mb-4 mt-5">
        <a href="./owner_dashboard.php" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div> -->

</body>

</html>