<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Yeokart</title>
    <link rel="icon" type="image/png" href="../res/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/contacts.css">
</head>

<?php
    require('../database/db_yeokart.php');

// Fetch FAQ data from database
$faq_query = "SELECT * FROM faqs ORDER BY created_at ASC";
$faq_result = $con->query($faq_query);

$faqs = [];
if ($faq_result->num_rows > 0) {
    while($faq = $faq_result->fetch_assoc()) {
        $faqs[] = $faq;
    }
}

?>
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
                        <form action="customer_shop.php" method="GET" class="search-form1">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="public_customer_shop.php">Shop</a></li>
                    <li><a href="public_faq.php" class="active">FAQ</a></li>
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
    </header>
    <section class="faq" id="faq">
        <div class="container">
            <h1 class="heading"><span>Frequently Asked Questions</span></h1>
            <br></br>
            <h3 class="faq-h3">Welcome to YeoKart! Below are answers to some common questions you may have about our store and our products.</h3>
            <br></br>
            <div class="faq-content">
                <?php foreach ($faqs as $faq): ?>
                    <div class="faq-question">
                        <input id="q<?= $faq['faq_id']; ?>" type="checkbox" class="panel">
                        <div class="plus">+</div>
                        <label for="q<?= $faq['faq_id']; ?>" class="panel-title"><?= htmlspecialchars($faq['question']); ?></label>
                        <div class="panel-content"><?= nl2br(htmlspecialchars($faq['answer'])); ?></div>
                    </div>
                <?php endforeach; ?>
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


</body>

</html>