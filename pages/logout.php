<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    
    header("Location: public_customer_homepage.php");
    exit();
?>
