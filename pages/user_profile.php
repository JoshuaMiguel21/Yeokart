<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeokart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_homepage_customer.css">
</head>
<script>
    function openPopup() {
        document.getElementById('editProfilePopup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('editProfilePopup').style.display = 'none';
    }

    document.querySelector('.nav-user .logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        openPopup(); 
    });

    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }
</script>

<?php
    session_start();

    if(isset($_SESSION['firstname'])) {
        $firstname = $_SESSION['firstname'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if(isset($_SESSION['lastname'])) {
        $lastname = $_SESSION['lastname'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        header("Location: login_page.php");
        exit();
    }

    if(isset($_SESSION['email'])) {
        $email = strtolower($_SESSION['email']);
    } else {
        header("Location: login_page.php");
        exit();
    }


?>


<body>
    <header class="header" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="header-1">
            <a href="customer_homepage.php" class="button-image"><img src="../res/logo.png" alt="Yeokart Logo" class="logo"></a>
            <form action="" class="search-form">
                <input type="search" name="" placeholder="Search here..." id="search-box">
                <label for="search-box" class="fas fa-search"></label>
            </form>
            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="#">Shop</a>
                <a href="#" class="fas fa-shopping-cart"></a>
                <div id="user-btn" class="fas fa-user"></div>
            </div>
        </div>
    </header>
  <section class="user-profile">

      <div id="editProfilePopup" class="popup-container" style="display: none;">
          <div class="popup-content">
              <span class="close-btn" onclick="closePopup()">&times;</span>
              <h3>Edit Profile</h3>
              <form id="editProfileForm" action="update_profile.php" method="POST">
                  <div class="form-group">
                      <label for="editFirstname">First Name</label>
                      <input type="text" id="editFirstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                  </div>
                  <div class="form-group">
                      <label for="editLastname">Last Name</label>
                      <input type="text" id="editLastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                  </div>
                  <div class="form-group">
                      <label for="editUsername">Username</label>
                      <input type="text" id="editUsername" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                  </div>
                  <button type="submit" class="update-btn">Save Changes</button>
              </form>
          </div>
      </div>

      <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
          <div class="popup-content">
              <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
              <p>Are you sure you want to logout?<p>
              <div class="logout-btns">
                  <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                  <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
              </div>
          </div>
      </div>

      <div class="header-3">
        <h2>My Account</h2>
        <div class="nav-user">
          <a href="#" onclick="openPopup(); return false;" class="logout-link">EDIT PROFILE</a>
          <a href="#" onclick="openLogoutPopup(); return false;" class="logout-link">LOG OUT<i class="fas fa-chevron-right"></i></a> 
        </div>
      </div>
      <hr>
    <div class="container">
      <div class="left-column">
        <p>ORDER HISTORY<p>
        <hr class="gradient">
      </div>
      
      <div class="right-column">

        <div class="account">
          <p>ACCOUNT DETAILS<p>
          <hr class="gradient">
            <div class="account-info">
              <p><?php echo $username;?></p>
            </div>
            <div class="account-info">
              <i class="fas fa-user-circle"></i>
              <p><?php echo $firstname ." ". $lastname;?></p>
            </div>
            <div class="account-info">
              <i class="fas fa-envelope"></i>
              <p><?php echo $email;?></p>
            </div>
        </div>
        
        <div class="Address">

        </div>
      </div>    
    </div>
  </section>
</body>
</html>
