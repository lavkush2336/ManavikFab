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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        <a class="nav-link active" href="products.php">All Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                
                <form class="d-flex me-3" method="GET" action="products.php">
                    <input class="form-control search-bar" type="search" name="search" placeholder="Search for products..." value="<?php echo htmlspecialchars($search); ?>">
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
                <li class="breadcrumb-item active" aria-current="page">Products</li>
                <?php if($category): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo ucfirst($category); ?></li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <!-- Products Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <h5 class="mb-3">Filters</h5>
                    
                    <!-- Category Filter -->
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

                    <!-- Price Range -->
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

                    <!-- Size Filter -->
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

                    <!-- Color Filter -->
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

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort and View Options -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">Showing 1-12 of 48 products</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <select class="form-select" style="width: auto;">
                            <option value="newest">Newest First</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
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

                <!-- Pagination -->
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

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 