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

                <div class="form-group">
                    <?php
                        if (isset($_POST["submit"])) {
                            $firstname = $_POST["firstname"];
                            $lastname = $_POST["lastname"];
                            $username = $_POST["username"];
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            $confirmPass = $_POST["confirmPass"];

                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                            $errors = array();

                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                array_push($errors, "Email is not valid");
                            }

                            if (strlen($password) < 8) {
                                array_push($errors, "Password must be at least 8 characters long");
                            }

                            if ($password !== $confirmPass) {
                                array_push($errors, "Password does not match");
                            }
                            
                            require_once "../database/db_account.php";
                            $sql = "SELECT * FROM user_accounts WHERE email = '$email'";
                            $result = mysqli_query($conn, $sql);
                            $rowCount = mysqli_num_rows($result);

                            if($rowCount>0){
                                array_push($errors, "Email already exists!");
                            }

                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-warning'>$error</div>";
                                }
                            } else {
                                require_once "../database/db_account.php";
                                $sql = "INSERT INTO user_accounts (firstname, lastname, username, email, password) VALUES ( ?, ?, ?, ?, ? )";	
                                $stmt = mysqli_stmt_init($conn);
                                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                                if ($prepareStmt){
                                    mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $username, $email, $passwordHash);
                                    mysqli_stmt_execute($stmt);
                                    echo "<div class ='alert alert-success'> You are registered successfully.</div>";
                                }else{
                                    die("Something went wrong");
                                }
                            }
                        }
                    ?>
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
    <!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(function(){
            $('#register').click(function(e){
                var valid = this.form.checkValidity();
                if(valid){
                    
                    var firstname = $('#firstname').val();
                    var lastname = $('#lastname').val();
                    var username = $('#username').val();
                    var email = $('#email').val();
                    var password = $('#password').val();
                    var confirmPass = $('#confirmPass').val();

                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        data: {firstname: firstname, lastname: lastname, username: username, email: email, password: password, confirmPass: confirmPass},

                        success: function(data){
                            Swal.fire({
                            title: "Success!",
                            text: "Congratulation, your account has been successfully created",
                            icon: "success"
                            }); 
                        },

                        error: function(data){
                            Swal.fire({
                            title: "Error!",
                            text: "Unsuccessful registration",
                            icon: "error"
                            }); 
                        }
                    });
                } 
            });         
        });
    </script> --->
</body>
</html>
