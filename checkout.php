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

// Sample saved addresses. In a real application, this would come from the database.
$saved_addresses = [
    [
        'id' => 1,
        'type' => 'Home',
        'name' => 'Priya Sharma',
        'address' => '123 Fashion Street, Connaught Place',
        'city_pin' => 'New Delhi - 110001',
        'phone' => '+91 98765 43210'
    ],
    [
        'id' => 2,
        'type' => 'Work',
        'name' => 'Priya Sharma',
        'address' => '456 Business Avenue, Cyber City',
        'city_pin' => 'Gurugram - 122002',
        'phone' => '+91 98765 43210'
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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            background: #ffffff; /* Change for checkout page */
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
        .form-control:focus {
            border-color: #f4b6cc;
            box-shadow: 0 0 0 0.2rem rgba(248, 201, 216, 0.3);
        }
        .saved-address-card {
            border: 2px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .saved-address-card:hover {
            border-color: #f4b6cc;
            background-color: #fef7f8;
        }
        .form-check-input:checked + .saved-address-card {
             border-color: #ff5f99;
             background-color: #fff0f6;
        }
        .form-check-input {
            display: none;
        }
        .btn-theme-outline {
            color: #c12b5a;
            border-color: #c12b5a;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-theme-outline:hover {
            color: #fff;
            background-color: #c12b5a;
            border-color: #c12b5a;
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

   
    <div class="container py-5" style="margin-top: 100px;">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <form id="checkoutForm">
                    <div class="checkout-container mb-4">
                        <h4 class="mb-3"><i class="bi bi-truck me-2"></i>Shipping Address</h4>
                        
                        <button class="btn btn-theme-outline mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addNewAddressCollapse" id="toggleAddressFormBtn">
                            <i class="bi bi-plus-circle me-2"></i>Add New Shipping Address
                        </button>

                        <div class="collapse" id="addNewAddressCollapse">
                            <div class="card card-body border-2" style="border-color: #f4b6cc !important;">
                                <h5 class="mb-3">Enter New Address Details</h5>
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
                                            <option value="punjab">Punjab</option>
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="saveAddress">
                                    <label class="form-check-label" for="saveAddress">
                                        Save this address for future use
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div id="savedAddressesWrapper">
                            <h5 class="mb-3"><i class="bi bi-journal-bookmark me-2"></i>Or Select a Saved Address</h5>
                            <?php if (empty($saved_addresses)): ?>
                                <div class="alert alert-info">
                                    You have no saved addresses. Please add a new one above.
                                </div>
                            <?php else: ?>
                                <?php foreach ($saved_addresses as $index => $address): ?>
                                    <label for="address-<?php echo $address['id']; ?>" class="w-100 mb-2">
                                        <input class="form-check-input" type="radio" name="selected_address" value="<?php echo $address['id']; ?>" id="address-<?php echo $address['id']; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                                        <div class="saved-address-card">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($address['type']); ?></span>
                                                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($address['name']); ?></p>
                                                    <p class="mb-1 text-muted small"><?php echo htmlspecialchars($address['address']); ?>, <?php echo htmlspecialchars($address['city_pin']); ?></p>
                                                    <p class="mb-0 text-muted small">Phone: <?php echo htmlspecialchars($address['phone']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="checkout-container">
                    <h4 class="mb-4">Order Summary</h4>
                    
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
                    
                    <button type="submit" form="checkoutForm" class="btn btn-primary-custom w-100 mb-3">
                        <i class="bi bi-lock me-2"></i>Proceed to Payment
                    </button>
                    
                    <div class="text-center">
                        <small class="text-muted">By proceeding, you agree to our Terms & Conditions</small>
                    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Search Bar functionality
            const searchTrigger = document.getElementById('searchTrigger');
            const mobileSearchTrigger = document.getElementById('mobileSearchTrigger');
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearchBtn = document.getElementById('closeSearch');

            const openSearch = () => {
                if (searchOverlay) searchOverlay.classList.add('active');
            };
            const closeSearch = () => {
                if (searchOverlay) searchOverlay.classList.remove('active');
            };

            if (searchTrigger) searchTrigger.addEventListener('click', openSearch);
            if (mobileSearchTrigger) mobileSearchTrigger.addEventListener('click', openSearch);
            if (closeSearchBtn) closeSearchBtn.addEventListener('click', closeSearch);
            if (searchOverlay) searchOverlay.addEventListener('click', (e) => {
                if (e.target === searchOverlay) closeSearch();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSearch();
            });

            // Checkout-specific JS
            const addressCollapseEl = document.getElementById('addNewAddressCollapse');
            const toggleBtn = document.getElementById('toggleAddressFormBtn');
            const savedAddressesWrapper = document.getElementById('savedAddressesWrapper');
            
            if (addressCollapseEl) {
                const radioButtons = savedAddressesWrapper.querySelectorAll('input[type="radio"]');

                addressCollapseEl.addEventListener('show.bs.collapse', () => {
                    toggleBtn.classList.remove('btn-theme-outline');
                    toggleBtn.classList.add('btn-danger');
                    toggleBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Cancel';
                    
                    savedAddressesWrapper.style.opacity = '0.5';
                    radioButtons.forEach(radio => {
                        radio.checked = false;
                        radio.disabled = true;
                    });
                });

                addressCollapseEl.addEventListener('hide.bs.collapse', () => {
                    toggleBtn.classList.add('btn-theme-outline');
                    toggleBtn.classList.remove('btn-danger');
                    toggleBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add New Shipping Address';

                    savedAddressesWrapper.style.opacity = '1';
                    radioButtons.forEach(radio => {
                        radio.disabled = false;
                    });
                    
                    if (radioButtons.length > 0) {
                        // Re-select the first saved address if it exists
                        radioButtons[0].checked = true;
                    }
                });
            }


            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const isAddingNewAddress = addressCollapseEl.classList.contains('show');
                let isAddressSelected = false;

                if (isAddingNewAddress) {
                    let isValid = true;
                    const requiredFields = document.querySelectorAll('#addNewAddressCollapse [required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        alert('Please fill in all required fields for the new address.');
                        return;
                    }
                    isAddressSelected = true;
                } else {
                    const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
                    if (selectedAddress) {
                        isAddressSelected = true;
                    }
                }
                
                if (!isAddressSelected) {
                    alert('Please select a shipping address or add a new one.');
                    return;
                }
                
                alert('Proceeding to payment...');
                window.location.href = 'order-confirmation.php';
            });
        });
    </script>
</body>
</html>