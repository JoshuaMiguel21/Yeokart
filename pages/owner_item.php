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
    
    <title>Yeokart Add Item Page</title>
</head>

<body style="background-color: #DD2F6E;">
    <div class="container mt-3">
        <h1 class="text-center text-white" >Add New Item</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_name" class="form-label">Name:</label>
                <input type="text" name="item_name" id="item_name" class="form-control" placeholder="Enter item name" autocomplete="off" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_price" class="form-label">Price:</label>
                <input type="number" name="item_price" id="item_price" class="form-control" placeholder="Enter item price" autocomplete="off" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_description" class="form-label">Description:</label>
                <textarea name="item_description" id="item_description" class="form-control" placeholder="Enter item description" required></textarea>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_quantity" class="form-label">Quantity:</label>
                <input type="number" name="item_quantity" id="item_quantity" class="form-control" placeholder="Enter item quantity" autocomplete="off" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <select name="product_category" id="product_category" class="form-select">
                    <option value="">Select item Category</option>
                    <?php
                    include('../database/db_items.php');
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
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <button type="button" class="btn btn-info mb-3 px-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    Add a Category
                </button>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image1" class="form-label">Item Image 1:</label>
                <input type="file" name="item_image1" id="item_image1" class="form-control form-outline w-100" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image2" class="form-label">Item Image 2:</label>
                <input type="file" name="item_image2" id="item_image2" class="form-control" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <label for="item_image3" class="form-label">Item Image 3:</label>
                <input type="file" name="item_image3" id="item_image3" class="form-control" required>
            </div>
            <div class="form-outline mb-3 w-50 mr-auto ml-auto">
                <input type="submit" name="insert_item" class="btn btn-info mb-3 px-3" value="Add Item">
            </div>
            
            <div class="form-outline mb-4 w-50 m-auto">
            <a href="./owner_item_homepage.php" class="btn btn-danger mb-0 px-3 ">
                Back
            </a>
        </div>
        </form>
        
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
        function updateDropdown(category_id, category_name) {
            var select = document.getElementById("product_category");
            var option = document.createElement("option");
            option.value = category_id;
            option.text = category_name;
            select.add(option);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
<?php
include('../database/db_items.php');
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
            $category_id = mysqli_insert_id($con); // Get the ID of the newly inserted category
            echo "<script>alert('Category has been added successfully')</script>";
            echo "<script>updateDropdown('$category_id', '$category_name')</script>"; // Call JavaScript function to update dropdown
        }
    }
}

if (isset($_POST['insert_item'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_description = mysqli_real_escape_string($con, $_POST['item_description']);
    $item_quantity = $_POST['item_quantity'];
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
        echo "<script>alert('This product already exists')</script>";
    } else {
        if ($item_name == '' or $item_price == '' or $item_description == '' or  $item_quantity == '' or $product_category == ''  or  $item_image1 == ''  or  $item_image2 == ''  or  $item_image3 == '') {
            echo "<script>alert('Please fill up all the fields')</script>";
            exit();
        } else {
            move_uploaded_file($temp_image1, "./item_images/$item_image1");
            move_uploaded_file($temp_image2, "./item_images/$item_image2");
            move_uploaded_file($temp_image3, "./item_images/$item_image3");

            $insert_items = "INSERT INTO products (item_name,item_price,item_description,item_quantity,category_name,item_image1,item_image2,item_image3) VALUES ('$item_name','$item_price','$item_description','$item_quantity','$product_category','$item_image1','$item_image2','$item_image3')";
            $result_query_item = mysqli_query($con, $insert_items);
            if ($result_query_item) {
                echo "<script>alert('Item successfully added')</script>";
            }
        }
    }
}
?>