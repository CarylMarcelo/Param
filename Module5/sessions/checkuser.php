<?php
session_start();

if(isset($_POST['submit'])) {
    if($_POST['email'] == "admin@test.com" && $_POST['password'] == "password") {
        $_SESSION['name'] = "Joseph"; // FROM DATABASE SOON
        $_SESSION['islogged'] = true;
        header('Location: index.php');
    } else {
        $_SESSION['error'] = "Username and/or password incorrect...";
        header('Location: login.php');
    }
}
?>