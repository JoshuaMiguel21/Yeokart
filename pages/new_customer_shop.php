    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shop - Yeokart</title>
        <link rel="icon" type="image/png" href="../res/icon.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <link rel="stylesheet" href="../css/style_homepage_customer.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <?php
    require('../database/db_yeokart.php');
    session_start();

    if (isset($_SESSION['id'])) {
        $customer_id = $_SESSION['id'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if (isset($_SESSION['firstname'])) {
        $firstname = $_SESSION['firstname'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if (isset($_SESSION['lastname'])) {
        $lastname = $_SESSION['lastname'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if (isset($_SESSION['email'])) {
        $email = strtolower($_SESSION['email']);
    } else {
        header("Location: login_page.php");
        exit();
    }

    $sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE customer_id = $customer_id";
    $result = $con->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $cartCount = $row['cart_count'];
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
    ?>

    <?php
    include('../database/db_yeokart.php');

    // Fetching categories from the products table
    $select_categories = "SELECT DISTINCT category_name FROM products";
    $result_categories = mysqli_query($con, $select_categories);

    // Fetching artists from the products table
    $select_artists = "SELECT DISTINCT artist_name FROM products";
    $result_artists = mysqli_query($con, $select_artists);

    ?>





    <body>
        <!-- Overlay for translucent background -->
        <div class="filter-overlay" id="filter-overlay"></div>

        <input type="checkbox" id="click">
        <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="header-1">
                <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
                <div class="icons">
                    <form action="new_customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
                        <input type="search" name="search" placeholder="Search here..." id="search-box">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <label for="click" class="menu-btn">
                        <i class="fas fa-bars"></i>
                    </label>
                </div>
                <div class="icons">
                    <ul>
                        <li class="search-ul">
                            <form action="new_customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
                                <input type="search" name="search" placeholder="Search here..." id="search-box">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </li>
                        <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                        <li><a href="new_customer_shop.php" class="active">Shop</a></li>
                        <li><a href="contact_page.php">Contact Us</a></li>
                        <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"><span id="cart-num"><?php echo $cartCount; ?></span></i></a></li>
                        <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="header-2">
                <nav class="navbar">

                    <div class="filter-section">
                        <form method="GET">
                            <h3>Filter By Category</h3>
                            <select name="category">
                                <option value="" disabled selected>Select a category</option>
                                <?php
                                include('../database/db_yeokart.php');
                                $category_query = "SELECT * FROM categories";
                                $result_category = mysqli_query($con, $category_query);
                                while ($category_row = mysqli_fetch_assoc($result_category)) {
                                    $category_id = $category_row['category_id'];
                                    $category_name = $category_row['category_name'];
                                    $selected = isset($_GET['category']) && $_GET['category'] == $category_name ? 'selected' : '';
                                    echo "<option value='$category_name' $selected>$category_name</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="filter_button">Filter</button>
                        </form>
                        <form method="GET">
                            <h3>Filter By Artist</h3>
                            <select name="artist">
                                <option value="" disabled selected>Select an artist</option>
                                <?php
                                include('../database/db_yeokart.php');
                                $artist_query = "SELECT * FROM artists";
                                $result_artist = mysqli_query($con, $artist_query);
                                while ($artist_row = mysqli_fetch_assoc($result_artist)) {
                                    $artist_id = $artist_row['artist_id'];
                                    $artist_name = $artist_row['artist_name'];
                                    $selected = isset($_GET['artist']) && $_GET['artist'] == $artist_name ? 'selected' : '';
                                    echo "<option value='$artist_name' $selected>$artist_name</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="filter_button">Filter</button>
                        </form>

                        <form method="GET" id="clear">
                            <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                        </form>
                    </div>
                </nav>
            </div>
            <!---->
            <nav class="bottom-navbar">

                <!-- Filter Popup Button -->
                <button id="filterPopupBtn">
                    <h3>Filter & Sort</h3>
                    <i class="fas fa-filter"></i>
                </button>

                <!-- Filter Popup Content -->
                <div class="filter-popup" id="filterPopup">

                    <div class="popup-filter-section">
                        <form method="GET">
                            <h2>Filter By Category</h2>
                            <select name="category">
                                <option value="" disabled selected>Select a category</option>
                                <?php
                                include('../database/db_yeokart.php');
                                $category_query = "SELECT * FROM categories";
                                $result_category = mysqli_query($con, $category_query);
                                while ($category_row = mysqli_fetch_assoc($result_category)) {
                                    $category_id = $category_row['category_id'];
                                    $category_name = $category_row['category_name'];
                                    $selected = isset($_GET['category']) && $_GET['category'] == $category_name ? 'selected' : '';
                                    echo "<option value='$category_name' $selected>$category_name</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="filter_button">Filter</button>
                        </form>
                        <form method="GET">
                            <h2>Filter By Artist</h2>
                            <select name="artist">
                                <option value="" disabled selected>Select an artist</option>
                                <?php
                                include('../database/db_yeokart.php');
                                $artist_query = "SELECT * FROM artists";
                                $result_artist = mysqli_query($con, $artist_query);
                                while ($artist_row = mysqli_fetch_assoc($result_artist)) {
                                    $artist_id = $artist_row['artist_id'];
                                    $artist_name = $artist_row['artist_name'];
                                    $selected = isset($_GET['artist']) && $_GET['artist'] == $artist_name ? 'selected' : '';
                                    echo "<option value='$artist_name' $selected>$artist_name</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="filter_button">Filter</button>
                        </form>
                    </div>

                    <form method="GET" id="clear">
                        <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                    </form>

                </div>
            </nav>
            <section class="best" id="best">
                <div class="best-slider">
                    <div class="wrapper">
                        <?php
                        include('../database/db_yeokart.php');
                        $itemsPerPage = 12;
                        $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($pageNumber - 1) * $itemsPerPage;

                        $totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM products";
                        $totalItemsResult = mysqli_query($con, $totalItemsQuery);
                        $totalItemsRow = mysqli_fetch_assoc($totalItemsResult);
                        $totalItems = $totalItemsRow['totalItems'];
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        // Base query
                        $select_query = "SELECT * FROM products";

                        // Apply search filter if search term is provided
                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
                            $select_query .= " WHERE item_name LIKE '%$searchTerm%'";
                        }

                        // Apply category filter if selected
                        if (isset($_GET['category']) && !empty($_GET['category'])) {
                            $category = mysqli_real_escape_string($con, $_GET['category']);
                            $select_query .= isset($_GET['search']) && !empty($_GET['search']) ? " AND category_name = '$category'" : " WHERE category_name = '$category'";
                        }

                        // Apply artist filter if selected
                        if (isset($_GET['artist']) && !empty($_GET['artist'])) {
                            $artist = mysqli_real_escape_string($con, $_GET['artist']);
                            $select_query .= isset($_GET['search']) && !empty($_GET['search']) || isset($_GET['category']) && !empty($_GET['category']) ? " AND artist_name = '$artist'" : " WHERE artist_name = '$artist'";
                        }

                        // Add limit and offset
                        $select_query .= " LIMIT $itemsPerPage OFFSET $offset";

                        $result_query = mysqli_query($con, $select_query);

                        if (mysqli_num_rows($result_query) == 0) {
                            echo "<div class='no-results'>0 results found</div>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $item_id = $row['item_id'];
                                $item_name = $row['item_name'];
                                $item_price = $row['item_price'];
                                $item_description = $row['item_description'];
                                $item_quantity = $row['item_quantity'];
                                $category_name = $row['category_name'];
                                $item_image1 = $row['item_image1'];
                                $artist_name = $row['artist_name'];
                        ?>
                                <div class='box'>
                                    <div class='icons'>
                                        <a href='#' class='fas fa-eye' onclick='handleImageClick(<?php echo $item_id; ?>)'></a>
                                    </div>
                                    <div class='image'>
                                        <img src='item_images/<?php echo $item_image1; ?>' alt='' onclick='handleImageClick(<?php echo $item_id; ?>)'>
                                    </div>
                                    <div class='content'>
                                        <h3 class='artist'><?php echo $artist_name; ?></h3>
                                        <h3 class='marquee'><?php echo $item_name; ?></h3>
                                        <div class='price'>₱ <?php echo $item_price; ?></div>
                                        <?php if ($item_quantity > 0) { ?>
                                            <a href='product_details.php?item_id=<?php echo $item_id; ?>' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
                                        <?php } else { ?>
                                            <button class='btn' disabled style="cursor: not-allowed; background-color: gray; border-radius: 3px;"><i class='fa-solid fa-cart-plus'></i> Out of Stock</button>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
                $baseUrl = 'new_customer_shop.php?';

                $pageQuery = '';
                if (isset($_GET['search_button'])) {
                    $pageQuery = 'search_button&search=' . urlencode($_GET['search']);
                } elseif (isset($_GET['filter_button'])) {
                    if (isset($_GET['category'])) {
                        $pageQuery = 'filter_button&category=' . urlencode($_GET['category']);
                    } elseif (isset($_GET['artist'])) {
                        $pageQuery = 'filter_button&artist=' . urlencode($_GET['artist']);
                    }
                }

                $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalItems / $itemsPerPage);

                $startPage = max(1, $pageNumber - 1);
                $endPage = min($totalPages, $pageNumber + 1);

                if ($pageNumber == 1) {
                    $startPage = 1;
                    $endPage = min(3, $totalPages);
                } elseif ($pageNumber == $totalPages) {
                    $startPage = max(1, $totalPages - 2);
                    $endPage = $totalPages;
                }

                echo "<div class='pagination'>";

                $prevPage = max(1, $pageNumber - 1);
                echo "<a href='{$baseUrl}page=$prevPage&$pageQuery' class='pagination-link' " . ($pageNumber <= 1 ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">&laquo; Previous</a>";

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $linkClass = $i == $pageNumber ? 'pagination-link current-page' : 'pagination-link';
                    echo "<a href='{$baseUrl}page=$i&$pageQuery' class='$linkClass'>$i</a>";
                }

                $nextPage = min($totalPages, $pageNumber + 1);
                echo "<a href='{$baseUrl}page=$nextPage&$pageQuery' class='pagination-link' " . ($pageNumber >= $totalPages ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">Next &raquo;</a>";

                echo "</div>";
                ?>
            </section>



            <script>
                function clearSearch() {
                    document.getElementsByName('category')[0].selectedIndex = 0;
                    document.getElementsByName('artist')[0].selectedIndex = 0;
                    window.location.href = 'new_customer_shop.php'; // Reload the page
                }
                document.addEventListener('DOMContentLoaded', function() {
                    const searchForm = document.querySelector('.search-form');
                    const searchBtn = document.querySelector('#search-btn');

                    searchBtn.addEventListener('click', function() {
                        searchForm.classList.toggle('active');
                    });

                    window.addEventListener('scroll', function() {
                        searchForm.classList.remove('active');
                        const header2 = document.querySelector('.header .header-2');
                        if (window.scrollY > 80) {
                            header2.classList.add('active');
                        } else {
                            header2.classList.remove('active');
                        }
                    });

                    if (window.scrollY > 80) {
                        document.querySelector('.header .header-2').classList.add('active');
                    }
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const itemNames = document.querySelectorAll('.marquee');

                    itemNames.forEach(itemName => {
                        if (itemName.scrollWidth > itemName.clientWidth) {
                            itemName.classList.add('marquee');
                        } else {
                            itemName.classList.remove('marquee');
                        }
                    });
                });

                function handleImageClick(itemId) {
                    // Construct the URL for the product details page
                    var url = "product_details.php?item_id=" + itemId;
                    // Redirect the user to the product details page
                    window.location.href = url;
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const checkbox = document.getElementById('click');
                    const bestToHide = document.getElementById('best');
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            bestToHide.style.display = 'none';
                        } else {
                            bestToHide.style.display = 'block';
                        }
                    });
                });
            </script>


            <script>
                // JavaScript to toggle the popup and overlay
                document.addEventListener("DOMContentLoaded", function() {
                    var filterPopup = document.getElementById("filterPopup");
                    var filterPopupBtn = document.getElementById("filterPopupBtn");
                    var overlay = document.getElementById("filter-overlay");

                    filterPopupBtn.addEventListener("click", function() {
                        filterPopup.classList.toggle("show");
                        overlay.style.display = filterPopup.classList.contains("show") ? "block" : "none";
                    });
                });

                function validateSearch() {
                    var searchBox = document.getElementById('search-box');
                    if (searchBox.value.trim() === '') {
                        return false;
                    }
                    return true;
                }
            </script>



            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

    </body>

    </html>