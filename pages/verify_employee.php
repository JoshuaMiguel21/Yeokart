<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <title>Yeokart Register Page</title>
</head>

<body>
    <?php
    require('../database/db_account.php');

    if (isset($_GET['email']) && isset($_GET['v_code'])) {
        $email = mysqli_real_escape_string($con, $_GET['email']);
        $v_code = mysqli_real_escape_string($con, $_GET['v_code']);

        $query = "SELECT * FROM `employee_accounts` WHERE `email`='$email' AND `verification_code`='$v_code'";
        $result = mysqli_query($con, $query);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $result_fetch = mysqli_fetch_assoc($result);
                if ($result_fetch['is_employee'] == 0) {
                    $update = "UPDATE `employee_accounts` SET `is_employee`='1' WHERE `email`='$email'";
                    if (mysqli_query($con, $update)) {
                        echo "  
                            <script>
                                Swal.fire({
                                    title: 'Successful!',
                                    text: 'Verification Successful! You are now cleared to proceed with the login.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href='login_page.php';
                                });
                            </script>";
                    } else {
                        echo "
                            <script>
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to update verification status.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href='register_page.php';
                                });
                            </script>";
                    }
                } else {
                    echo "
                        <script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Invalid or Expired Link',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href='register_page.php';
                            });
                        </script>";
                }
            }
        } else {
            echo "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Cannot run query.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='register_page.php';
                    });
                </script>";
        }
    }
    ?>
</body>

</html>
