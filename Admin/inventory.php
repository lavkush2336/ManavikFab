<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample inventory data
$inventory_items = [
    ['id' => 1, 'product_name' => 'Silk Saree - Traditional Red', 'sku' => 'SS-RED-001', 'category' => 'Sarees', 'current_stock' => 25, 'min_stock' => 10, 'max_stock' => 100, 'unit_cost' => 1200, 'total_value' => 30000, 'last_updated' => '2024-01-15', 'status' => 'In Stock', 'supplier' => 'Silk Weavers Ltd', 'location' => 'Warehouse A'],
    ['id' => 2, 'product_name' => 'Bridal Lehenga - Gold Embroidered', 'sku' => 'BL-GOLD-002', 'category' => 'Lehengas', 'current_stock' => 8, 'min_stock' => 5, 'max_stock' => 50, 'unit_cost' => 3500, 'total_value' => 28000, 'last_updated' => '2024-01-14', 'status' => 'Low Stock', 'supplier' => 'Royal Garments', 'location' => 'Warehouse B'],
    ['id' => 3, 'product_name' => 'Cotton Kurti - Blue Floral', 'sku' => 'CK-BLUE-003', 'category' => 'Kurtis', 'current_stock' => 45, 'min_stock' => 20, 'max_stock' => 80, 'unit_cost' => 450, 'total_value' => 20250, 'last_updated' => '2024-01-13', 'status' => 'In Stock', 'supplier' => 'Cotton Crafts', 'location' => 'Warehouse A'],
    ['id' => 4, 'product_name' => 'Party Dress - Black Sequined', 'sku' => 'PD-BLACK-004', 'category' => 'Dresses', 'current_stock' => 0, 'min_stock' => 5, 'max_stock' => 30, 'unit_cost' => 1800, 'total_value' => 0, 'last_updated' => '2024-01-12', 'status' => 'Out of Stock', 'supplier' => 'Fashion Forward', 'location' => 'Warehouse C'],
    ['id' => 5, 'product_name' => 'Silver Jewelry Set', 'sku' => 'SJS-SILVER-005', 'category' => 'Jewelry', 'current_stock' => 12, 'min_stock' => 10, 'max_stock' => 40, 'unit_cost' => 800, 'total_value' => 9600, 'last_updated' => '2024-01-11', 'status' => 'Low Stock', 'supplier' => 'Silver Artisans', 'location' => 'Warehouse B'],
    ['id' => 6, 'product_name' => 'Designer Handbag - Brown Leather', 'sku' => 'DH-BROWN-006', 'category' => 'Accessories', 'current_stock' => 18, 'min_stock' => 10, 'max_stock' => 60, 'unit_cost' => 1200, 'total_value' => 21600, 'last_updated' => '2024-01-10', 'status' => 'In Stock', 'supplier' => 'Leather Crafts', 'location' => 'Warehouse A']
];

$categories = ['All', 'Sarees', 'Lehengas', 'Kurtis', 'Dresses', 'Jewelry', 'Accessories'];
$statuses = ['All', 'In Stock', 'Low Stock', 'Out of Stock'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - ManavikFab</title>
    
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
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 6px 18px rgba(15,23,42,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(15,23,42,0.08); }
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
        .stock-bar { height: 6px; border-radius: 3px; background-color: #e9ecef; }
        .stock-bar .progress-bar { border-radius: 3px; }
        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; }

        /* --- NEW RESPONSIVE STYLES --- */
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
                    <a class="nav-link" href="products.php"><i class="bi bi-box me-2"></i>Products</a>
                    <a class="nav-link" href="customers.php"><i class="bi bi-people me-2"></i>Customers</a>
                    <a class="nav-link" href="categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link active" href="inventory.php"><i class="bi bi-boxes me-2"></i>Inventory</a>
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
                        <h4 class="mb-0">Inventory Management</h4>
                        <div class="d-flex align-items-center">
                             <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addStockModal">
                                <i class="bi bi-plus-circle me-2"></i>Add Stock
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
                     <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count($inventory_items); ?></h5><small class="text-muted">Total Items</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count(array_filter($inventory_items, fn($item) => $item['status'] == 'In Stock')); ?></h5><small class="text-muted">In Stock</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count(array_filter($inventory_items, fn($item) => $item['status'] == 'Low Stock')); ?></h5><small class="text-muted">Low Stock</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5>₹<?php echo number_format(array_sum(array_column($inventory_items, 'total_value'))); ?></h5><small class="text-muted">Total Value</small></div>
                        </div>
                    </div>

                    <div class="table-container">
                        <h5 class="mb-3">Inventory Status</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Stock Level</th>
                                        <th>Status</th>
                                        <th>Unit Cost</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($inventory_items as $item): ?>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 fw-bold"><?php echo $item['product_name']; ?></h6>
                                            <small class="text-muted">ID: <?php echo $item['id']; ?></small>
                                        </td>
                                        <td><code><?php echo $item['sku']; ?></code></td>
                                        <td><?php echo $item['category']; ?></td>
                                        <td>
                                            <?php $percentage = $item['max_stock'] > 0 ? ($item['current_stock'] / $item['max_stock']) * 100 : 0; ?>
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold me-2"><?php echo $item['current_stock']; ?></div>
                                                <div class="progress flex-grow-1 stock-bar">
                                                    <div class="progress-bar <?php 
                                                        if ($percentage < ($item['min_stock'] / $item['max_stock'] * 100)) echo 'bg-danger';
                                                        elseif ($percentage < 50) echo 'bg-warning';
                                                        else echo 'bg-success';
                                                    ?>" style="width: <?php echo $percentage; ?>%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                if ($item['status'] == 'In Stock') echo 'success';
                                                elseif ($item['status'] == 'Low Stock') echo 'warning';
                                                else echo 'danger';
                                            ?>"><?php echo $item['status']; ?></span>
                                        </td>
                                        <td><strong>₹<?php echo number_format($item['unit_cost']); ?></strong></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-success" onclick="addStock(<?php echo $item['id']; ?>)" title="Add Stock"><i class="bi bi-plus-circle"></i></button>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewItem(<?php echo $item['id']; ?>)" title="View"><i class="bi bi-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeStock(<?php echo $item['id']; ?>)" title="Remove Stock"><i class="bi bi-dash-circle"></i></button>
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
    
    <div class="modal fade" id="addStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Product *</label>
                                <select class="form-select" required>
                                    <option value="">Select a product...</option>
                                    <?php foreach($inventory_items as $item): ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['product_name']; ?> (SKU: <?php echo $item['sku']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantity *</label>
                                <input type="number" class="form-control" placeholder="e.g., 50" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" placeholder="Add any notes about this stock update..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Stock</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
    <script>
        function viewItem(itemId) { alert('View inventory details for item ID: ' + itemId); }
        function addStock(itemId) { alert('Add stock for item ID: ' + itemId); }
        function removeStock(itemId) { alert('Remove stock for item ID: ' + itemId); }
    </script>
</body>
</html>