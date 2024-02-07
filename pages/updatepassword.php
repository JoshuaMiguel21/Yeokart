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
    <title>Yeokart - Password Update</title>
</head>

<body>
    <?php 
        require('../database/db_account.php');

        if(isset($_GET['email']) && isset($_GET['reset_token'])) {
            date_default_timezone_set('Asia/Manila');
            $date = date("Y-m-d");
            $email = mysqli_real_escape_string($con, $_GET['email']); // Sanitize input
            $reset_token = mysqli_real_escape_string($con, $_GET['reset_token']); // Sanitize input

            $query = "SELECT * FROM `user_accounts` WHERE `email`='$email' AND `reset_token`='$reset_token' AND `reset_token_expire`='$date'";

            $result = mysqli_query($con, $query);

            if($result) {
                if(mysqli_num_rows($result) == 1) {
                    // Display the password reset form
                    echo "
                    <div class='container'>
                        <div class='row'>
                            <div class='col-sm-12 col-md-6 col-lg-6' id='div1'>
                                <form method='POST'>
                                    <h2 style='margin-bottom: 30px;'><b>Forgot Password</b></h2>
                                    <p>Enter your new password and confirm it.</p>
                                    <hr>
                                    <label for='Password'>Password</label>
                                    <input type='password' class='form-control' id='Password' name='Password' placeholder='Enter new password' required>
                                    <label for='confirmPass'>Confirm Password</label>
                                    <input type='password' class='form-control' id='confirmPass' name='confirmPass' placeholder='Confirm password' required>
                                    <input type='hidden' name='email' value='$email'>
                                    <br>
                                    <center><button type='submit' class='custom-button' name='submit' id='enter'>Submit</button></center>
                                </form>
                            </div>
                            <div class='col-sm-12 col-md-6 col-lg-6' id='div2'>
                                <img src='../res/logo.png' alt='Yeokart Logo'>
                                <p style='font-style: italic;'>Welcome back chingu~</p>
                            </div>
                        </div>
                    </div>";
                } else {
                    // Invalid or expired link
                    echo "
                    <script>
                        alert('Invalid or Expired Link');
                        window.location.href='login_page.php';
                    </script>";
                }
            } else {
                // Server error
                echo "
                <script>
                    alert('Server Down! Try again later');
                    window.location.href='login_page.php';
                </script>";
            }
        }

        if(isset($_POST['submit'])) {
            $email = mysqli_real_escape_string($con, $_POST['email']); // Sanitize input
            $password = mysqli_real_escape_string($con, $_POST['Password']);
            $confirmPassword = mysqli_real_escape_string($con, $_POST['confirmPass']);

            if($password == $confirmPassword) {
                $pass = password_hash($password, PASSWORD_BCRYPT);
                $update = "UPDATE `user_accounts` SET `password`='$pass', `reset_token`= NULL, `reset_token_expire`= NULL WHERE `email`='$email'";

                if(mysqli_query($con, $update)) {
                    // Password updated successfully
                    echo "  
                            <script>
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
                    // Server error
                    echo "
                    <script>
                        alert('Server Down! Try again later');
                        window.location.href='updatepassword.php';
                    </script>";
                }
            } else {
                // Passwords do not match
                echo "
                <script>
                    alert('Password and Confirm Password do not match');
                </script>";
            }
        }
    ?>
</body>

</html>
