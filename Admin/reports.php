<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample reports data
$sales_data = [
    ['month' => 'Jan', 'sales' => 125000, 'orders' => 45], ['month' => 'Feb', 'sales' => 145000, 'orders' => 52],
    ['month' => 'Mar', 'sales' => 135000, 'orders' => 48], ['month' => 'Apr', 'sales' => 165000, 'orders' => 58],
    ['month' => 'May', 'sales' => 155000, 'orders' => 55], ['month' => 'Jun', 'sales' => 185000, 'orders' => 65],
    ['month' => 'Jul', 'sales' => 175000, 'orders' => 62], ['month' => 'Aug', 'sales' => 195000, 'orders' => 68],
    ['month' => 'Sep', 'sales' => 185000, 'orders' => 65], ['month' => 'Oct', 'sales' => 205000, 'orders' => 72],
    ['month' => 'Nov', 'sales' => 225000, 'orders' => 78], ['month' => 'Dec', 'sales' => 245000, 'orders' => 85]
];
$category_sales = [
    ['category' => 'Sarees', 'sales' => 35, 'revenue' => 87500], ['category' => 'Lehengas', 'sales' => 20, 'revenue' => 70000],
    ['category' => 'Kurtis', 'sales' => 25, 'revenue' => 37500], ['category' => 'Dresses', 'sales' => 15, 'revenue' => 27000],
    ['category' => 'Accessories', 'sales' => 5, 'revenue' => 8000]
];
$top_products = [
    ['name' => 'Silk Saree - Red', 'sales' => 45, 'revenue' => 54000], ['name' => 'Bridal Lehenga - Gold', 'sales' => 32, 'revenue' => 112000],
    ['name' => 'Cotton Kurti - Blue', 'sales' => 58, 'revenue' => 26100], ['name' => 'Party Dress - Black', 'sales' => 28, 'revenue' => 50400],
    ['name' => 'Silver Jewelry Set', 'sales' => 42, 'revenue' => 33600]
];
$customer_metrics = [
    ['metric' => 'Total Customers', 'value' => 1250, 'change' => '+12%'], ['metric' => 'New Customers', 'value' => 85, 'change' => '+8%'],
    ['metric' => 'Repeat Customers', 'value' => 420, 'change' => '+15%'], ['metric' => 'Average Order Value', 'value' => 2899, 'change' => '+5%']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - ManavikFab Admin</title>
    <meta name="description" content="View comprehensive business reports, sales analytics, and performance metrics in the ManavikFab admin panel">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* START: Standardized CSS from index.php */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
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
            display: block;
            align-items: center;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #2d2d2d;
            box-shadow: none;
            width: 100%;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
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
            max-width: calc(100% - 240px);
            background-color: #f8f9fa;
            font-size: 0.96rem;
        }

        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar .container-fluid h4.mb-0{ font-size:1.5rem; font-weight:800; letter-spacing:-0.2px; margin:0; color:#0f172a }

        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }

        .table-container h5 { font-size:1.05rem; font-weight:700; color:#0f172a; margin-bottom:.75rem }

        table { font-size:.95rem }
        thead th { font-weight:700; font-size:.98rem; background-color: #f8f9fa; }
        .table tbody tr td, .table thead th { padding:.75rem .9rem; vertical-align:middle }

        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; font-size:1rem }
        /* END: Standardized CSS */

        /* Custom styles for reports page */
        .chart-container { position: relative; height: 320px; }
        .positive-change { color: #198754; }
        .negative-change { color: #dc3545; }
        .btn-custom {
            background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%);
            color: #2d2d2d;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }

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
                    <a class="nav-link" href="inventory.php"><i class="bi bi-boxes me-2"></i>Inventory</a>
                    <a class="nav-link active" href="reports.php"><i class="bi bi-graph-up me-2"></i>Reports</a>
                    <a class="nav-link" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                    <hr>
                    <a class="nav-link" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10 main-content p-0">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <h4 class="mb-0">Reports & Analytics</h4>
                        <div class="d-flex align-items-center">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2"></i>Admin
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item admin-dropdown-item" href="settings.php"><i class="bi bi-gear me-0"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item admin-dropdown-item" href="../logout.php"><i class="bi bi-box-arrow-right me-0"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="bi bi-currency-rupee text-success" style="font-size: 2rem;"></i></div><div class="flex-grow-1 ms-3"><h5 class="mb-1">₹<?php echo number_format(array_sum(array_column($sales_data, 'sales'))); ?></h5><small class="text-muted">Total Revenue</small></div></div></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="bi bi-cart3 text-primary" style="font-size: 2rem;"></i></div><div class="flex-grow-1 ms-3"><h5 class="mb-1"><?php echo array_sum(array_column($sales_data, 'orders')); ?></h5><small class="text-muted">Total Orders</small></div></div></div>
                        </div>
                        <div class="col-md-3">
                             <div class="stats-card"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="bi bi-people text-warning" style="font-size: 2rem;"></i></div><div class="flex-grow-1 ms-3"><h5 class="mb-1"><?php echo $customer_metrics[0]['value']; ?></h5><small class="text-muted">Total Customers</small></div></div></div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card"><div class="d-flex align-items-center"><div class="flex-shrink-0"><i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i></div><div class="flex-grow-1 ms-3"><h5 class="mb-1">₹<?php echo number_format($customer_metrics[3]['value']); ?></h5><small class="text-muted">Avg Order Value</small></div></div></div>
                        </div>
                    </div>
                    
                    <div class="table-container p-4 mb-4">
                        <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Report Filters</h5>
                        <form class="row">
                            <div class="col-md-3 mb-3"><label class="form-label">Date Range</label><select class="form-select"><option>Last 30 Days</option><option>Last 90 Days</option><option>Last Year</option></select></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Category</label><select class="form-select"><option>All Categories</option><option>Sarees</option><option>Lehengas</option></select></div>
                            <div class="col-md-3 mb-3"><label class="form-label">Report Type</label><select class="form-select"><option>Sales Report</option><option>Customer Report</option></select></div>
                            <div class="col-md-3 d-flex align-items-end mb-3"><button class="btn btn-custom w-100"><i class="bi bi-download me-2"></i>Generate & Export</button></div>
                        </form>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-lg-8"><div class="table-container p-4 h-100"><h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Sales Trend (Last 12 Months)</h5><div class="chart-container"><canvas id="salesChart"></canvas></div></div></div>
                        <div class="col-lg-4"><div class="table-container p-4 h-100"><h5 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Category Revenue</h5><div class="chart-container"><canvas id="categoryChart"></canvas></div></div></div>
                    </div>
                    
                    <div class="table-container p-4">
                        <h5 class="mb-3"><i class="bi bi-table me-2"></i>Detailed Monthly Report</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Month</th><th>Sales (₹)</th><th>Orders</th><th>Avg Order Value</th><th>Growth</th></tr></thead>
                                <tbody>
                                    <?php foreach($sales_data as $index => $data): ?>
                                    <tr>
                                        <td><strong><?php echo $data['month']; ?></strong></td>
                                        <td>₹<?php echo number_format($data['sales']); ?></td>
                                        <td><?php echo $data['orders']; ?></td>
                                        <td>₹<?php echo number_format($data['sales'] / $data['orders']); ?></td>
                                        <td>
                                            <?php if($index > 0): $growth = (($data['sales'] - $sales_data[$index-1]['sales']) / $sales_data[$index-1]['sales']) * 100; ?>
                                                <span class="<?php echo $growth >= 0 ? 'positive-change' : 'negative-change'; ?>">
                                                    <?php echo ($growth >= 0 ? '+' : ''); ?><?php echo round($growth, 1); ?>% <i class="bi <?php echo $growth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                                </span>
                                            <?php else: echo '<span class="text-muted">-</span>'; endif; ?>
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
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($sales_data, 'month')); ?>,
                datasets: [{ label: 'Sales (₹)', data: <?php echo json_encode(array_column($sales_data, 'sales')); ?>, borderColor: '#f4b6cc', backgroundColor: 'rgba(244, 182, 204, 0.2)', tension: 0.4, fill: true }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
        
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($category_sales, 'category')); ?>,
                datasets: [{ data: <?php echo json_encode(array_column($category_sales, 'revenue')); ?>, backgroundColor: ['#f8c9d8', '#f4b6cc', '#e56b6f', '#d89a9e', '#b68d91'] }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</body>
</html>