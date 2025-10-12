<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - ManavikFab | Premium Fashion for Women</title>
    <meta name="description" content="Discover the story, mission, and values behind ManavikFab. Learn how we're bringing premium, beautiful fashion to women across India.">
    <meta name="keywords" content="about manavikfab, our story, women fashion, ethnic wear, fashion brand">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
        }
        .navbar-brand { font-size: 1.8rem; font-weight: 700; }
        .btn-primary-custom {
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
            border: none; color: #2d2d2d; font-weight: 600;
            padding: 0.75rem 2rem; border-radius: 2rem; transition: all 0.3s ease;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .footer { background: #2d2d2d; color: white; }
        footer a, footer .text-muted { color: #CCCCCC !important; }
        footer a:hover { color: #FFFFFF !important; text-decoration: none !important; }

        /* Timeline Styles */
        .timeline { position: relative; max-width: 1200px; margin: 0 auto; }
        .timeline::after {
            content: ''; position: absolute; width: 6px;
            background-color: white; top: 0; bottom: 0;
            left: 50%; margin-left: -3px;
        }
        .timeline-container { padding: 10px 40px; position: relative; background-color: inherit; width: 50%; }
        .timeline-container.left { left: 0; }
        .timeline-container.right { left: 50%; }
        
        .timeline-container::after {
            content: ''; position: absolute; width: 25px; height: 25px;
            right: -17px; background-color: white; border: 4px solid #f4b6cc;
            top: 15px; border-radius: 50%; z-index: 1;
        }
        .timeline-container.right::after { left: -16px; }
        
        .timeline-content {
            padding: 2rem; background-color: white; position: relative;
            border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Responsive Timeline */
        @media screen and (max-width: 768px) {
            .timeline::after { left: 31px; }
            .timeline-container { width: 100%; padding-left: 70px; padding-right: 25px; }
            .timeline-container.right { left: 0%; }
            .timeline-container.left::after, .timeline-container.right::after { left: 15px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-heart-fill text-danger me-2"></i>ManavikFab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">All Products</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="login.php" class="btn btn-primary-custom me-2">Login</a>
                    <a href="signup.php" class="btn btn-primary-custom">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="py-5 text-center text-white" style="background: rgba(0,0,0,0.2);">
        <div class="container" data-aos="fade-up">
            <h1 class="display-3 fw-bold">Our Story</h1>
            <p class="lead col-lg-8 mx-auto">ManavikFab was born from a simple vision: to make premium fashion accessible to every woman, celebrating beauty, confidence, and empowerment through clothing.</p>
        </div>
    </section>
    
    <div class="timeline py-5">
        <div class="timeline-container left" data-aos="fade-right">
            <div class="timeline-content">
                <h2>The Beginning (2020)</h2>
                <p class="lead">Founded in 2020, we started as a small boutique in Delhi. Our goal was to build a brand that stands for quality, trust, and the vibrant spirit of Indian fashion.</p>
                <a href="products.php" class="btn btn-primary-custom">Explore Our Collection</a>
            </div>
        </div>
        
        <div class="timeline-container right" data-aos="fade-left">
            <div class="timeline-content">
                <div class="text-center mb-3"><i class="bi bi-bullseye text-primary" style="font-size: 3rem;"></i></div>
                <h3 class="text-center">Our Mission</h3>
                <p class="text-center">To provide high-quality, trendy, and affordable fashion that celebrates the diversity and beauty of Indian women. We strive to create clothing that makes every woman feel confident and beautiful.</p>
            </div>
        </div>
        <div class="timeline-container left" data-aos="fade-right">
            <div class="timeline-content">
                 <div class="text-center mb-3"><i class="bi bi-eye text-success" style="font-size: 3rem;"></i></div>
                <h3 class="text-center">Our Vision</h3>
                <p class="text-center">To become India's most trusted and loved fashion destination, known for quality, innovation, and customer satisfaction. We aim to be the go-to brand for every woman's fashion needs.</p>
            </div>
        </div>
    </div>
    
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Numbers</h2>
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100"><div class="stats-card"><h2 class="text-primary fw-bold">50K+</h2><p class="text-muted">Happy Customers</p></div></div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200"><div class="stats-card"><h2 class="text-success fw-bold">1000+</h2><p class="text-muted">Products</p></div></div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300"><div class="stats-card"><h2 class="text-warning fw-bold">25+</h2><p class="text-muted">Cities Served</p></div></div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400"><div class="stats-card"><h2 class="text-danger fw-bold">4.8★</h2><p class="text-muted">Customer Rating</p></div></div>
            </div>
        </div>
    </section>

    <div class="timeline py-5">
        <div class="timeline-container right" data-aos="fade-left">
            <div class="timeline-content">
                <h2 class="text-center">Our Values</h2>
                <p class="text-center text-muted">The principles that guide every decision we make.</p>
                <div class="row g-3 mt-3">
                    <div class="col-md-6"><div class="value-card text-start"><i class="bi bi-award text-primary mb-2 fs-3"></i><h5>Quality</h5><p class="small text-muted">We never compromise on quality.</p></div></div>
                    <div class="col-md-6"><div class="value-card text-start"><i class="bi bi-heart text-danger mb-2 fs-3"></i><h5>Customer First</h5><p class="small text-muted">Our customers are at the heart of everything we do.</p></div></div>
                    <div class="col-md-6"><div class="value-card text-start"><i class="bi bi-lightbulb text-warning mb-2 fs-3"></i><h5>Innovation</h5><p class="small text-muted">We constantly innovate to bring the latest trends.</p></div></div>
                    <div class="col-md-6"><div class="value-card text-start"><i class="bi bi-shield-check text-success mb-2 fs-3"></i><h5>Trust</h5><p class="small text-muted">We build lasting, transparent relationships.</p></div></div>
                </div>
            </div>
        </div>

        <div class="timeline-container left" data-aos="fade-right">
            <div class="timeline-content">
                <h4>Why Choose ManavikFab?</h4>
                <p>We source the finest fabrics and work with skilled artisans to create products that meet international quality standards. Your satisfaction is our priority.</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Premium fabric selection & skilled craftsmanship</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Easy returns, exchanges, and 24/7 support</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Secure payment options and quality assurance</li>
                </ul>
            </div>
        </div>
    </div>
    
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Meet Our Team</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100"><div class="team-member"><img src="images/team1.jpg" alt="CEO" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;"><h5>Priya Sharma</h5><p class="text-muted">Founder & CEO</p><p class="small">15+ years of experience in fashion retail</p></div></div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200"><div class="team-member"><img src="images/team2.jpg" alt="Design Head" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;"><h5>Anjali Patel</h5><p class="text-muted">Design Head</p><p class="small">Expert in ethnic and contemporary designs</p></div></div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300"><div class="team-member"><img src="images/team3.jpg" alt="Operations Manager" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;"><h5>Riya Singh</h5><p class="text-muted">Operations Manager</p><p class="small">Ensuring smooth operations and customer satisfaction</p></div></div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400"><div class="team-member"><img src="images/team4.jpg" alt="Marketing Head" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;"><h5>Kavya Reddy</h5><p class="text-muted">Marketing Head</p><p class="small">Building brand awareness and customer engagement</p></div></div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container text-center">
            <div class="bg-white p-5 rounded-3 shadow" data-aos="zoom-in">
                <h2 class="mb-4">Ready to Experience Premium Fashion?</h2>
                <p class="lead mb-4">Join thousands of satisfied customers who trust ManavikFab for their fashion needs.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="products.php" class="btn btn-primary-custom btn-lg">Shop Now</a>
                    <a href="contact.php" class="btn btn-primary-custom btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">ManavikFab</h5>
                    <p class="text-muted">Your one-stop destination for premium fashion. We bring you the latest trends in ethnic and western wear.</p>
                    <div class="d-flex gap-3"><a href="#" class="text-white"><i class="bi bi-facebook"></i></a><a href="#" class="text-white"><i class="bi bi-instagram"></i></a><a href="#" class="text-white"><i class="bi bi-twitter"></i></a><a href="#" class="text-white"><i class="bi bi-youtube"></i></a></div>
                </div>
                <div class="col-lg-2 col-6"><h6 class="mb-3">Quick Links</h6><ul class="list-unstyled"><li><a href="about.php">About Us</a></li><li><a href="contact.php">Contact</a></li><li><a href="products.php">Products</a></li><li><a href="blog.php">Blog</a></li></ul></div>
                <div class="col-lg-2 col-6"><h6 class="mb-3">Categories</h6><ul class="list-unstyled"><li><a href="products.php?category=ethnic">Ethnic Wear</a></li><li><a href="products.php?category=western">Western Wear</a></li><li><a href="products.php?category=sarees">Sarees</a></li><li><a href="products.php?category=accessories">Accessories</a></li></ul></div>
                <div class="col-lg-2 col-6"><h6 class="mb-3">Customer Service</h6><ul class="list-unstyled"><li><a href="help.php">Help Center</a></li><li><a href="shipping.php">Shipping Info</a></li><li><a href="returns.php">Returns</a></li><li><a href="size-guide.php">Size Guide</a></li></ul></div>
                <div class="col-lg-2 col-6"><h6 class="mb-3">Account</h6><ul class="list-unstyled"><li><a href="login.php">Login</a></li><li><a href="signup.php">Sign Up</a></li><li><a href="orders.php">My Orders</a></li><li><a href="wishlist.php">Wishlist</a></li></ul></div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted">&copy; 2025 ManavikFab. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 120
        });
    </script>
</body>
</html>