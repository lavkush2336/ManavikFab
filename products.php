<?php
session_start();
include 'connection.php';

// Get category filter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ManavikFab | Shop Latest Fashion Trends</title>
    <meta name="description" content="Shop the latest fashion trends at ManavikFab. Browse our collection of ethnic wear, western wear, sarees, lehengas and accessories. Free shipping on orders above ₹999.">
    <meta name="keywords" content="women fashion, ethnic wear, western wear, sarees, lehengas, kurtis, dresses, online shopping">
    
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

        .product-card {
            background: white;
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
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
        .filter-sidebar {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            height: fit-content;
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
        .pagination .page-link {
            color: #f4b6cc;
            border-color: #f8c9d8;
        }
        .pagination .page-item.active .page-link {
            background-color: #f4b6cc;
            border-color: #f4b6cc;
            color: white;
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

    <div class="container" style="margin-top: 120px;">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-end">
                <select class="form-select" style="width: auto;">
                    <option value="newest">Newest First</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                    <option value="rating">Highest Rated</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <h5 class="mb-3">Filters</h5>
                    
                    <div class="mb-4">
                        <h6>Categories</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="all" value="" <?php echo $category == '' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="all">All Categories</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="ethnic" value="ethnic" <?php echo $category == 'ethnic' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="ethnic">Ethnic Wear</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="western" value="western" <?php echo $category == 'western' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="western">Western Wear</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="sarees" value="sarees" <?php echo $category == 'sarees' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="sarees">Sarees</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="lehengas" value="lehengas" <?php echo $category == 'lehengas' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="lehengas">Lehengas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="accessories" value="accessories" <?php echo $category == 'accessories' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="accessories">Accessories</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Price Range</h6>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control" placeholder="Min" name="price_min" value="<?php echo $price_min; ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" placeholder="Max" name="price_max" value="<?php echo $price_max; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Size</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="xs" name="size[]" value="XS">
                            <label class="form-check-label" for="xs">XS</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="s" name="size[]" value="S">
                            <label class="form-check-label" for="s">S</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="m" name="size[]" value="M">
                            <label class="form-check-label" for="m">M</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="l" name="size[]" value="L">
                            <label class="form-check-label" for="l">L</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="xl" name="size[]" value="XL">
                            <label class="form-check-label" for="xl">XL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="xxl" name="size[]" value="XXL">
                            <label class="form-check-label" for="xxl">XXL</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Color</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="red" name="color[]" value="red">
                                <label class="form-check-label" for="red">Red</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="blue" name="color[]" value="blue">
                                <label class="form-check-label" for="blue">Blue</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="green" name="color[]" value="green">
                                <label class="form-check-label" for="green">Green</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="yellow" name="color[]" value="yellow">
                                <label class="form-check-label" for="yellow">Yellow</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pink" name="color[]" value="pink">
                                <label class="form-check-label" for="pink">Pink</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="purple" name="color[]" value="purple">
                                <label class="form-check-label" for="purple">Purple</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">Apply Filters</button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">Showing 1-12 of 48 products</span>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>

                <div class="row g-4">
                    <?php
                    // Sample products data
                    $products = [
                        [
                            'id' => 1,
                            'name' => 'Embroidered Silk Saree',
                            'category' => 'sarees',
                            'price' => 2499,
                            'original_price' => 3999,
                            'image' => 'images/product1.jpg',
                            'rating' => 4.5,
                            'reviews' => 128,
                            'discount' => 37
                        ],
                        [
                            'id' => 2,
                            'name' => 'Designer Lehenga Set',
                            'category' => 'lehengas',
                            'price' => 5999,
                            'original_price' => 8999,
                            'image' => 'images/product2.jpg',
                            'rating' => 4.8,
                            'reviews' => 89,
                            'discount' => 33
                        ],
                        [
                            'id' => 3,
                            'name' => 'Cotton Kurti with Palazzo',
                            'category' => 'ethnic',
                            'price' => 1299,
                            'original_price' => 1999,
                            'image' => 'images/product3.jpg',
                            'rating' => 4.3,
                            'reviews' => 156,
                            'discount' => 35
                        ],
                        [
                            'id' => 4,
                            'name' => 'Western Dress Collection',
                            'category' => 'western',
                            'price' => 1899,
                            'original_price' => 2499,
                            'image' => 'images/product4.jpg',
                            'rating' => 4.6,
                            'reviews' => 203,
                            'discount' => 24
                        ],
                        [
                            'id' => 5,
                            'name' => 'Bridal Lehenga Set',
                            'category' => 'lehengas',
                            'price' => 8999,
                            'original_price' => 12999,
                            'image' => 'images/product5.jpg',
                            'rating' => 4.9,
                            'reviews' => 67,
                            'discount' => 31
                        ],
                        [
                            'id' => 6,
                            'name' => 'Silk Anarkali Suit',
                            'category' => 'ethnic',
                            'price' => 3499,
                            'original_price' => 4999,
                            'image' => 'images/product6.jpg',
                            'rating' => 4.4,
                            'reviews' => 142,
                            'discount' => 30
                        ],
                        [
                            'id' => 7,
                            'name' => 'Casual Western Dress',
                            'category' => 'western',
                            'price' => 999,
                            'original_price' => 1499,
                            'image' => 'images/product7.jpg',
                            'rating' => 4.2,
                            'reviews' => 178,
                            'discount' => 33
                        ],
                        [
                            'id' => 8,
                            'name' => 'Designer Saree Collection',
                            'category' => 'sarees',
                            'price' => 3999,
                            'original_price' => 5999,
                            'image' => 'images/product8.jpg',
                            'rating' => 4.7,
                            'reviews' => 95,
                            'discount' => 33
                        ],
                        [
                            'id' => 9,
                            'name' => 'Party Wear Lehenga',
                            'category' => 'lehengas',
                            'price' => 4499,
                            'original_price' => 6999,
                            'image' => 'images/product9.jpg',
                            'rating' => 4.5,
                            'reviews' => 113,
                            'discount' => 36
                        ],
                        [
                            'id' => 10,
                            'name' => 'Ethnic Kurti Set',
                            'category' => 'ethnic',
                            'price' => 899,
                            'original_price' => 1299,
                            'image' => 'images/product10.jpg',
                            'rating' => 4.1,
                            'reviews' => 234,
                            'discount' => 31
                        ],
                        [
                            'id' => 11,
                            'name' => 'Western Top & Jeans',
                            'category' => 'western',
                            'price' => 1499,
                            'original_price' => 1999,
                            'image' => 'images/product11.jpg',
                            'rating' => 4.3,
                            'reviews' => 167,
                            'discount' => 25
                        ],
                        [
                            'id' => 12,
                            'name' => 'Traditional Saree',
                            'category' => 'sarees',
                            'price' => 1799,
                            'original_price' => 2499,
                            'image' => 'images/product12.jpg',
                            'rating' => 4.6,
                            'reviews' => 189,
                            'discount' => 28
                        ]
                    ];

                    // Filter products based on category
                    if($category) {
                        $products = array_filter($products, function($product) use ($category) {
                            return $product['category'] == $category;
                        });
                    }

                    foreach($products as $product):
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card">
                            <div class="position-relative">
                                <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger"><?php echo $product['discount']; ?>% OFF</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <button class="btn btn-sm btn-light rounded-circle">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $product['name']; ?></h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning me-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= $product['rating'] ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <small class="text-muted">(<?php echo $product['rating']; ?>)</small>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="fw-bold text-danger">₹<?php echo number_format($product['price']); ?></span>
                                        <small class="text-muted text-decoration-line-through">₹<?php echo number_format($product['original_price']); ?></small>
                                    </div>
                                    <small class="text-muted"><?php echo $product['reviews']; ?> reviews</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                    </button>
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <nav aria-label="Product pagination" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
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
    </script>
</body>
</html>