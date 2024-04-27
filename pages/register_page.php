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
                                $mail->Username   = 'yeokartstore@gmail.com';               //SMTP username
                                $mail->Password   = 'oiprjetdssfltprn';                        //SMTP password
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                                //Recipients
                                $mail->setFrom('yeokartstore@gmail.com', 'YeoKart');
                                $mail->addAddress($email);                                  //Add a recipient

                                //Content
                                $mail->isHTML(true);                                        //Set email format to HTML
                                $mail->Subject = 'YeoKart - Email Verification for Account Creation';
                                $mail->Body    = "<p>Dear User,</p>
                                                <p>Thank you for registering with <b>YeoKart</b>. To ensure the security of your account 
                                                and to activate your membership, we need to verify your email address.</p>
                                                <p>Please click the link below to complete the email verification process:</p>
                                                <p><a href='http://localhost/Yeokart/pages/verify_email.php?email=$email&v_code=$v_code'>Verify Your Email</a></p>
                                                <p>Thank you for using YeoKart WebApp!</p>
                                                <p>Best regards,<br>The YeoKart Team</p>";
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
                        } else {
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

                <div class="form-group-terms">
                    <a href="#" id="terms">Terms and Conditions</a>
                </div>

                <div class="button-container">
                    <button type="submit" id="register" class="btn btn-custom btn-lg" name="submit">Sign Up</button>
                    <center><a onclick="history.back()"><button type="button" class="btn btn-custom btn-lg">Back</button></a></center>
                </div>
            </form>
        </div>
    </div>

    <div id="terms-popup" class="terms-popup terms-popup-content">
        <article class="terms-container">
            <header class="terms-container-header">
                <h1 class="terms-container-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="var(--pink)" d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z" />
                    </svg>
                    Terms and Conditions
                </h1>
                <button class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="currentColor" d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
                    </svg>
                </button>
            </header>
            <section class="terms-container-body rtf">
                <h2>Terms and Conditions for YeoKart K-Pop Merch Store
                </h2>

                <p>Welcome to YeoKart! These terms and conditions outline the rules and regulations for using YeoKart's Website, which specializes in selling Kpop merchandise, located at [yourwebsite.com].
                    By accessing this website, we assume you accept these terms and conditions. Do not continue shopping at YeoKart if you do not agree to take all of the terms and conditions stated on this page.
                </p>

                <h3>1. Product Descriptions</h3>

                <p>We strive to describe all merchandise listed on our website accurately. However, please note that these items may have minor flaws or imperfections due to the nature of these items. We provide detailed descriptions and images to give you a clear understanding of the condition of each item. </p>

                <h3>2. Authenticity</h3>

                <p>We guarantee the authenticity of all merchandise sold on our website. We source our products from reputable suppliers and conduct thorough inspections to ensure their authenticity. </p>

                <h3>3. Pricing</h3>

                <p>All prices listed on our website are in Philippine pesos (Php) and are inclusive of any applicable taxes. Prices are subject to change without prior notice. We reserve the right to modify or discontinue any product without liability to you or any third party.</p>

                <h3>4. Orders</h3>

                <p>By placing an order through YeoKart, you warrant that you are at least 18 years old or have obtained parental/guardian consent to make a purchase. Once an order is placed, you will receive details of your purchase.</p>

                <h3>5. Payment</h3>

                <p>Payment for orders must be made in full within 24 hours after creating an order. We accept payment via GCash only. To confirm payment, you must upload a screenshot of your transaction so that YeoKart can verify your payment. Your order will not be processed until payment has been received in full.</p>

                <h3>7. Returns</h3>

                <p>We offer shipping only to areas in the Philippines. Shipping costs and delivery times may vary depending on your location. Please refer to our Shipping Policy for more information.</p>

                <h3>8. Privacy</h3>

                <p>Your privacy is important to us. We collect and use personal information in accordance with our Privacy Policy. By using YeoKart, you consent to the collection and use of your personal information as described therein.</p>

                <h3>9. Limitation of Liability</h3>

                <p>In no event shall YeoKart, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from (i) your access to or use of or inability to access or use YeoKart; (ii) any conduct or content of any third party on YeoKart; (iii) any content obtained from YeoKart; and (iv) unauthorized access, use, or alteration of your transmissions or content, whether based on warranty, contract, tort (including negligence), or any other legal theory, whether or not we have been informed of the possibility of such damage.</p>

                <h3>10. Governing Law</h3>

                <p>These terms and conditions shall be governed by and construed in accordance with the laws of the Philippines, and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>

                <h3>11. Contact Us</h3>

                <p>If you have any questions or concerns about these terms and conditions, please contact us at Yeokartstore@gmail.com or on our other socials on our Contact Us page.</p>

                <br>
                <p>By using YeoKart, you agree to abide by these terms and conditions. Thank you for shopping with us!</p>
            </section>
            <footer class="terms-container-footer">
                <button class="button is-ghost">Decline</button>
                <button class="button is-primary">Accept</button>
            </footer>
        </article>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Function to show the popup
            function showPopup() {
                $('#terms-popup').show();
            }

            // Function to hide the popup
            function hidePopup() {
                $('#terms-popup').hide();
            }

            // Add event listener to the "Terms and Conditions" link
            $('a[href="#"]').click(function(e) {
                e.preventDefault(); // Prevent the default action of the link
                showPopup(); // Show the popup
            });

            // Add event listener to the accept button
            $('.button.is-primary').click(function() {
                hidePopup(); // Hide the popup
            });

            // Add event listener to the icon button
            $('.icon-button').click(function() {
                hidePopup(); // Hide the popup
            });

            // Add event listener to the decline button
            $('.button.is-ghost').click(function() {
                hidePopup(); // Hide the popup
            });
        });
    </script>
</body>

</html>