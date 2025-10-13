<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: login.php");
    exit();
}

// FIX: SAVE3 was failing because the quantity input field had duplicate name attributes.
if(isset($_POST['save3']))
{
    $productID = $_POST['pid2'];
    $quantityToRemove = $_POST['qty2']; // This matches the fixed HTML name="qty2"

    if (!empty($productID) && !empty($quantityToRemove) && is_numeric($quantityToRemove) && $quantityToRemove > 0) {
        // Ensure stock doesn't go below zero
        $sql = "UPDATE product SET Quantity = GREATEST(0, Quantity - ?) WHERE ProductID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantityToRemove, $productID);
        mysqli_stmt_execute($stmt);
        header("Location: inventory.php?status=stock_removed");
        exit();
    }
}

if(isset($_POST['save2']))
{
    $productID = $_POST['pid'];
    $quantityToAdd = $_POST['qty1'];

    if (!empty($productID) && !empty($quantityToAdd) && is_numeric($quantityToAdd) && $quantityToAdd > 0) {
        $sql = "UPDATE product SET Quantity = Quantity + ? WHERE ProductID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantityToAdd, $productID);
        mysqli_stmt_execute($stmt);
        header("Location: inventory.php?status=stock_added");
        exit();
    }
}

// --- START: Handle General Stock Adjustment from Header Modal ---
if (isset($_POST['general_add_stock'])) {
    $productID = $_POST['product_id_select'];
    $quantityToAdd = $_POST['quantity'];

    if (!empty($productID) && !empty($quantityToAdd) && is_numeric($quantityToAdd) && $quantityToAdd > 0) {
        $sql = "UPDATE product SET Quantity = Quantity + ? WHERE ProductID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantityToAdd, $productID);
        mysqli_stmt_execute($stmt);
        header("Location: inventory.php?status=stock_added");
        exit();
    }
}
// --- END: Handle General Stock ---

// --- START: Handle Add Stock (per-item) Form Submission ---
if (isset($_POST['add_stock'])) {
    $productID = $_POST['product_id'];
    $quantityToAdd = $_POST['quantity'];

    if (!empty($productID) && !empty($quantityToAdd) && is_numeric($quantityToAdd) && $quantityToAdd > 0) {
        $sql = "UPDATE product SET Quantity = Quantity + ? WHERE ProductID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantityToAdd, $productID);
        mysqli_stmt_execute($stmt);
        header("Location: inventory.php?status=stock_added");
        exit();
    }
}
// --- END: Handle Add Stock ---

