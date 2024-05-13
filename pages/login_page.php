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
    <title>Yeokart - Login</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>
<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

<body style="background-color: #E6A4B4;">
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

                    require_once "../database/db_yeokart.php";

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
                        if ($admin && password_verify($password, $admin["password"])) {
                            sleep(1); // Add 2-second delay
                            header("Location: owner_dashboard.php");
                            $_SESSION['logged_in'] = true;  
                            $_SESSION['firstname'] = $admin['firstname'];
                            $_SESSION['email'] = $admin['email'];
                            $_SESSION['lastname'] = $admin['lastname'];
                            die();
                        }
                        // Check if the user is an employee
                        elseif ($employee && password_verify($password, $employee["password"])) {
                            sleep(1); // Add 2-second delay
                            header("Location: emp_dashboard.php");
                            $_SESSION['logged_in'] = true;
                            $_SESSION['firstname'] = $employee['firstname'];
                            $_SESSION['email'] = $employee['email'];
                            $_SESSION['username'] = $employee['username'];
                            die();
                        }
                        // Check if the user is a verified customer
                        elseif ($user && $user['is_verified'] == 1 && password_verify($password, $user["password"])) {
                            sleep(1); // Add 2-second delay
                            header("Location: customer_homepage.php");
                            $_SESSION['logged_in'] = true;
                            $_SESSION['id'] = $user['id'];
                            $_SESSION['firstname'] = $user['firstname'];
                            $_SESSION['lastname'] = $user['lastname'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['email'] = $user['email'];
                            die();
                        } else {
                            // Password or email does not match
                            sleep(1);
                            echo "<div class='alert alert-danger'>Password or Email does not match</div>";
                        }
                    } else {
                        // User does not exist
                        sleep(1);
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
                <div class="form-group">
                    <input type="checkbox" onclick="myFunction()" id="showPasswordCheckbox">
                    <label for="showPasswordCheckbox" class="checkbox-label">Show Password</label>
                </div>


                <a href="forgot_email.php" class="forgot-pass"><strong>Forgot Password?</strong></a>

                <div class="button-container">
                    <a href="index.php"><button type="button" class="custom-button">Back</button></a>
                    <button type="submit" class="custom-button" value="login" name="login" id="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>