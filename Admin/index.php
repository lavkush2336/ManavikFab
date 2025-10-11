<?php
    session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample admin dashboard data
$stats = [
    'total_orders' => 1247,
    'total_revenue' => 2847500,
    'total_customers' => 892,
    'total_products' => 156
];

$recent_orders = [
    [
        'id' => 'ORD-001',
        'customer' => 'Priya Sharma',
        'amount' => 2499,
        'status' => 'Delivered',
        'date' => '2024-01-15'
    ],
    [
        'id' => 'ORD-002',
        'customer' => 'Anjali Patel',
        'amount' => 5999,
        'status' => 'Processing',
        'date' => '2024-01-14'
    ],
    [
        'id' => 'ORD-003',
        'customer' => 'Riya Singh',
        'amount' => 1299,
        'status' => 'Shipped',
        'date' => '2024-01-13'
    ],
    [
        'id' => 'ORD-004',
        'customer' => 'Kavya Reddy',
        'amount' => 8999,
        'status' => 'Pending',
        'date' => '2024-01-12'
    ]
];

$top_products = [
    [
        'name' => 'Embroidered Silk Saree',
        'sales' => 45,
        'revenue' => 112455
    ],
    [
        'name' => 'Designer Lehenga Set',
        'sales' => 32,
        'revenue' => 191968
    ],
    [
        'name' => 'Cotton Kurti with Palazzo',
        'sales' => 67,
        'revenue' => 87033
    ],
    [
        'name' => 'Western Dress Collection',
        'sales' => 28,
        'revenue' => 53172
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ManavikFab</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* Reset & layout fixes */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            color: #0f172a;
        }

        /* Sidebar */
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

        /* Stats cards */
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 6px 18px rgba(15,23,42,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(15,23,42,0.08); }

        /* Main content area - ensure no horizontal overflow */
        .main-content {
            margin-left: 240px;
            max-width: calc(100% - 240px);
            background-color: #f8f9fa;
            font-size: 0.96rem;
        }

        /* Navbar & main title */
        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar .container-fluid h4.mb-0{ font-size:1.5rem; font-weight:800; letter-spacing:-0.2px; margin:0; color:#0f172a }

        /* Table/card containers */
        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }

        /* Section headings - stronger hierarchy */
        .table-container h5,
        .stats-card h5,
        .table-container .h5,
        .main-content h5 { font-size:1.05rem; font-weight:700; color:#0f172a; margin-bottom:.75rem }

        /* Tables: readable and consistent */
        table { font-size:.95rem }
        thead th { font-weight:700; font-size:.98rem; background-color: #f8f9fa; }
        .table tbody tr td, .table thead th { padding:.75rem .9rem; vertical-align:middle }
        .table-responsive { overflow-x:auto }

        /* Spacing between major sections */
        .p-4 > .row.g-4 { margin-bottom: 1.75rem }
        .row.g-4.mt-4 { margin-top: 1.5rem }

        /* Reusable admin dropdown item style: bold, clear typography with icon alignment */
        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; font-size:1rem }
        
        /* --- NEW RESPONSIVE STYLES --- */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed; /* Keep it fixed so it can overlay */
                top: 0;
                left: 0;
                width: 240px;
                height: 100%;
                z-index: 1030; /* Higher z-index to appear on top */
                transform: translateX(-100%); /* Start off-screen */
                transition: transform 0.3s ease-in-out;
            }

            /* The 'show' class will be toggled by JavaScript */
            .sidebar.show {
                transform: translateX(0);
            }

            /* Make the main content take up the full width */
            .main-content {
                margin-left: 0;
                max-width: 100%;
            }
            
            /* Optional: Add an overlay when the sidebar is open */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1020;
                display: none; /* Hidden by default */
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
                    <a class="nav-link active" href="index.php">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="orders.php">
                        <i class="bi bi-cart3 me-2"></i>Orders
                    </a>
                    <a class="nav-link" href="products.php">
                        <i class="bi bi-box me-2"></i>Products
                    </a>
                    <a class="nav-link" href="customers.php">
                        <i class="bi bi-people me-2"></i>Customers
                    </a>
                    <a class="nav-link" href="categories.php">
                        <i class="bi bi-tags me-2"></i>Categories
                    </a>
                    <a class="nav-link" href="inventory.php">
                        <i class="bi bi-boxes me-2"></i>Inventory
                    </a>
                    <a class="nav-link" href="reports.php">
                        <i class="bi bi-graph-up me-2"></i>Reports
                    </a>
                    <a class="nav-link" href="settings.php">
                        <i class="bi bi-gear me-2"></i>Settings                 
                    </a>
                    <hr>
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </nav>
            </div>

            <div class="col-md-9 col-lg-10 main-content p-4">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <h4 class="mb-0">Dashboard</h4>
                        <div class="d-flex align-items-center">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2"></i>Admin
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item admin-dropdown-item" href="settings.php"><i class="bi bi-gear me-0"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item admin-dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-0"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-cart3 text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1"><?php echo number_format($stats['total_orders']); ?></h5>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-currency-rupee text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">₹<?php echo number_format($stats['total_revenue']); ?></h5>
                                        <small class="text-muted">Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-people text-warning" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1"><?php echo number_format($stats['total_customers']); ?></h5>
                                        <small class="text-muted">Total Customers</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-box text-info" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1"><?php echo number_format($stats['total_products']); ?></h5>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="table-container">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Recent Orders</h5>
                                    <a href="orders.php" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recent_orders as $order): ?>
                                            <tr>
                                                <td><?php echo $order['id']; ?></td>
                                                <td><?php echo $order['customer']; ?></td>
                                                <td>₹<?php echo number_format($order['amount']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo $order['status'] == 'Delivered' ? 'success' : 
                                                            ($order['status'] == 'Processing' ? 'warning' : 
                                                            ($order['status'] == 'Shipped' ? 'info' : 'secondary')); 
                                                    ?>">
                                                        <?php echo $order['status']; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                                <td>
                                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="table-container">
                                <h5 class="mb-3">Top Products</h5>
                                <?php foreach($top_products as $product): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo $product['name']; ?></h6>
                                        <small class="text-muted">
                                            <?php echo $product['sales']; ?> sales • ₹<?php echo number_format($product['revenue']); ?>
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="progress" style="width: 60px; height: 6px;">
                                            <div class="progress-bar" style="width: <?php echo ($product['sales'] / 67) * 100; ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-lg-8">
                            <div class="table-container">
                                <h5 class="mb-3">
                                    <i class="bi bi-graph-up me-2"></i>Sales Overview
                                </h5>
                                <div style="position: relative; height: 350px;">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="table-container">
                                <h5 class="mb-3">
                                    <i class="bi bi-pie-chart me-2"></i>Category Distribution
                                </h5>
                                <div style="position: relative; height: 350px;">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-12">
                            <div class="table-container">
                                <h5 class="mb-3">Quick Actions</h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="products.php?action=add" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Add Product
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="orders.php?status=pending" class="btn btn-outline-warning w-100">
                                            <i class="bi bi-clock me-2"></i>Pending Orders
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="inventory.php" class="btn btn-outline-info w-100">
                                            <i class="bi bi-boxes me-2"></i>Check Inventory
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="reports.php" class="btn btn-outline-success w-100">
                                            <i class="bi bi-graph-up me-2"></i>Generate Report
                                        </a>
                                    </div>
                                </div>
                            </div>
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
        // Sales Chart - Fixed with better data and styling
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales (₹)',
                    data: [1250000, 1450000, 1350000, 1650000, 1550000, 1850000, 1750000, 1950000, 1850000, 2050000, 2250000, 2450000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Orders',
                    data: [45, 52, 48, 58, 55, 65, 62, 68, 65, 72, 78, 85],
                    borderColor: '#764ba2',
                    backgroundColor: 'rgba(118, 75, 162, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#764ba2',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Poppins'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Sales (₹)') {
                                    return 'Sales: ₹' + context.parsed.y.toLocaleString('en-IN');
                                } else {
                                    return 'Orders: ' + context.parsed.y;
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Poppins'
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Sales (₹)',
                            font: {
                                size: 14,
                                family: 'Poppins',
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₹' + (value / 100000) + 'L';
                            },
                            font: {
                                size: 12,
                                family: 'Poppins'
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Orders',
                            font: {
                                size: 14,
                                family: 'Poppins',
                                weight: '600'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Poppins'
                            }
                        }
                    }
                }
            }
        });

        // Category Chart - Enhanced with better styling
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sarees', 'Lehengas', 'Kurtis', 'Dresses', 'Accessories'],
                datasets: [{
                    data: [35, 20, 25, 15, 5],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#f5576c',
                        '#4facfe'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Poppins'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + '% (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
</body>
</html>