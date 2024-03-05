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

    <title>Yeokart Add Artist Page</title>
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Add New Artist</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="artist_name" class="form-label">Name:</label>
                <input type="text" name="artist_name" id="artist_name" class="form-control" placeholder="Enter artist name" autocomplete="off" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <input type="submit" name="insert_artist" class="btn btn-info mb-3 px-3" value="Add Artist">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <a href="owner_artist_table.php" class="btn btn-danger mb-0 px-3 ">
                    Back
                </a>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
<?php
include('../database/db_yeokart.php');

if (isset($_POST['insert_artist'])) {
    $artist_name = $_POST['artist_name'];

    $select_query = "SELECT * FROM artists WHERE artist_name='$artist_name'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>alert('This artist already exists')</script>";
    } else {
        if ($artist_name == '') {
            echo "<script>alert('Please fill up the field')</script>";
            exit();
        } else {

            $insert_artist = "INSERT INTO artists (artist_name) VALUES ('$artist_name')";
            $result_query_artist = mysqli_query($con, $insert_artist);
            if ($result_query_artist) {
                echo "<script>alert('Artist successfully added')</script>";
                echo "<script>window.location.href = 'owner_artist_table.php';</script>";
            }
        }
    }
}
?>