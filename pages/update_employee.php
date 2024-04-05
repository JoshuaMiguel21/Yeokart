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
    <title>Manage Employees - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>

<body>
<?php
require('../database/db_yeokart.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeId = $_POST['employeeId'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $username = $_POST['username'];

    // Query to get the current username of the employee being updated
    $currentUsernameQuery = "SELECT username FROM `employee_accounts` WHERE id = $employeeId";
    $currentResult = $con->query($currentUsernameQuery);
    $currentUsernameRow = $currentResult->fetch_assoc();
    $currentUsername = $currentUsernameRow['username'];

    // Only check if the username exists in other rows if it's being changed
    if ($username !== $currentUsername) {
        $checkUsernameQuery = "
            (SELECT username FROM `user_accounts` WHERE `username`='$username' AND `is_verified`=1)
            UNION
            (SELECT username FROM `employee_accounts` WHERE `username`='$username' AND `is_employee`=1 AND id != $employeeId)
        ";

        $result = $con->query($checkUsernameQuery);

        if ($result->num_rows > 0) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Username Exists',
                    text: 'The username already exists with either a verified user or an active employee. Please choose a different username.',
                }).then(function(){
                    window.history.go(-1);
                });
            </script>";
            $con->close();
            exit;
        }
    }

    $updateQuery = "UPDATE `employee_accounts` SET 
                    firstname = '$firstName',
                    lastname = '$lastName',
                    username = '$username'
                    WHERE id = $employeeId";

    if ($con->query($updateQuery) === TRUE) {
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
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating employee information.',
            });
        </script>";
    }

    $con->close();
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Invalid request method.',
        }).then(function(){
            window.location.href = 'edit_employee.php';
        });
    </script>";
}
?>


</body>

</html>