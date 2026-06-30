<?php
    session_start();
    
    $errors = array();

    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];

        if(!checkname($name)){
            $errors[] = "Invalid character in name...";
            
        }

        if(!checkemail($email)){
            $errors[] = "Invalid email address...";
        }

        if(!checkpassword($password)) {
            $errors[] = "Password must be 8 characters or more...";
        }

        if(!checkconfirmpassword($password, $confirmpassword)) {
            $errors[] = "Passwords does not match...";
        }

        if(!empty($errors)){
            $_SESSION['errors'] = $errors;
            header('Location: signup.php');
        } else {
            // STORE DATA TO DATABASE IF ALL IS OK
            $_SESSION['success'] = "Signup successful, you may now login...";
            header('Location: login.php');
        }
    }

    function checkname($name) {
        $pattern = "/^[A-Za-z\.\s-]+$/";
        if(preg_match($pattern, $name)){
            return true;
        }
        return false;
    }

    function checkemail($email) {
        $pattern = "/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if(preg_match($pattern, $email)){
            return true;
        }
        return false;
    }

    function checkpassword($password) {
        if(strlen($password) >= 8){
            return true;
        }
        return false;
    }

    function checkconfirmpassword($password, $confirmpassword) {
        if($password == $confirmpassword){
            return true;
        }
        return false;
    }
?>