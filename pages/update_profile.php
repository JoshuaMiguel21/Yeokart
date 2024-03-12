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
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
            echo "<script>window.location.href = 'customer_profile.php';</script>";
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

            header("Location: customer_profile.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
