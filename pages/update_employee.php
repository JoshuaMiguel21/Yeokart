<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <title>Yeokart Admin Side</title>
</head>

<body>
    <?php
    require('../database/db_yeokart.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate the form data
        $employeeId = $_POST['employeeId'];
        $firstName = $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $username = $_POST['username'];

        // Perform the update query
        $updateQuery = "UPDATE `employee_accounts` SET 
                    firstname = '$firstName',
                    lastname = '$lastName',
                    username = '$username'
                    WHERE id = $employeeId";

        if ($con->query($updateQuery) === TRUE) {
            // Update successful
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Update Successful',
                    text: 'Employee information has been updated successfully!',
                    timer: 2000,
                    showConfirmButton: true
                }).then(function(){
                    window.location.href = 'manage_employees.php'; 
                });
            </script>";
        } else {
            // Update failed
            echo "Error updating employee: " . $con->error;
        }

        $con->close();
    } else {
        // If the form is not submitted through POST method, redirect to an error page or home page
        header("Location: error.php");
        exit();
    }
    ?>
</body>

</html>