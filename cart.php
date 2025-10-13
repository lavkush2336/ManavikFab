<?php
session_start();
include 'connection.php';

// Sample cart data
$cart_items = [
    [
        'id' => 1,
        'name' => 'Embroidered Silk Saree',
        'price' => 2499,
        'original_price' => 3999,
        'image' => 'images/product1.jpg',
        'size' => 'M',
        'color' => 'Red',
        'quantity' => 1
    ],
    [
        'id' => 2,
        'name' => 'Designer Lehenga Set',
        'price' => 5999,
        'original_price' => 8999,
        'image' => 'images/product2.jpg',
        'size' => 'L',
        'color' => 'Blue',
        'quantity' => 1
    ],
    [
        'id' => 3,
        'name' => 'Cotton Kurti with Palazzo',
        'price' => 1299,
        'original_price' => 1999,
        'image' => 'images/product3.jpg',
        'size' => 'S',
        'color' => 'Green',
        'quantity' => 2
    ]
];

$subtotal = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $cart_items));

// Calculate total savings across cart
$total_savings = array_sum(array_map(function($item){
    return ($item['original_price'] - $item['price']) * $item['quantity'];
}, $cart_items));

$shipping = $subtotal > 999 ? 0 : 200;
$tax = $subtotal * 0.18; // 18% GST
$total = $subtotal + $shipping + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ManavikFab</title>
    <meta name="description" content="View and manage your shopping cart at ManavikFab. Secure checkout with multiple payment options.">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        html, body {
            height: 100%;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #fff0f6 0%, #fff8fb 100%);
        }
        
        /* HEADER CSS FROM index.php */
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
            background: #ffffff; /* Change for cart page to be visible */
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
            color: #444; /* Set link color to dark */
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
            color: #2d2d2d; /* Set icon color to dark */
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
        
        .cart-container {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
        }
        .cart-item {
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 12px;
            padding: 0.9rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 20px rgba(16,24,40,0.04);
            display: flex;gap:1rem;align-items:center
        }
        .cart-item .item-img{flex:0 0 110px}
        .cart-item .item-img img{width:110px;height:110px;object-fit:cover;border-radius:8px}
        .cart-item .item-meta{flex:1}
        .cart-item .item-actions{display:flex;flex-direction:column;gap:.5rem;align-items:flex-end}
        .cart-item .price-row{display:flex;gap:.5rem;align-items:center}
        .cart-item .original-price{text-decoration:line-through;color:#9aa0a6;font-size:.9rem}
        .cart-item .savings{color:#198754;font-weight:700}
        .cart-item .action-icon{background:transparent;border:none;color:#6c757d;font-size:1.1rem}
        .btn-primary-custom {
            background: linear-gradient(90deg,#ff6aa6,#ff4f87);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 0.95rem 2.25rem;
            border-radius: 12px;
            transition: all 0.22s ease;
            box-shadow: 0 12px 30px rgba(255,79,135,0.12);
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .footer {
            background: #2d2d2d;
            color: white;
        }
        /* Ensure footer text and links are readable on dark background */
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
        .quantity-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            background: white;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .quantity-btn:hover {
            background: #f4b6cc;
            border-color: #f4b6cc;
            color: white;
        }
        /* Order summary emphasis */
        .order-summary{background:linear-gradient(180deg,#fff,#fff);border-radius:12px;padding:1.25rem;border:1px solid rgba(0,0,0,0.04);box-shadow:0 10px 30px rgba(16,24,40,0.04)}
        .order-summary .total-amount{font-size:1.6rem;font-weight:800;color:#c12b5a}
        .btn-checkout{background:#ff3f7a;color:#fff;border:none;padding:1rem 1.5rem;border-radius:12px;font-weight:800;font-size:1rem;box-shadow:0 18px 40px rgba(255,63,122,0.12)}
        .secure-checkout{display:flex;align-items:center;gap:.5rem;color:#6c757d;font-size:.9rem;margin-top:.6rem}
        .secure-checkout i{color:#198754}

        /* Recently viewed carousel */
        .recent-carousel{display:flex;gap:1rem;overflow:auto;padding-bottom:.5rem}
        .recent-card{min-width:220px;border-radius:12px;border:1px solid rgba(0,0,0,0.06);overflow:hidden;background:#fff;box-shadow:0 10px 30px rgba(16,24,40,0.04)}
        .recent-card .img-wrap{height:180px;overflow:hidden}
        .recent-card img{width:100%;height:100%;object-fit:cover}
        .recent-card .card-body{padding:.75rem}
        .recent-card .add-cart{background:#ff6aa6;border:none;color:#fff;padding:.45rem .6rem;border-radius:8px}

        /* Additional cart redesign styles (appended) */
        .cart-item{
            display:flex;
            gap:16px;
            align-items:flex-start;
            padding:16px;
            border-radius:12px;
            box-shadow:0 6px 18px rgba(0,0,0,0.06);
            background:#fff;
            margin-bottom:14px;
        }
        .cart-item .item-img{
            width:120px;
            height:120px;
            flex:0 0 120px;
            overflow:hidden;
            border-radius:8px;
        }
        .cart-item .item-img img{width:100%;height:100%;object-fit:cover;display:block}
        .cart-item .item-meta{flex:1}
        .cart-item .price-row{display:flex;gap:8px;align-items:center}
        .original-price{color:#888;text-decoration:line-through;font-size:.9rem}
        .savings{color:#1a9a48;font-weight:600;font-size:.95rem;margin-left:8px}
        .item-actions{display:flex;flex-direction:column;align-items:flex-end;gap:8px;min-width:120px}
        .quantity-btn{background:#f4f4f6;border:0;padding:6px 10px;border-radius:6px}
        .action-icon{background:transparent;border:0;font-size:1.05rem;padding:6px}

        .order-summary{background:linear-gradient(180deg,#fff 0,#fff 100%);padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.06)}
        .order-summary .total-amount{font-size:1.5rem;color:#e83e8c;font-weight:800}
        .btn-checkout{background:linear-gradient(90deg,#ff6fbf,#ff2d95);border:0;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700}
        .secure-checkout{display:flex;align-items:center;gap:8px;color:#6c757d;margin-top:8px}
        .secure-checkout i{background:#f8f9fa;padding:6px;border-radius:6px;color:#ff2d95}

        .recent-carousel{display:flex;gap:12px;overflow-x:auto;padding-bottom:8px}
        .recent-card{min-width:220px;background:#fff;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06);overflow:hidden}
        .recent-card .img-wrap{height:140px;overflow:hidden}
        .recent-card .img-wrap img{width:100%;height:100%;object-fit:cover}
        .recent-card .card-body{padding:10px}
        .add-cart{background:#fff;border:1px solid #ff2d95;color:#ff2d95;padding:6px 10px;border-radius:8px}

        /* small screens */
        @media (max-width:767px){
            .cart-item{flex-direction:column;gap:12px}
            .item-actions{align-items:flex-start;min-width:auto}
            .cart-item .item-img{width:100%;height:220px;flex:0 0 auto}
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
                        <span class="nav-badge"><?php echo count($cart_items); ?></span>
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

    <!-- <div class="container mt-4 pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>
    </div> -->

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="cart-container">
                    <h3 class="mb-4">Shopping Cart (<?php echo count($cart_items); ?> items)</h3>
                    
                    <?php if(empty($cart_items)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cart3 text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Your cart is empty</h4>
                            <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                            <a href="products.php" class="btn btn-primary-custom">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="item-img">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="item-meta">
                                <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                <small class="text-muted d-block mb-2">Size: <?php echo $item['size']; ?> | Color: <?php echo $item['color']; ?></small>
                                <div class="price-row">
                                    <span class="fw-bold text-danger">₹<?php echo number_format($item['price']); ?></span>
                                    <span class="original-price">₹<?php echo number_format($item['original_price']); ?></span>
                                    <span class="savings">You save ₹<?php echo number_format(($item['original_price'] - $item['price']) * $item['quantity']); ?></span>
                                </div>
                            </div>
                            <div class="item-actions">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                                        <span class="mx-3" id="quantity-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                                    </div>
                                </div>
                                <div class="mt-2 text-end">
                                    <span class="fw-bold">₹<?php echo number_format($item['price'] * $item['quantity']); ?></span>
                                </div>
                                <div class="mt-3">
                                    <button class="action-icon" title="Move to wishlist" onclick="moveToWishlist(<?php echo $item['id']; ?>)"><i class="bi bi-heart"></i></button>
                                    <button class="action-icon text-danger" title="Remove" onclick="removeItem(<?php echo $item['id']; ?>)"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="products.php" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <button class="btn btn-outline-secondary" onclick="clearCart()">
                                <i class="bi bi-trash me-2"></i>Clear Cart
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="mb-3">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span><?php echo $shipping == 0 ? '<span class="text-success">Free</span>' : '₹' . number_format($shipping); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (GST):</span>
                        <span>₹<?php echo number_format($tax); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Estimated Savings:</span>
                        <span class="text-success">₹<?php echo number_format($total_savings); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Total:</strong>
                        <div class="text-end">
                            <div class="total-amount">₹<?php echo number_format($total); ?></div>
                            <div class="text-muted" style="font-size:.9rem">Inclusive of taxes</div>
                        </div>
                    </div>
                    <?php if($shipping > 0): ?>
                        <div class="alert alert-info">
                            <small>Add ₹<?php echo 999 - $subtotal; ?> more to get free shipping!</small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <small><i class="bi bi-check-circle me-1"></i>Free shipping applied!</small>
                        </div>
                    <?php endif; ?>
                    <button class="btn-checkout w-100 mb-2" onclick="proceedToCheckout()">
                        <i class="bi bi-lock-fill me-2"></i>Proceed to Secure Checkout
                    </button>
                    <div class="secure-checkout"><i class="bi bi-shield-lock-fill"></i><span>Secure checkout — 256-bit SSL encryption</span></div>
                </div>

                <div class="cart-container mt-4">
                    <h6 class="mb-3">Have a Coupon?</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter coupon code">
                        <button class="btn btn-outline-primary">Apply</button>
                    </div>
                </div>

                <div class="cart-container mt-4">
                    <h6 class="mb-3">Accepted Payment Methods</h6>
                    <div class="row text-center">
                        <div class="col-3">
                            <i class="bi bi-credit-card text-primary"></i>
                            <small class="d-block">Cards</small>
                        </div>
                        <div class="col-3">
                            <i class="bi bi-phone text-success"></i>
                            <small class="d-block">UPI</small>
                        </div>
                        <div class="col-3">
                            <i class="bi bi-bank text-warning"></i>
                            <small class="d-block">Net Banking</small>
                        </div>
                        <div class="col-3">
                            <i class="bi bi-cash text-info"></i>
                            <small class="d-block">COD</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">You Might Also Like</h4>
                <div class="recent-carousel">
                    <?php
                    $recent_products = [
                        [
                            'name' => 'Western Dress Collection',
                            'price' => 1899,
                            'original_price' => 2499,
                            'image' => 'images/product4.jpg'
                        ],
                        [
                            'name' => 'Bridal Lehenga Set',
                            'price' => 8999,
                            'original_price' => 12999,
                            'image' => 'images/product5.jpg'
                        ],
                        [
                            'name' => 'Silk Anarkali Suit',
                            'price' => 3499,
                            'original_price' => 4999,
                            'image' => 'images/product6.jpg'
                        ]
                    ];

                    foreach($recent_products as $product):
                    ?>
                    <div class="recent-card">
                        <div class="img-wrap">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1"><?php echo $product['name']; ?></h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-danger">₹<?php echo number_format($product['price']); ?></span>
                                    <small class="text-muted text-decoration-line-through ms-2">₹<?php echo number_format($product['original_price']); ?></small>
                                </div>
                                <button class="add-cart" onclick="addToCart('<?php echo $product['name']; ?>')">Add</button>
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

        // Cart-specific JavaScript
        function updateQuantity(itemId, delta) {
            const quantityElement = document.getElementById(`quantity-${itemId}`);
            let currentQuantity = parseInt(quantityElement.textContent);
            let newQuantity = currentQuantity + delta;
            
            if (newQuantity >= 1) {
                quantityElement.textContent = newQuantity;
                updateCart();
            }
        }

        function removeItem(itemId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                // Remove item from cart
                const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                updateCart();
            }
        }

        function clearCart() {
            if (confirm('Are you sure you want to clear your entire cart?')) {
                // Clear all cart items
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach(item => item.remove());
                updateCart();
            }
        }

        function updateCart() {
            // Update cart totals and item count
            // This would typically make an AJAX call to update the cart
            console.log('Cart updated');
        }

        function proceedToCheckout() {
            // Redirect to checkout page
            window.location.href = 'checkout.php';
        }
    </script>
</body>
</html>