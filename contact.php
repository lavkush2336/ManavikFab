<?php
session_start();
include 'connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Here you would typically save to database or send email
    $success_message = "Thank you for your message! We'll get back to you soon.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ManavikFab | Get in Touch</title>
    <meta name="description" content="Contact ManavikFab for any queries, support, or feedback. We're here to help you with all your fashion needs.">
    <meta name="keywords" content="contact manavikfab, customer support, fashion queries, help">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
        }
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d2d2d;
        }
        .contact-container {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
            border: none;
            color: #2d2d2d;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .footer {
            background: #2d2d2d;
            color: white;
        }
        /* Ensure footer links, paragraphs, and list items are readable on dark background */
        footer.footer p,
        footer.footer li,
        footer.footer a,
        footer.footer .text-muted,
        footer.footer ul li a,
        footer.footer .text-white {
            color: #CCCCCC !important;
        }
        footer.footer a:hover,
        footer.footer ul li a:hover,
        footer.footer .text-white:hover {
            color: #FFFFFF !important;
            text-decoration: none !important;
        }
        /* Target the container descendants for maximum specificity */
        footer.footer .container a,
        footer.footer .container p,
        footer.footer .container li,
        footer.footer .container ul li a {
            color: #CCCCCC !important;
        }
        .search-bar {
            border-radius: 2rem;
            border: 2px solid #f8c9d8;
            padding: 0.75rem 1.5rem;
        }
        .search-bar:focus {
            border-color: #f4b6cc;
            box-shadow: 0 0 0 0.2rem rgba(248, 201, 216, 0.3);
        }
        .contact-info {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-info:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            border-color: #f4b6cc;
            box-shadow: 0 0 0 0.2rem rgba(248, 201, 216, 0.3);
        }
        .map-container {
            border-radius: 1rem;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-heart-fill text-danger me-2"></i>ManavikFab
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="products.php?category=ethnic">Ethnic Wear</a></li>
                            <li><a class="dropdown-item" href="products.php?category=western">Western Wear</a></li>
                            <li><a class="dropdown-item" href="products.php?category=sarees">Sarees</a></li>
                            <li><a class="dropdown-item" href="products.php?category=lehengas">Lehengas</a></li>
                            <li><a class="dropdown-item" href="products.php?category=accessories">Accessories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">All Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
                
                <form class="d-flex me-3">
                    <input class="form-control search-bar" type="search" placeholder="Search for products..." aria-label="Search">
                </form>
                
                <div class="d-flex align-items-center">
                    <a href="wishlist.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-heart"></i>
                    </a>
                    <a href="cart.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-cart3"></i>
                        <span class="badge bg-danger ms-1">3</span>
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> My Account
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                                <li><a class="dropdown-item" href="wishlist.php">Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                        <a href="signup.php" class="btn btn-primary-custom">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>

    <!-- Contact Hero Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">Get in Touch</h1>
                    <p class="lead mb-4">Have questions, feedback, or need assistance? We're here to help! Reach out to us through any of the channels below.</p>
                    <p class="mb-4">Our customer support team is available 24/7 to assist you with all your fashion needs.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="images/contact-hero.jpg" alt="Contact Us" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info">
                        <i class="bi bi-telephone text-primary mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Phone Support</h5>
                        <p class="text-muted">Call us anytime</p>
                        <p class="fw-bold">+91 98765 43210</p>
                        <p class="fw-bold">+91 87654 32109</p>
                        <small class="text-muted">Mon-Sun: 9:00 AM - 9:00 PM</small>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info">
                        <i class="bi bi-envelope text-success mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Email Support</h5>
                        <p class="text-muted">Send us an email</p>
                        <p class="fw-bold">support@manavikfab.com</p>
                        <p class="fw-bold">info@manavikfab.com</p>
                        <small class="text-muted">We reply within 24 hours</small>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-info">
                        <i class="bi bi-geo-alt text-warning mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Visit Us</h5>
                        <p class="text-muted">Our main office</p>
                        <p class="fw-bold">123 Fashion Street</p>
                        <p class="fw-bold">Connaught Place, New Delhi</p>
                        <small class="text-muted">Mon-Fri: 10:00 AM - 6:00 PM</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="contact-container">
                        <h3 class="mb-4">Send us a Message</h3>
                        
                        <?php if(isset($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject *</label>
                                <select class="form-select" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Product Information">Product Information</option>
                                    <option value="Order Support">Order Support</option>
                                    <option value="Returns & Exchanges">Returns & Exchanges</option>
                                    <option value="Size Guide">Size Guide</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Partnership">Partnership</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" rows="5" name="message" placeholder="Tell us how we can help you..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Map & Additional Info -->
                <div class="col-lg-4">
                    <div class="contact-container mb-4">
                        <h5 class="mb-3">Quick Contact</h5>
                        <div class="mb-3">
                            <h6>WhatsApp Support</h6>
                            <p class="text-muted">+91 98765 43210</p>
                            <small class="text-muted">Quick responses for urgent queries</small>
                        </div>
                        <div class="mb-3">
                            <h6>Live Chat</h6>
                            <p class="text-muted">Available on website</p>
                            <small class="text-muted">Chat with us in real-time</small>
                        </div>
                        <div class="mb-3">
                            <h6>Social Media</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="contact-container">
                        <h5 class="mb-3">Our Location</h5>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3504.5!2d77.2094!3d28.6139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDM2JzUwLjAiTiA3N8KwMTInMzMuOSJF!5e0!3m2!1sen!2sin!4v1234567890"
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>
                                123 Fashion Street, Connaught Place, New Delhi - 110001
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Frequently Asked Questions</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How can I track my order?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can track your order by logging into your account and visiting the "My Orders" section. You'll also receive tracking updates via email and SMS.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What is your return policy?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We offer a 30-day return policy for all products. Items must be unworn, unwashed, and in original packaging with all tags attached.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Do you offer international shipping?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Currently, we ship to all major cities in India. International shipping will be available soon. Stay tuned for updates!
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    How can I change my order size?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can request a size change within 24 hours of placing your order. Contact our customer support team for assistance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Hours -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="contact-container">
                        <h4 class="mb-4">Business Hours</h4>
                        <div class="row">
                            <div class="col-6">
                                <h6>Customer Support</h6>
                                <p class="text-muted">24/7 Available</p>
                                <p class="text-muted">Phone & Email Support</p>
                            </div>
                            <div class="col-6">
                                <h6>Physical Store</h6>
                                <p class="text-muted">Mon-Sat: 10:00 AM - 8:00 PM</p>
                                <p class="text-muted">Sunday: 11:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-container">
                        <h4 class="mb-4">Response Times</h4>
                        <div class="row">
                            <div class="col-6">
                                <h6>Email Support</h6>
                                <p class="text-muted">Within 24 hours</p>
                            </div>
                            <div class="col-6">
                                <h6>Phone Support</h6>
                                <p class="text-muted">Immediate response</p>
                            </div>
                            <div class="col-6">
                                <h6>Live Chat</h6>
                                <p class="text-muted">Instant response</p>
                            </div>
                            <div class="col-6">
                                <h6>WhatsApp</h6>
                                <p class="text-muted">Within 2 hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html> 