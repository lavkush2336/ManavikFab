<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ManavikFab | Premium Fashion for Women</title>
    <meta name="description" content="Learn about ManavikFab's journey in bringing premium fashion to women. Discover our mission, values, and commitment to quality ethnic and western wear.">
    <meta name="keywords" content="about manavikfab, women fashion, ethnic wear, western wear, fashion brand">
    
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
        .about-section {
            background: white;
            border-radius: 1rem;
            padding: 3rem;
            margin-bottom: 2rem;
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
        /* Ensure footer text and links are readable against dark background */
        footer.footer p,
        footer.footer li,
        footer.footer .text-muted,
        footer.footer a,
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
        /* Target container descendants for maximum specificity */
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
        .team-member {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .value-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            border-left: 4px solid #f4b6cc;
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
                        <a class="nav-link active" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
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
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">Our Story</h1>
                    <p class="lead mb-4">ManavikFab was born from a simple yet powerful vision: to make premium fashion accessible to every Indian woman. We believe that every woman deserves to feel beautiful, confident, and empowered through her clothing choices.</p>
                    <p class="mb-4">Founded in 2020, we started as a small boutique in Delhi, and today we're proud to serve customers across India with our curated collection of ethnic and western wear.</p>
                    <a href="products.php" class="btn btn-primary-custom">Explore Our Collection</a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="images/about-hero.jpg" alt="ManavikFab Story" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="about-section">
                        <div class="text-center mb-4">
                            <i class="bi bi-bullseye text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="text-center mb-4">Our Mission</h3>
                        <p class="text-center">To provide high-quality, trendy, and affordable fashion that celebrates the diversity and beauty of Indian women. We strive to create clothing that makes every woman feel confident and beautiful.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-section">
                        <div class="text-center mb-4">
                            <i class="bi bi-eye text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="text-center mb-4">Our Vision</h3>
                        <p class="text-center">To become India's most trusted and loved fashion destination, known for quality, innovation, and customer satisfaction. We aim to be the go-to brand for every woman's fashion needs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Numbers</h2>
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-card">
                        <h2 class="text-primary fw-bold">50K+</h2>
                        <p class="text-muted">Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-card">
                        <h2 class="text-success fw-bold">1000+</h2>
                        <p class="text-muted">Products</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-card">
                        <h2 class="text-warning fw-bold">25+</h2>
                        <p class="text-muted">Cities Served</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-card">
                        <h2 class="text-danger fw-bold">4.8★</h2>
                        <p class="text-muted">Customer Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Values</h2>
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-card">
                        <i class="bi bi-award text-primary mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Quality</h5>
                        <p class="text-muted">We never compromise on quality. Every product undergoes rigorous quality checks to ensure the best for our customers.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card">
                        <i class="bi bi-heart text-danger mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Customer First</h5>
                        <p class="text-muted">Our customers are at the heart of everything we do. We strive to exceed their expectations with every interaction.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card">
                        <i class="bi bi-lightbulb text-warning mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Innovation</h5>
                        <p class="text-muted">We constantly innovate to bring the latest trends and styles to our customers while maintaining our traditional values.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="value-card">
                        <i class="bi bi-shield-check text-success mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Trust</h5>
                        <p class="text-muted">We build lasting relationships with our customers through transparency, honesty, and reliable service.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="value-card">
                        <i class="bi bi-people text-info mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Inclusivity</h5>
                        <p class="text-muted">We celebrate diversity and create fashion that makes every woman feel beautiful, regardless of size, age, or style preference.</p>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="value-card">
                        <i class="bi bi-recycle text-secondary mb-3" style="font-size: 2.5rem;"></i>
                        <h5>Sustainability</h5>
                        <p class="text-muted">We're committed to sustainable practices and reducing our environmental impact while delivering quality products.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Meet Our Team</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-member">
                        <img src="images/team1.jpg" alt="CEO" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Priya Sharma</h5>
                        <p class="text-muted">Founder & CEO</p>
                        <p class="small">15+ years of experience in fashion retail</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-member">
                        <img src="images/team2.jpg" alt="Design Head" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Anjali Patel</h5>
                        <p class="text-muted">Design Head</p>
                        <p class="small">Expert in ethnic and contemporary designs</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="team-member">
                        <img src="images/team3.jpg" alt="Operations Manager" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Riya Singh</h5>
                        <p class="text-muted">Operations Manager</p>
                        <p class="small">Ensuring smooth operations and customer satisfaction</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="team-member">
                        <img src="images/team4.jpg" alt="Marketing Head" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <h5>Kavya Reddy</h5>
                        <p class="text-muted">Marketing Head</p>
                        <p class="small">Building brand awareness and customer engagement</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Why Choose ManavikFab?</h2>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-section">
                        <h4 class="mb-4">Premium Quality</h4>
                        <p>We source the finest fabrics and work with skilled artisans to create products that meet international quality standards. Every piece is carefully crafted to ensure durability and comfort.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Premium fabric selection</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Skilled craftsmanship</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Quality assurance</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-section">
                        <h4 class="mb-4">Customer Satisfaction</h4>
                        <p>Your satisfaction is our priority. We offer easy returns, size exchanges, and 24/7 customer support to ensure you have the best shopping experience with us.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Easy returns & exchanges</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>24/7 customer support</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Secure payment options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4" data-aos="fade-up">Ready to Experience Premium Fashion?</h2>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Join thousands of satisfied customers who trust ManavikFab for their fashion needs.</p>
            <div class="d-flex justify-content-center gap-3" data-aos="fade-up" data-aos-delay="200">
                <a href="products.php" class="btn btn-primary-custom btn-lg">Shop Now</a>
                <a href="contact.php" class="btn btn-outline-primary btn-lg">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">ManavikFab</h5>
                    <p class="text-muted">Your one-stop destination for premium fashion. We bring you the latest trends in ethnic and western wear.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="contact.php" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="products.php" class="text-muted text-decoration-none">Products</a></li>
                        <li><a href="blog.php" class="text-muted text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Categories</h6>
                    <ul class="list-unstyled">
                        <li><a href="products.php?category=ethnic" class="text-muted text-decoration-none">Ethnic Wear</a></li>
                        <li><a href="products.php?category=western" class="text-muted text-decoration-none">Western Wear</a></li>
                        <li><a href="products.php?category=sarees" class="text-muted text-decoration-none">Sarees</a></li>
                        <li><a href="products.php?category=accessories" class="text-muted text-decoration-none">Accessories</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Customer Service</h6>
                    <ul class="list-unstyled">
                        <li><a href="help.php" class="text-muted text-decoration-none">Help Center</a></li>
                        <li><a href="shipping.php" class="text-muted text-decoration-none">Shipping Info</a></li>
                        <li><a href="returns.php" class="text-muted text-decoration-none">Returns</a></li>
                        <li><a href="size-guide.php" class="text-muted text-decoration-none">Size Guide</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Account</h6>
                    <ul class="list-unstyled">
                        <li><a href="login.php" class="text-muted text-decoration-none">Login</a></li>
                        <li><a href="signup.php" class="text-muted text-decoration-none">Sign Up</a></li>
                        <li><a href="orders.php" class="text-muted text-decoration-none">My Orders</a></li>
                        <li><a href="wishlist.php" class="text-muted text-decoration-none">Wishlist</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; 2024 ManavikFab. All rights reserved.</p>
                </div>
                <!-- <div class="col-md-6 text-md-end">
                    <img src="images/payment-methods.png" alt="Payment Methods" class="img-fluid" style="height: 30px;">
                </div> -->
            </div>
        </div>
    </footer>

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