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
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item"><a href="products.php?category=<?php echo $product['category']; ?>"><?php echo ucfirst($product['category']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <div class="mb-3">
                        <img src="<?php echo $product['images'][0]; ?>" class="img-fluid rounded" id="mainImage" alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="row g-2">
                        <?php foreach($product['images'] as $index => $image): ?>
                        <div class="col-3">
                            <img src="<?php echo $image; ?>" class="img-fluid rounded thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 onclick="changeImage(this, '<?php echo $image; ?>')" alt="Product Image <?php echo $index + 1; ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h2 class="mb-3"><?php echo $product['name']; ?></h2>
                    
                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?php echo $i <= $product['rating'] ? '-fill' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="me-3"><?php echo $product['rating']; ?></span>
                        <span class="text-muted">(<?php echo $product['reviews']; ?> reviews)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <span class="h3 text-danger fw-bold">₹<?php echo number_format($product['price']); ?></span>
                        <span class="h5 text-muted text-decoration-line-through ms-2">₹<?php echo number_format($product['original_price']); ?></span>
                        <span class="badge bg-danger ms-2"><?php echo $product['discount']; ?>% OFF</span>
                    </div>

                    <!-- Description -->
                    <p class="text-muted mb-4"><?php echo $product['description']; ?></p>

                    <!-- Color Selection -->
                    <div class="mb-4">
                        <h6>Color:</h6>
                        <div class="d-flex gap-2">
                            <?php foreach($product['colors'] as $color): ?>
                            <div class="color-option" style="background-color: <?php echo $color['code']; ?>" 
                                 title="<?php echo $color['name']; ?>" onclick="selectColor(this)"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Size Selection -->
                    <div class="mb-4">
                        <h6>Size:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach($product['sizes'] as $size): ?>
                            <button class="btn size-btn" onclick="selectSize(this)"><?php echo $size; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <h6>Quantity:</h6>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-secondary" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="form-control mx-2" value="1" min="1" max="<?php echo $product['stock']; ?>" id="quantity" style="width: 80px;">
                            <button class="btn btn-outline-secondary" onclick="changeQuantity(1)">+</button>
                            <span class="text-muted ms-2"><?php echo $product['stock']; ?> available</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mb-4">
                        <button class="btn btn-primary-custom flex-fill">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <h6>Features:</h6>
                        <ul class="list-unstyled">
                            <?php foreach($product['features'] as $feature): ?>
                            <li><i class="bi bi-check-circle text-success me-2"></i><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Delivery Info -->
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

        <!-- Product Details Tabs -->
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
                                        <!-- Sample Reviews -->
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

        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Related Products</h4>
                <div class="row g-4">
                    <?php foreach($related_products as $related): ?>
                    <div class="col-md-4">
                        <div class="card product-card h-100">
                            <img src="<?php echo $related['image']; ?>" class="card-img-top" alt="<?php echo $related['name']; ?>">
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

    <!-- Footer -->
   <?php include 'footer.php'; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function changeImage(thumbnail, imageSrc) {
            document.getElementById('mainImage').src = imageSrc;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }

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