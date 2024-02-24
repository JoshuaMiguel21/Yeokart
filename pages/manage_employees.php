<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link href="../css/manage_employees.css" rel="stylesheet" />
    <title>Manage Employees</title>
</head>

<body>
    <center>
        <header>
            <h2>Employees</h2>
        </header>
    </center>
    
    <a href="add_employee.php"><button class="add-employee-button" type="submit" name="addEmployee">Add Employee</button></a>
    
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

</body>

</html>
