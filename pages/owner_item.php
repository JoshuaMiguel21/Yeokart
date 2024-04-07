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
    <link rel="icon" type="image/png" href="../res/icon.png">
    <title>Add Item - Yeokart</title>
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Add New Item</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_name" class="form-label">Name:</label>
                <span id="itemNameCounter"><?php echo isset($row['item_name']) ? strlen($row['item_name']) : 0; ?>/100</span>
                <input type="text" name="item_name" id="item_name" class="form-control" placeholder="Enter item name" autocomplete="off" required maxlength="100" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_price" class="form-label">Price:</label>
                <input type="number" name="item_price" id="item_price" class="form-control" placeholder="Enter item price" autocomplete="off" required required step="0.01">
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_description" class="form-label">Description:</label>
                <span id="itemDescriptionCounter"><?php echo isset($row['item_description']) ? strlen($row['item_description']) : 0; ?>/300</span>
                <textarea name="item_description" id="item_description" class="form-control" placeholder="Enter item description" required maxlength="300" required></textarea>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_quantity" class="form-label">Quantity:</label>
                <input type="number" name="item_quantity" id="item_quantity" class="form-control" placeholder="Enter item quantity" autocomplete="off" min="0" required>
            </div>
            <div class="select-container">
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <select name="product_artist" id="product_artist" class="form-select">
                        <option value="">Select Artist</option>
                        <?php
                        include('../database/db_yeokart.php');
                        $select_query_artist = "Select * from artists";
                        $result_query_artist = mysqli_query($con, $select_query_artist);
                        while ($row = mysqli_fetch_assoc($result_query_artist)) {
                            $artist_name = $row['artist_name'];
                            $artist_id = $row['artist_id'];
                            echo "<option value='$artist_name'>$artist_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <select name="product_category" id="product_category" class="form-select">
                        <option value="">Select item Category</option>
                        <?php
                        include('../database/db_yeokart.php');
                        $select_query_category = "Select * from categories";
                        $result_query_category = mysqli_query($con, $select_query_category);
                        while ($row = mysqli_fetch_assoc($result_query_category)) {
                            $category_name = $row['category_name'];
                            $category_id = $row['category_id'];
                            echo "<option value='$category_name'>$category_name</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image1" class="form-label">Item Image 1:</label>
                <input type="file" name="item_image1" id="item_image1" accept="image/*" class="image-input" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image2" class="form-label">Item Image 2:</label>
                <input type="file" name="item_image2" id="item_image2" accept="image/*" class="image-input">
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image3" class="form-label">Item Image 3:</label>
                <input type="file" name="item_image3" id="item_image3" accept="image/*" class="image-input">
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <input type="submit" name="insert_item" class="btn btn-info mb-3 px-3" value="Add Item">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <a href="owner_item_homepage.php" class="btn btn-danger mb-0 px-3 ">
                    Back
                </a>
            </div>
        </form>

    </div>
    <script>
        const itemNameInput = document.getElementById('item_name');
        const itemDescriptionInput = document.getElementById('item_description');
        const itemNameCounter = document.getElementById('itemNameCounter');
        const itemDescriptionCounter = document.getElementById('itemDescriptionCounter');

        itemNameInput.addEventListener('input', updateCounter);
        itemDescriptionInput.addEventListener('input', updateCounter);

        function updateCounter() {
            itemNameCounter.textContent = `${itemNameInput.value.length}/100`;
            itemDescriptionCounter.textContent = `${itemDescriptionInput.value.length}/300`;
        }
        const textarea = document.getElementById('item_description');

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

if (isset($_POST['insert_item'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_description = mysqli_real_escape_string($con, $_POST['item_description']);
    $item_quantity = $_POST['item_quantity'];
    $product_artist = $_POST['product_artist'];
    $product_category = $_POST['product_category'];

    $item_image1 = $_FILES['item_image1']['name'];
    $item_image2 = $_FILES['item_image2']['name'];
    $item_image3 = $_FILES['item_image3']['name'];

    $temp_image1 = $_FILES['item_image1']['tmp_name'];
    $temp_image2 = $_FILES['item_image2']['tmp_name'];
    $temp_image3 = $_FILES['item_image3']['tmp_name'];

    $select_query = "SELECT * FROM products WHERE item_name='$item_name'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'An item with this name already exists.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
    } else {
        if ($item_name == '' or $item_price == '' or $item_description == '' or  $item_quantity == '' or  $product_artist == '' or $product_category == '') {
            echo "<script>alert('Please fill up all the fields')</script>";
            exit();
        } else {
            move_uploaded_file($temp_image1, "./item_images/$item_image1");
            move_uploaded_file($temp_image2, "./item_images/$item_image2");
            move_uploaded_file($temp_image3, "./item_images/$item_image3");

            $insert_items = "INSERT INTO products (item_name,item_price,item_description,item_quantity,artist_name,category_name,item_image1,item_image2,item_image3) VALUES ('$item_name','$item_price','$item_description','$item_quantity', '$product_artist','$product_category','$item_image1','$item_image2','$item_image3')";
            $result_query_item = mysqli_query($con, $insert_items);
            if ($result_query_item) {

                $total_items = mysqli_num_rows(mysqli_query($con, "SELECT * FROM products"));
                $items_per_page = 10;
                $total_pages = ceil($total_items / $items_per_page);

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Item successfully added!',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'owner_item_homepage.php?page=$total_pages#item-$item_name';
                        }
                    });
                </script>";
            }
        }
    }
}
?>