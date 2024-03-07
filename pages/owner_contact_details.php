<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this contact?");
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
                    <a href=""><span class="las la-chart-line"></span>
                        <span>Report</span></a>
                </li>
                <li>
                    <a href="manage_employees.php"><span class="las la-user-circle"></span>
                        <span>Manage Employee</span></a>
                </li>
                <li>
                    <a href="owner_contact_details.php" class="active"><span class="las la-tasks"></span>
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
                    <h3>Contact Details</h3>
                </div>
                <a href="add_contacts.php" class="btn-employee">
                    <i class="las la-plus"></i>
                    <span class="text">Add Contacts</span>
                </a>
            </div>

            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Contact Title</th>
                            <th>Contact Type</th>
                            <th>Description</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');
                        if (isset($_POST['delete_contacts'])) {
                            $contacts_id = $_POST['contacts_id'];
                            // Perform deletion query
                            $delete_query = "DELETE FROM contacts WHERE contacts_id='$contacts_id'";
                            $result_query = mysqli_query($con, $delete_query);
                            if ($result_query) {
                                echo "<script>alert('Contact deleted successfully')</script>";
                                echo "<script>window.location.href = 'owner_contact_details.php';</script>";
                            } else {
                                echo "<script>alert('Failed to delete item')</script>";
                                echo "<script>window.location.href = 'owner_contact_details.php';</script>";
                            }
                        }
                        $select_query = "SELECT * FROM contacts";
                        $result_query = mysqli_query($con, $select_query);
                        while ($row = mysqli_fetch_assoc($result_query)) {
                            $contacts_id = $row['contacts_id'];
                            $contacts_name = $row['contacts_name'];
                            $icon_link = $row['icon_link'];
                            $contacts_description = $row['contacts_description'];
                            echo "<tr>";
                            echo "<td>" . $row['contacts_name'] . "</td>";
                            echo "<td><div class='iconbox'>" . $row['icon_link'] . "</div></td>";
                            echo "<td style='max-width: 350px;'>" . $row['contacts_description'] . "</td>";
                            echo "<td>";
                            echo "<div class='button-class'>";
                            echo "<a href='edit_contacts.php?contacts_id=$contacts_id' class='edit-button'>Edit</a> 
                          <form method='post' onsubmit='return confirmDelete()'>
                          <input type='hidden' name='contacts_id' value='$contacts_id'>
                          <button type='submit' name='delete_contacts' class='delete-button'>Delete</button>
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