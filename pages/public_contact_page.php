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

    <input type="checkbox" id="click">
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="public_customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
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
                    <li class="home-class"><a href="public_customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="public_customer_shop.php">Shop</a></li>
                    <li><a href="public_faq.php">FAQ</a></li>
                    <li><a href="public_contact_page.php" class="active">Contact Us</a></li>
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
                            $contacts_link = $contacts_description;
                            if (filter_var($contacts_description, FILTER_VALIDATE_URL)) {
                                $contacts_link = "<a href='$contacts_description' target='_blank'>$contacts_description</a>";
                            }

                            echo "<div class='box' onclick='redirectToURL(\"$contacts_description\")'>
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

        function redirectToURL(url) {
            if (isValidURL(url)) {
                window.open(url, '_blank');
            }
        }

        function isValidURL(url) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!pattern.test(url);
        }
    </script>


</body>

</html>