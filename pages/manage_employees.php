<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Manage Employees - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this employee?");
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
                        <span>Owner Dashboard</span></a>
                </li>
                <li>
                    <a href="owner_view_customers.php"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="./owner_item_homepage.php"><span class="las la-shopping-basket"></span>
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
                    <a href="manage_employees.php" class="active"><span class="las la-user-circle"></span>
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

                Manage Employees
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
                    <h3>Manage Employee</h3>
                </div>
                <div class="head-buttons">
                    <a href="add_employee.php" class="btn-main">
                        <i class="las la-plus"></i>
                        <span class="text">Add Employee</span>
                    </a>
                </div>
            </div>
            <?php
            require('../database/db_yeokart.php');

            $employeesPerPage = 10;
            $pageNumber = 1;

            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $pageNumber = $_GET['page'];
            }

            $offset = ($pageNumber - 1) * $employeesPerPage;

            $totalEmployeesQuery = "SELECT COUNT(*) AS total_employees FROM `employee_accounts` WHERE is_employee = 1";
            $totalEmployeesResult = mysqli_query($con, $totalEmployeesQuery);
            $totalEmployeesRow = mysqli_fetch_assoc($totalEmployeesResult);
            $totalEmployees = $totalEmployeesRow['total_employees'];
            
            // Fetch employees with pagination
            $sql = "SELECT id, firstname, lastname, username, email FROM `employee_accounts` WHERE is_employee = 1 LIMIT $employeesPerPage OFFSET $offset";
            $result = $con->query($sql);


            if (isset($_POST['deleteEmployee'])) {
                $employeeIdToDelete = $_POST['deleteEmployee'];
                $deleteSql = "DELETE FROM `employee_accounts` WHERE id = $employeeIdToDelete";

                if ($con->query($deleteSql) === TRUE) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Employee Deleted Successfully',
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.href = './manage_employees.php';
                            }
                        });
                    </script>";
            
                } else {
                    echo "Error";
                }
            }

            // Check if there are rows in the result
            if ($result->num_rows > 0) {
                // Output data of each row
                echo "<form method='post' action='#'>
                            <table border='0'>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th><center>Action<center></th>
                                </tr>";

                // Counter variable for incremented number
                $counter = 1;

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                                <td>" . $counter . "</td>
                                <td>" . $row["firstname"] . "</td>
                                <td>" . $row["lastname"] . "</td>
                                <td>" . $row["username"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>
                                    <div class='button-class'>
                                        <a href='edit_employee.php?id=" . $row["id"] . "' class='edit-button'>Edit</a>
                                        <button class='delete-button' type='submit' name='deleteEmployee' value='" . $row["id"] . "'>Delete</button>
                                    </div>
                                </td>
                            </tr>";

                    // Increment the counter
                    $counter++;
                }

                echo "</table></form>";
            } else {
                echo "<form method='post' action='#'>
                            <table border='0'>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th><center>Action<center></th>
                                </tr>
                                <tr>
                                    <td colspan='6'><center><b>No employees found</b></center></td>
                                </tr>
                            </table>
                        </form>";
            }

                $baseUrl = 'manage_employees.php?';

                // Adjust the query parameters as needed for your employee management context
                $pageQuery = '';
                if (isset($_GET['search'])) {
                    $pageQuery = 'search=' . urlencode($_GET['search']);
                    // Add any other parameters you need to maintain during pagination
                }

                $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalEmployees / $employeesPerPage);

                $startPage = max(1, $pageNumber - 2);
                $endPage = min($totalPages, $pageNumber + 2);

                echo "<div class='pagination'>";

                $prevPage = max(1, $pageNumber - 1);
                echo "<a href='{$baseUrl}page=$prevPage&$pageQuery' class='pagination-link'" . ($pageNumber <= 1 ? " style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">&laquo; Previous</a>";

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $linkClass = $i == $pageNumber ? 'pagination-link current-page' : 'pagination-link';
                    echo "<a href='{$baseUrl}page=$i&$pageQuery' class='$linkClass'>$i</a>";
                }

                $nextPage = min($totalPages, $pageNumber + 1);
                echo "<a href='{$baseUrl}page=$nextPage&$pageQuery' class='pagination-link'" . ($pageNumber >= $totalPages ? " style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">Next &raquo;</a>";

                echo "</div>";
                
            // Close connection
            $con->close();
            ?>
        </main>

        <script>
            // Add confirmation to the delete button
            var deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(function(button) {
                button.onclick = function() {
                    return confirmDelete();
                };
            });
        </script>
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