<?php
    session_start();
    if(!isset($_SESSION['islogged'])) {
        // Redirect to login page
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Demo - Log In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col col-md-8">
                <h1>WELCOME TO OUR WEB PAGE</h1>
                <h3><?= $_SESSION['name']; ?></h3>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </div>
</body>

</html>