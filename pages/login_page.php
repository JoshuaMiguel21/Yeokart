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
    <title>Yeokart Login Page</title>
</head>

<body>
    <div class="container">
        <div class="col-sm-12 col-md-6 col-lg-6" id="div1">
            <form action="login_page.php" method="post">
                <h1 style="margin-bottom: 30px;"><b>Login</b></h1>
                <hr>
                <p style="font-style: italic;">Don't have an account yet? <a href="#"><strong style="font-style: italic;">Sign-up</strong></a></p>
                <?php

                session_start();

                    if (isset($_POST["login"])) {
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        
                        require_once "../database/db_account.php";
                        $sql = "SELECT * FROM user_accounts WHERE email = '$email'";
                        $result = mysqli_query($con, $sql);
                        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        if ($user){
                            if($user['is_verified'] == 1)
                            {
                                if (password_verify($password, $user["password"])){
                                    header("Location: customer_homepage.html");
                                    $_SESSION['logged_in'] = true;
                                    $_SESSION['username'] = $result_fetch['username'];
                                    die();
                                }
                                else{
                                    echo "<div class='alert alert-danger'>Password does not match</div>";
                                }
                            }
                            else
                            {
                                echo "<div class='alert alert-danger'>Email not verified</div>";
                            }
                        }
                        else{
                            echo "<div class='alert alert-danger'>Email does not match</div>";
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
                <a href="#" class="forgot-pass"><strong>Forgot Password?</strong></a>

                <div class="button-container">
                    <a href="/pages/start_page.html"><button type="button" class="custom-button">Back</button></a>
                    <button type="submit" class="custom-button" value="login" name="login" id="login">Login</button>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6" id="div2">
            <img src="../res/logo.png" alt="Yeokart Logo">
            <p style="font-style: italic;">Sign up to see the world of KPOP</p>
        </div>
    </div>
</body>
</html>
