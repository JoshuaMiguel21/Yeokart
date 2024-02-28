<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
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
                    <span>Admin Dashboard</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-users"></span>
                    <span>Customers</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-shopping-basket"></span>
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
                    <a href="manage_employees.php" class="active"><span class="las la-user-circle"></span>
                    <span>Manage Employee</span></a>
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

                Manage Employees
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3>Unknown</h3>
                    <small>Super admin</small>
                </div>       
            </div>
        </header>

        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Employee Accounts</h3>
                </div>

                <a href="add_employee.php" class="btn-employee">
                    <i class="las la-user-plus"></i>
                    <span class="text">Add Employee</span>
                </a>
            </div>
            <?php
                require('../database/db_account.php');

                // SQL query to select employees with is_employee = 1
                $sql = "SELECT id, firstname, lastname, username, email FROM `employee_accounts` WHERE is_employee = 1";
                $result = $con->query($sql);


                if(isset($_POST['deleteEmployee'])) {
                    $employeeIdToDelete = $_POST['deleteEmployee'];
                    $deleteSql = "DELETE FROM `employee_accounts` WHERE id = $employeeIdToDelete";

                    if ($con->query($deleteSql) === TRUE) {
                        echo"
                                <script>
                                    alert('Employee Deleted Successfully');
                                </script>
                            ";
                    } 

                    else 
                    {
                        echo "Error";
                    }
                }

                // Check if there are rows in the result
                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<form method='post' action='#'>
                            <table border='1'>
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
                                    <center><button class='delete-button' type='submit' name='deleteEmployee' value='" . $row["id"] . "'>Delete</button></center>
                                </td>
                            </tr>";

                        // Increment the counter
                        $counter++;
                    }

                    echo "</table></form>";
                } 
                else 
                {
                    echo "<form method='post' action='#'>
                            <table border='1'>
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

                // Close connection
                $con->close();
            ?>
        </main>
    </div>
</body>
</html>