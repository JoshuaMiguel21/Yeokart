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
include('../database/db_yeokart.php');

// Fetching categories from the products table
$select_categories = "SELECT DISTINCT category_name FROM products";
$result_categories = mysqli_query($con, $select_categories);

// Fetching artists from the products table
$select_artists = "SELECT DISTINCT artist_name FROM products";
$result_artists = mysqli_query($con, $select_artists);

?>


<body>
    <!-- Overlay for translucent background of popup -->
    <div class="filter-overlay" id="filter-overlay"></div>

    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="index.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="public_customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
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
                        <form action="public_customer_shop.php" method="GET" class="search-form1">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="index.php" id="home-nav">Home</a></li>
                    <li><a href="public_customer_shop.php" class="active">Shop</a></li>
                    <li><a href="public_faq.php">FAQ</a></li>
                    <li><a href="public_contact_page.php">Contact Us</a></li>
                    <button class="sign-in-button" onclick="window.location.href='login_page.php';">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
                        </svg>


                        <div class="sign-in-btn-text">
                            Sign In
                        </div>

                    </button>
                </ul>
            </div>
        </div>
        <div class="header-2">
            <nav class="navbar">
                <div class="filter-button-container">
                    <button id="filterButton" onclick="toggleFilterSection()">Filter & Sort <span id="arrowIcon">&#x25BC;</span></button>
                </div>
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

                    <form method="GET">
                        <h3>Sort By Price</h3>
                        <select name="price_order">
                            <option value="" disabled selected>Sort By</option>
                            <option value="low_to_high" <?php if (isset($_GET['price_order']) && $_GET['price_order'] == 'low_to_high') echo 'selected'; ?>>Price: Low to High</option>
                            <option value="high_to_low" <?php if (isset($_GET['price_order']) && $_GET['price_order'] == 'high_to_low') echo 'selected'; ?>>Price: High to Low</option>
                        </select>
                        <button type="submit" name="filter_button">Sort</button>
                    </form>


                    <form method="GET" id="clear">
                        <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                    </form>
                </div>
            </nav>
        </div>

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

                    <form method="GET">
                        <h2>Sort By Price</h2>
                        <select name="price_order">
                            <option value="" disabled selected>Sort By</option>
                            <option value="low_to_high">Price: Low to High</option>
                            <option value="high_to_low">Price: High to Low</option>
                        </select>
                        <button type="submit" name="filter_button">Sort</button>
                    </form>


                </div>


                <form method="GET" id="clear">
                    <button type="button" name="clear_button" onclick="clearSearch()">Clear</button>
                </form>

            </div>
        </nav>
        <section class="best" id="best">
            <h1 class="heading"><span>Shop</span></h1>
            <?php
            $selectedFilters = [];
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = htmlspecialchars($_GET['search']);
                $selectedFilters[] = "Search term: \"$searchTerm\"";
            }
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = htmlspecialchars($_GET['category']);
                $selectedFilters[] = "Category: \"$category\"";
            }
            if (isset($_GET['artist']) && !empty($_GET['artist'])) {
                $artist = htmlspecialchars($_GET['artist']);
                $selectedFilters[] = "Artist: \"$artist\"";
            }
            if (isset($_GET['price_order']) && ($_GET['price_order'] === 'low_to_high' || $_GET['price_order'] === 'high_to_low')) {
                $priceOrder = $_GET['price_order'] === 'low_to_high' ? 'Low to High' : 'High to Low';
                $selectedFilters[] = "Price Order: \"$priceOrder\"";
            }

            if (!empty($selectedFilters)) {
                echo "<h3>Showing results for " . implode(", ", $selectedFilters) . "</h3>";
            }
            ?>
            <div class="best-slider">
                <?php
                include('../database/db_yeokart.php');
                $itemsPerPage = 12;
                $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($pageNumber - 1) * $itemsPerPage;

                // Base query
                $select_query = "SELECT * FROM products WHERE is_archive = 0";

                // Apply search filter if search term is provided
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
                    $select_query .= " AND item_name LIKE '%$searchTerm%'"; // Use AND instead of WHERE here
                }

                // Apply category filter if selected
                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $category = mysqli_real_escape_string($con, $_GET['category']);
                    $select_query .= " AND category_name = '$category'";
                }

                // Apply artist filter if selected
                if (isset($_GET['artist']) && !empty($_GET['artist'])) {
                    $artist = mysqli_real_escape_string($con, $_GET['artist']);
                    $select_query .= " AND artist_name = '$artist'";
                }

                // Check if the form is submitted
                if (isset($_GET['price_order'])) {
                    $price_order = $_GET['price_order'];
                    if ($price_order === 'low_to_high') {
                        $select_query .= " ORDER BY item_price ASC";
                    } elseif ($price_order === 'high_to_low') {
                        $select_query .= " ORDER BY item_price DESC";
                    }
                }

                // Get total number of items for pagination
                $totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM ($select_query) AS subquery";
                $totalItemsResult = mysqli_query($con, $totalItemsQuery);
                $totalItemsRow = mysqli_fetch_assoc($totalItemsResult);
                $totalItems = $totalItemsRow['totalItems'];
                $totalPages = ceil($totalItems / $itemsPerPage);

                // Add limit and offset
                $select_query .= " LIMIT $itemsPerPage OFFSET $offset";

                $result_query = mysqli_query($con, $select_query);

                if (mysqli_num_rows($result_query) == 0) {
                    echo "
                                <div class='empty-shop-container'>
                                    <div class='no-results'>
                                        <div class='no-results-icon'>
                                            <i class='fas fa-store-slash'></i>
                                        </div>
                                        <div class='no-results-text'>
                                            <h2>Shop is Empty</h2>
                                            <p>There's nothing here... yet.</p>
                                        </div>
                                    </div>
                                </div>
                                ";

                    echo "<div class='wrapper empty-shop'>";
                } else {
                    echo "<div class='wrapper'>";
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
                        <center>
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
                                    <div class='price'>₱ <?php echo number_format($item_price, 2); ?></div>
                                    <?php if ($item_quantity > 0) { ?>
                                        <a href='public_product_details.php?item_id=<?php echo $item_id; ?>' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
                                    <?php } else { ?>
                                        <button class='btn' disabled style="cursor: not-allowed; background-color: gray; border-radius: 3px;"><i class='fa-solid fa-cart-plus'></i> Out of Stock</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </center>
                <?php
                    }
                }
                echo "</div>";
                ?>
            </div>
            </div>
            <?php
            $baseUrl = 'public_customer_shop.php?';

            $pageQuery = http_build_query(array_diff_key($_GET, array_flip(['page'])));

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
                window.location.href = 'public_customer_shop.php'; // Reload the page
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
                var url = "public_product_details.php?item_id=" + itemId;
                // Redirect the user to the product details page
                window.location.href = url;
            }
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

                cancelBtn.addEventListener("click", function() {
                    filterPopup.classList.remove("show");
                    overlay.style.display = "none";
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


        <script>
            function toggleFilterSection() {
                var filterSection = document.querySelector('.filter-section');
                var arrowIcon = document.getElementById('arrowIcon');

                if (filterSection.style.maxHeight) {
                    // If the max-height is set, hide the section
                    filterSection.style.maxHeight = null;
                    arrowIcon.innerHTML = '&#x25BC;'; // Change arrow to down
                } else {
                    // If the max-height is not set, show the section
                    filterSection.style.maxHeight = filterSection.scrollHeight + "px";
                    arrowIcon.innerHTML = '&#x25B2;'; // Change arrow to up
                }
            }
        </script>



        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

</body>

</html>