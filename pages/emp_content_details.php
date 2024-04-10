<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Manage Content - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding: 10px;
    }

    .pagination-link {
        padding: 8px 16px;
        border: 1px solid var(--main-color);
        background-color: #fff;
        text-decoration: none;
        color: var(--main-color);
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .pagination-link:hover {
        background-color: var(--main-color);
        color: #fff;
        box-shadow: 0 2px 5px rgba(221, 47, 110, 0.5);
    }

    .current-page {
        font-weight: bold;
        color: #fff;
        background-color: var(--main-color);
        border-color: var(--dark-color);
    }

    .current-page:hover {
        background-color: var(--main-color);
        color: #fff;
        border-color: var(--dark-color);
        box-shadow: 0 2px 5px rgba(209, 28, 73, 0.5);
    }
</style>
<script>
    function openDeletePopup(contacts_id) {
        document.getElementById('deleteConfirmationPopup').style.display = 'flex';
        document.getElementById('deleteForm_' + contacts_id).contacts_id.value = contacts_id;
    }

    function closeDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    function confirmDelete(contacts_id) {
        // Get the form associated with the delete action
        var deleteForm = document.getElementById('deleteForm_' + contacts_id);
        // Submit the form
        deleteForm.submit();
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
                    <h3>Manage Contact Details</h3>
                </div>

            </div>
            <div class="head-buttons">
                <a href="emp_featured.php" class="btn-employee">
                    <i class="las la-edit"></i>
                    <span class="text">Edit Featured Section</span>
                </a>
                <a href="emp_add_contacts.php" class="btn-employee">
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
                                echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Contact deleted successfully',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'emp_content_details.php';
                                        }
                                    });
                                </script>";
                            } else {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to delete item',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'emp_content_details.php';
                                        }
                                    });
                                </script>";
                            }
                        }

                        $contactsPerPage = 10;
                        $pageNumber = 1;

                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $pageNumber = $_GET['page'];
                        }

                        $offset = ($pageNumber - 1) * $contactsPerPage;

                        $totalContactsQuery = "SELECT COUNT(*) AS total_contacts FROM contacts";
                        $totalContactsResult = mysqli_query($con, $totalContactsQuery);
                        $totalContactsRow = mysqli_fetch_assoc($totalContactsResult);
                        $totalContacts = $totalContactsRow['total_contacts'];

                        $select_query = "SELECT * FROM contacts LIMIT $contactsPerPage OFFSET $offset";
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
                            echo "<a href='emp_edit_contacts.php?contacts_id=$contacts_id' class='edit-button'>Edit</a>";
                            echo "<button type='button' onclick='openDeletePopup(" . $contacts_id . ")' class='delete-button'>Delete</button>";
                            echo "<form id='deleteForm_$contacts_id' method='post'>";
                            echo "<input type='hidden' name='contacts_id' value='$contacts_id'>";
                            echo "<input type='hidden' name='delete_contacts' value='true'>";
                            echo "</form>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                $baseUrl = 'emp_content_details.php?';

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
                $totalPages = ceil($totalContacts / $contactsPerPage);

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

            <div id="deleteConfirmationPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeDeletePopup()">&times;</span>
                    <p>Are you sure you want to delete this contact?</p>
                    <div class="logout-btns">
                        <button onclick="confirmDelete(<?php echo $contacts_id; ?>)" class="confirm-logout-btn">Delete</button>
                        <button onclick="closeDeletePopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

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