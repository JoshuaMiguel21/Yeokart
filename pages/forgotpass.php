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
    <title>Yeokart - Forgot Password</title>
    <title>Document</title>
</head>
<body>
    <?php 
        require ('../database/db_account.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        require ('PHPMailer/src/PHPMailer.php');
        require("PHPMailer/src/SMTP.php");
        require("PHPMailer/src/Exception.php");

        function sendMail($email, $reset_token)
        {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'cipcastro123@gmail.com';               //SMTP username
                $mail->Password   = 'kycvxgkkrpixxtuv';                     //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('cipcastro123@gmail.com', 'Ivan Castro');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Yeokart - Password Reset Link';
                $mail->Body    = "<p>Dear User,</p>
                                    <p>We received a password reset request for your Yeokart WebApp account. If you did not initiate this request, please ignore this email.</p>
                                    <p>To reset your password, click on the following link:</p>
                                    <a href='http://localhost/Yeokart/pages/updatepassword.php?email=$email&reset_token=$reset_token'>
                                        Reset Password
                                    </a>
                                    <p>Thank you for using Yeokart WebApp!</p>
                                    <p>Best regards,<br>Your Company Name Support Team</p>";

                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        if(isset($_POST['submit']))
        {
            $query ="SELECT * FROM `user_accounts` WHERE `email`='$_POST[email]'";
            $result = mysqli_query($con, $query);
            if ($result)
            {
                if(mysqli_num_rows($result) == 1)
                {
                    $reset_token = bin2hex(random_bytes(16));
                    date_default_timezone_set('Asia/Manila');
                    $date = date("Y-m-d");
                    $query = "UPDATE `user_accounts` SET `reset_token`='$reset_token',`reset_token_expire`='$date' WHERE `email`='$_POST[email]'";
                    if(mysqli_query($con, $query) && sendMail($_POST['email'], $reset_token)) 
                    {
                        echo "  <script>
                                    Swal.fire({
                                    icon: 'info',
                                    title: 'Great news!',
                                    text: 'The password reset link was sent to your email',
                                    confirmButtonText: 'OK'
                                    });
                                </script>";
                    }
                    else
                    {
                        echo"
                            <script>
                                alert('Server Down! Try again later');
                                window.location.href='login_page.php';
                            </script>
                            ";
                    }
                }
                else
                {
                    echo"
                    <script>
                        alert('Email not found');
                        window.location.href='login_page.php';
                    </script>
                    ";
                }
            }
            else
            {
                echo"
                    <script>
                        alert('Cannot Run Query');
                        window.location.href='login_page.php';
                    </script>
                    ";
            }
        }
    ?>
</body>
</html>
