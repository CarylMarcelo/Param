<?php
// Contact Details 
$pageTitle         = "Contact Us";
$brandName         = "Param";

//Store Info
$storeAddress      = "123 P. Paredes Street in Sampaloc, Manila";
$storePhoneDisplay = "+0123-456-789";
$storePhoneRaw     = "+0123456789";
$storeEmail        = "param@gmail.com";

// Store Working Hours
$hoursWeekday      = "10:00 - 20:00";
$hoursWeekend      = "11:00 - 18:00";

// Hiring 
$isHiring          = true;
$hiringTitle       = "We're Hiring!";
$hiringText        = "Interested in joining the Param team? We are looking for passionate individuals to grow with us. Fill out the application form below to submit your CV and portfolio.";

// Social Links 
$socialLinks = [
    'facebook'  => ['url' => '#', 'icon' => 'fab fa-facebook-f', 'label' => 'Facebook'],
    'youtube'   => ['url' => '#', 'icon' => 'fab fa-youtube',    'label' => 'YouTube'],
    'twitter'   => ['url' => '#', 'icon' => 'fab fa-twitter',    'label' => 'Twitter'],
    'instagram' => ['url' => '#', 'icon' => 'fab fa-instagram',  'label' => 'Instagram'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo$brandName; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ContactUs.css">
</head>
<body>

    <?php 
    $path = ''; 
    include 'includes/header.php'; 
    ?>

    <main class="contact-container">
        <div class="contact-card">
            <div class="row g-0">
                
                <div class="col-lg-7 col-md-12 form-section">
                    <h2 class="section-title">Get in Touch with Us!</h2>
                    <p class="sub-text">Your email address will not be published. Required fields are marked *</p>

                    <form action="contact_process.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label-custom">Your Name *</label>
                                <input type="text" class="form-control form-control-custom" id="name" name="name" placeholder="Ex. John Doe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label-custom">Email *</label>
                                <input type="email" class="form-control form-control-custom" id="email" name="email" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label-custom">Subject *</label>
                            <input type="text" class="form-control form-control-custom" id="subject" name="subject" placeholder="Enter Subject" required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label-custom">Your Message *</label>
                            <textarea class="form-control form-control-custom" id="message" name="message" rows="5" placeholder="Enter here.." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-send">Send Message</button>
                    </form>
                </div>

                <div class="col-lg-5 col-md-12 info-section">
                    
                    <div class="info-block">
                        <h3 class="info-block-title">Address</h3>
                        <p class="info-text"><?php echo htmlspecialchars($storeAddress); ?></p>
                    </div>

                    <div class="info-block">
                        <h3 class="info-block-title">Contact</h3>
                        <p class="info-text">
                            <strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($storePhoneRaw); ?>"><?php echo htmlspecialchars($storePhoneDisplay); ?></a><br>
                            <strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($storeEmail); ?>"><?php echo htmlspecialchars($storeEmail); ?></a>
                        </p>
                    </div>

                    <div class="info-block">
                        <h3 class="info-block-title">Open Time</h3>
                        <p class="info-text">
                            Monday - Friday : <?php echo htmlspecialchars($hoursWeekday); ?><br>
                            Saturday - Sunday : <?php echo htmlspecialchars($hoursWeekend); ?>
                        </p>
                    </div>

                    <div class="info-block mb-0">
                        <h3 class="info-block-title">Stay Connected</h3>
                        <div class="social-links">
                            <?php foreach ($socialLinks as$social): ?>
                                <a href="<?php echo htmlspecialchars($social['url']); ?>" class="social-icon" aria-label="<?php echo htmlspecialchars($social['label']); ?>">
                                    <i class="<?php echo htmlspecialchars($social['icon']); ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <?php if ($isHiring): ?>
            <div class="hiring-card mt-4">
                <div class="hiring-header mb-3">
                    <div class="hiring-title">
                        <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($hiringTitle); ?>
                        <span class="hiring-badge">Open Positions</span>
                    </div>
                    <p class="hiring-text mt-2"><?php echo htmlspecialchars($hiringText); ?></p>
                </div>

                <hr class="hiring-divider">

                <form action="career_process.php" method="POST" enctype="multipart/form-data" class="career-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="applicant_name" class="form-label-custom">Full Name *</label>
                            <input type="text" class="form-control form-control-custom" id="applicant_name" name="applicant_name" placeholder="Ex. Jane Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label for="applicant_email" class="form-label-custom">Email Address *</label>
                            <input type="email" class="form-control form-control-custom" id="applicant_email" name="applicant_email" placeholder="janedoe@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="applicant_cv" class="form-label-custom">Upload CV (PDF/Doc) *</label>
                            <input type="file" class="form-control form-control-custom" id="applicant_cv" name="applicant_cv" accept=".pdf,.doc,.docx" required>
                        </div>
                        <div class="col-md-6">
                            <label for="applicant_portfolio" class="form-label-custom">Portfolio Link or File</label>
                            <input type="text" class="form-control form-control-custom" id="applicant_portfolio" name="applicant_portfolio" placeholder="https://myportfolio.com or Behance link">
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-apply-submit">
                                Submit Application <i class="fas fa-paper-plane ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php 
$path = ''; 
include 'includes/footer.php'; 
?>