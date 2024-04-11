<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <title>Register - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
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
    <div class="container">
        <div class="left-column">
            <img src="../res/logo.png" alt="Yeokart Logo">
            <p id="yeokart-info">Sign up to see the world of KPOP</p>
        </div>
        <div class="right-column">
            <form action="register_page.php" method="post">
                <h1><b>Sign Up</b></h1>
                <p>Fill up the form with the correct information</p>
                <hr>

                <div class="form-group">
                <?php
                require('../database/db_yeokart.php');
                require('PHPMailer/src/PHPMailer.php');
                require('PHPMailer/src/SMTP.php');
                require('PHPMailer/src/Exception.php');

                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\SMTP;
                use PHPMailer\PHPMailer\Exception;

                if (isset($_POST['submit'])) {
                    function sendMail($email, $v_code)
                    {
                        $mail = new PHPMailer(true);

                        try {
                            //Server settings
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'cipcastro123@gmail.com';               //SMTP username
                            $mail->Password   = 'rzktkbebxdissxix';                     //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom('cipcastro123@gmail.com', 'Ivan Castro');
                            $mail->addAddress($email);                                  //Add a recipient

                            //Content
                            $mail->isHTML(true);                                        //Set email format to HTML
                            $mail->Subject = 'Yeokart - Email Verification for Account Creation';
                            $mail->Body    = "<p>Dear User,</p>
                                                <p>Thank you for registering with <b>Yeokart</b>. To ensure the security of your account 
                                                and to activate your membership, we need to verify your email address.</p>
                                                <p>Please click the link below to complete the email verification process:</p>
                                                <p><a href='http://localhost/Yeokart/pages/verify_email.php?email=$email&v_code=$v_code'>Verify Your Email</a></p>
                                                <p>Thank you for using Yeokart WebApp!</p>
                                                <p>Best regards,<br>Your Company Name Support Team</p>";
                            $mail->send();
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    }

                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $combined_query = "
                            (SELECT username, email, is_verified as status, 'user' as type FROM `user_accounts` WHERE `username`='$username' OR `email`='$email')
                            UNION
                            (SELECT username, email, is_employee as status, 'employee' as type FROM `employee_accounts` WHERE `username`='$username' OR `email`='$email' AND `is_employee`=1)
                        ";

                        $result = mysqli_query($con, $combined_query);

                        if ($result) {
                            $num_rows = mysqli_num_rows($result);

                            if ($num_rows > 0) {
                                $result_fetch = mysqli_fetch_assoc($result);
                                if ($result_fetch['type'] == 'user') {
                                    if ($result_fetch['username'] == $username) {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: '$username - Username already taken'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.history.go(-1);
                                                }
                                            });
                                        </script>";
                                    } elseif ($result_fetch['email'] == $email && $result_fetch['status'] == 1) {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: '$email - E-mail already registered'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.history.go(-1);
                                                }
                                            });
                                        </script>";
                                    } elseif ($result_fetch['email'] == $email && $result_fetch['status'] == 0) {
                                        // Sanitize and validate input before using it in your queries
                                        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
                                        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
                                        $username = mysqli_real_escape_string($con, $_POST['username']);
                                        // Hash the password securely
                                        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                        // Generate a new verification code
                                        $v_code = bin2hex(random_bytes(16));
                                        
                                        // Prepare the update query
                                        $update_query = "UPDATE `user_accounts` SET 
                                                         `firstname`=?, `lastname`=?, `username`=?, `password`=?, `verification_code` = ?
                                                         WHERE `email`=?";
                                        $stmt = mysqli_prepare($con, $update_query);
                                        mysqli_stmt_bind_param($stmt, 'ssssss', $firstname, $lastname, $username, $password, $v_code, $email);
                                        
                                        if (mysqli_stmt_execute($stmt)) {
                                            // Re-fetch the user's data to get the updated verification code
                                            $user_exist_query = "SELECT * FROM `user_accounts` WHERE `email`=?";
                                            $user_stmt = mysqli_prepare($con, $user_exist_query);
                                            mysqli_stmt_bind_param($user_stmt, 's', $email);
                                            mysqli_stmt_execute($user_stmt);
                                            $result = mysqli_stmt_get_result($user_stmt);
                                            
                                            if ($result && $row = mysqli_fetch_assoc($result)) {
                                                if (sendMail($email, $row['verification_code'])) {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'info',
                                                                title: 'Email Sent!',
                                                                text: 'Verification email has been sent again. Please check your email to proceed with the login.',
                                                                confirmButtonText: 'OK',
                                                                backdrop: true, 
                                                                allowOutsideClick: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    window.location = 'login_page.php';
                                                                }
                                                            });
                                                          </script>";
                                                } else {
                                                    echo "<script>
                                                            Swal.fire({
                                                                title: 'Oops!',
                                                                text: 'Failed to send email. Please try again later.',
                                                                icon: 'error',
                                                                confirmButtonText: 'OK'
                                                            });
                                                          </script>";
                                                }
                                            }
                                        } else {
                                            echo "<script>
                                                    Swal.fire({
                                                        title: 'Oops!',
                                                        text: 'Could not update your information. Please try again later.',
                                                        icon: 'error',
                                                        confirmButtonText: 'OK'
                                                    });
                                                  </script>";
                                        }
                                    }
                                } elseif ($result_fetch['type'] == 'employee') {
                                    if ($result_fetch['username'] == $username && $result_fetch['status'] == 1) {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: '$username - Username already taken'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'register_page.php';
                                                }
                                            });
                                        </script>";
                                    } elseif ($result_fetch['email'] == $email && $result_fetch['status'] == 1) {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: '$email - E-mail already registered'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'register_page.php';
                                                }
                                            });
                                        </script>";
                                    }
                                }
                            } else {
                            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                            $v_code = bin2hex(random_bytes(16));
                            $query = "INSERT INTO `user_accounts` (`firstname`, `lastname`, `username`, `email`, `password`, `verification_code`, `is_verified`) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$username', '$email', '$password', '$v_code', '0')";
                                if (mysqli_query($con, $query) && sendMail($_POST['email'], $v_code)) {
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Email Sent!',
                                                text: 'Verification email has been sent again. Please check your email to proceed with the login.',
                                                confirmButtonText: 'OK',
                                                backdrop: true, 
                                                allowOutsideClick: false
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = 'login_page.php';
                                                }
                                            });
                                        </script>";
                                } else {
                                    echo "<script>
                                    Swal.fire({
                                        title: 'Oops!',
                                        text: 'Server is down or failed to send email. Please try again later.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    </script>";   
                                }
                            }
                    }
                    else{
                        echo "<script>alert('Cannot Run Query');</script>";
                    }
                }
                ?>

                </div>
                <br>
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter last name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    <p id="message">Password is <span id="strength"></span></p>
                </div>

                <div class="form-group">
                    <label for="confirmPass">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPass" name="confirmPass" placeholder="Confirm password" required>
                    <small id="passwordMatch"></small>
                </div>

                <div class="button-container">
                    <button type="submit" id="register" class="btn btn-custom btn-lg" name="submit">Sign Up</button>
                    <center><a onclick="history.back()"><button type="button" class="btn btn-custom btn-lg">Back</button></a></center>
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>