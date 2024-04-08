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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="../css/add&edit_item.css" rel="stylesheet" />
    <title>Edit Contacts - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Edit Contact</h1>
        <?php
        include('../database/db_yeokart.php');

        if (isset($_GET['contacts_id'])) {
            $contacts_id = $_GET['contacts_id'];
            $select_query = "SELECT * FROM contacts WHERE contacts_id='$contacts_id'";
            $result_query = mysqli_query($con, $select_query);
            $row = mysqli_fetch_assoc($result_query);
        ?>
            <form action="" method="post">
                <input type="hidden" name="previous_page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="contacts_description" class="form-label">Description:</label>
                    <span id="contactsDescriptionCounter"><?php echo isset($row['contacts_description']) ? strlen($row['contacts_description']) : 0; ?>/80</span>
                    <textarea name="contacts_description" id="contacts_description" class="form-control" placeholder="Enter contact description" required maxlength="80" required><?php echo isset($row['contacts_description']) ? $row['contacts_description'] : ''; ?></textarea>
                </div>
                <input type="hidden" name="contacts_id" value="<?php echo $contacts_id; ?>">
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <input type="submit" name="update_contacts" class="btn btn-info mb-3 px-3" value="Update Contact">
                </div>

                <div class="form-outline mb-4 w-50 m-auto">
                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-danger mb-3 px-3">
                        Back
                    </a>
                </div>
            </form>

        <?php
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
<?php
include('../database/db_yeokart.php');

if (isset($_GET['contacts_id'])) {
    $contacts_id = $_GET['contacts_id'];
    $select_query = "SELECT * FROM contacts WHERE contacts_id='$contacts_id'";
    $result_select = mysqli_query($con, $select_query);
    $row = mysqli_fetch_assoc($result_select);
    $contacts_description = $row['contacts_description'];

    if (isset($_POST['update_contacts'])) {
        $new_contacts_description = $_POST['contacts_description'];

        $update_query = "UPDATE contacts SET contacts_description='$new_contacts_description' WHERE contacts_id='$contacts_id'";
        $result_update = mysqli_query($con, $update_query);

        if ($result_update) {
            $update_contacts_query = "UPDATE contacts SET contacts_description='$new_contacts_description' WHERE contacts_description='$contacts_description'";
            $result_update_contacts = mysqli_query($con, $update_contacts_query);
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Contact successfully updated',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'owner_contact_details.php';
                    }
                });
            </script>";

            if ($result_update_contacts) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Contact successfully updated',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'owner_content_details.php';
                            }
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update contact',
                            confirmButtonText: 'Ok'
                        });
                      </script>";
            }
        } 
    }
}
?>