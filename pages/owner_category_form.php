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
    <link href="../css/add&edit_item.css" rel="stylesheet" />

    <title>Yeokart Edit Category Page</title>
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Edit Category</h1>
        <?php
        include('../database/db_yeokart.php');
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
            $select_query = "SELECT * FROM categories WHERE category_id='$category_id'";
            $result_query = mysqli_query($con, $select_query);
            $row = mysqli_fetch_assoc($result_query);
        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                <div class="form-outline mb-4 w-50 m-auto">
                    <label for="category_name" class="form-outline mb-3 w-50 mr-auto ml-auto">Edit Name:</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Enter new name" autocomplete="off" value="<?php echo $row['category_name']; ?>" required>
                </div>
                <br></br>
                <div class="form-outline mb-4 w-50 m-auto">
                    <button type="submit" name="update_category" class="btn btn-info mb-3 px-3">Update Category</button>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <a href="./owner_category_table.php" class="btn btn-danger mb-3 px-3 ">
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
if (isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];


    $update_query = "UPDATE categories SET category_name='$category_name' WHERE category_id='$category_id'";
    $result_query_category = mysqli_query($con, $update_query);
    if ($result_query_category) {
        echo "<script>alert('Category successfully updated')</script>";
        echo "<script>window.location.href = './owner_category_table.php';</script>";
    }
}
?>