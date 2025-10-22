<?php
session_start();
include 'connection.php';

// Get category filter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';

// --- Dummy Data and Filtering Logic ---
$products = [
    [
        'id' => 1,
        'name' => 'Embroidered Silk Saree',
        'brand' => 'ManavikFab Premium',
        'category' => 'sarees',
        'price' => 2499,
        'original_price' => 3999,
        'image' => 'images/product1.jpg',
        'rating' => 4.5,
        'reviews' => 128,
        'discount' => 37,
        'color' => 'Red',
        'sizes' => ['S', 'M', 'L']
    ],
    [
        'id' => 2,
        'name' => 'Designer Lehenga Set',
        'brand' => 'Sarees',
        'category' => 'lehengas',
        'price' => 5999,
        'original_price' => 8999,
        'image' => 'images/product2.jpg',
        'rating' => 4.8,
        'reviews' => 89,
        'discount' => 33,
        'color' => 'Blue',
        'sizes' => ['M', 'L', 'XL']
    ],
    [
        'id' => 3,
        'name' => 'Cotton Kurti with Palazzo',
        'brand' => 'Cotton Dreams',
        'category' => 'ethnic',
        'price' => 1299,
        'original_price' => 1999,
        'image' => 'images/product3.jpg',
        'rating' => 4.3,
        'reviews' => 156,
        'discount' => 35,
        'color' => 'Green',
        'sizes' => ['XS', 'S', 'M']
    ],
    [
        'id' => 4,
        'name' => 'Western Dress Collection',
        'brand' => 'Glamour Fit',
        'category' => 'western',
        'price' => 1899,
        'original_price' => 2499,
        'image' => 'images/product4.jpg',
        'rating' => 4.6,
        'reviews' => 203,
        'discount' => 24,
        'color' => 'Pink',
        'sizes' => ['S', 'M', 'L']
    ],
    // Added more data for demonstration of filters
    [
        'id' => 5,
        'name' => 'Bridal Lehenga Set',
        'brand' => 'ManavikFab Premium',
        'category' => 'lehengas',
        'price' => 8999,
        'original_price' => 12999,
        'image' => 'images/product5.jpg',
        'rating' => 4.9,
        'reviews' => 67,
        'discount' => 31,
        'color' => 'Red',
        'sizes' => ['L', 'XL']
    ],
    [
        'id' => 6,
        'name' => 'Silk Anarkali Suit',
        'brand' => 'Cotton Dreams',
        'category' => 'ethnic',
        'price' => 3499,
        'original_price' => 4999,
        'image' => 'images/product6.jpg',
        'rating' => 4.4,
        'reviews' => 142,
        'discount' => 30,
        'color' => 'Purple',
        'sizes' => ['XS', 'S']
    ],
];

$all_categories = ['sarees', 'lehengas', 'ethnic', 'western', 'accessories'];
$all_brands = ['ManavikFab Premium', 'Cotton Dreams', 'Glamour Fit', 'Sarees', 'Ethnic Touch'];
$all_sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
$all_colors = ['Red', 'Blue', 'Green', 'Pink', 'Purple', 'Yellow'];

// Filter products based on category, price, etc. (basic PHP filtering for demo)
$filtered_products = $products;

if($category) {
    $filtered_products = array_filter($filtered_products, function($product) use ($category) {
        return $product['category'] == $category;
    });
}
// You would expand the filtering logic here for brands, prices, etc.

