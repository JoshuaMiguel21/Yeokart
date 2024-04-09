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

    <title>Add Contacts - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Add New Contact</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="previous_page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="contacts_name" class="form-label">Title:</label>
                <span id="contactsNameCounter"><?php echo isset($row['contacts_name']) ? strlen($row['contacts_name']) : 0; ?>/15</span>
                <input type="text" name="contacts_name" id="contacts_name" class="form-control" placeholder="Enter contact title" autocomplete="off" required maxlength="15" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="contacts_description" class="form-label">Description:</label>
                <span id="contactsDescriptionCounter"><?php echo isset($row['contacts_description']) ? strlen($row['contacts_description']) : 0; ?>/80</span>
                <textarea name="contacts_description" id="contacts_description" class="form-control" placeholder="Enter contact description" required maxlength="80" required value="<?php echo $row['contacts_description']; ?>"></textarea>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <select name="contacts_icon" id="contacts_icon" class="form-select">
                    <option value="">Select Type</option>
                    <?php
                    include('../database/db_yeokart.php');
                    $select_query_contact = "SELECT * FROM contacts_icons";
                    $result_query_contact = mysqli_query($con, $select_query_contact);
                    while ($row = mysqli_fetch_assoc($result_query_contact)) {
                        $icon_name = $row['icon_name'];
                        $icon_link = $row['icon_link'];
                        echo "<option value='" . htmlspecialchars($icon_link) . "'>$icon_name</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <input type="submit" name="insert_contacts" class="btn btn-info mb-3 px-3" value="Add Contact">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-danger mb-3 px-3">
                    Back
                </a>
            </div>
        </form>

    </div>
    <script>
        const contactsNameInput = document.getElementById('contacts_name');
        const contactsDescriptionInput = document.getElementById('contacts_description');
        const contactsNameCounter = document.getElementById('contactsNameCounter');
        const contactsDescriptionCounter = document.getElementById('contactsDescriptionCounter');

        contactsNameInput.addEventListener('input', updateCounter);
        contactsDescriptionInput.addEventListener('input', updateCounter);

        function updateCounter() {
            contactsNameCounter.textContent = `${contactsNameInput.value.length}/15`;
            contactsDescriptionCounter.textContent = `${contactsDescriptionInput.value.length}/50`;
        }
        const textarea = document.getElementById('contacts_description');

        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto'; // Reset the height to auto to properly calculate the new height
            textarea.style.height = `${textarea.scrollHeight}px`; // Set the height to match the content
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
<?php
include('../database/db_yeokart.php');

if (isset($_POST['insert_contacts'])) {
    $contacts_name = $_POST['contacts_name'];
    $contacts_description = mysqli_real_escape_string($con, $_POST['contacts_description']);
    $contacts_icon = $_POST['contacts_icon'];

    $select_query = "SELECT * FROM contacts WHERE contacts_description='$contacts_description'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'This contact already exists',
                confirmButtonText: 'Ok'
            });
        </script>";
    } else {
        if ($contacts_name == '' or $contacts_description == '' or  $contacts_icon == '') {
            echo "<script>alert('Please fill up all the fields')</script>";
            exit();
        } else {
            // Escape the icon_link value
            $contacts_icon_escaped = mysqli_real_escape_string($con, $contacts_icon);

            $insert_contacts = "INSERT INTO contacts (contacts_name, contacts_description, icon_link) VALUES ('$contacts_name', '$contacts_description','$contacts_icon_escaped')";
            $result_query_contact = mysqli_query($con, $insert_contacts);
            if ($result_query_contact) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Content for contacts successfully added!',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'emp_content_details.php';
                        }
                    });
                </script>";
            }
        }
    }
}
?>