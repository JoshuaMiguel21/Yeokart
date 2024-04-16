<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/contacts.css">
</head>


<body>
    <div class="overlay" id="overlay"></div>

    <div class="access-popup" id="access-popup">
        <a href="#" class="access-close" id="access-close-popup">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="10px" viewBox="215.186 215.671 80.802 80.8" enable-background="new 215.186 215.671 80.802 80.8" xml:space="preserve">
                <polygon fill="#FFFFFF" points="280.486,296.466 255.586,271.566 230.686,296.471 215.19,280.964 240.086,256.066 215.186,231.17 
230.69,215.674 255.586,240.566 280.475,215.671 295.985,231.169 271.987,256.064 295.987,280.96 " />
            </svg>
        </a>

        <div class="valid2">
            <!-- Shopping cart GIF icon -->
            <img src="../res/unlock-gif.gif" alt="Shopping Cart" width="40px" height="40px">
        </div>

        <h1>Interested to see more?</h1>
        <p>Please log in or sign up to continue.</p>
        <div class="bottom-popup">
            <a class="start" href="login_page.php">Login / Sign Up</a>
        </div>
    </div>
    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="public_customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="customer_shop.php" method="GET" class="search-form" onsubmit="return validateSearch()">
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
                        <form action="customer_shop.php" method="GET" class="search-form1">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="public_customer_shop.php">Shop</a></li>
                    <li><a href="public_contact_page.php" class="active">Contact Us</a></li>
                    <li><a href="#"><i class="fas fa-shopping-cart"></i></a></li>
                    <li><a href="#" id="user-btn"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
        </div>
    </header>
    <section class="contacts" id="contacts">
        <div class="container">
            <h1 class="heading"><span>Contact Us</span></h1>
            <br></br>

            <div class="box-container">
                <?php
                include('../database/db_yeokart.php');
                $select_query = "SELECT * FROM contacts";
                $result_query = mysqli_query($con, $select_query);

                if (mysqli_num_rows($result_query) > 0) {
                    while ($row = mysqli_fetch_assoc($result_query)) {
                        $contacts_id = $row['contacts_id'];
                        $contacts_name = $row['contacts_name'];
                        $icon_link = $row['icon_link'];
                        $contacts_description = $row['contacts_description'];

                        if ($icon_link !== "<i class='fa-solid fa-peso-sign'></i>") {
                            if (filter_var($contacts_description, FILTER_VALIDATE_URL)) {
                                $contacts_link = "<a href='$contacts_description' target='_blank'>$contacts_description</a>";
                            } else {
                                $contacts_link = $contacts_description;
                            }

                            echo "<div class='box'>
                            <div class='iconbox'>
                                $icon_link
                            </div>
                            <h3>$contacts_name</h3>
                            <p>$contacts_link</p>
                        </div>";
                        }
                    }
                } else {
                    echo "<h1>No contacts found.</h1>";
                }
                ?>
            </div>
        </div>
    </section>
    <script>
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

        function validateSearch() {
            var searchBox = document.getElementById('search-box');
            if (searchBox.value.trim() === '') {
                return false;
            }
            return true;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartBtn = document.querySelector('.fa-shopping-cart');
            const userBtn = document.querySelector('.fas.fa-user');
            const popup = document.getElementById('access-popup');
            const accessClosePopup = document.getElementById('access-close-popup');
            const overlay = document.getElementById('overlay'); // Get the overlay element

            cartBtn.addEventListener('click', function(event) {
                event.preventDefault();
                popup.style.display = 'block';
                overlay.style.display = 'block'; // Display the overlay
            });

            userBtn.addEventListener('click', function(event) {
                event.preventDefault();
                popup.style.display = 'block';
                overlay.style.display = 'block'; // Display the overlay
            });

            accessClosePopup.addEventListener('click', function(event) {
                event.preventDefault();
                popup.style.display = 'none';
                overlay.style.display = 'none'; // Hide the overlay
            });

            // Optional: Hide popup and overlay when clicking outside of the popup
            overlay.addEventListener('click', function() {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            });
        });
    </script>

</body>

</html>