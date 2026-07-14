<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account - Param Clothing</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

<div class="signup-card">
    <h2 class="form-title">Create Your Account</h2>

    <form action="signup_process.php" method="POST">
        
        <div class="section-label">Complete Name</div>
        <div class="row g-2">
            <div class="col-md-4">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="e.g., Juan" required>
            </div>
            <div class="col-md-4">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="e.g., Dela" >
            </div>
            <div class="col-md-4">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="e.g., Cruz" required>
            </div>
        </div>

        <div class="section-label">Complete Address</div>
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <label for="house_no" class="form-label">House No.</label>
                <input type="text" class="form-control" id="house_no" name="house_no" placeholder="e.g., 123" required>
            </div>
            <div class="col-md-8">
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="e.g., Rizal Street" required>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <label for="barangay" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangay" name="barangay" placeholder="e.g., San Lorenzo" required>
            </div>
            <div class="col-md-5">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="e.g., Makati" required>
            </div>
            <div class="col-md-3">
                <label for="zip_code" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="e.g., 1900" required>
            </div>
        </div>

        <div class="section-label">Email Address</div>
        <div class="row g-2">
            <div class="col-12">
                <label for="email_address" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_address" name="email_address" placeholder="e.g., juan.delacruz@example.com" required>
            </div>
        </div>

        <div class="section-label">Contact Number</div>
        <div class="row g-2">
            <div class="col-12">
                <label for="contact_number" class="form-label">Mobile / Phone Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="e.g., 09123456789" required>
            </div>
        </div>

        <div class="section-label">Password</div>
        <div class="row g-2">
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
        </div>

        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
            <label class="form-check-label terms-text" for="terms">
                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            </label>
        </div>

        <button type="submit" class="btn btn-signup">Sign Up</button>

        <div class="login-link-container">
            Already have an account? <a href="login.php">Login</a>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>