<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/forgotPass.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <title>Yeokart - Password Update</title>
</head>
<script>
    $(document).ready(function() {
        function validatePassword() {
            var pass = document.getElementById("password");
            var conf = document.getElementById("confirmPass");
            var msg = document.getElementById("message");
            var str = document.getElementById("strength");

            if (pass.value.length > 0) {
                msg.style.display = "block";
            } else {
                msg.style.display = "none";
            }

            if (pass.value.length < 4) {
                str.innerHTML = "weak";
                pass.style.borderColor = "#ff5925";
                msg.style.color = "#ff5925";
                return false;

            } else if (pass.value.length >= 4 && pass.value.length < 8) {
                str.innerHTML = "medium";
                pass.style.borderColor = "yellow";
                msg.style.color = "yellow";
            } else if (pass.value.length >= 8) {
                str.innerHTML = "strong";
                pass.style.borderColor = "#26d730";
                msg.style.color = "#26d730";
            }

            if (pass.value !== conf.value) {
                showPasswordMatchMessage('Passwords do not match', '#ff5925');
                conf.style.borderColor = "#ff5925";
                return false;
            } else {
                conf.style.borderColor = "#26d730";
                showPasswordMatchMessage('Passwords match', '#26d730');
            }

            return true;
        }

        function showPasswordMatchMessage(message, color) {
            $('#passwordMatch').text(message).css('color', color);
        }

        $('#password, #confirmPass').on('input', function() {
            validatePassword();
        });

        $('form').submit(function(e) {
            if (!validatePassword()) {
                e.preventDefault();
                showErrorAlert('Oops!', 'Please check your password and try again.');
            }
        });

        function showErrorAlert(title, text) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonText: 'OK'
            });
        }
    });
</script>

<body style="background-color: #E6A4B4;">
<?php
require('../database/db_yeokart.php');

if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    date_default_timezone_set('Asia/Manila');
    $date = date("Y-m-d");
    $email = mysqli_real_escape_string($con, $_GET['email']); // Sanitize input
    $reset_token = mysqli_real_escape_string($con, $_GET['reset_token']); // Sanitize input

    // Attempt to find the record in user_accounts
    $userQuery = "SELECT * FROM `user_accounts` WHERE `email`='$email' AND `reset_token`='$reset_token' AND `reset_token_expire`='$date'";
    $userResult = mysqli_query($con, $userQuery);

    // Attempt to find the record in employee_accounts
    $employeeQuery = "SELECT * FROM `employee_accounts` WHERE `email`='$email' AND `reset_token`='$reset_token' AND `reset_token_expire`='$date'";
    $employeeResult = mysqli_query($con, $employeeQuery);

    if ($userResult || $employeeResult) {
        if (mysqli_num_rows($userResult) == 1 || mysqli_num_rows($employeeResult) == 1) {
            $tableName = mysqli_num_rows($userResult) == 1 ? 'user_accounts' : 'employee_accounts';
            echo "
                <div class='container'>
                    <div class='row'>
                        <div class='col-sm-12 col-md-6 col-lg-6' id='div2'>
                            <img src='../res/logo.png' alt='Yeokart Logo'>
                            <p style='font-style: italic;'>Welcome back chingu~</p>
                        </div>
                        <div class='col-sm-12 col-md-6 col-lg-6' id='div1'>
                            <form method='POST'>
                                <h2 style='margin-bottom: 30px;'><b>Forgot Password</b></h2>
                                <p>Enter your new password and confirm it.</p>
                                <hr>
                                <label for='Password'>Password</label>
                                <input type='password' class='form-control' id='password' name='password' placeholder='Enter new password' required>
                                <p id='message'>Password is <span id='strength'></span></p>
                                <label for='confirmPass'>Confirm Password</label>
                                <input type='password' class='form-control' id='confirmPass' name='confirmPass' placeholder='Confirm password' required>
                                <small id='passwordMatch'></small>
                                <input type='hidden' name='email' value='$email'>
                                <input type='hidden' name='table' value='$tableName'>
                                <br>
                                <center><button type='submit' class='custom-button' name='submit' id='enter'>Submit</button></center>
                            </form>
                        </div>
                    </div>
                </div>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Invalid or Expired Link',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='login_page.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Server Down! Try again later',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href='login_page.php';
            });
        </script>";
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']); // Sanitize input
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirmPass']);
    $tableName = mysqli_real_escape_string($con, $_POST['table']); // Sanitize input

    if ($password == $confirmPassword) {
        $pass = password_hash($password, PASSWORD_BCRYPT);
        $update = "UPDATE `$tableName` SET `password`='$pass', `reset_token`= NULL, `reset_token_expire`= NULL WHERE `email`='$email'";

        if (mysqli_query($con, $update)) {
            echo "<script>
                Swal.fire({
                    title: 'Successful!',
                    text: 'Password Updated Successfully. You can now login with your new password.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='login_page.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Server Down! Try again later',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='updatepassword.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password and Confirm Password do not match',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>

</body>

</html>