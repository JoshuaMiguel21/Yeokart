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
    <title>Edit FAQ - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Edit FAQ</h1>
        <?php
        include('../database/db_yeokart.php');

        if (isset($_GET['faq_id'])) {
            $faq_id = $_GET['faq_id'];
            $select_query = "SELECT * FROM faqs WHERE faq_id='$faq_id'";
            $result_query = mysqli_query($con, $select_query);
            $row = mysqli_fetch_assoc($result_query);
        ?>
            <form action="" method="post">
                <<input type="hidden" name="previous_page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="question" class="form-label">Question:</label>
                    <input type="text" name="question" id="question" class="form-control" placeholder="Enter the question" value="<?php echo isset($row['question']) ? $row['question'] : ''; ?>" required>
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="answer" class="form-label">Answer:</label>
                    <textarea name="answer" id="answer" class="form-control" placeholder="Enter the answer" required><?php echo isset($row['answer']) ? $row['answer'] : ''; ?></textarea>
                </div>
                <input type="hidden" name="faq_id" value="<?php echo $faq_id; ?>">
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <input type="submit" name="update_faq" class="btn btn-info mb-3 px-3" value="Update FAQ">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

<?php
include('../database/db_yeokart.php');

if (isset($_GET['faq_id'])) {
    $faq_id = $_GET['faq_id'];
    $select_query = "SELECT * FROM faqs WHERE faq_id='$faq_id'";
    $result_select = mysqli_query($con, $select_query);
    $row = mysqli_fetch_assoc($result_select);
    $answer = $row['answer'];

    if (isset($_POST['update_faq'])) {
        $new_answer = $_POST['answer'];

        $update_query = "UPDATE faqs SET answer='$new_answer' WHERE faq_id='$faq_id'";
        $result_update = mysqli_query($con, $update_query);

        if ($result_update) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'FAQ successfully updated',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'owner_faqs.php';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update FAQ',
                    confirmButtonText: 'Ok'
                });
            </script>";
        }
    }
}
?>
