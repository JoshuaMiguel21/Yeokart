<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Yeokart Item Catalog Page</title>
</head>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this item?");
    }
</script>

<body>
    <div class="container mt-3">
        <h1 class="text-center mb-4">Item Catalog</h1>
    </div>
    <div class="form-outline mb-4 mt-5">
        <a href="./owner_item.php" class="btn btn-info mb-3 px-3 mx-auto">
            Add a new Item
        </a>
    </div>
    <div class="table">
        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('../database/db_items.php');
                if (isset($_POST['delete_item'])) {
                    $item_id = $_POST['item_id'];
                    // Perform deletion query
                    $delete_query = "DELETE FROM products WHERE item_id='$item_id'";
                    $result_query = mysqli_query($con, $delete_query);
                    if ($result_query) {
                        echo "<script>alert('Item deleted successfully')</script>";
                        echo "<script>window.location.href = './owner_item_homepage.php';</script>";
                    } else {
                        echo "<script>alert('Failed to delete item')</script>";
                        echo "<script>window.location.href = './owner_item_homepage.php';</script>";
                    }
                }
                $select_query = "SELECT * FROM products";
                $result_query = mysqli_query($con, $select_query);
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $item_id = $row['item_id'];
                    $item_name = $row['item_name'];
                    $item_price = $row['item_price'];
                    $item_description = $row['item_description'];
                    $item_quantity = $row['item_quantity'];
                    $category_name = $row['category_name'];
                    $item_image1 = $row['item_image1'];
                    $item_image2 = $row['item_image2'];
                    $item_image3 = $row['item_image3'];
                    echo "<tr>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['item_price'] . "</td>";
                    echo "<td style='max-width: 350px;'>" . $row['item_description'] . "</td>";
                    echo "<td>" . $row['item_quantity'] . "</td>";
                    echo "<td>" . $row['category_name'] . "</td>";
                    echo "<td>";
                    echo "<img src='./item_images/$item_image1' alt='Twice Album' width='50' height='50'>&nbsp;";
                    echo "<img src='./item_images/$item_image2' alt='Twice Album' width='50' height='50'>&nbsp;";
                    echo "<img src='./item_images/$item_image3' alt='Twice Album' width='50' height='50'>&nbsp;";
                    echo "</td>";
                    echo "<td><a href='./owner_edit_item.php?item_id=$item_id' class='btn btn-primary'>Edit</a></td>";
                    echo "<td>
                    <form method='post' onsubmit='return confirmDelete()'>
                        <input type='hidden' name='item_id' value='$item_id'>
                        <button type='submit' name='delete_item' class='btn btn-danger'>Delete</button>
                    </form>
                </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="form-outline mb-4 mt-5">
        <a href="./owner_homepage.html" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div>
</body>

</html>