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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
        }
        
        /* HEADER CSS FROM index.php */
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            transition: color 0.3s ease;
        }
        
        .site-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: transparent;
            padding: 1.5rem 0;
            transition: background .3s ease, padding .3s ease, box-shadow .3s ease;
        }

        .site-navbar.scrolled {
            background: #ffffff;
            padding: 1rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .site-navbar.scrolled .navbar-brand,
        .site-navbar.scrolled .nav-icon {
            color: #2d2d2d;
        }

        .site-navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-navbar .nav-links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .site-navbar .nav-links .nav-link {
            color: #fff;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color .2s ease, transform .2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }

        .site-navbar.scrolled .nav-links .nav-link {
            color: #444;
        }

        .site-navbar .nav-links .nav-link:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #ff5f99;
            transition: all .3s ease;
            transform: translateX(-50%);
        }

        .site-navbar .nav-links .nav-link:hover:after {
            width: 100%;
        }

        .site-navbar .nav-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .nav-icon {
            color: #fff;
            font-size: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 50%;
            transition: color .2s ease, transform .2s ease, background .2s ease;
        }

        .nav-icon:hover {
            color: #ff5f99;
            transform: translateY(-2px);
        }

        .nav-cart {
            position: relative;
        }
        .nav-cart .nav-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 18px;
            height: 18px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: .6rem;
            background: #dc3545;
            color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }

        .search-placeholder-wrapper {
            max-width: 400px;
        }
        .search-placeholder {
            background: #f1f1f1;
            color: #888;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .search-placeholder:hover {
            background: #e9e9e9;
            color: #444;
        }

        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .search-overlay.active {
            display: flex;
            opacity: 1;
        }

        .search-overlay-content {
            background: #fff;
            width: 90%;
            max-width: 900px;
            padding: 2.5rem;
            border-radius: 12px;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .search-overlay.active .search-overlay-content {
            transform: scale(1);
        }

        .search-input-wrapper {
            position: relative;
        }
        .search-input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .search-input-wrapper input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid #ddd;
            border-radius: 999px;
            font-size: 1.1rem;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .search-input-wrapper input:focus {
            border-color: #ff5f99;
            box-shadow: 0 0 0 4px rgba(255, 95, 153, 0.1);
        }

        .btn-close-overlay {
            font-size: 1.25rem;
            color: #888;
            background: none;
            border: none;
            transition: color 0.3s ease;
        }
        .btn-close-overlay:hover {
            color: #444;
        }

        .search-suggestions-container {
            padding-top: 1rem;
            border-top: 1px solid #eee;
            margin-top: 2rem;
        }
        .search-categories li a,
        .search-list li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            color: #444;
            text-decoration: none;
            transition: background 0.3s ease, color 0.3s ease;
            border-radius: 8px;
        }
        .search-categories li a i {
            margin-right: 0.75rem;
        }
        .search-categories li a:hover,
        .search-list li a:hover {
            background: #f5f5f5;
            color: #ff5f99;
        }
        .search-list .badge {
            background: #f1f1f1 !important;
            color: #888 !important;
            font-weight: 500;
        }

        @media (max-width:992px){
            .site-navbar .nav-links { display: none; }
            .site-navbar .nav-icons { flex-grow: 1; justify-content: flex-end; }
            .site-navbar.scrolled .nav-icon, .site-navbar.scrolled .navbar-brand { color: #444 !important; }
            .nav-icon { color: #fff; }
        }
        /* END HEADER CSS */
        
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
    <nav class="site-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="index.php">ManavikFab</a>
            
            <div class="search-placeholder-wrapper d-none d-md-flex align-items-center flex-grow-1 mx-5">
                <div class="search-placeholder w-100" id="searchTrigger">
                    <i class="bi bi-search me-2"></i>
                    <span>Search by inspiration</span>
                </div>
            </div>

            <ul class="nav nav-links d-none d-lg-flex">
                <li class="nav-item"><a class="nav-link" href="#">New Arrivals</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Collections</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Dresses</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Sale</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <div class="nav-icons">
                    <button class="btn nav-icon d-md-none" aria-label="Search" id="mobileSearchTrigger"><i class="bi bi-search"></i></button>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="nav-icon" title="Profile"><i class="bi bi-person-circle"></i></a>
                    <?php else: ?>
                        <a href="logout.php" class="nav-icon" title="Logout"><i class="bi bi-box-arrow-in-right"></i></a>
                    <?php endif; ?>
                    <a href="wishlist.php" class="nav-icon" title="Wishlist"><i class="bi bi-heart"></i></a>
                    <a href="cart.php" class="nav-icon nav-cart" title="Cart">
                        <i class="bi bi-cart3"></i>
                        <span class="nav-badge">3</span>
                    </a>
                </div>
                <button class="btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavCollapse">
                    <i class="bi bi-list" style="font-size:1.5rem"></i>
                </button>
            </div>
        </div>
        
        <div class="collapse d-lg-none w-100" id="siteNavCollapse">
            <ul class="nav flex-column mt-3 px-3">
                <li class="nav-item"><a class="nav-link text-dark" href="#">New Arrivals</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Collections</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Dresses</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#">Sale</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="search-overlay" id="searchOverlay">
        <div class="search-overlay-content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="search-input-wrapper flex-grow-1 me-4">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search by inspiration" autofocus>
                </div>
                <button class="btn btn-close-overlay" id="closeSearch">Close</button>
            </div>
            <div class="search-suggestions-container">
                <div class="row">
                    <div class="col-12 col-md-4 search-categories">
                        <ul class="list-unstyled">
                            <li><a href="#"><i class="bi bi-fire"></i> Trending</a></li>
                            <li><a href="#"><i class="bi bi-grid-3x3"></i> By Category</a></li>
                            <li><a href="#"><i class="bi bi-code-slash"></i> By Technology</a></li>
                            <li><a href="#"><i class="bi bi-folder-fill"></i> Collections</a></li>
                            <li><a href="#"><i class="bi bi-book"></i> Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-8 search-list mt-4 mt-md-0">
                        <ul class="list-unstyled">
                            <li><a href="#">Portfolio Websites <span class="badge bg-light text-dark">9143</span></a></li>
                            <li><a href="#">Free fonts <span class="badge bg-light text-dark">288</span></a></li>
                            <li><a href="#">Animated websites <span class="badge bg-light text-dark">10837</span></a></li>
                            <li><a href="#">Sites of the Day <span class="badge bg-light text-dark">6623</span></a></li>
                            <li><a href="#">Scrolling <span class="badge bg-light text-dark">5621</span></a></li>
                            <li><a href="#">UI design <span class="badge bg-light text-dark">6709</span></a></li>
                            <li><a href="#">E-commerce layouts <span class="badge bg-light text-dark">6069</span></a></li>
                            <li><a href="#">Photography websites <span class="badge bg-light text-dark">1513</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 text-center text-white" style="background: rgba(0,0,0,0.2); margin-top: 100px;">
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

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 120
        });

        // Navbar scroll effect
        (function(){
            const navbar = document.querySelector('.site-navbar');
            if(!navbar) return;
            const onScroll = () => {
                if(window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            };
            document.addEventListener('scroll', onScroll, {passive:true});
            onScroll();
        })();

        // Dynamic Search Bar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchTrigger = document.getElementById('searchTrigger');
            const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearchBtn = document.getElementById('closeSearch');

            const openSearch = () => {
                if (searchOverlay) {
                    searchOverlay.classList.add('active');
                    const searchInput = searchOverlay.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            };

            const closeSearch = () => {
                if (searchOverlay) {
                    searchOverlay.classList.remove('active');
                }
            };

            if (searchTrigger) {
                searchTrigger.addEventListener('click', openSearch);
            }

            if (mobileSearchTrigger) {
                mobileSearchTrigger.addEventListener('click', openSearch);
            }

            if (closeSearchBtn) {
                closeSearchBtn.addEventListener('click', closeSearch);
            }

            if (searchOverlay) {
                searchOverlay.addEventListener('click', (e) => {
                    if (e.target === searchOverlay) {
                        closeSearch();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeSearch();
                }
            });
        });
    </script>
</body>
</html>