$product_count = count($filtered_products);
$display_count = min(12, $product_count);
$products_to_display = array_slice($filtered_products, 0, $display_count);
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
            background: #f1f1f1; /* Changed background for contrast */
            padding-top: 5rem; 
        }

        /* HEADER CSS (Kept from original) */
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
            padding: 0.5rem 0; /* Reduced padding */
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

        /* Fullscreen search overlay - Kept */
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
        /* END HEADER CSS */
        
        /* NEW STYLING FOR MYNTRA-LIKE EXPERIENCE */
        .filter-sidebar {
            /* On large screens (lg), the sidebar becomes fixed */
            position: sticky;
            top: 5rem; /* Space below the fixed header */
            height: calc(100vh - 5rem); /* Full height minus header height */
            overflow-y: auto; /* Allows scrolling within the filter panel */
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            z-index: 10;
        }

        /* Custom scrollbar for filter panel (optional, but nice) */
        .filter-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .filter-sidebar::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 3px;
        }

        .filter-heading {
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            color: #535766; /* Myntra-like gray */
            border-bottom: 1px solid #eaeaec;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }

        .product-card {
            /* Adjusted for a cleaner, modern look */
            background: white;
            border-radius: 0.25rem; /* Sharper corners */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        .product-card:hover {
            transform: none; /* Removed lift effect for flat Myntra look */
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .product-card .card-body {
            padding: 0.75rem;
        }
        .product-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .product-card .card-text-brand {
            font-size: 0.9rem;
            color: #535766; /* Myntra gray */
            font-weight: 500;
            margin-bottom: 0;
        }
        .product-card .card-text-name {
            font-size: 0.9rem;
            color: #535766;
            margin-bottom: 0.5rem;
        }
        
        .price-text {
            font-size: 1rem;
            font-weight: 700;
            color: #282c3f; /* Dark text for price */
        }
        .original-price-text {
            font-size: 0.9rem;
            color: #888;
            margin-left: 0.5rem;
        }
        .discount-text {
            font-size: 0.9rem;
            color: #ff905a; /* Discount color */
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .btn-primary-custom {
            /* Hidden on this layout, filter applies automatically */
            display: none; 
        }

        .sort-view-bar {
            position: sticky;
            top: 5rem; /* Below fixed header */
            z-index: 50;
            background: #fff;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05); /* subtle shadow to lift it */
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }

        /* Responsive filter sidebar toggle */
        @media (max-width: 991.98px) {
            .filter-sidebar-wrapper {
                position: fixed;
                top: 5rem;
                left: 0;
                width: 100%;
                height: calc(100vh - 5rem);
                background: white;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                overflow-y: auto;
            }
            .filter-sidebar-wrapper.active {
                transform: translateX(0);
            }
            .filter-sidebar {
                position: relative; /* Override sticky inside wrapper */
                height: auto;
                top: 0;
                border-radius: 0;
            }
        }
        
        /* Checkbox customization (for cleaner look) */
        .form-check-input:checked {
            background-color: #ff3f6c; /* Myntra Pink */
            border-color: #ff3f6c;
        }
        .form-check-input {
            border-radius: 0.15rem; /* Square checkboxes */
            border-width: 2px;
        }
    </style>
</head>
<body>
    <!-- Navbar (Kept from original) -->
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
                       <a href="login.php" class="nav-icon" title="Login/Signup"><i class="bi bi-person-circle"></i></a>
                    <?php endif; ?>
                    <a href="wishlist.php" class="nav-icon" title="Wishlist"><i class="bi bi-heart"></i></a>
                    <a href="cart.php" class="nav-icon nav-cart" title="Cart">
                        <i class="bi bi-cart3"></i>
                        <span class="nav-badge">3</span>
                    </a>
                </div>
                <button class="btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavCollapse" aria-expanded="false" aria-controls="siteNavCollapse">
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
    
    <!-- Search Overlay (Kept from original) -->
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
    
    <!-- Main Content Grid -->
    <div class="container py-3">
        <div class="row">
            <!-- Breadcrumb and Product Count (Myntra-like header) -->
            <div class="col-12">
                
                <h1 class="text-2xl font-bold mb-1 text-gray-800">Ethnic Wear for Women</h1>
                <p class="text-gray-500 mb-4"><?php echo $product_count; ?> Items</p>
                
                <!-- Mobile Filter Trigger -->
                <button id="mobileFilterToggle" class="btn btn-sm btn-outline-secondary d-lg-none mb-3 w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3 filter-sidebar-wrapper d-lg-block" id="filterSidebarWrapper">
                <form method="GET" action="products.php" class="filter-sidebar">
                    <div class="d-flex justify-content-between align-items-center mb-4 d-lg-none">
                        <h5 class="mb-0">Filter By</h5>
                        <button type="button" class="btn-close" id="closeFilterSidebar" aria-label="Close"></button>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6 class="filter-heading">Categories</h6>
                        <?php foreach($all_categories as $cat): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="cat_<?php echo $cat; ?>" value="<?php echo $cat; ?>" onchange="this.form.submit()" <?php echo $category == $cat ? 'checked' : ''; ?>>
                                <label class="form-check-label text-gray-700" for="cat_<?php echo $cat; ?>"><?php echo ucwords($cat); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <h6 class="filter-heading">Brand</h6>
                        <?php foreach(array_slice($all_brands, 0, 5) as $brand): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="brand[]" id="brand_<?php echo str_replace(' ', '_', $brand); ?>" value="<?php echo $brand; ?>">
                                <label class="form-check-label text-gray-700" for="brand_<?php echo str_replace(' ', '_', $brand); ?>"><?php echo $brand; ?></label>
                            </div>
                        <?php endforeach; ?>
                        <a href="#" class="text-pink-600 hover:text-pink-800 text-sm mt-1 d-block">Show more...</a>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-4">
                        <h6 class="filter-heading">Price Range</h6>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control form-control-sm me-2" placeholder="Min" name="price_min" value="<?php echo $price_min; ?>">
                            <span class="text-gray-500">-</span>
                            <input type="number" class="form-control form-control-sm ms-2" placeholder="Max" name="price_max" value="<?php echo $price_max; ?>">
                        </div>
                    </div>
                    
                    <!-- Discount Filter (New) -->
                    <div class="mb-4">
                        <h6 class="filter-heading">Discount Range</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" id="disc_10" value="10">
                            <label class="form-check-label text-gray-700" for="disc_10">10% and above</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" id="disc_30" value="30">
                            <label class="form-check-label text-gray-700" for="disc_30">30% and above</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="discount" id="disc_50" value="50">
                            <label class="form-check-label text-gray-700" for="disc_50">50% and above</label>
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="mb-4">
                        <h6 class="filter-heading">Size</h6>
                        <?php foreach($all_sizes as $size): ?>
                            <div class="form-check form-check-inline me-2">
                                <input class="form-check-input" type="checkbox" id="size_<?php echo $size; ?>" name="size[]" value="<?php echo $size; ?>">
                                <label class="form-check-label text-gray-700" for="size_<?php echo $size; ?>"><?php echo $size; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Submit button for mobile form -->
                    <button type="submit" class="btn btn-primary-custom w-100 d-lg-none mt-4">Show Products</button>
                </form>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <!-- Sort and View Bar (Sticky) -->
                <div class="sort-view-bar d-flex justify-content-between align-items-center">
                    <div class="text-sm font-semibold text-gray-700 d-none d-md-block">
                        Sort By: <span class="font-normal text-gray-500">Recommended</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option value="recommended">Recommended</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="newest">Newest First</option>
                            <option value="popular">Popularity</option>
                        </select>
                        <div class="btn-group d-none d-md-flex" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary active">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <?php
                    // Display products in the grid
                    foreach($products_to_display as $product):
                    ?>
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="product-card">
                            <div class="position-relative overflow-hidden">
                                <img src="<?php echo $product['image']; ?>" class="w-full h-auto object-cover" onerror="this.onerror=null; this.src='https://placehold.co/400x600/f0f0f0/333?text=Product+Image';" alt="<?php echo $product['name']; ?>">
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    <!-- Simple heart button for wishlist -->
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm" style="opacity: 0.8;">
                                        <i class="bi bi-heart text-danger"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text-brand text-gray-900 font-bold"><?php echo $product['brand']; ?></p>
                                <p class="card-text-name truncate text-gray-600"><?php echo $product['name']; ?></p>
                                
                                <div class="d-flex align-items-center mb-1">
                                    <span class="price-text">₹<?php echo number_format($product['price']); ?></span>
                                    <span class="original-price-text text-decoration-line-through">₹<?php echo number_format($product['original_price']); ?></span>
                                    <span class="discount-text">(<?php echo $product['discount']; ?>% OFF)</span>
                                </div>
                                <!-- The rating stars and number were here. They have been removed. -->
                                <!-- No "Add to Cart" button, Myntra typically requires going to the product detail page first -->
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

        // Dynamic Search Bar functionality (Kept from original)
        document.addEventListener('DOMContentLoaded', function() {
            const searchTrigger = document.getElementById('searchTrigger');
            const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearchBtn = document.getElementById('closeSearch');
            
            // New Filter Toggle Logic
            const mobileFilterToggle = document.getElementById('mobileFilterToggle');
            const filterSidebarWrapper = document.getElementById('filterSidebarWrapper');
            const closeFilterSidebar = document.getElementById('closeFilterSidebar');

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
            
            const openFilter = () => {
                if (filterSidebarWrapper) {
                    filterSidebarWrapper.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                }
            };

            const closeFilter = () => {
                if (filterSidebarWrapper) {
                    filterSidebarWrapper.classList.remove('active');
                    document.body.style.overflow = ''; // Restore scrolling
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

            if (mobileFilterToggle) {
                mobileFilterToggle.addEventListener('click', openFilter);
            }
            
            if (closeFilterSidebar) {
                closeFilterSidebar.addEventListener('click', closeFilter);
            }
            
            // Automatically submit the form on radio/checkbox change (Myntra behavior)
            const filterForm = document.querySelector('.filter-sidebar');
            if(filterForm) {
                filterForm.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                    input.addEventListener('change', () => {
                        // For a real application, you might want to debounce this or only submit on a button press,
                        // but this mimics the instant filtering of sites like Myntra.
                        filterForm.submit();
                    });
                });
            }


            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeSearch();
                    closeFilter(); // Also close filter sidebar on escape
                }
            });
        });
    </script>
</body>
</html>
