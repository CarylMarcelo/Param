<?php
    session_start();
    unset($_SESSION['islogged']);
    unset($_SESSION['name']);
    session_destroy();
    header('Location: login.php');
    exit();
?>