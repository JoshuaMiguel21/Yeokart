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
    <title>Add FAQ - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Add New FAQ</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="previous_page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="question" class="form-label">Question:</label>
                <input type="text" name="question" id="question" class="form-control" placeholder="Enter the question" autocomplete="off" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="answer" class="form-label">Answer:</label>
                <textarea name="answer" id="answer" class="form-control" placeholder="Enter the answer" required></textarea>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <input type="submit" name="insert_faq" class="btn btn-info mb-3 px-3" value="Add FAQ">
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

if (isset($_POST['insert_faq'])) {
    $question = $_POST['question'];
    $answer = mysqli_real_escape_string($con, $_POST['answer']);

    $select_query = "SELECT * FROM faqs WHERE question='$question' AND answer='$answer'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'This FAQ already exists',
                confirmButtonText: 'Ok'
            });
        </script>";
    } else {
        if ($question == '' || $answer == '') {
            echo "<script>alert('Please fill up all the fields')</script>";
            exit();
        } else {
            $insert_faq = "INSERT INTO faqs (question, answer) VALUES ('$question', '$answer')";
            $result_query_faq = mysqli_query($con, $insert_faq);
            if ($result_query_faq) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'FAQ successfully added!',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'owner_faqs.php';
                        }
                    });
                </script>";
            }
        }
    }
}
?>