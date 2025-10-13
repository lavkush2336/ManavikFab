<?php
session_start();
include 'connection.php';

// Sample checkout data
$cart_items = [
    [
        'id' => 1,
        'name' => 'Embroidered Silk Saree',
        'price' => 2499,
        'image' => 'images/product1.jpg',
        'size' => 'M',
        'color' => 'Red',
        'quantity' => 1
    ],
    [
        'id' => 2,
        'name' => 'Designer Lehenga Set',
        'price' => 5999,
        'image' => 'images/product2.jpg',
        'size' => 'L',
        'color' => 'Blue',
        'quantity' => 1
    ]
];

$subtotal = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $cart_items));

$shipping = 0; // Free shipping
$tax = $subtotal * 0.18; // 18% GST
$total = $subtotal + $shipping + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ManavikFab</title>
    <meta name="description" content="Complete your purchase securely at ManavikFab. Multiple payment options available.">
    
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
        .checkout-container {
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
        .footer {
            background: #2d2d2d;
            color: white;
        }
        /* Ensure footer links, paragraphs, and list items are readable on dark background */
        footer.footer p,
        footer.footer li,
        footer.footer a,
        footer.footer .text-muted,
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
        /* Target the container descendants for maximum specificity */
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
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method:hover, .payment-method.active {
            border-color: #f4b6cc;
            background-color: #fef7f8;
        }
        .form-control:focus {
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
                        <span class="badge bg-danger ms-1"><?php echo count($cart_items); ?></span>
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

    <!-- Breadcrumb
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div> -->

    <!-- Checkout Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8 mb-4">
                <form id="checkoutForm">
                    <!-- Shipping Information -->
                    <div class="checkout-container mb-4">
                        <h4 class="mb-4">
                            <i class="bi bi-truck me-2"></i>Shipping Information
                        </h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="tel" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <input type="text" class="form-control" placeholder="Street Address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">State *</label>
                                <select class="form-select" required>
                                    <option value="">Select State</option>
                                    <option value="delhi">Delhi</option>
                                    <option value="mumbai">Mumbai</option>
                                    <option value="bangalore">Bangalore</option>
                                    <option value="chennai">Chennai</option>
                                    <option value="kolkata">Kolkata</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">PIN Code *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea class="form-control" rows="3" placeholder="Any special instructions for delivery"></textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-container mb-4">
                        <h4 class="mb-4">
                            <i class="bi bi-credit-card me-2"></i>Payment Method
                        </h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" onclick="selectPayment('card')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment" value="card" class="me-3">
                                        <div>
                                            <h6 class="mb-1">Credit/Debit Card</h6>
                                            <small class="text-muted">Visa, Mastercard, RuPay</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" onclick="selectPayment('upi')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment" value="upi" class="me-3">
                                        <div>
                                            <h6 class="mb-1">UPI</h6>
                                            <small class="text-muted">Google Pay, PhonePe, Paytm</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" onclick="selectPayment('netbanking')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment" value="netbanking" class="me-3">
                                        <div>
                                            <h6 class="mb-1">Net Banking</h6>
                                            <small class="text-muted">All major banks</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="payment-method" onclick="selectPayment('cod')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="payment" value="cod" class="me-3">
                                        <div>
                                            <h6 class="mb-1">Cash on Delivery</h6>
                                            <small class="text-muted">Pay when you receive</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" class="mt-4" style="display: none;">
                            <h6 class="mb-3">Card Details</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control" placeholder="Name on card">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="checkout-container">
                    <h4 class="mb-4">Order Summary</h4>
                    
                    <!-- Order Items -->
                    <?php foreach($cart_items as $item): ?>
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo $item['image']; ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                            <small class="text-muted">Size: <?php echo $item['size']; ?> | Color: <?php echo $item['color']; ?></small>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Qty: <?php echo $item['quantity']; ?></span>
                                <span class="fw-bold">₹<?php echo number_format($item['price'] * $item['quantity']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <!-- Price Breakdown -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (GST):</span>
                        <span>₹<?php echo number_format($tax); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong class="text-danger">₹<?php echo number_format($total); ?></strong>
                    </div>
                    
                    <!-- Security Info -->
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-shield-check me-1"></i>
                            Your payment information is secure and encrypted
                        </small>
                    </div>
                    
                    <!-- Place Order Button -->
                    <button type="submit" form="checkoutForm" class="btn btn-primary-custom w-100 mb-3">
                        <i class="bi bi-lock me-2"></i>Place Order Securely
                    </button>
                    
                    <div class="text-center">
                        <small class="text-muted">By placing your order, you agree to our Terms & Conditions</small>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="checkout-container mt-4">
                    <h6 class="mb-3">Delivery Information</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="bi bi-truck text-primary"></i>
                            <small class="d-block">Free Shipping</small>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-calendar text-success"></i>
                            <small class="d-block">3-5 Days</small>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-arrow-clockwise text-warning"></i>
                            <small class="d-block">Easy Returns</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
   <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectPayment(method) {
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('active');
            });
            
            // Add active class to selected payment method
            event.currentTarget.classList.add('active');
            
            // Show/hide card details
            const cardDetails = document.getElementById('cardDetails');
            if (method === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        // Form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Check if payment method is selected
            const selectedPayment = document.querySelector('input[name="payment"]:checked');
            if (!selectedPayment) {
                alert('Please select a payment method.');
                return;
            }
            
            // Process order
            alert('Order placed successfully! You will receive a confirmation email shortly.');
            window.location.href = 'order-confirmation.php';
        });
    </script>
</body>
</html> 