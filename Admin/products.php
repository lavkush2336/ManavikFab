<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample products data
$products = [
    ['id' => 1, 'name' => 'Embroidered Silk Saree', 'category' => 'Sarees', 'price' => 2499, 'original_price' => 3999, 'stock' => 25, 'status' => 'Active', 'image' => '../images/product1.jpg'],
    ['id' => 2, 'name' => 'Designer Lehenga Set', 'category' => 'Lehengas', 'price' => 5999, 'original_price' => 8999, 'stock' => 15, 'status' => 'Active', 'image' => '../images/product2.jpg'],
    ['id' => 3, 'name' => 'Cotton Kurti with Palazzo', 'category' => 'Ethnic Wear', 'price' => 1299, 'original_price' => 1999, 'stock' => 40, 'status' => 'Active', 'image' => '../images/product3.jpg'],
    ['id' => 4, 'name' => 'Western Dress Collection', 'category' => 'Western Wear', 'price' => 1899, 'original_price' => 2499, 'stock' => 8, 'status' => 'Active', 'image' => '../images/product4.jpg'],
    ['id' => 5, 'name' => 'Bridal Lehenga Set', 'category' => 'Lehengas', 'price' => 8999, 'original_price' => 12999, 'stock' => 5, 'status' => 'Active', 'image' => '../images/product5.jpg'],
    ['id' => 6, 'name' => 'Silk Anarkali Suit', 'category' => 'Ethnic Wear', 'price' => 3499, 'original_price' => 4999, 'stock' => 0, 'status' => 'Inactive', 'image' => '../images/product6.jpg']
];

$categories = ['All', 'Ethnic Wear', 'Western Wear', 'Sarees', 'Lehengas', 'Accessories'];
$brands = ['ManavikFab Originals', 'Glamour Attire', 'Elegance Fashion', 'Traditional Trends'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - ManavikFab</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #0f172a;
        }
        .sidebar {
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: #2d2d2d;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #2d2d2d;
        }
        .main-content {
            margin-left: 240px;
            background-color: #f8f9fa;
        }
        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar .container-fluid h4.mb-0{ font-size:1.5rem; font-weight:800; }
        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }
        .table-container h5 { font-weight:700; color:#0f172a; margin-bottom:.75rem }
        table { font-size:.95rem }
        thead th { font-weight:700; background-color: #f8f9fa; }
        .table tbody tr td, .table thead th { padding:.75rem .9rem; vertical-align:middle }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 240px;
                height: 100%;
                z-index: 1030;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                max-width: 100%;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1020;
                display: none;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3" id="sidebar">
                <div class="text-center mb-4">
                   <a href="index.php" class="text-decoration-none"> <h4 class="fw-bold text-dark"><i class="bi bi-heart-fill text-danger me-2"></i>ManavikFab</h4></a>
                    <small class="text-muted">Admin Panel</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link" href="orders.php"><i class="bi bi-cart3 me-2"></i>Orders</a>
                    <a class="nav-link active" href="products.php"><i class="bi bi-box me-2"></i>Products</a>
                    <a class="nav-link" href="customers.php"><i class="bi bi-people me-2"></i>Customers</a>
                    <a class="nav-link" href="categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link" href="inventory.php"><i class="bi bi-boxes me-2"></i>Inventory</a>
                    <a class="nav-link" href="reports.php"><i class="bi bi-graph-up me-2"></i>Reports</a>
                    <a class="nav-link" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                    <hr>
                    <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                </nav>
            </div>

            <div class="col-md-9 col-lg-10 main-content p-4">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <h4 class="mb-0">Products Management</h4>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="bi bi-plus-circle me-2"></i>Add Product
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2"></i>Admin
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item admin-dropdown-item" href="settings.php"><i class="bi bi-gear"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item admin-dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="p-4">
                    <div class="table-container">
                        <h5 class="mb-3">All Products (<?php echo count($products); ?>)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products as $product): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image me-3">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?php echo $product['name']; ?></h6>
                                                    <small class="text-muted">ID: <?php echo $product['id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark"><?php echo $product['category']; ?></span></td>
                                        <td>
                                            <span class="fw-bold">₹<?php echo number_format($product['price']); ?></span>
                                            <small class="text-muted text-decoration-line-through d-block">₹<?php echo number_format($product['original_price']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                if ($product['stock'] <= 0) echo 'danger';
                                                elseif ($product['stock'] < 10) echo 'warning';
                                                else echo 'success';
                                            ?>">
                                                <?php echo $product['stock'] > 0 ? $product['stock'] . ' in stock' : 'Out of stock'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $product['status'] == 'Active' ? 'success' : 'secondary'; ?>">
                                                <?php echo $product['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="bi bi-pencil"></i></button>
                                                <button class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Category *</label><select class="form-select" required><option>Select Category</option></select></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Stock Quantity *</label><input type="number" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Price (₹) *</label><input type="number" class="form-control" required></div>
                        </div>
                        <div class="row">
                        <div class="col mb-3">
                                <label class="form-label">Brand *</label>
                                <select class="form-select" required>
                                    <option>Select Brand</option>
                                    <?php foreach($brands as $brand): ?>
                                        <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>    
                        
                        </div>
                        
                        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" rows="3"></textarea></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Product Images (up to 5)</label>
                            <div id="product-images-container">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control" name="product_images[]" accept="image/*">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Product</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Category *</label><select class="form-select" required><option>Select Category</option></select></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Stock Quantity *</label><input type="number" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Price (₹) *</label><input type="number" class="form-control" required></div>
                        </div>
                        <div class="row">
                        <div class="col mb-3">
                                <label class="form-label">Brand *</label>
                                <select class="form-select" required>
                                    <option>Select Brand</option>
                                    <?php foreach($brands as $brand): ?>
                                        <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>    
                        
                        </div>
                        
                        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" rows="3"></textarea></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Product Images (up to 5)</label>
                            <div id="product-images-container">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control mb-2" name="product_images[]" accept="image/*">
                                <input type="file" class="form-control" name="product_images[]" accept="image/*">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar toggle script
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                const overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);

                const closeSidebar = () => {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                };

                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', closeSidebar);
            }
        });
    </script>
</body>
</html>