<?php

require('../database/db_yeokart.php');

if (isset($_GET['addressId'])) {
    $addressId = $_GET['addressId'];

    $sql = "SELECT * FROM addresses WHERE address_id = ?";
    $stmt = $con->prepare($sql);

    $stmt->bind_param("i", $addressId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $addressDetails = $result->fetch_assoc();

        echo json_encode([
            'status' => 'success',
            'address_id' => $addressDetails['address_id'],
            'address' => $addressDetails['address'],
            'street' => $addressDetails['street'],
            'city' => $addressDetails['city'],
            'province' => $addressDetails['province'],
            'zipCode' => $addressDetails['zipCode'],
            'phoneNumber' => $addressDetails['phoneNumber'],
            'is_default' => $addressDetails['is_default']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Address not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Address ID not provided.']);
}

$con->close();
?>
