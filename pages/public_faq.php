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
$faq_query = "SELECT * FROM faqs ORDER BY faq_id ASC";
$faq_result = $con->query($faq_query);

$faqs = [];
if ($faq_result->num_rows > 0) {
    while ($faq = $faq_result->fetch_assoc()) {
        $faqs[] = $faq;
    }
}

?>

<body>

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
                <?php foreach ($faqs as $faq) : ?>
                    <div class="faq-question">
                        <input id="q<?= $faq['faq_id']; ?>" type="checkbox" class="panel">
                        <div class="plus">+</div>
                        <label for="q<?= $faq['faq_id']; ?>" class="panel-title"><?= htmlspecialchars($faq['question']); ?></label>
                        <div class="panel-content"><?= nl2br(htmlspecialchars($faq['answer'])); ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="faq-question">
                    <input id="q" type="checkbox" class="panel">
                    <div class="plus">+</div>
                    <label for="q" class="panel-title">Terms & Conditions</label>
                    <div class="panel-content"><a href="#" id="terms">Terms and Conditions</a></div>
                </div>
            </div>
    </section>
    <div id="terms-popup" class="terms-popup terms-popup-content" style="display: none;">
        <article class="terms-container">
            <header class="terms-container-header">
                <h1 class="terms-container-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="var(--pink)" d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z" />
                    </svg>
                    Terms and Conditions
                </h1>
                <button class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path fill="currentColor" d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
                    </svg>
                </button>
            </header>
            <section class="terms-container-body rtf">
                <h2>Terms and Conditions for YeoKart K-Pop Merch Store
                </h2>

                <p>Welcome to YeoKart! These terms and conditions outline the rules and regulations for using YeoKart's Website, which specializes in selling Kpop merchandise, located at [yourwebsite.com].
                    By accessing this website, we assume you accept these terms and conditions. Do not continue shopping at YeoKart if you do not agree to take all of the terms and conditions stated on this page.
                </p>

                <h3>1. Product Descriptions</h3>

                <p>We strive to describe all merchandise listed on our website accurately. However, please note that these items may have minor flaws or imperfections due to the nature of these items. We provide detailed descriptions and images to give you a clear understanding of the condition of each item. </p>

                <h3>2. Authenticity</h3>

                <p>We guarantee the authenticity of all merchandise sold on our website. We source our products from reputable suppliers and conduct thorough inspections to ensure their authenticity. </p>

                <h3>3. Pricing</h3>

                <p>All prices listed on our website are in Philippine pesos (Php) and are inclusive of any applicable taxes. Prices are subject to change without prior notice. We reserve the right to modify or discontinue any product without liability to you or any third party.</p>

                <h3>4. Orders</h3>

                <p>By placing an order through YeoKart, you warrant that you are at least 18 years old or have obtained parental/guardian consent to make a purchase. Once an order is placed, you will receive details of your purchase.</p>

                <h3>5. Payment</h3>

                <p>Payment for orders must be made in full within 24 hours after creating an order. We accept payment via GCash only. To confirm payment, you must upload a screenshot of your transaction so that YeoKart can verify your payment. Your order will not be processed until payment has been received in full.</p>

                <h3>7. Returns</h3>

                <p>We offer shipping only to areas in the Philippines. Shipping costs and delivery times may vary depending on your location. Please refer to our Shipping Policy for more information.</p>

                <h3>8. Privacy</h3>

                <p>Your privacy is important to us. We collect and use personal information in accordance with our Privacy Policy. By using YeoKart, you consent to the collection and use of your personal information as described therein.</p>

                <h3>9. Limitation of Liability</h3>

                <p>In no event shall YeoKart, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from (i) your access to or use of or inability to access or use YeoKart; (ii) any conduct or content of any third party on YeoKart; (iii) any content obtained from YeoKart; and (iv) unauthorized access, use, or alteration of your transmissions or content, whether based on warranty, contract, tort (including negligence), or any other legal theory, whether or not we have been informed of the possibility of such damage.</p>

                <h3>10. Governing Law</h3>

                <p>These terms and conditions shall be governed by and construed in accordance with the laws of the Philippines, and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>

                <h3>11. Contact Us</h3>

                <p>If you have any questions or concerns about these terms and conditions, please contact us at Yeokartstore@gmail.com or on our other socials on our Contact Us page.</p>

                <br>
                <p>By using YeoKart, you agree to abide by these terms and conditions. Thank you for shopping with us!</p>
            </section>
            <footer class="terms-container-footer">
                <button class="button is-ghost">Decline</button>
                <button class="button is-primary">Accept</button>
            </footer>
        </article>
    </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to show the popup
            function showPopup() {
                $('#terms-popup').show();
            }

            // Function to hide the popup
            function hidePopup() {
                $('#terms-popup').hide();
            }

            // Add event listener to the "Terms and Conditions" link
            $('a[href="#"]').click(function(e) {
                e.preventDefault(); // Prevent the default action of the link
                showPopup(); // Show the popup
            });

            // Add event listener to the accept button
            $('.button.is-primary').click(function() {
                hidePopup(); // Hide the popup
            });

            // Add event listener to the icon button
            $('.icon-button').click(function() {
                hidePopup(); // Hide the popup
            });

            // Add event listener to the decline button
            $('.button.is-ghost').click(function() {
                hidePopup(); // Hide the popup
            });
        });
    </script>

</body>

</html>