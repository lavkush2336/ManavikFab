<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManavikFab - Premium Fashion for Women | Shop Latest Trends</title>
    <meta name="description" content="Discover the latest fashion trends for women at ManavikFab. Shop from our exclusive collection of ethnic wear, western wear, accessories and more. Free shipping on orders above ₹999.">
    <meta name="keywords" content="women fashion, ethnic wear, western wear, sarees, lehengas, kurtis, dresses, accessories, online shopping">
    <meta name="author" content="ManavikFab">
    <meta property="og:title" content="ManavikFab - Premium Fashion for Women">
    <meta property="og:description" content="Discover the latest fashion trends for women at ManavikFab">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://manavikfab.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body, html {
            overflow-x: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
        }
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

        .hero-section {
            position: relative;
            min-height: 78vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }
        .hero-banner{
            position:absolute;
            inset:0;
            background-image: url('images/image.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            filter: contrast(0.98) saturate(1.02);
            transform: scale(1.02);
        }
        .hero-overlay{
            position:absolute;inset:0;
            background: rgba(0,0,0,0.32);
            backdrop-filter: blur(2px);
        }
        .hero-content{
            position:relative;
            z-index:2;
            padding:3rem 1rem;
            max-width:1100px;
            width:100%;
            display:flex;
            flex-direction:column;
            align-items:center;
            text-align:center
        }
        .hero-headline{
            font-size:3.6rem;
            font-weight:800;
            color:#fff;
            margin-bottom:0.6rem;
            line-height:1.02;
            text-shadow:0 8px 30px rgba(12,12,12,0.45)
        }
        .hero-sub{color:rgba(255,255,255,0.92);font-size:1.25rem;margin-bottom:1.25rem;max-width:820px}
        .hero-cta .btn{border-radius:999px;padding:1rem 2.4rem;font-weight:800;font-size:1.05rem}
       .hero-cta .btn-primary-custom {background: linear-gradient(90deg, #ff7ab6, #ff5f99);
        color: #fff;box-shadow: 0 12px 40px rgba(255, 95, 153, 0.18);margin-top: 103%;}
        .hero-cta .btn-outline-light {
    background: rgba(255, 255, 255, 0.9);
    color: #3a2a3f;
    font-weight: 700;
    margin-top: 103%;
    margin-left: 50%;
}
        .hero-side-img{display:none}

        .category-card, .product-card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 280ms cubic-bezier(.2,.9,.2,1), box-shadow 280ms ease;
            overflow: hidden;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .category-card:hover, .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.10);
        }
        
        .product-card .card-img-top{
            object-fit:cover;
            height:260px;
        }

        .btn-primary-custom {
            background: linear-gradient(90deg,#f8c9d8,#f4b6cc);
            border: none;
            color: #3a2a3f;
            font-weight: 700;
            padding: 0.65rem 1.6rem;
            border-radius: 999px;
            transition: transform .18s ease, box-shadow .18s ease
        }
        .btn-primary-custom:hover{
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(244,182,204,0.18)
        }

        .footer {
            background: #2d2d2d;
            color: white;
        }
        footer.footer p,
        footer.footer li,
        footer.footer .text-muted,
        footer.footer a,
        footer.footer ul li a {
            color: #CCCCCC !important;
        }
        footer.footer a:hover,
        footer.footer ul li a:hover {
            color: #FFFFFF !important;
            text-decoration: none !important;
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
        .category-grid .icon{font-size:2rem}
        
        @media (max-width:992px){
            .hero-headline{font-size:2.4rem}
            .hero-sub{font-size:1rem}
            .hero-cta .btn{padding:.85rem 1.6rem;font-size:.98rem}
            .site-navbar .nav-links { display: none; }
            .site-navbar .nav-icons { flex-grow: 1; justify-content: flex-end; }
            .site-navbar.scrolled .nav-icon, .site-navbar.scrolled .navbar-brand { color: #444 !important; }
            .nav-icon { color: #fff; }
        }
        @media (max-width:576px){
            .hero-section{min-height:60vh}
            .hero-headline{font-size:1.9rem}
            .hero-sub{font-size:.95rem}
            .hero-cta .btn{padding:.7rem 1.25rem;font-size:.9rem}
        }
        
        .content-section{background:#ffffff}
        .category-section{margin-top:3.5rem}

        .btn, .btn-primary-custom, .btn-outline-light {
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover{
            background-image: linear-gradient(90deg,#ff5fa8,#ff4f87);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(255,95,153,0.14);
        }
        .btn-outline-light:hover{
            background-color:#ffffff;
            color:#3a2a3f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .category-card:hover, .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.10);
        }

        /* Styles for the new search bar */
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

        /* Fullscreen search overlay */
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
                        <a href="login.php" class="nav-icon" title="Login"><i class="bi bi-box-arrow-in-right"></i></a>
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
    
    <section class="hero-section">
        <div class="hero-banner"></div>
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center">
            <div data-aos="fade-up" data-aos-duration="1200">
                <div class="hero-cta d-flex gap-5 justify-content-center">
                    <a href="products.php" class="btn btn-primary-custom">Shop Now</a>
                    <a href="blog.php" class="btn btn-outline-light">Explore Article</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 content-section category-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Shop by Category</h2>
            <div class="row g-3 category-grid">
                <?php
                $cats = [
                    ['icon'=>'bi-heart-fill','title'=>'Ethnic Wear','sub'=>'Traditional Elegance'],
                    ['icon'=>'bi-star-fill','title'=>'Western Wear','sub'=>'Modern Fashion'],
                    ['icon'=>'bi-gem','title'=>'Sarees','sub'=>'Timeless Beauty'],
                    ['icon'=>'bi-flower1','title'=>'Lehengas','sub'=>'Festive Collection'],
                    ['icon'=>'bi-diamond','title'=>'Accessories','sub'=>'Complete Your Look'],
                    ['icon'=>'bi-fire','title'=>'New Arrivals','sub'=>'Latest Trends']
                ];
                foreach($cats as $i=>$c):
                ?>
                <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="<?php echo ($i+1)*80; ?>">
                    <div class="category-card">
                        <div class="icon text-danger mb-2"><i class="bi <?php echo $c['icon']; ?>"></i></div>
                        <h6 class="mb-1"><?php echo $c['title']; ?></h6>
                        <small class="text-muted"><?php echo $c['sub']; ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-5 content-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Featured Products</h2>
            <div class="row g-4">
                <?php
                // Sample featured products data (placeholders)
                $featured_products = [
                    ['name'=>'Embroidered Silk Saree','price'=>'₹2,499','original_price'=>'₹3,999','image'=>'images/product1.jpg','rating'=>4.5],
                    ['name'=>'Designer Lehenga Set','price'=>'₹5,999','original_price'=>'₹8,999','image'=>'images/product2.jpg','rating'=>4.8],
                    ['name'=>'Cotton Kurti with Palazzo','price'=>'₹1,299','original_price'=>'₹1,999','image'=>'images/product3.jpg','rating'=>4.3],
                    ['name'=>'Western Dress Collection','price'=>'₹1,899','original_price'=>'₹2,499','image'=>'images/product4.jpg','rating'=>4.6]
                ];
                foreach($featured_products as $index => $product):
                ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 80; ?>">
                    <div class="product-card h-100">
                        <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo $product['name']; ?></h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo $i <= round($product['rating']) ? '-fill' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">(<?php echo $product['rating']; ?>)</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-danger"><?php echo $product['price']; ?></span>
                                    <small class="text-muted text-decoration-line-through ms-2"><?php echo $product['original_price']; ?></small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="products.php" class="btn btn-primary-custom">View All Products</a>
            </div>
        </div>
    </section>

    <section class="py-5 content-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <h4 class="text-danger mb-3">🎉 Special Offer</h4>
                        <h5>Get 50% OFF on Ethnic Collection</h5>
                        <p class="text-muted">Valid till 31st December 2024</p>
                        <a href="products.php?category=ethnic" class="btn btn-primary-custom">Shop Now</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <h4 class="text-success mb-3">🚚 Free Shipping</h4>
                        <h5>Free Delivery on Orders Above ₹999</h5>
                        <p class="text-muted">Pan India delivery</p>
                        <a href="products.php" class="btn btn-primary-custom">Explore</a>
                    </div>
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
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
    <script>
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