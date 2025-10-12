<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: login.php");
    exit();
}

// Sample orders data
$orders = [
    ['id' => 'ORD001', 'customer_name' => 'Priya Sharma', 'customer_email' => 'priya.sharma@email.com', 'order_date' => '2024-01-15', 'total_amount' => 2499, 'status' => 'Delivered', 'payment_status' => 'Paid', 'items' => 3],
    ['id' => 'ORD002', 'customer_name' => 'Anjali Patel', 'customer_email' => 'anjali.patel@email.com', 'order_date' => '2024-01-14', 'total_amount' => 1899, 'status' => 'In Transit', 'payment_status' => 'Paid', 'items' => 2],
    ['id' => 'ORD003', 'customer_name' => 'Meera Singh', 'customer_email' => 'meera.singh@email.com', 'order_date' => '2024-01-13', 'total_amount' => 3299, 'status' => 'Processing', 'payment_status' => 'Pending', 'items' => 4],
    ['id' => 'ORD004', 'customer_name' => 'Kavya Reddy', 'customer_email' => 'kavya.reddy@email.com', 'order_date' => '2024-01-12', 'total_amount' => 1599, 'status' => 'Cancelled', 'payment_status' => 'Refunded', 'items' => 1],
    ['id' => 'ORD005', 'customer_name' => 'Riya Gupta', 'customer_email' => 'riya.gupta@email.com', 'order_date' => '2024-01-11', 'total_amount' => 4199, 'status' => 'Delivered', 'payment_status' => 'Paid', 'items' => 5]
];

$statuses = ['All', 'Pending', 'Processing', 'In Transit', 'Delivered', 'Cancelled'];
$payment_statuses = ['All', 'Pending', 'Paid', 'Failed', 'Refunded'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - ManavikFab</title>
    
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
                    <a class="nav-link active" href="orders.php"><i class="bi bi-cart3 me-2"></i>Orders</a>
                    <a class="nav-link" href="products.php"><i class="bi bi-box me-2"></i>Products</a>
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
                        <h4 class="mb-0">Orders Management</h4>
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
                </nav>
                
                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count($orders); ?></h5><small class="text-muted">Total Orders</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count(array_filter($orders, fn($o) => $o['status'] == 'Pending')); ?></h5><small class="text-muted">Pending Orders</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count(array_filter($orders, fn($o) => $o['status'] == 'In Transit')); ?></h5><small class="text-muted">In Transit</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5>₹<?php echo number_format(array_sum(array_column($orders, 'total_amount'))); ?></h5><small class="text-muted">Total Revenue</small></div>
                        </div>
                    </div>
                    
                    <div class="table-container mb-4">
                        <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Filters</h5>
                        <form class="row g-3">
                            <div class="col-md-3"><select class="form-select"><option>All Statuses</option></select></div>
                            <div class="col-md-3"><select class="form-select"><option>All Payments</option></select></div>
                            <div class="col-md-3"><input type="date" class="form-control"></div>
                            <div class="col-md-3"><input type="text" class="form-control" placeholder="Search Order ID, Customer..."></div>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo $order['id']; ?></strong></td>
                                        <td>
                                            <h6 class="mb-0 fw-bold"><?php echo $order['customer_name']; ?></h6>
                                            <small class="text-muted"><?php echo $order['customer_email']; ?></small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                        <td><strong>₹<?php echo number_format($order['total_amount']); ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                if ($order['status'] == 'Delivered') echo 'success';
                                                elseif ($order['status'] == 'Processing') echo 'warning';
                                                elseif ($order['status'] == 'In Transit') echo 'info';
                                                elseif ($order['status'] == 'Cancelled') echo 'danger';
                                                else echo 'secondary';
                                            ?>"><?php echo $order['status']; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo strtolower($order['payment_status']) == 'paid' ? 'success' : 'warning'; ?> text-dark">
                                                <?php echo $order['payment_status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></button>
                                                <button class="btn btn-sm btn-outline-success" title="Update" data-bs-toggle="modal" data-bs-target="#updateStatusModal"><i class="bi bi-pencil"></i></button>
                                                <button class="btn btn-sm btn-outline-info" title="Invoice"><i class="bi bi-printer"></i></button>
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
    
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-grid gap-2">
                    <button type="button" class="btn btn-secondary w-50 mx-auto">On Hold</button>
                    <button type="button" class="btn btn-primary w-50 mx-auto">Confirm</button>
                    <button type="button" class="btn btn-warning w-50 mx-auto">Processing</button>
                    <button type="button" class="btn btn-info w-50 mx-auto">In Transit</button>
                    <button type="button" class="btn btn-success w-50 mx-auto">Delivered</button>
                    <button type="button" class="btn btn-danger w-50 mx-auto">Canceled</button>
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
</body>
</html>