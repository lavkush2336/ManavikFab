<?php
session_start();
include 'connection.php';

// Sample wishlist data
$wishlist_items = [
    [
        'id' => 1,
        'name' => 'Embroidered Silk Saree',
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
        'price' => 1899,
        'original_price' => 2499,
        'image' => 'images/product4.jpg',
        'rating' => 4.6,
        'reviews' => 203,
        'discount' => 24
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - ManavikFab</title>
    <meta name="description" content="View and manage your wishlist at ManavikFab. Save your favorite products for later purchase.">
    
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
        .wishlist-container {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
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
                <li class="breadcrumb-item active" aria-current="page">My Wishlist</li>
            </ol>
        </nav>
    </div>

    <!-- Wishlist Section -->
    <div class="container py-5">
        <div class="wishlist-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">
                    <i class="bi bi-heart-fill text-danger me-2"></i>My Wishlist
                </h3>
                <span class="text-muted"><?php echo count($wishlist_items); ?> items</span>
            </div>
            
            <?php if(empty($wishlist_items)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Your wishlist is empty</h4>
                    <p class="text-muted">Start adding products to your wishlist to save them for later.</p>
                    <a href="products.php" class="btn btn-primary-custom">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach($wishlist_items as $item): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="product-card">
                            <div class="position-relative">
                                <img src="<?php echo $item['image']; ?>" class="card-img-top" alt="<?php echo $item['name']; ?>">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger"><?php echo $item['discount']; ?>% OFF</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <button class="btn btn-sm btn-light rounded-circle" onclick="removeFromWishlist(<?php echo $item['id']; ?>)">
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $item['name']; ?></h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning me-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= $item['rating'] ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <small class="text-muted">(<?php echo $item['rating']; ?>)</small>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="fw-bold text-danger">₹<?php echo number_format($item['price']); ?></span>
                                        <small class="text-muted text-decoration-line-through">₹<?php echo number_format($item['original_price']); ?></small>
                                    </div>
                                    <small class="text-muted"><?php echo $item['reviews']; ?> reviews</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary flex-fill" onclick="addToCart(<?php echo $item['id']; ?>)">
                                        <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                    </button>
                                    <a href="product-detail.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Wishlist Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button class="btn btn-outline-secondary" onclick="clearWishlist()">
                        <i class="bi bi-trash me-2"></i>Clear Wishlist
                    </button>
                    <a href="products.php" class="btn btn-primary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recommended Products -->
    <div class="container py-5">
        <h4 class="mb-4">You Might Also Like</h4>
        <div class="row g-4">
            <?php
            $recommended_products = [
                [
                    'name' => 'Bridal Lehenga Set',
                    'price' => 8999,
                    'original_price' => 12999,
                    'image' => 'images/product5.jpg',
                    'rating' => 4.9
                ],
                [
                    'name' => 'Silk Anarkali Suit',
                    'price' => 3499,
                    'original_price' => 4999,
                    'image' => 'images/product6.jpg',
                    'rating' => 4.4
                ],
                [
                    'name' => 'Casual Western Dress',
                    'price' => 999,
                    'original_price' => 1499,
                    'image' => 'images/product7.jpg',
                    'rating' => 4.2
                ],
                [
                    'name' => 'Designer Saree Collection',
                    'price' => 3999,
                    'original_price' => 5999,
                    'image' => 'images/product8.jpg',
                    'rating' => 4.7
                ]
            ];
            
            foreach($recommended_products as $product):
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="product-card">
                    <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
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
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold text-danger">₹<?php echo number_format($product['price']); ?></span>
                                <small class="text-muted text-decoration-line-through">₹<?php echo number_format($product['original_price']); ?></small>
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
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function removeFromWishlist(itemId) {
            if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                // Remove item from wishlist
                console.log('Removing item:', itemId);
                // Here you would typically make an AJAX call to remove the item
                location.reload();
            }
        }

        function addToCart(itemId) {
            // Add item to cart
            console.log('Adding to cart:', itemId);
            // Here you would typically make an AJAX call to add to cart
            alert('Item added to cart successfully!');
        }

        function clearWishlist() {
            if (confirm('Are you sure you want to clear your entire wishlist?')) {
                // Clear wishlist
                console.log('Clearing wishlist');
                // Here you would typically make an AJAX call to clear the wishlist
                location.reload();
            }
        }
    </script>
</body>
</html> 