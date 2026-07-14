<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/Login.css">
    <title>Param Clothing - Log In</title>
</head>
<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center justify-content-md-end p-4 pe-md-5">
        
        <div class="card card-custom p-4 p-sm-5 mx-md-4">
            
            <div class="text-center mb-4">
                <img src="images/logo-header.png" alt="Param Clothing" class="logo-img">
                <h4 class="fw-bold mt-3 mb-1">Login to your account</h4>
            </div>

            <form action="#" method="post">
                <div class="section-label">Email Address</div>
                <div class="mb-3">
                    <input type="email" class="form-control py-2" name="email" id="email" placeholder="Email address">
                </div>
                
                <div class="section-label">Password</div>
                <div class="mb-3">
                    <input type="password" class="form-control py-2" name="password" id="password" placeholder="Password">
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted small" for="remember">Remember me</label>
                </div>
                
                <div class="d-grid mb-3">
                    <button type="submit" name="submit" class="btn btn-custom py-2">Login</button>
                </div>
            </form>

            <div class="text-center mt-2">
                <p class="text-muted small mb-0">Don't have an account? <a href="signup.php" class="custom-link">Create an account</a></p>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>