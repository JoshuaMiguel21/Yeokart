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

            header("Location: user_profile.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
