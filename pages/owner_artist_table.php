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

    function openAddArtistPopup() {
        document.getElementById('addArtistPopup').style.display = 'flex';
    }

    function closeAddArtistPopup() {
        document.getElementById('addArtistPopup').style.display = 'none';
    }

    function openEditArtistPopup(artist_id, artist_name) {
        document.getElementById('edit_artist_id').value = artist_id;
        document.getElementById('edit_artist_name').value = artist_name;
        document.getElementById('editArtistPopup').style.display = 'flex';
    }

    function closeEditArtistPopup() {
        document.getElementById('editArtistPopup').style.display = 'none';
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
                    <h3><?php echo $firstname . " " . $lastname; ?></h3>
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
                    <h3>Artist Table</h3>
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
                <a href="#" onclick="openAddArtistPopup()" class="btn-employee">
                    <i class="las la-plus"></i>
                    <span class="text">Add Artist</span>
                </a>
            </div>
            <div class="head-search">
                <form method="POST" id="searchForm">
                    <input type="text" name="search" placeholder="Search artists..." id="searchInput" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                    <button type="submit" name="search_button">Search</button>
                    <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                </form>
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

                        if (isset($_POST['search_button'])) {
                            $search = $_POST['search'];
                            $select_query = "SELECT * FROM artists WHERE artist_name LIKE '%$search%'";
                        } else {
                            $select_query = "SELECT * FROM artists";
                        }

                        $result_query = mysqli_query($con, $select_query);

                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $artist_id = $row['artist_id'];
                            $artist_name = $row['artist_name'];
                            echo "<tr>";
                            echo "<td>" . $row['artist_name'] . "</td>";
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo '<a href="#" onclick="openEditArtistPopup(' . $artist_id . ', \'' . $artist_name . '\')" class="edit-button">Edit</a>';
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

            <div id="addArtistPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeAddArtistPopup()">&times;</span>
                    <h2>Add New Artist</h2>
                    <form class="add-artist-form" method="post" enctype="multipart/form-data">
                        <label for="artist_name">Artist Name:</label>
                        <input type="text" id="artist_name" name="artist_name" class="form-control" placeholder="Enter artist name" required>
                        <button type="submit" name="insert_artist">Add Artist</button>
                    </form>
                </div>
            </div>

            <div id="editArtistPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeEditArtistPopup()">&times;</span>
                    <h2>Edit Artist</h2>
                    <form class="add-artist-form" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="edit_artist_id" name="artist_id" value="">
                        <label for="edit_artist_name">Artist Name:</label>
                        <input type="text" id="edit_artist_name" name="artist_name" class="form-control" placeholder="Enter artist name" required>
                        <input type="hidden" id="previous_page" name="previous_page">
                        <button type="submit" name="update_artist">Update Artist</button>
                    </form>
                </div>
            </div>

            <?php
            include('../database/db_yeokart.php');

            if (isset($_POST['insert_artist'])) {
                $artist_name = $_POST['artist_name'];

                $select_query = "SELECT * FROM artists WHERE artist_name='$artist_name'";
                $result_select = mysqli_query($con, $select_query);
                $number = mysqli_num_rows($result_select);
                if ($number > 0) {
                    echo "<script>alert('This artist already exists')</script>";
                } else {
                    if ($artist_name == '') {
                        echo "<script>alert('Please fill up the field')</script>";
                        exit();
                    } else {

                        $insert_artist = "INSERT INTO artists (artist_name) VALUES ('$artist_name')";
                        $result_query_artist = mysqli_query($con, $insert_artist);
                        if ($result_query_artist) {
                            echo "<script>alert('Artist successfully added')</script>";
                            echo "<script>window.location.href = 'owner_artist_table.php';</script>";
                        }
                    }
                }
            }

            if (isset($_POST['update_artist'])) {
                $artist_id = $_POST['artist_id'];
                $artist_name = $_POST['artist_name'];
            
                $get_old_artist_query = "SELECT artist_name FROM artists WHERE artist_id='$artist_id'";
                $result_old_artist = mysqli_query($con, $get_old_artist_query);
                $row_old_artist = mysqli_fetch_assoc($result_old_artist);
                $old_artist_name = $row_old_artist['artist_name'];
            
                $select_query = "SELECT * FROM artists WHERE artist_name='$artist_name' AND artist_id <> '$artist_id'";
                $result_select = mysqli_query($con, $select_query);
                $number = mysqli_num_rows($result_select);
            
                if ($number > 0) {
                    echo "<script>alert('This artist already exists')</script>";
                } else {
                    if ($artist_name == '') {
                        echo "<script>alert('Please fill up the field')</script>";
                        exit();
                    } else {
                        $update_artist = "UPDATE artists SET artist_name='$artist_name' WHERE artist_id='$artist_id'";
                        $result_query_artist = mysqli_query($con, $update_artist);
            
                        $update_products_artist = "UPDATE products SET artist_name='$artist_name' WHERE artist_name='$old_artist_name'";
                        $result_query_products_artist = mysqli_query($con, $update_products_artist);
            
                        if ($result_query_artist && $result_query_products_artist) {
                            echo "<script>alert('Artist successfully updated')</script>";
                            echo "<script>window.location.href = 'owner_artist_table.php';</script>";
                        }
                    }
                }
            }
            ?>



            <!-- <div class="form-outline mb-4 mt-5">
        <a href="./owner_dashboard.php" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div> -->

</body>

</html>