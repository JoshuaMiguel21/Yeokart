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

                Dashboard
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
                            echo "<a href='edit_category.php?category_id=$category_id' class='edit-button'>Edit</a> ";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
</body>

</html>