// --- START: Handle Remove Stock (per-item) Form Submission ---
if (isset($_POST['remove_stock'])) {
    $productID = $_POST['product_id'];
    $quantityToRemove = $_POST['quantity'];

    if (!empty($productID) && !empty($quantityToRemove) && is_numeric($quantityToRemove) && $quantityToRemove > 0) {
        // Ensure stock doesn't go below zero
        $sql = "UPDATE product SET Quantity = GREATEST(0, Quantity - ?) WHERE ProductID = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $quantityToRemove, $productID);
        mysqli_stmt_execute($stmt);
        header("Location: inventory.php?status=stock_removed");
        exit();
    }
}
// --- END: Handle Remove Stock ---

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
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #0f172a; }
        .sidebar { background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%); min-height: 100vh; position: fixed; top: 0; left: 0; width: 240px; z-index: 1000; overflow-y: auto; }
        .sidebar .nav-link { color: #2d2d2d; padding: 0.75rem 1rem; border-radius: 0.5rem; margin: 0.25rem 0; transition: all 0.3s ease; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: rgba(255, 255, 255, 0.2); color: #2d2d2d; }
        .stats-card { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.06); transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(15,23,42,0.08); }
        .main-content { margin-left: 240px; background-color: #f8f9fa; }
        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar .container-fluid h4.mb-0{ font-size:1.5rem; font-weight:800; }
        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }
        .table-container h5 { font-weight:700; color:#0f172a; margin-bottom:.75rem }
        table { font-size:.95rem }
        thead th { font-weight:700; background-color: #f8f9fa; }
        .table tbody tr td, .table thead th { padding:.75rem .9rem; vertical-align:middle }
        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; }
        @media (max-width: 991.98px) {
            .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100%; z-index: 1030; transform: translateX(-100%); transition: transform 0.3s ease-in-out; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; max-width: 100%; }
            .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1020; display: none; }
            .sidebar-overlay.show { display: block; }
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
            
            <main class="col-md-9 col-lg-10 main-content p-4">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle"><i class="bi bi-list"></i></button>
                        <h4 class="mb-0">Inventory Management</h4>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#generalAdjustStockModal">
                                <i class="bi bi-plus-circle me-2"></i>Adjust Stock
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle me-2"></i>Admin</button>
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
                        <div class="col-md-3"><div class="stats-card"><h5><?php $result = mysqli_query($con, "SELECT COUNT(ProductID) AS total FROM product"); echo mysqli_fetch_assoc($result)['total']; ?></h5><small class="text-muted">Total Products</small></div></div>
                        <div class="col-md-3"><div class="stats-card"><h5><?php $result = mysqli_query($con, "SELECT SUM(Quantity) AS total FROM product"); echo number_format(mysqli_fetch_assoc($result)['total']); ?></h5><small class="text-muted">Total Units</small></div></div>
                        <div class="col-md-3"><div class="stats-card"><h5><?php $result = mysqli_query($con, "SELECT COUNT(ProductID) AS total FROM product WHERE Quantity > 0 AND Quantity <= 5"); echo mysqli_fetch_assoc($result)['total']; ?></h5><small class="text-muted">Low Stock</small></div></div>
                        <div class="col-md-3"><div class="stats-card"><h5>₹<?php $result = mysqli_query($con, "SELECT SUM(Price * Quantity) AS total FROM product"); echo number_format(mysqli_fetch_assoc($result)['total']); ?></h5><small class="text-muted">Total Inventory Value</small></div></div>
                    </div>

                    <div class="table-container">
                        <h5 class="mb-3">Inventory Status</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr><th>Product</th><th>Category</th><th>Quantity</th><th>Status</th><th>Unit Cost</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql="SELECT * FROM `product` ORDER BY `Name` ASC";
                                        $result=mysqli_query($con,$sql);
                                        if(mysqli_num_rows($result) > 0) {
                                            while($row = mysqli_fetch_assoc($result)) {
                                                $quantity = $row['Quantity'];
                                                $stock_class = ($quantity > 5) ? 'success' : (($quantity > 0) ? 'warning' : 'danger');
                                                $stock_text = ($quantity > 5) ? 'In Stock' : (($quantity > 0) ? 'Low Stock' : 'Out of Stock');

                                                echo "<tr>
                                                    <td>
                                                        <h6 class='mb-0 fw-bold'>".htmlspecialchars($row['Name'])."</h6>
                                                        <small class='text-muted'>ID: ".$row['ProductID']."</small>
                                                    </td>
                                                    <td>".htmlspecialchars($row['Category'])."</td>
                                                    <td><div class='fw-bold'>".$quantity."</div></td>
                                                    <td><span class='badge bg-".$stock_class."'>".$stock_text."</span></td>
                                                    <td><strong>₹".number_format($row['Price'])."</strong></td>
                                                    <td>
                                                        <div class='btn-group'>
                                                            <button class='btn btn-sm btn-outline-success' title='Add Stock' data-bs-toggle='modal' data-bs-target='#adjustStockModal' data-product-id='".$row['ProductID']."' data-product-name='".htmlspecialchars($row['Name'])."' data-action='add'><i class='bi bi-plus-circle'></i></button>
                                                            <a href='../product-detail.php?product=".$row['ProductID']."' class='btn btn-sm btn-outline-info' title='View/Edit Product'><i class='bi bi-eye'></i></a>
                                                            <button class='btn btn-sm btn-outline-danger' title='Remove Stock' data-bs-toggle='modal' data-bs-target='#adjustStockModal' data-product-id='".$row['ProductID']."' data-product-name='".htmlspecialchars($row['Name'])."' data-action='remove'><i class='bi bi-dash-circle'></i></button>
                                                        </div>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'><div class='alert alert-warning mt-3' role='alert'>No Products Found!</div></td></tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <div class="modal fade" id="generalAdjustStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Product *</label>
                            <select class="form-select" name="product_id_select" required>
                                <option value="">Choose a product...</option>
                                <?php
                                    $sql_products = "SELECT `ProductID`, `Name` FROM `product` ORDER BY `Name` ASC";
                                    $result_products = mysqli_query($con, $sql_products);
                                    while($row_product = mysqli_fetch_assoc($result_products)) {
                                        echo "<option value='".$row_product['ProductID']."'>".htmlspecialchars($row_product['Name'])." (ID: ".$row_product['ProductID'].")</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add *</label>
                            <input type="number" class="form-control" name="quantity" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="general_add_stock" class="btn btn-primary">Confirm Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adjustStockModalLabel">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="addStockForm">
                    <div class="modal-body">
                        <input type="hidden" name="pid" id="addProductId"> 
                        <div class="mb-3"><label class="form-label">Product</label><input type="text" class="form-control" id="addProductName" readonly></div>
                        <div class="mb-3"><label for="addQuantity" class="form-label">Quantity to Add *</label><input type="number" class="form-control" name="qty1" id="addQuantity" min="1" required></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" name="save2" class="btn btn-primary">Confirm Add</button></div>
                </form>
                 <form method="POST" id="removeStockForm" style="display:none;">
                    <div class="modal-body">
                        <input type="hidden" name="pid2" id="removeProductId">
                        <div class="mb-3"><label class="form-label">Product</label><input type="text" class="form-control" id="removeProductName" readonly></div>
                        <div class="mb-3"><label for="removeQuantity" class="form-label">Quantity to Remove *</label><input type="number" class="form-control" name="qty2" id="removeQuantity" min="1" required></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" name="save3" class="btn btn-danger">Confirm Remove</button></div>
                </form>
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
            const closeSidebar = () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); };
            sidebarToggle.addEventListener('click', () => { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); });
            overlay.addEventListener('click', closeSidebar);
        }

        // SCRIPT FOR PER-ITEM ADJUST STOCK MODAL
        const adjustStockModal = document.getElementById('adjustStockModal');
        const modalLabel = document.getElementById('adjustStockModalLabel');
        const addForm = document.getElementById('addStockForm');
        const removeForm = document.getElementById('removeStockForm');

        adjustStockModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const action = button.getAttribute('data-action');

            if (action === 'add') {
                modalLabel.textContent = 'Add Stock';
                addForm.style.display = 'block';
                removeForm.style.display = 'none';
                document.getElementById('addProductId').value = productId;
                document.getElementById('addProductName').value = productName;
            } else if (action === 'remove') {
                modalLabel.textContent = 'Remove Stock';
                addForm.style.display = 'none';
                removeForm.style.display = 'block';
                document.getElementById('removeProductId').value = productId;
                document.getElementById('removeProductName').value = productName;
            }
        });
    });
    </script>
</body>
</html>