<?php
session_start();
include 'connection.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Sample product data
$product = [
    'id' => $product_id,
    'name' => 'Embroidered Silk Saree',
    'category' => 'sarees',
    'price' => 2499,
    'original_price' => 3999,
    'discount' => 37,
    'rating' => 4.5,
    'reviews' => 128,
    'description' => 'This beautiful embroidered silk saree features intricate handwork and premium silk fabric. Perfect for weddings, festivals, and special occasions. The saree comes with a matching blouse piece and is available in multiple sizes.',
    'features' => [
        'Premium Silk Fabric',
        'Hand Embroidered',
        'Matching Blouse Piece',
        'Multiple Size Options',
        'Dry Clean Only'
    ],
    'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
    'colors' => [
        ['name' => 'Red', 'code' => '#dc3545'],
        ['name' => 'Blue', 'code' => '#0d6efd'],
        ['name' => 'Green', 'code' => '#198754'],
        ['name' => 'Pink', 'code' => '#e83e8c']
    ],
    'images' => [
        'images/product1.jpg',
        'images/product1-2.jpg',
        'images/product1-3.jpg',
        'images/product1-4.jpg'
    ],
    'stock' => 15
];

$related_products = [
    [
        'id' => 2,
        'name' => 'Designer Lehenga Set',
        'price' => 5999,
        'original_price' => 8999,
        'image' => 'images/product2.jpg',
        'rating' => 4.8
    ],
    [
        'id' => 3,
        'name' => 'Cotton Kurti with Palazzo',
        'price' => 1299,
        'original_price' => 1999,
        'image' => 'images/product3.jpg',
        'rating' => 4.3
    ],
    [
        'id' => 4,
        'name' => 'Western Dress Collection',
        'price' => 1899,
        'original_price' => 2499,
        'image' => 'images/product4.jpg',
        'rating' => 4.6
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - ManavikFab</title>
    <meta name="description" content="<?php echo $product['description']; ?>">
    <meta name="keywords" content="<?php echo $product['name']; ?>, sarees, ethnic wear, women fashion">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
            padding-top: 8rem; /* ADDED: This creates space for the fixed header */
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d2d2d;
            transition: color 0.3s ease;
        }
        
        .site-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
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
            color: #444;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color .2s ease, transform .2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
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
            color: #2d2d2d;
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

        @media (max-width:992px){
            .site-navbar .nav-links { display: none; }
            .site-navbar .nav-icons { flex-grow: 1; justify-content: flex-end; }
            .site-navbar.scrolled .nav-icon, .site-navbar.scrolled .navbar-brand { color: #444 !important; }
            .nav-icon { color: #2d2d2d; }
        }
        /* END HEADER CSS */
        
        /* Product page specific styles */
        .product-gallery {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
        }
        .product-info {
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
        .size-btn {
            border: 2px solid #e9ecef;
            background: white;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .size-btn:hover, .size-btn.active {
            border-color: #f4b6cc;
            background: #f4b6cc;
            color: white;
        }
        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .color-option:hover, .color-option.active {
            border-color: #f4b6cc;
            transform: scale(1.1);
        }
        .footer {
            background: #2d2d2d;
            color: white;
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
        .thumbnail {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .thumbnail:hover, .thumbnail.active {
            border-color: #f4b6cc;
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


    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/product_1760297195_1.png" class="d-block w-100 rounded" alt="Product Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="images/product_1760296843_3.png" class="d-block w-100 rounded" alt="Product Image 2">
                            </div>
                              <div class="carousel-item">
                                <img src="images/product_1760296843_4.png" class="d-block w-100 rounded" alt="Product Image 2">
                            </div>
                              <div class="carousel-item">
                                <img src="images/product_1760297261_1.png" class="d-block w-100 rounded" alt="Product Image 2">
                            </div>
                        </div>

                        <div class="carousel-indicators">
                            <?php foreach($product['images'] as $index => $image): ?>
                            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                                    class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $index + 1; ?>">
                            </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-info">
                    <h2 class="mb-3"><?php echo $product['name']; ?></h2>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?php echo $i <= $product['rating'] ? '-fill' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="me-3"><?php echo $product['rating']; ?></span>
                        <span class="text-muted">(<?php echo $product['reviews']; ?> reviews)</span>
                    </div>

                    <div class="mb-4">
                        <span class="h3 text-danger fw-bold">₹<?php echo number_format($product['price']); ?></span>
                        <span class="h5 text-muted text-decoration-line-through ms-2">₹<?php echo number_format($product['original_price']); ?></span>
                        <span class="badge bg-danger ms-2"><?php echo $product['discount']; ?>% OFF</span>
                    </div>

                    <p class="text-muted mb-4"><?php echo $product['description']; ?></p>

                    <div class="mb-4">
                        <h6>Color:</h6>
                        <div class="d-flex gap-2">
                            <?php foreach($product['colors'] as $color): ?>
                            <div class="color-option" style="background-color: <?php echo $color['code']; ?>" 
                                 title="<?php echo $color['name']; ?>" onclick="selectColor(this)"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Size:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach($product['sizes'] as $size): ?>
                            <button class="btn size-btn" onclick="selectSize(this)"><?php echo $size; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Quantity:</h6>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-secondary" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="form-control mx-2" value="1" min="1" max="<?php echo $product['stock']; ?>" id="quantity" style="width: 80px;">
                            <button class="btn btn-outline-secondary" onclick="changeQuantity(1)">+</button>
                            <span class="text-muted ms-2"><?php echo $product['stock']; ?> available</span>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <button class="btn btn-primary-custom flex-fill">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <h6>Features:</h6>
                        <ul class="list-unstyled">
                            <?php foreach($product['features'] as $feature): ?>
                            <li><i class="bi bi-check-circle text-success me-2"></i><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="border-top pt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-truck text-primary"></i>
                                <small class="d-block">Free Shipping</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-clockwise text-success"></i>
                                <small class="d-block">Easy Returns</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-shield-check text-warning"></i>
                                <small class="d-block">Secure Payment</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-white rounded-3 p-4">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Shipping</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="p-3">
                                <h5>Product Description</h5>
                                <p><?php echo $product['description']; ?></p>
                                <p>This beautiful saree is crafted with premium silk fabric and features intricate hand embroidery work. The design is perfect for weddings, festivals, and special occasions. The saree comes with a matching blouse piece and is available in multiple sizes to ensure the perfect fit.</p>
                                <p>Care Instructions: Dry clean only. Store in a cool, dry place away from direct sunlight.</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="p-3">
                                <h5>Customer Reviews</h5>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h2 class="text-warning"><?php echo $product['rating']; ?></h2>
                                        <div class="text-warning mb-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= $product['rating'] ? '-fill' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-muted"><?php echo $product['reviews']; ?> reviews</p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between">
                                                <h6>Priya Sharma</h6>
                                                <div class="text-warning">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                            <p class="text-muted">Beautiful saree! The embroidery work is exquisite and the fabric quality is excellent.</p>
                                        </div>
                                        <div class="border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between">
                                                <h6>Anjali Patel</h6>
                                                <div class="text-warning">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star"></i>
                                                </div>
                                            </div>
                                            <p class="text-muted">Great product, fast delivery. The color is exactly as shown in the picture.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel">
                            <div class="p-3">
                                <h5>Shipping Information</h5>
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Free shipping on orders above ₹999</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Standard delivery: 3-5 business days</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Express delivery: 1-2 business days</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Cash on delivery available</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Easy returns within 30 days</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Related Products</h4>
                <div class="row g-4">
                    <?php foreach($related_products as $related): ?>
                    <div class="col-md-4">
                        <div class="card product-card h-100">
                            <img src="product_1760297261_1.jpg" class="card-img-top" alt="<?php echo $related['name']; ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $related['name']; ?></h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning me-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= $related['rating'] ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <small class="text-muted">(<?php echo $related['rating']; ?>)</small>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-bold text-danger">₹<?php echo number_format($related['price']); ?></span>
                                        <small class="text-muted text-decoration-line-through">₹<?php echo number_format($related['original_price']); ?></small>
                                    </div>
                                    <a href="product-detail.php?id=<?php echo $related['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
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

        // Product detail page specific JS
        // The original changeImage function is no longer needed with the carousel
        // and has been removed to avoid confusion.

        function selectColor(colorOption) {
            document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
            colorOption.classList.add('active');
        }

        function selectSize(sizeBtn) {
            document.querySelectorAll('.size-btn').forEach(s => s.classList.remove('active'));
            sizeBtn.classList.add('active');
        }

        function changeQuantity(delta) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + delta;
            
            if (newValue >= 1 && newValue <= <?php echo $product['stock']; ?>) {
                quantityInput.value = newValue;
            }
        }
    </script>
</body>
</html>