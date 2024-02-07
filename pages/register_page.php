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
    <title>Yeokart Register Page</title>
</head>

<body>
    <div class="container">
        <div class="col-sm-12 col-md-6 col-lg-6" id="div1">
            <form action="register_page.php" method="post">
                <h1><b>Sign Up</b></h1>
                <p>Fill up the form with the correct information</p>
                <hr>

                <div class="form-group">
                <?php
                    require('../database/db_account.php');
                    session_start();
                    
                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\SMTP;
                    use PHPMailer\PHPMailer\Exception;

                    require ('PHPMailer/src/PHPMailer.php');
                    require("PHPMailer/src/SMTP.php");
                    require("PHPMailer/src/Exception.php");


                    function sendMail($email, $v_code)
                    {
                        
                       
                        $mail = new PHPMailer(true);
                        
                        try 
                        {
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
                            $mail->addAddress($email);                                  //Add a recipient
                         
                            //Content
                            $mail->isHTML(true);                                        //Set email format to HTML
                            $mail->Subject = 'Email Verification for the Creation of your Yeokart Account';
                            $mail->Body    = "  <p>Dear User,</p>
                                                <p>Thank you for registering with <b>Yeokart</b>. To ensure the security of your account 
                                                and to activate your membership, we need to verify your email address.</p>
                                                <p>Please click the link below to complete the email verification process: </p>
                                                <p><a href='http://localhost/Yeokart/pages/verify_email.php?email=$email&v_code=$v_code'>Verify Your Email</a></p>";
                            
                        
                            $mail->send();
                            return true;
                        } 

                        catch (Exception $e) 
                        {
                            return false;
                        }
                    }

                    if (isset($_POST['submit']))
                    {
                        $user_exist_query = "SELECT * FROM `user_accounts` WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
                        $result = mysqli_query($con, $user_exist_query);

                        if($result)
                        {
                            if(mysqli_num_rows($result) > 0)
                            {
                                $result_fetch = mysqli_fetch_assoc($result);
                                if ($result_fetch['username']==$_POST['username'])
                                {
                                    echo"
                                        <script>
                                            alert('$result_fetch[username] - Username already taken');
                                        </script>
                                        ";
                                }
                                else
                                {
                                    echo"
                                        <script>
                                            alert('$result_fetch[email] - E-mail already registered');
                                        </script>
                                        ";     
                                }
                            }
                            else
                            {
                                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                $v_code = bin2hex(random_bytes(16));
                                $query = "INSERT INTO `user_accounts` (`firstname`, `lastname`, `username`, `email`, `password`, `verification_code`, `is_verified`) VALUES ('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', '$_POST[email]', '$password', '$v_code', '0')";
                                if(mysqli_query($con, $query) && sendMail($_POST['email'], $v_code))
                                {
                                    echo "  <script>
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Great news!',
                                                text: 'Your account is one step away from being fully secured. Please verify your email to proceed with the login.',
                                                confirmButtonText: 'OK'
                                            });
                                            </script>";
                                }
                                else
                                {
                                    echo "  <script>
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
                        else
                        {
                            echo"
                                <script>
                                    alert('Cannot Run Query');
                                </script>
                                ";
                        }
                    }
                ?>
                </div>
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
                </div>
                <div class="form-group">
                    <label for="confirmPass">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPass" name="confirmPass" placeholder="Confirm password" required>
                </div>

                <div class="button-container">
                    <a href="/pages/start_page.html"><button type="button" class="custom-button">Back</button></a>
                    <button type="submit" class="custom-button" id="register" name="submit" id="signUp">Sign Up</button>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6" id="div2">
            <img src="../res/logo.png" alt="Yeokart Logo">
            <p style="font-style: italic;">Sign up to see the world of KPOP</p>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
