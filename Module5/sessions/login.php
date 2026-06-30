<?php
session_start();
if (isset($_SESSION['islogged'])) {
    // Redirect to login page
    header('Location: index.php');
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
            <div class="col col-md-4">
                <?php
                if (isset($_SESSION['error'])):
                ?>
                    <div class="alert alert-danger">
                        <p><?= $_SESSION['error'] ?></p>
                    </div>
                <?php
                    unset($_SESSION['error']);
                endif;
                ?>
                <?php
                if (isset($_SESSION['success'])):
                ?>
                    <div class="alert alert-success">
                        <p><?= $_SESSION['success'] ?></p>
                    </div>
                <?php
                    unset($_SESSION['success']);
                endif;
                ?>
                <form action="checkuser.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Type email...">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Type password...">

                    </div>
                    <div class="mb-3">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div>
                        <p>No account yet? <a href="signup.php">Click here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>