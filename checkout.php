<?php
session_start();

include 'connection.php'; // Make sure this file correctly establishes $con

// --- PHP BLOCK TO HANDLE AJAX REQUESTS (CRITICAL FOR SESSION UPDATE ON RADIO CHANGE) ---
// This checks if a request was made specifically to update the session address
if (isset($_POST['action']) && $_POST['action'] === 'update_session_address' && isset($_POST['address_id'])) {
    
    // Sanitize the input
    $addressId = filter_var($_POST['address_id'], FILTER_VALIDATE_INT);
    
    if ($addressId !== false && $addressId > 0) {
        // --- THE FIX: Set the session variable directly via AJAX ---
        $_SESSION['address'] = $addressId;
        
        // Send a success JSON response and exit
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'address_id' => $addressId]);
        exit;
    } else {
        // Send an error JSON response and exit
        header('Content-Type: application/json');
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Invalid address ID.']);
        exit;
    }
}
// --- END PHP BLOCK ---


// 1. Initial login check (assuming intent is to redirect if NOT logged in)
if(!isset($_SESSION['userid']))
{
    // The original code had a redundant assignment: $id=$_SESSION['userid']; 
    // when it immediately checks for its non-existence.
    header('Location: login.php');
    exit();
}

$id = $_SESSION['userid']; // Get the logged-in user ID

// 2. Handle New Address Submission (only runs if the 'Ship' button was pressed)
if(isset($_POST['save']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $add = $_POST['add'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pin = $_POST['pin'];
    $phone = $_POST['phone'];

    if(isset($_POST['check']))
    {
        // Save to Database
        // NOTE: Always use prepared statements in a production environment to prevent SQL injection.
        $sql = "INSERT INTO `Address`(`UserID`,`FName`, `LName`, `Address`, `City`, `State`, `Pin`, `Phone`) 
                VALUES ('$id', '$fname', '$lname', '$add', '$city', '$state', '$pin', '$phone')";
        
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            // Get the ID of the newly inserted address
            $newAddressId = mysqli_insert_id($con); 
            // Also set the session to the new address ID
            $_SESSION['address'] = $newAddressId; 
            
            // Redirect after successful save-to-database
            header('Location: checkout.php');
            exit();
        } else {
            // Handle error, e.g., $_SESSION['error'] = mysqli_error($con);
        }
    }
    else
    {
        // Store in Session (for one-time use if not saved to DB)
        $_SESSION['address']=0;
        $_SESSION['fname']=$_POST['fname'];
        $_SESSION['lname']=$_POST['lname'];
        $_SESSION['add']=$_POST['add'];
        $_SESSION['city']=$_POST['city'];
        $_SESSION['state']=$_POST['state'];
        $_SESSION['pin']=$_POST['pin'];
        $_SESSION['phone']=$_POST['phone'];
        
        // IMPORTANT: If you set a temporary address, you might want to use a different session key 
        // or structure to distinguish it from a saved address ID. For simplicity here, we skip 
        // setting a single $_SESSION['address'] for non-saved temporary entries.
    }
}

// NOTE: This payment processing block relies on $_SESSION['address'] being set via AJAX/Redirect flow now.
if(isset($_POST['payment']))
{
    // FIX: Check if a saved address is selected via $_SESSION['address'] 
    // or if a temporary address is available (via $_SESSION['add'] for non-saved flow)
    if(isset($_SESSION['address']) || isset($_SESSION['add']))
    {
        header('Location: payment.php');
        exit(); // Added exit for safety
    } else {
        // Optional: Error message if no address is selected/entered
        // $_SESSION['checkout_error'] = "Please select or enter a shipping address.";
    }
}


// --- Sample checkout data (Fallback if session cart is empty) ---

// In a real app, you'd retrieve $cart_items from $_SESSION['cart']
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // Sample checkout data used as fallback
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
} else {
    // Replace with actual session cart items
    // $cart_items = $_SESSION['cart'];
    $cart_items = [/* Your actual cart data structure based on session */];
}


// Sample saved addresses (Used only for structural reference, not display fallback)
$saved_addresses_sample = [
    [
        'id' => 1001, // Using high IDs to avoid conflict with potential DB IDs
        'type' => 'Home',
        'name' => 'Priya Sharma',
        'address' => '123 Fashion Street, Connaught Place',
        'city_pin' => 'New Delhi - 110001',
        'phone' => '+91 98765 43210'
    ]
];
// $saved_addresses = $saved_addresses_sample; // NO LONGER USED AS FALLBACK

