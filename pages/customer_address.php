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
        function openDeletePopup(addressId) {
            document.getElementById("deletePopup").style.display = "block";
            document.getElementById("deletePopup").setAttribute("data-addressId", addressId);
        }

        function closeDeletePopup() {
            document.getElementById("deletePopup").style.display = "none";
        }

        function confirmDeletion() {
            var addressId = document.getElementById("deletePopup").getAttribute("data-addressId");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_address_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status == 200) {
                    alert(xhr.responseText);
                    closeDeletePopup();
                    location.reload();
                } else {
                    alert("Error deleting address.");
                }
            };

            xhr.send("addressId=" + addressId);
        }


        function openPopup() {
            document.getElementById("popup").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        function openEditPopup(addressId) {
            document.getElementById("editPopup").style.display = "block";
            fetchAddressDetails(addressId);
        }

        function fetchAddressDetails(addressId) {
            fetch(`fetch_address_details.php?addressId=${addressId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(addressDetails => {
                document.getElementById("editAddressId").value = addressDetails.address_id;
                document.getElementById("editAddress").value = addressDetails.address;
                document.getElementById("editStreet").value = addressDetails.street;
                document.getElementById("editCity").value = addressDetails.city;
                document.getElementById("editProvince").value = addressDetails.province;
                document.getElementById("editZipCode").value = addressDetails.zipCode;
                document.getElementById("editPhoneNumber").value = addressDetails.phoneNumber;

                var defaultAddressCheckbox = document.getElementById("defaultAddress");
                var defaultAddressContainer = document.getElementById("defaultAddressCheckboxContainer");
                if (addressDetails.is_default == 1) {
                    if (defaultAddressCheckbox) defaultAddressCheckbox.checked = true;
                    if (defaultAddressContainer) defaultAddressContainer.style.display = "none";
                } else {
                    if (defaultAddressCheckbox) defaultAddressCheckbox.checked = false;
                    if (defaultAddressContainer) defaultAddressContainer.style.display = "block";
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }

        function closeEditPopup() {
            document.getElementById("editPopup").style.display = "none";
        }
    </script>

    
<?php
    require('../database/db_yeokart.php');
    session_start();

    if(isset($_SESSION['id'])) {
        $customer_id = $_SESSION['id'];
    } else {
        header("Location: login_page.php");
        exit();
    }

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {      
        $customer_id = $_SESSION['id'];
        $address = $_POST['address'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $zipCode = $_POST['zipCode'];
        $phoneNumber = $_POST['phoneNumber'];
    
        $isDefault = isset($_POST['defaultAddress']) ? 1 : 0;

        if ($isDefault === 1) {
            $resetSql = "UPDATE addresses SET is_default = 0 WHERE customer_id = ?";
            $resetStmt = $con->prepare($resetSql);
            $resetStmt->execute([$customer_id]);
        }

        $sql = "INSERT INTO addresses (customer_id, address, street, city, province, zipCode, phoneNumber, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->execute([$customer_id, $address, $street, $city, $province, $zipCode, $phoneNumber, $isDefault]);

        header("Location: customer_address.php");
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
                <a href="customer_profile.php" id="user-btn" class="fas fa-user"></a>
            </div>
        </div>
    </header>
  <section class="user-profile">
      <div class="header-3">
        <h2>My Account</h2>
        <div class="nav-user">
            <a href="#" class="btn-address" onclick="openPopup()">
                <i class="fas fa-plus"></i>
                <span class="text">ADD A NEW ADDRESS</span>
            </a>
        </div>
      </div>
      <hr>
    <div class="address">
        <div class="btn-return">
            <a href="customer_profile.php" class="btn-address">
                <i class="fas fa-arrow-left"></i>
                <span class="text">RETURN TO PROFILE</span>
            </a>
        </div>
        <div class="address-info">
            <p id="info">YOUR ADDRESSES<p>
            <hr class="gradient">
        </div>
        <div class="address-list">
        <?php
            require('../database/db_yeokart.php');

            $sql = "SELECT * FROM addresses WHERE customer_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='address-item'>";
                    if ($row['is_default'] == 1) {
                        echo "<h3>(Default)</h3>"; 
                    }
                    echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                    echo "<p><strong>Street:</strong> " . htmlspecialchars($row['street']) . "</p>";
                    echo "<p><strong>City:</strong> " . htmlspecialchars($row['city']) . "</p>";
                    echo "<p><strong>Province:</strong> " . htmlspecialchars($row['province']) . "</p>";
                    echo "<p><strong>Zip Code:</strong> " . htmlspecialchars($row['zipCode']) . "</p>";
                    echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($row['phoneNumber']) . "</p>";
                    echo "<div class='buttons-container'>";
                    echo "<button class='edit-btn' onclick='openEditPopup(" . $row['address_id'] . ")'>Edit</button>";
                    echo "<button class='delete-btn' onclick='openDeletePopup(" . $row['address_id'] . ")'>Delete</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p id='info1'>No addresses found.</p>";
            }

            $stmt->close();
            $con->close();
            ?>
            
        </div>
    </div>
  </section>

    <div id="deletePopup" class="popup-del" style="display: none;">
        <div class="popup-del-content">
            <span class="close" onclick="closeDeletePopup()">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this address?</p>
            <button class="btn-confirm" onclick="confirmDeletion()">Delete</button>
            <button class="btn-cancel" onclick="closeDeletePopup()">Cancel</button>
        </div>
    </div>

    <div id="editPopup" class="popup-add" style="display: none;">
        <div class="popup-add-content">
            <span class="close" onclick="closeEditPopup()">&times;</span>
            <h2>Edit Address</h2>
            <form action="edit_address_process.php" method="post" id="editAddressForm">
                <input type="hidden" id="editAddressId" name="addressId">
                <div class="form-group">
                    <label for="editAddress">Address:</label>
                    <input type="text" id="editAddress" name="address" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editStreet">Street:</label>
                    <input type="text" id="editStreet" name="street" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editCity">City:</label>
                    <input type="text" id="editCity" name="city" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editProvince">Province:</label>
                    <select id="editProvince" name="province" class="form-control" required>
                    <option value="">Select Province</option>
                        <option value="Abra">Abra</option>
                        <option value="Agusan del Norte">Agusan del Norte</option>
                        <option value="Agusan del Sur">Agusan del Sur</option>
                        <option value="Aklan">Aklan</option>
                        <option value="Albay">Albay</option>
                        <option value="Antique">Antique</option>
                        <option value="Apayao">Apayao</option>
                        <option value="Aurora">Aurora</option>
                        <option value="Basilan">Basilan</option>
                        <option value="Bataan">Bataan</option>
                        <option value="Batanes">Batanes</option>
                        <option value="Batangas">Batangas</option>
                        <option value="Benguet">Benguet</option>
                        <option value="Biliran">Biliran</option>
                        <option value="Bohol">Bohol</option>
                        <option value="Bukidnon">Bukidnon</option>
                        <option value="Bulacan">Bulacan</option>
                        <option value="Cagayan">Cagayan</option>
                        <option value="Camarines Norte">Camarines Norte</option>
                        <option value="Camarines Sur">Camarines Sur</option>
                        <option value="Camiguin">Camiguin</option>
                        <option value="Capiz">Capiz</option>
                        <option value="Catanduanes">Catanduanes</option>
                        <option value="Cavite">Cavite</option>
                        <option value="Cebu">Cebu</option>
                        <option value="Cotabato">Cotabato</option>
                        <option value="Davao del Norte">Davao del Norte</option>
                        <option value="Davao del Sur">Davao del Sur</option>
                        <option value="Davao Occidental">Davao Occidental</option>
                        <option value="Davao Oriental">Davao Oriental</option>
                        <option value="Dinagat Islands">Dinagat Islands</option>
                        <option value="Eastern Samar">Eastern Samar</option>
                        <option value="Guimaras">Guimaras</option>
                        <option value="Ifugao">Ifugao</option>
                        <option value="Ilocos Norte">Ilocos Norte</option>
                        <option value="Ilocos Sur">Ilocos Sur</option>
                        <option value="Iloilo">Iloilo</option>
                        <option value="Isabela">Isabela</option>
                        <option value="Kalinga">Kalinga</option>
                        <option value="La Union">La Union</option>
                        <option value="Laguna">Laguna</option>
                        <option value="Lanao del Norte">Lanao del Norte</option>
                        <option value="Lanao del Sur">Lanao del Sur</option>
                        <option value="Leyte">Leyte</option>
                        <option value="Maguindanao">Maguindanao</option>
                        <option value="Marinduque">Marinduque</option>
                        <option value="Masbate">Masbate</option>
                        <option value="Metro Manila">Metro Manila</option>
                        <option value="Misamis Occidental">Misamis Occidental</option>
                        <option value="Misamis Oriental">Misamis Oriental</option>
                        <option value="Mountain Province">Mountain Province</option>
                        <option value="Negros Occidental">Negros Occidental</option>
                        <option value="Negros Oriental">Negros Oriental</option>
                        <option value="Northern Samar">Northern Samar</option>
                        <option value="Nueva Ecija">Nueva Ecija</option>
                        <option value="Nueva Vizcaya">Nueva Vizcaya</option>
                        <option value="Occidental Mindoro">Occidental Mindoro</option>
                        <option value="Oriental Mindoro">Oriental Mindoro</option>
                        <option value="Palawan">Palawan</option>
                        <option value="Pampanga">Pampanga</option>
                        <option value="Pangasinan">Pangasinan</option>
                        <option value="Quezon">Quezon</option>
                        <option value="Quirino">Quirino</option>
                        <option value="Rizal">Rizal</option>
                        <option value="Romblon">Romblon</option>
                        <option value="Samar">Samar</option>
                        <option value="Sarangani">Sarangani</option>
                        <option value="Siquijor">Siquijor</option>
                        <option value="Sorsogon">Sorsogon</option>
                        <option value="South Cotabato">South Cotabato</option>
                        <option value="Southern Leyte">Southern Leyte</option>
                        <option value="Sultan Kudarat">Sultan Kudarat</option>
                        <option value="Sulu">Sulu</option>
                        <option value="Surigao del Norte">Surigao del Norte</option>
                        <option value="Surigao del Sur">Surigao del Sur</option>
                        <option value="Tarlac">Tarlac</option>
                        <option value="Tawi-Tawi">Tawi-Tawi</option>
                        <option value="Zambales">Zambales</option>
                        <option value="Zamboanga del Norte">Zamboanga del Norte</option>
                        <option value="Zamboanga del Sur">Zamboanga del Sur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editZipCode">Zip Code:</label>
                    <input type="text" id="editZipCode" name="zipCode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editPhoneNumber">Phone Number:</label>
                    <input type="number" id="editPhoneNumber" name="phoneNumber" class="form-control" required>
                </div>

                <div id="defaultAddressCheckboxContainer" class="form-group">
                    <label class="custom-checkbox">Set as default address
                        <input type="checkbox" id="defaultAddress" name="defaultAddress">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <input type="hidden" id="defaultAddressHidden" name="defaultAddressHidden" value="0">

                <button type="submit" class="addr-btn">Save Changes</button>
            </form>
        </div>
    </div>


  <div id="popup" class="popup-add">
    <div class="popup-add-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Add New Address</h2>
        <form action="#" method="post" id="addressForm">
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="street">Street:</label>
                <input type="text" id="street" name="street" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="province">Province:</label>
                <select id="province" name="province" class="form-control" required>
                    <option value="">Select Province</option>
                    <option value="Abra">Abra</option>
                    <option value="Agusan del Norte">Agusan del Norte</option>
                    <option value="Agusan del Sur">Agusan del Sur</option>
                    <option value="Aklan">Aklan</option>
                    <option value="Albay">Albay</option>
                    <option value="Antique">Antique</option>
                    <option value="Apayao">Apayao</option>
                    <option value="Aurora">Aurora</option>
                    <option value="Basilan">Basilan</option>
                    <option value="Bataan">Bataan</option>
                    <option value="Batanes">Batanes</option>
                    <option value="Batangas">Batangas</option>
                    <option value="Benguet">Benguet</option>
                    <option value="Biliran">Biliran</option>
                    <option value="Bohol">Bohol</option>
                    <option value="Bukidnon">Bukidnon</option>
                    <option value="Bulacan">Bulacan</option>
                    <option value="Cagayan">Cagayan</option>
                    <option value="Camarines Norte">Camarines Norte</option>
                    <option value="Camarines Sur">Camarines Sur</option>
                    <option value="Camiguin">Camiguin</option>
                    <option value="Capiz">Capiz</option>
                    <option value="Catanduanes">Catanduanes</option>
                    <option value="Cavite">Cavite</option>
                    <option value="Cebu">Cebu</option>
                    <option value="Cotabato">Cotabato</option>
                    <option value="Davao del Norte">Davao del Norte</option>
                    <option value="Davao del Sur">Davao del Sur</option>
                    <option value="Davao Occidental">Davao Occidental</option>
                    <option value="Davao Oriental">Davao Oriental</option>
                    <option value="Dinagat Islands">Dinagat Islands</option>
                    <option value="Eastern Samar">Eastern Samar</option>
                    <option value="Guimaras">Guimaras</option>
                    <option value="Ifugao">Ifugao</option>
                    <option value="Ilocos Norte">Ilocos Norte</option>
                    <option value="Ilocos Sur">Ilocos Sur</option>
                    <option value="Iloilo">Iloilo</option>
                    <option value="Isabela">Isabela</option>
                    <option value="Kalinga">Kalinga</option>
                    <option value="La Union">La Union</option>
                    <option value="Laguna">Laguna</option>
                    <option value="Lanao del Norte">Lanao del Norte</option>
                    <option value="Lanao del Sur">Lanao del Sur</option>
                    <option value="Leyte">Leyte</option>
                    <option value="Maguindanao">Maguindanao</option>
                    <option value="Marinduque">Marinduque</option>
                    <option value="Masbate">Masbate</option>
                    <option value="Metro Manila">Metro Manila</option>
                    <option value="Misamis Occidental">Misamis Occidental</option>
                    <option value="Misamis Oriental">Misamis Oriental</option>
                    <option value="Mountain Province">Mountain Province</option>
                    <option value="Negros Occidental">Negros Occidental</option>
                    <option value="Negros Oriental">Negros Oriental</option>
                    <option value="Northern Samar">Northern Samar</option>
                    <option value="Nueva Ecija">Nueva Ecija</option>
                    <option value="Nueva Vizcaya">Nueva Vizcaya</option>
                    <option value="Occidental Mindoro">Occidental Mindoro</option>
                    <option value="Oriental Mindoro">Oriental Mindoro</option>
                    <option value="Palawan">Palawan</option>
                    <option value="Pampanga">Pampanga</option>
                    <option value="Pangasinan">Pangasinan</option>
                    <option value="Quezon">Quezon</option>
                    <option value="Quirino">Quirino</option>
                    <option value="Rizal">Rizal</option>
                    <option value="Romblon">Romblon</option>
                    <option value="Samar">Samar</option>
                    <option value="Sarangani">Sarangani</option>
                    <option value="Siquijor">Siquijor</option>
                    <option value="Sorsogon">Sorsogon</option>
                    <option value="South Cotabato">South Cotabato</option>
                    <option value="Southern Leyte">Southern Leyte</option>
                    <option value="Sultan Kudarat">Sultan Kudarat</option>
                    <option value="Sulu">Sulu</option>
                    <option value="Surigao del Norte">Surigao del Norte</option>
                    <option value="Surigao del Sur">Surigao del Sur</option>
                    <option value="Tarlac">Tarlac</option>
                    <option value="Tawi-Tawi">Tawi-Tawi</option>
                    <option value="Zambales">Zambales</option>
                    <option value="Zamboanga del Norte">Zamboanga del Norte</option>
                    <option value="Zamboanga del Sur">Zamboanga del Sur</option>
                </select>

            </div>

            <div class="form-group">
                <label for="zipCode">Zip Code:</label>
                <input type="text" id="zipCode" name="zipCode" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="number" id="phoneNumber" name="phoneNumber" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="custom-checkbox">Set as default address
                    <input type="checkbox" id="defaultAddress" name="defaultAddress">
                    <span class="checkmark"></span>
                </label>
            </div>

            <button type="submit" class="addr-btn">Add Address</button>
        </form>
    </div>
</div>
</body>
</html>
