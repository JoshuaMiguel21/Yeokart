<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Manage Employees - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="../css/add_employee.css" rel="stylesheet" />
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

<body style="background-color: #DD2F6E;">
    <div class="container">
        <h2 class="mt-4 mb-4">Add Employees</h2>

        <form action="add_employee.php" method="post">
            <?php
            require('../database/db_yeokart.php');
            session_start();

            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;

            require('PHPMailer/src/PHPMailer.php');
            require("PHPMailer/src/SMTP.php");
            require("PHPMailer/src/Exception.php");

            if (isset($_POST['submit'])) {
            function sendMail($email, $v_code)
            {
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'yeokartstore@gmail.com';               //SMTP username
                    $mail->Password   = 'oiprjetdssfltprn';                     //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('yeokartstore@gmail.com', 'YeoKart');
                    $mail->addAddress($email);                                  //Add a recipient

                    //Content
                    $mail->isHTML(true);                                        //Set email format to HTML
                    $mail->Subject = 'YeoKart - Employee Invitation';
                    $mail->Body    = "  <p>Dear Employee,</p>
                                            <p>We hope this email finds you well. You have been invited to verify your email address in order to complete the 
                                            registration process for our employee portal. This step is essential to ensure the security and integrity of our platform.</p>
                                            <p>To verify your email address, please click on the following link: </p>
                                            <p><a href='http://localhost/Yeokart/pages/verify_employee.php?email=$email&v_code=$v_code'>Verify Your Email</a></p>
                                            <p>Thank you for using YeoKart WebApp!</p>
                                            <p>Best regards,<br>The YeoKart Team</p>";
                    $mail->send();
                    return true;
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    return false;
                }
            }

            $username = $_POST['username'];
            $email = $_POST['email'];
            $combined_query = "
                (SELECT username, email, is_verified as status, 'user' as type FROM `user_accounts` WHERE `username`='$username' OR `email`='$email' AND `is_verified`=1)
                UNION
                (SELECT username, email, is_employee as status, 'employee' as type FROM `employee_accounts` WHERE `username`='$username' OR `email`='$email')
            ";

            $result = mysqli_query($con, $combined_query);

            if ($result) {
                $num_rows = mysqli_num_rows($result);

                if ($num_rows > 0) {
                    $result_fetch = mysqli_fetch_assoc($result);
                    if ($result_fetch['type'] == 'employee') {
                        if ($result_fetch['username'] == $username && $result_fetch['status'] == 1) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: '$username - Username already taken'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = 'add_employee.php';
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
                                        window.location = 'add_employee.php';
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
                            $update_query = "UPDATE `employee_accounts` SET 
                                             `firstname`=?, `lastname`=?, `username`=?, `password`=?, `verification_code` = ?
                                             WHERE `email`=?";
                            $stmt = mysqli_prepare($con, $update_query);
                            mysqli_stmt_bind_param($stmt, 'ssssss', $firstname, $lastname, $username, $password, $v_code, $email);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                // Re-fetch the user's data to get the updated verification code
                                $user_exist_query = "SELECT * FROM `employee_accounts` WHERE `email`=?";
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
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location = 'manage_employees.php';
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
                    } elseif ($result_fetch['type'] == 'user') {
                        if ($result_fetch['username'] == $username && $result_fetch['status'] == 1) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: '$username - Username already taken'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = 'add_employee.php';
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
                                        window.location = 'add_employee.php';
                                    }
                                });
                            </script>";
                        }
                    }
                } 
                
                else {
                        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                        $v_code = bin2hex(random_bytes(16));
                        $query = "INSERT INTO `employee_accounts` (`firstname`, `lastname`, `username`, `email`, `password`, `verification_code`, `is_employee`) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', '$_POST[email]', '$password', '$v_code', '0')";
                        if (mysqli_query($con, $query) && sendMail($_POST['email'], $v_code)) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Great news!',
                                    text: 'Your account is one step away from being fully secured. Please verify your email to proceed with the login.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    title: 'Oops!',
                                    text: 'Server is down',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    }
                } 
                
                else {
                    echo "<script>alert('Cannot Run Query');</script>";
                }
            }
            ?>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Email" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
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
                <button type="submit" id="submit" class="btn btn-info mb-3 px-3" name="submit">Submit</button>
            </div>
            <br></br>
            <div class="button-container">
                <a href="manage_employees.php" class="btn btn-danger mb-0 px-3 ">
                    Back
                </a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>