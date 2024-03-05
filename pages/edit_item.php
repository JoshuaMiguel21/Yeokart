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

    <title>Yeokart Edit Item Page</title>
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white">Edit Item</h1>
        <?php
        include('../database/db_yeokart.php');
        if (isset($_GET['item_id'])) {
            $item_id = $_GET['item_id'];
            $select_query = "SELECT * FROM products WHERE item_id='$item_id'";
            $result_query = mysqli_query($con, $select_query);
            $row = mysqli_fetch_assoc($result_query);
        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                <input type="hidden" name="current_image1" value="<?php echo $row['item_image1']; ?>">
                <input type="hidden" name="current_image2" value="<?php echo $row['item_image2']; ?>">
                <input type="hidden" name="current_image3" value="<?php echo $row['item_image3']; ?>">
                <input type="hidden" name="previous_page" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                <div class="form-outline mb-4 w-50 m-auto">
                    <label for="item_name" class="form-label">Name:</label>
                    <span id="itemNameCounter"><?php echo strlen($row['item_name']); ?>/100</span>
                    <input type="text" name="item_name" id="item_name" class="form-control" placeholder="Enter item name" autocomplete="off" value="<?php echo $row['item_name']; ?>" required maxlength="100">
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="item_price" class="form-label">Price:</label>
                    <input type="number" name="item_price" id="item_price" class="form-control" placeholder="Enter item price" autocomplete="off" value="<?php echo $row['item_price']; ?>" required>
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="item_description" class="form-label">Description:</label>
                    <span id="itemDescriptionCounter"><?php echo strlen($row['item_description']); ?>/300</span>
                    <textarea name="item_description" id="item_description" class="form-control" placeholder="Enter item description" required maxlength="300"><?php echo $row['item_description']; ?></textarea>
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <label for="item_quantity" class="form-label">Quantity:</label>
                    <input type="number" name="item_quantity" id="item_quantity" class="form-control" placeholder="Enter item quantity" autocomplete="off" value="<?php echo $row['item_quantity']; ?>" min="0" required>
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <select name="product_artist" id="product_artist" class="form-select">
                        <option value="">Select Artist</option>
                        <?php
                        $select_query_artist = "Select * from artists";
                        $result_query_artist = mysqli_query($con, $select_query_artist);
                        while ($artist_row = mysqli_fetch_assoc($result_query_artist)) {
                            $artist_name = $artist_row['artist_name'];
                            $selected = ($artist_name == $row['artist_name']) ? 'selected' : '';
                            echo "<option value='$artist_name' $selected>$artist_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <button type="button" class="btn btn-info mb-3 px-3" data-bs-toggle="modal" data-bs-target="#addArtistModal">
                        Add an Artist
                    </button>
                </div>
                <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                    <select name="product_category" id="product_category" class="form-select">
                        <option value="">Select item Category</option>
                        <?php
                        $select_query_category = "Select * from categories";
                        $result_query_category = mysqli_query($con, $select_query_category);
                        while ($category_row = mysqli_fetch_assoc($result_query_category)) {
                            $category_name = $category_row['category_name'];
                            $selected = ($category_name == $row['category_name']) ? 'selected' : '';
                            echo "<option value='$category_name' $selected>$category_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <button type="button" class="btn btn-info mb-3 px-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        Add a Category
                    </button>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <label for="item_image1" class="form-label">Item Image 1:</label>
                    <input type="file" name="item_image1" id="item_image1" class="form-control">
                    <?php if ($row['item_image1']) : ?>
                        <p>Current Image: <br></br><img src='./item_images/<?php echo $row['item_image1']; ?>' alt='Twice Album' height="200px" width="200px">
                        <?php endif; ?>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <label for="item_image2" class="form-label">Item Image 2:</label>
                    <input type="file" name="item_image2" id="item_image2" class="form-control">
                    <?php if ($row['item_image2']) : ?>
                        <p>Current Image: <br></br><img src='./item_images/<?php echo $row['item_image2']; ?>' alt='Twice Album' height="200px" width="200px">
                        <?php endif; ?>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <label for="item_image3" class="form-label">Item Image 3:</label>
                    <input type="file" name="item_image3" id="item_image3" class="form-control">
                    <?php if ($row['item_image3']) : ?>
                        <p>Current Image: <br></br><img src='./item_images/<?php echo $row['item_image3']; ?>' alt='Twice Album' height="200px" width="200px">
                        <?php endif; ?>
                </div>
                <div class="form-outline mb-4 w-50 m-auto">
                    <button type="submit" name="update_item" class="btn btn-info mb-3 px-3">Update Item</button>
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
    <div class="modal fade" id="addArtistModal" tabindex="-1" aria-labelledby="addArtistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addArtistModalLabel">Add Artist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="artistName" class="form-label">Artist Name:</label>
                            <input type="text" class="form-control" name="artist_name" id="artist_name" autocomplete="off" placeholder="Enter artist name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="add_artist" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name:</label>
                            <input type="text" class="form-control" name="category_name" id="category_name" autocomplete="off" placeholder="Enter category name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="add_category" value="Add">
                    </div>
                </form>
            </div>
        </div>
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
if (isset($_POST['add_artist'])) {
    $artist_name = $_POST['artist_name'];
    $select_query = "SELECT * FROM artists WHERE artist_name='$artist_name'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>alert('This artist is already added')</script>";
    } else {
        $insert_query = "INSERT INTO artists (artist_name) VALUES ('$artist_name')";
        $result = mysqli_query($con, $insert_query);
        if ($result) {
            $artist_name = mysqli_insert_id($con); // Get the ID of the newly inserted category
            echo "<script>alert('Artist has been added successfully')</script>";
            echo "<script>updateDropdownArtist('$artist_name')</script>"; // Call JavaScript function to update dropdown
        }
    }
}
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $select_query = "SELECT * FROM categories WHERE category_name='$category_name'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);
    if ($number > 0) {
        echo "<script>alert('This category is already added')</script>";
    } else {
        $insert_query = "INSERT INTO categories (category_name) VALUES ('$category_name')";
        $result = mysqli_query($con, $insert_query);
        if ($result) {
            $category_name = mysqli_insert_id($con); // Get the ID of the newly inserted category
            echo "<script>alert('Category has been added successfully')</script>";
            echo "<script>updateDropdownCategory('$category_name')</script>"; // Call JavaScript function to update dropdown
        }
    }
}
if (isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_description = mysqli_real_escape_string($con, $_POST['item_description']);
    $item_quantity = $_POST['item_quantity'];
    $product_artist = $_POST['product_artist'];
    $product_category = $_POST['product_category'];

    $item_image1 = $_POST['current_image1'];
    $item_image2 = $_POST['current_image2'];
    $item_image3 = $_POST['current_image3'];

    if ($_FILES['item_image1']['size'] > 0) {
        $item_image1 = $_FILES['item_image1']['name'];
        $upload_dir = "./item_images/";
        move_uploaded_file($_FILES['item_image1']['tmp_name'], $upload_dir . $item_image1);
    }

    if ($_FILES['item_image2']['size'] > 0) {
        $item_image2 = $_FILES['item_image2']['name'];
        $upload_dir = "./item_images/";
        move_uploaded_file($_FILES['item_image2']['tmp_name'], $upload_dir . $item_image2);
    }

    if ($_FILES['item_image3']['size'] > 0) {
        $item_image3 = $_FILES['item_image3']['name'];
        $upload_dir = "./item_images/";
        move_uploaded_file($_FILES['item_image3']['tmp_name'], $upload_dir . $item_image3);
    }

    $update_query = "UPDATE products SET item_name='$item_name', item_price='$item_price', item_description='$item_description', item_quantity='$item_quantity', artist_name='$product_artist', category_name='$product_category', item_image1='$item_image1', item_image2='$item_image2', item_image3='$item_image3' WHERE item_id='$item_id'";
    $result_query_item = mysqli_query($con, $update_query);
    if ($result_query_item) {
        echo "<script>alert('Item successfully updated')</script>";
        $previous_page = $_POST['previous_page'];
        echo "<script>document.location.href = '$previous_page';</script>";
    }
}
?>