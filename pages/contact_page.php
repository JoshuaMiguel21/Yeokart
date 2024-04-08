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

<body>
    <input type="checkbox" id="click">
    <header class="header">
        <div class="header-1">
            <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <div class="icons">
                <form action="new_customer_shop.php" method="GET" class="search-form">
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
                        <form action="new_customer_shop.php" method="GET" class="search-form">
                            <input type="search" name="search" placeholder="Search here..." id="search-box">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li class="home-class"><a href="customer_homepage.php" id="home-nav">Home</a></li>
                    <li><a href="new_customer_shop.php">Shop</a></li>
                    <li><a href="contact_page.php" class="active">Contact Us</a></li>
                    <li><a href="customer_cart.php"><i class="fas fa-shopping-cart"><span id="cart-num"><?php echo $cartCount; ?></span></i></a></li>
                    <li><a href="customer_profile.php" id="user-btn"><i class="fas fa-user"></i></a></li>
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
                $select_query = $select_query = "SELECT * FROM contacts";
                $result_query = mysqli_query($con, $select_query);
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $contacts_id = $row['contacts_id'];
                    $contacts_name = $row['contacts_name'];
                    $icon_link = $row['icon_link'];
                    $contacts_description = $row['contacts_description'];

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

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('click');
            const contactsToHide = document.getElementById('contacts'); // Change 'home' to the ID of the section you want to hide/show

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    contactsToHide.style.display = 'none';
                } else {
                    contactsToHide.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>