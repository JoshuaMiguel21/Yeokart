<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Yeokart - Login Page</title>
</head>

<body>
    <div class="container">
        <div class="col-sm-12 col-md-6 col-lg-6" id="div2">
            <img src="../res/logo.png" alt="Yeokart Logo">
            <p style="font-style: italic;">Sign up to see the world of KPOP</p>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6" id="div1">
            <form action="login_page.php" method="post">
                <h1><b>Login</b></h1>
                <hr>
                <p style="font-style: italic;">Don't have an account yet? <a href="register_page.php"><strong style="font-style: italic;">Sign-up</strong></a></p>
                <?php

                session_start();

                if (isset($_POST["login"])) {
                    $email = $_POST["email"];
                    $password = $_POST["password"];

                    require_once "../database/db_account.php";

                    // Check user_accounts table
                    $sql_user = "SELECT * FROM user_accounts WHERE email = '$email'";
                    $result_user = mysqli_query($con, $sql_user);
                    $user = mysqli_fetch_array($result_user, MYSQLI_ASSOC);

                    // Check employee_accounts table
                    $sql_employee = "SELECT * FROM employee_accounts WHERE email = '$email'";
                    $result_employee = mysqli_query($con, $sql_employee);
                    $employee = mysqli_fetch_array($result_employee, MYSQLI_ASSOC);

                    // Check admin_accounts table
                    $sql_admin = "SELECT * FROM admin_account WHERE email = '$email'";
                    $result_admin = mysqli_query($con, $sql_admin);
                    $admin = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);

                    // Check if user exists in any of the tables
                    if ($user || $employee || $admin) {
                        // Check if the user is an admin
                        if ($admin && $password == $admin["password"]) {
                            header("Location: owner_homepage.html");
                            $_SESSION['logged_in'] = true;
                            $_SESSION['username'] = $admin['username'];
                            die();
                        }
                        // Check if the user is an employee
                        elseif ($employee && password_verify($password, $employee["password"])) {
                            header("Location: emp_dashboard.php");
                            $_SESSION['logged_in'] = true;
                            $_SESSION['firstname'] = $employee['firstname'];
                            $_SESSION['username'] = $employee['username'];
                            die();
                        }
                        // Check if the user is a verified customer
                        elseif ($user && $user['is_verified'] == 1 && password_verify($password, $user["password"])) {
                            header("Location: customer_homepage.php");
                            $_SESSION['logged_in'] = true;
                            $_SESSION['username'] = $user['username'];
                            die();
                        } else {
                            // Password or email does not match
                            echo "<div class='alert alert-danger'>Password or Email does not match</div>";
                        }
                    } else {
                        // User does not exist
                        echo "<div class='alert alert-danger'>Email not found</div>";
                    }
                }

                ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <a href="forgot_email.html" class="forgot-pass"><strong>Forgot Password?</strong></a>

                <div class="button-container">
                    <a href="/pages/start_page.html"><button type="button" class="custom-button">Back</button></a>
                    <button type="submit" class="custom-button" value="login" name="login" id="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>