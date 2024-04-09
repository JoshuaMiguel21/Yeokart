
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
</head>
<style>
    .swal2-custom-popup {
        font-size: 16px;
        width: 500px;
    }

    .swal2-custom-title {
        font-size: 20px;
    }
</style>
<body>
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

require('../database/db_yeokart.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $sessionUsername = $_SESSION['username'];

    // Validate if the username already exists in user_accounts or employee_accounts
    $checkExistingUser = "SELECT username FROM user_accounts WHERE username = ? UNION SELECT username FROM employee_accounts WHERE username = ?";
    
    if ($checkStmt = $con->prepare($checkExistingUser)) {
        $checkStmt->bind_param("ss", $param_username, $param_username);
        $param_username = $username;
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Use SweetAlert2 for the alert and redirection
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Username already exists. Please choose a different username.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-custom-popup',
                            title: 'swal2-custom-title',
                            content: 'swal2-custom-text'
                        },
                        backdrop: true, 
                        allowOutsideClick: false 
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'customer_profile.php';
                        }
                    });
                </script>";
            exit();
        }

        $checkStmt->close();
    }

    // Proceed with the update query
    $sql = "UPDATE user_accounts SET firstname = ?, lastname = ?, username = ? WHERE username = ?";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ssss", $param_firstname, $param_lastname, $param_username, $param_sessionUsername);

        $param_firstname = $firstname;
        $param_lastname = $lastname;
        $param_username = $username;
        $param_sessionUsername = $sessionUsername;

        if ($stmt->execute()) {
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['username'] = $username;

            // Use header for redirection after successful update
            header("Location: customer_profile.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$con->close();
?>

</body>
</html>
