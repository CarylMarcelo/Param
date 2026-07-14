<?php
session_start();

// User information placeholders
$fullName   = $_SESSION['user_fullname'] ?? '';
$email      = $_SESSION['user_email']    ?? '';
$phone      = $_SESSION['user_phone']    ?? '';
$address    = $_SESSION['user_address']  ?? '';
$profilePic = $_SESSION['user_avatar']   ?? ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - Param Clothing</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/Profile.css">
</head>
<body>

  <?php 
  $path = ''; 
  include 'includes/header.php'; 
  ?>

<div class="profile-card">

  <div class="profile-sidebar">
    
    <div class="user-summary">
      <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="user-avatar" id="avatarPreview">
      <h3><?php echo $fullName; ?></h3>
      <p><?php echo $email; ?></p>
    </div>

    <button class="nav-btn active" id="btn-info" onclick="showSection('personal-info')">
      Personal Info
    </button>
    
    <button class="nav-btn" id="btn-password" onclick="showSection('update-password')">
      Update Password
    </button>

    <a href="login.php" class="nav-btn logout-btn">
      Logout
    </a>
  </div>

  <div class="profile-content">

    <div id="personal-info" class="content-section active">
      <h2>Personal Information</h2>

      <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
          <label>Upload Profile Picture</label>
          <input type="file" name="profile_picture" accept="image/*">
        </div>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname" value="<?php echo $fullName; ?>" required>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" value="<?php echo $email; ?>" required>
        </div>

        <div class="form-group">
          <label>Contact Number</label>
          <input type="text" name="phone" value="<?php echo $phone; ?>" required>
        </div>

        <div class="form-group">
          <label>Address</label>
          <textarea name="address" rows="3" required><?php echo $address; ?></textarea>
        </div>

        <button type="submit" class="btn-save">Save Info</button>
      </form>
    </div>

    <div id="update-password" class="content-section">
      <h2>Update Password</h2>

      <form action="update_password.php" method="POST">
        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password" required>
        </div>

        <div class="form-group">
          <label>New Password</label>
          <input type="password" name="new_password" required>
        </div>

        <div class="form-group">
          <label>Confirm New Password</label>
          <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn-save">Update Password</button>
      </form>
    </div>

  </div>

</div>

<script>
  function showSection(sectionId) {
    
    document.getElementById('personal-info').classList.remove('active');
    document.getElementById('update-password').classList.remove('active');

    
    document.getElementById('btn-info').classList.remove('active');
    document.getElementById('btn-password').classList.remove('active');

    
    document.getElementById(sectionId).classList.add('active');
    if (sectionId === 'personal-info') {
      document.getElementById('btn-info').classList.add('active');
    } else {
      document.getElementById('btn-password').classList.add('active');
    }
  }
</script>

<?php 
$path = ''; 
include 'includes/footer.php'; 
?>