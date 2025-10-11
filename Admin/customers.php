<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample customers data
$customers = [
    ['id' => 1, 'name' => 'Priya Sharma', 'email' => 'priya.sharma@email.com', 'phone' => '+91 98765 43210', 'join_date' => '2023-12-01', 'total_orders' => 8, 'total_spent' => 18999, 'last_order' => '2024-01-15', 'status' => 'Active', 'city' => 'New Delhi', 'state' => 'Delhi'],
    ['id' => 2, 'name' => 'Anjali Patel', 'email' => 'anjali.patel@email.com', 'phone' => '+91 87654 32109', 'join_date' => '2023-11-15', 'total_orders' => 5, 'total_spent' => 12499, 'last_order' => '2024-01-14', 'status' => 'Active', 'city' => 'Gurgaon', 'state' => 'Haryana'],
    ['id' => 3, 'name' => 'Meera Singh', 'email' => 'meera.singh@email.com', 'phone' => '+91 76543 21098', 'join_date' => '2023-10-20', 'total_orders' => 12, 'total_spent' => 28999, 'last_order' => '2024-01-13', 'status' => 'Active', 'city' => 'Mumbai', 'state' => 'Maharashtra'],
    ['id' => 4, 'name' => 'Kavya Reddy', 'email' => 'kavya.reddy@email.com', 'phone' => '+91 65432 10987', 'join_date' => '2023-09-10', 'total_orders' => 3, 'total_spent' => 5999, 'last_order' => '2024-01-12', 'status' => 'Inactive', 'city' => 'Bangalore', 'state' => 'Karnataka'],
    ['id' => 5, 'name' => 'Riya Gupta', 'email' => 'riya.gupta@email.com', 'phone' => '+91 54321 09876', 'join_date' => '2023-08-25', 'total_orders' => 15, 'total_spent' => 35999, 'last_order' => '2024-01-11', 'status' => 'Active', 'city' => 'Kolkata', 'state' => 'West Bengal'],
    ['id' => 6, 'name' => 'Zara Khan', 'email' => 'zara.khan@email.com', 'phone' => '+91 43210 98765', 'join_date' => '2024-01-05', 'total_orders' => 1, 'total_spent' => 2499, 'last_order' => '2024-01-10', 'status' => 'Active', 'city' => 'Hyderabad', 'state' => 'Telangana']
];

$statuses = ['All', 'Active', 'Inactive'];
$cities = ['All', 'New Delhi', 'Gurgaon', 'Mumbai', 'Bangalore', 'Kolkata', 'Hyderabad'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Management - ManavikFab</title>
    
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
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f8c9d8, #f4b6cc);
            color: #5b2b4a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
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
                    <a class="nav-link" href="products.php"><i class="bi bi-box me-2"></i>Products</a>
                    <a class="nav-link active" href="customers.php"><i class="bi bi-people me-2"></i>Customers</a>
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
                        <h4 class="mb-0">Customers Management</h4>
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
                            <div class="stats-card"><h5><?php echo count($customers); ?></h5><small class="text-muted">Total Customers</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo count(array_filter($customers, function($c) { return $c['status'] == 'Active'; })); ?></h5><small class="text-muted">Active Customers</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5>₹<?php echo number_format(array_sum(array_column($customers, 'total_spent'))); ?></h5><small class="text-muted">Total Revenue</small></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><h5><?php echo array_sum(array_column($customers, 'total_orders')); ?></h5><small class="text-muted">Total Orders</small></div>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <h5 class="mb-3">All Customers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Join Date</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Status</th>
                                        <th>Mail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($customers as $customer): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="customer-avatar me-3"><?php echo strtoupper(substr($customer['name'], 0, 1)); ?></div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?php echo $customer['name']; ?></h6>
                                                    <small class="text-muted">ID: <?php echo $customer['id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="mb-1"><?php echo $customer['email']; ?></div>
                                                <small class="text-muted"><?php echo $customer['phone']; ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo $customer['city']; ?>, <?php echo $customer['state']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($customer['join_date'])); ?></td>
                                        <td><?php echo $customer['total_orders']; ?></td>
                                        <td><strong>₹<?php echo number_format($customer['total_spent']); ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?php echo strtolower($customer['status']) == 'active' ? 'success' : 'secondary'; ?>">
                                                <?php echo $customer['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="sendEmail(<?php echo $customer['id']; ?>)" title="Send Email">
                                                <i class="bi bi-envelope"></i>
                                            </button>
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
        // REMOVED UNUSED JAVASCRIPT FUNCTIONS
        function sendEmail(customerId) { alert('Send email to customer: ' + customerId); }
    </script>
</body>
</html>