// --- Calculations ---
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
            height: 100%; /* Important for card consistency in flex layout */
        }
        .saved-address-card:hover {
            border-color: #f4b6cc;
            background-color: #fef7f8;
        }
        /* Target the saved-address-card when its sibling radio is checked */
        .form-check-input:checked + .saved-address-card {
             border-color: #ff5f99;
             background-color: #fff0f6;
        }
        .form-check-input {
            display: none;
        }
        .btn-theme-action {
            background-color: #fff0f6;
            border: 1px solid #f8c9d8;
            color: #c12b5a;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            transition: all 0.3s ease;
        }
        .btn-theme-action:hover {
            background-color: #f8c9d8;
            border-color: #f4b6cc;
            color: #2d2d2d;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .checkout-container h4 {
            font-size: 1.5rem; 
            margin-bottom: 0;
        }

        .btn-ship {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .address-link-wrapper { text-decoration: none; color: inherit; display: block; height: 100%; } /* Added for clickable area */
        .address-link-wrapper:hover { text-decoration: none; } /* Prevents underline on hover */
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
                    <?php if(isset($_SESSION['user_id'])): // Changed to 'user_id' for consistency if that's the session key used for profile/login check ?>
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
                <form id="checkoutForm" method="POST">
                    <div class="checkout-container mb-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4><i class="bi bi-truck me-2"></i>Shipping Address</h4>
                            <button class="btn btn-theme-action" type="button" data-bs-toggle="collapse" data-bs-target="#addNewAddressCollapse" id="toggleAddressFormBtn">
                                <i class="bi bi-plus-circle me-2"></i>Add New Address
                            </button>
                        </div>
                        
                        <div class="collapse" id="addNewAddressCollapse">
                            <div class="card card-body border-2 mb-4" style="border-color: #f4b6cc !important;">
                                <h5 class="mb-3">Enter New Address Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Receiver's First Name *</label>
                                        <input type="text" class="form-control" required name="fname">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Receiver's Last Name *</label>
                                        <input type="text" class="form-control" required name="lname">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address *</label>
                                    <input type="text" class="form-control" placeholder="Street Address" required name="add">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City *</label>
                                        <input type="text" class="form-control" required name="city" id="newAddressCity" placeholder="Fill Pin Code..." readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">State *</label>
                                        <input type="text" class="form-control" required name="state" id="newAddressState" placeholder="Fill Pin Code..." readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">PIN Code *</label>
                                        <input type="text" class="form-control" required name="pin" id="newAddressPin" pattern="[0-9]{6}" maxlength="6">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Number *</label>
                                    <input type="tel" class="form-control" placeholder="10-digit mobile number" required pattern="[0-9]{10}" name="phone">
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="saveAddress" style="display: inline-block;" name="check">
                                        <label class="form-check-label" for="saveAddress">
                                            Save this address
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-ship" name="save">
                                        <span>Ship</span>
                                        <i class="bi bi-truck"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="savedAddressesWrapper">
                            <h5 class="mb-3"><i class="bi bi-journal-bookmark me-2"></i>Or Select a Saved Address</h5>
                            <?php
                                // 1. Logic to fetch saved addresses from the database
                                $sql_fetch="SELECT * FROM `Address` WHERE `UserID`='$id' ORDER BY AddressID DESC";
                                
                                $addresses_to_display = [];
                                
                                // --- FIX: Check for connection and execute query ---
                                if (isset($con) && $con) {
                                    $result_fetch=mysqli_query($con,$sql_fetch);
                                    
                                    if ($result_fetch) {
                                        $rows_count=mysqli_num_rows($result_fetch);
                                        
                                        if($rows_count > 0)
                                        {
                                            // Fetch addresses from the database
                                            while($row = mysqli_fetch_assoc($result_fetch)) {
                                                // The 'type' key should ideally come from a column, but for now we set it to 'Saved'
                                                $addresses_to_display[] = [
                                                    'id' => $row['AddressID'],
                                                    'type' => 'Saved', // Defaulting to 'Saved' for DB entries
                                                    'name' => htmlspecialchars($row['FName'] . ' ' . $row['LName']),
                                                    'address' => htmlspecialchars($row['Address']),
                                                    'city_pin' => htmlspecialchars($row['City'] . ' - ' . $row['Pin']),
                                                    'phone' => htmlspecialchars($row['Phone'])
                                                ];
                                            }
                                        }
                                    } else {
                                        // Optional: Handle query error
                                        // echo "<div class='alert alert-danger'>Error fetching addresses: " . mysqli_error($con) . "</div>";
                                    }
                                } else {
                                    // Optional: Handle connection failure
                                    // echo "<div class='alert alert-danger'>Database connection failed.</div>";
                                }
                                // --- END FIX ---
                                
                                // 2. If no addresses from DB, do NOT use sample, and display a message
                                if (empty($addresses_to_display)) {
                                    echo "<div class='alert alert-info'>
                                        You have no saved addresses. Please use the **Add New Address** option above to continue.
                                    </div>";
                                }

                                // Determine which address ID should be checked and/or set in session if none exists
                                $checked_address_id = null;
                                if (isset($_SESSION['address']) && is_numeric($_SESSION['address'])) {
                                    $checked_address_id = (int)$_SESSION['address'];
                                } else if (!empty($addresses_to_display)) {
                                    // Default to the first address if session isn't set, and set the session here (optional, but convenient)
                                    $checked_address_id = $addresses_to_display[0]['id'];
                                    $_SESSION['address'] = $checked_address_id; // Set default session on load
                                }

                            ?>
                            
                            <div class="row">
    <?php foreach ($addresses_to_display as $address): ?>
        <div class="col-12 col-md-6 mb-3">
            
            <label for="address-<?php echo $address['id']; ?>" class="w-100 h-100">
                <input class="form-check-input address-radio-selector" 
                       type="radio" 
                       name="selected_address" 
                       value="<?php echo $address['id'];?>"
                       id="address-<?php echo $address['id']; ?>" 
                       <?php echo $address['id'] === $checked_address_id ? 'checked' : ''; ?>
                       
                       >
                
                <div class="saved-address-card h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-secondary mb-2"><?php echo $address['type']; ?></span>
                            <p class="mb-1 fw-bold"><?php echo $address['name']; ?></p>
                            <p class="mb-1 text-muted small"><?php echo $address['address']; ?>, <?php echo $address['city_pin']; ?></p>
                            <p class="mb-0 text-muted small">Phone: <?php echo $address['phone']; ?></p>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    <?php endforeach; ?>
</div>
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
                    <form method="POST">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-3" name="payment">
                        <i class="bi bi-lock me-2"></i>Proceed to Payment
                    </button>
                    </form>
                    <div class="text-center">
                        <small class="text-muted">By proceeding, you agree to our Terms & Conditions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Assuming 'footer.php' exists and contains your site's footer content
    // include 'footer.php'; 
    ?>

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
            
            // Elements for PIN Code lookup
            const newAddressPin = document.getElementById('newAddressPin');
            const newAddressState = document.getElementById('newAddressState');
            const newAddressCity = document.getElementById('newAddressCity'); 
            const shipButton = document.querySelector('button[name="save"]'); // New

            // API Integration for PIN Code to State and City lookup
            const fetchAddressDetailsFromPinCode = async () => {
                const pinCode = newAddressPin.value;
                
                // Reset city and state fields and their validation status
                newAddressCity.value = '';
                newAddressState.value = '';
                newAddressPin.classList.remove('is-invalid');
                newAddressCity.classList.remove('is-invalid');
                newAddressState.classList.remove('is-invalid');
                
                if (pinCode.length !== 6 || !/^\d{6}$/.test(pinCode)) {
                    return; 
                }
                
                newAddressCity.value = 'Loading...';
                newAddressState.value = 'Loading...';

                try {
                    const response = await fetch(`https://api.postalpincode.in/pincode/${pinCode}`);
                    const data = await response.json();

                    if (data && data[0] && data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
                        const postOffice = data[0].PostOffice[0];
                        const city = postOffice.District; 
                        const state = postOffice.State;
                        
                        newAddressCity.value = city; 
                        newAddressState.value = state; 
                    } else {
                        newAddressCity.value = '';
                        newAddressState.value = 'Invalid PIN Code';
                        newAddressPin.classList.add('is-invalid');
                        newAddressState.classList.add('is-invalid');
                        alert('Invalid PIN Code: No records found.');
                    }
                } catch (error) {
                    console.error('Error fetching PIN code data:', error);
                    newAddressCity.value = '';
                    newAddressState.value = 'Error';
                    newAddressState.classList.add('is-invalid');
                    alert('An error occurred while trying to validate the PIN Code.');
                }
            };

            if (newAddressPin) {
                newAddressPin.addEventListener('blur', fetchAddressDetailsFromPinCode);
            }
            // End API Integration

            if (addressCollapseEl) {
                // Select all radio buttons within the saved addresses wrapper
                const radioButtons = savedAddressesWrapper.querySelectorAll('input[type="radio"]');

                addressCollapseEl.addEventListener('show.bs.collapse', () => {
                    toggleBtn.classList.remove('btn-theme-action');
                    toggleBtn.classList.add('btn-danger');
                    toggleBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Cancel';
                    
                    savedAddressesWrapper.style.opacity = '0.5';
                    radioButtons.forEach(radio => {
                        radio.checked = false;
                        radio.disabled = true;
                    });
                });

                addressCollapseEl.addEventListener('hide.bs.collapse', () => {
                    toggleBtn.classList.add('btn-theme-action');
                    toggleBtn.classList.remove('btn-danger');
                    toggleBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add New Address';

                    savedAddressesWrapper.style.opacity = '1';
                    radioButtons.forEach(radio => {
                        radio.disabled = false;
                    });
                    
                    // Check the first radio button to restore a selection, if available
                    // Use the checked property on page load if one was set by PHP
                    const checkedRadio = document.querySelector('input[name="selected_address"]:checked');
                    if (!checkedRadio) {
                       const firstRadio = document.querySelector('input[name="selected_address"]');
                        if (firstRadio) {
                            firstRadio.checked = true;
                        }
                    }
                });
            }

            // --- START OF CRITICAL FIX IMPLEMENTATION FOR SESSION UPDATE ---
            
            /**
             * NEW: Event listener to update $_SESSION['address'] via AJAX whenever 
             * a saved address radio button is checked.
             */
            const addressRadios = document.querySelectorAll('.address-radio-selector');

            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const addressId = this.value; // Get the address ID from the radio value
                        
                        // Use Fetch API for a cleaner AJAX call
                        fetch('checkout.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            // Send the 'action' and 'address_id' for the PHP block to handle
                            body: 'action=update_session_address&address_id=' + addressId
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                // Session updated successfully. No page reload is necessary.
                                // console.log('Session updated successfully for Address ID:', data.address_id);
                            } else {
                                // console.error('Error updating session:', data.message);
                                alert('Could not update address selection. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('AJAX Error:', error);
                            alert('An error occurred during address selection.');
                            // Fallback if AJAX fails:
                            // window.location.reload(); 
                        });
                    }
                });
            });
            
            // --- END OF CRITICAL FIX IMPLEMENTATION ---


            // The main form submit handler now only focuses on the 'Ship' button if the collapse is open
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const isAddingNewAddress = addressCollapseEl.classList.contains('show');
                const saveButtonPressed = document.activeElement && document.activeElement.name === 'save';
                const paymentButtonPressed = document.activeElement && document.activeElement.name === 'payment';

                if (isAddingNewAddress && saveButtonPressed) {
                    // Let PHP handle the form submission for saving the new address
                    // Validation moved to JS before PHP submission for better UX
                    let isValid = true;
                    const formRequiredFields = document.querySelectorAll('#addNewAddressCollapse [required]');
                    
                    formRequiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    // Additional PIN code validation
                    if (!newAddressState.value || newAddressState.value.toLowerCase().includes('invalid') || newAddressState.value.toLowerCase().includes('error')) {
                        isValid = false;
                        newAddressPin.classList.add('is-invalid');
                        alert('Please enter a valid 6-digit PIN Code to determine the City and State.');
                    }
                    
                    if (!isValid) {
                        e.preventDefault(); // Stop form submission if validation fails
                        return;
                    }
                    // If validation passes, let the form submit to PHP to process 'save'
                } else if (!isAddingNewAddress && saveButtonPressed) {
                    // Prevent submission if 'ship' is pressed but the new address form is hidden
                    e.preventDefault(); 
                    alert('Click the "Add New Address" button to use the "Ship" button.');
                } else if (paymentButtonPressed) {
                    // The payment button is in a separate form, but if it were here, you'd check $_SESSION['address']
                    // For now, let the payment form submit as it has its own logic in PHP
                } else {
                    // Prevent default form submission on enter key
                    e.preventDefault(); 
                }
            });
        });
    </script>
</body>
</html>
<?PHP
    include 'footer.php';
?>