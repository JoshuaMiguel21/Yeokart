<?php
$hostname = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "yeokart_database";

$con = mysqli_connect($hostname, $dbUser, $dbPassword, $dbName);

if (mysqli_connect_error()) {
    echo "<script>alert('Cannot connect to the database');</script>";
}
