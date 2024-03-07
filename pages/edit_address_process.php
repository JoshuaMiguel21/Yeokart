<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login_page.php");
    exit();
}

require('../database/db_yeokart.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressId = $_POST['addressId'];
    $address = $_POST['address'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zipCode = $_POST['zipCode'];
    $phoneNumber = $_POST['phoneNumber'];
    $isDefault = isset($_POST['defaultAddress']) ? 1 : 0;

    $con->begin_transaction();

    try {
        if ($isDefault === 1) {
            $resetSql = "UPDATE addresses SET is_default = 0 WHERE customer_id = ?";
            $resetStmt = $con->prepare($resetSql);
            $resetStmt->execute([$_SESSION['id']]);
        }

        $sql = "UPDATE addresses SET address = ?, street = ?, city = ?, province = ?, zipCode = ?, phoneNumber = ?, is_default = ? WHERE address_id = ? AND customer_id = ?";
        
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('ssssssiis', $address, $street, $city, $province, $zipCode, $phoneNumber, $isDefault, $addressId, $_SESSION['id']);
            
            if ($stmt->execute()) {
                // Commit transaction
                $con->commit();
                header("Location: customer_address.php");
                exit();
            } else {
                throw new Exception("Error updating record: " . $con->error);
            }
        } else {
            throw new Exception("Error preparing the query: " . $con->error);
        }
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $con->rollback();
        echo $e->getMessage();
    }

    if (isset($stmt)) {
        $stmt->close();
    }
}

$con->close();
?>
