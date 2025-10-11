<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header("Location: login.php");
    // exit();
}

// Sample categories data
$categories = [
    ['id' => 1, 'name' => 'Ethnic Wear', 'description' => 'Traditional Indian ethnic wear including sarees, lehengas, and kurtis', 'products_count' => 45, 'status' => 'Active', 'created_date' => '2023-01-15', 'parent_category' => null],
    ['id' => 2, 'name' => 'Western Wear', 'description' => 'Modern western clothing including dresses, tops, and jeans', 'products_count' => 38, 'status' => 'Active', 'created_date' => '2023-01-20', 'parent_category' => null],
    ['id' => 3, 'name' => 'Accessories', 'description' => 'Fashion accessories including jewelry, bags, and footwear', 'products_count' => 52, 'status' => 'Active', 'created_date' => '2023-02-01', 'parent_category' => null],
    ['id' => 4, 'name' => 'Sarees', 'description' => 'Traditional Indian sarees in various fabrics and designs', 'products_count' => 28, 'status' => 'Active', 'created_date' => '2023-01-25', 'parent_category' => 'Ethnic Wear'],
    ['id' => 5, 'name' => 'Lehengas', 'description' => 'Bridal and party wear lehengas with intricate designs', 'products_count' => 15, 'status' => 'Active', 'created_date' => '2023-02-10', 'parent_category' => 'Ethnic Wear'],
    ['id' => 6, 'name' => 'Dresses', 'description' => 'Casual and party dresses for all occasions', 'products_count' => 22, 'status' => 'Active', 'created_date' => '2023-01-30', 'parent_category' => 'Western Wear'],
    ['id' => 7, 'name' => 'Jewelry', 'description' => 'Traditional and modern jewelry pieces', 'products_count' => 35, 'status' => 'Inactive', 'created_date' => '2023-02-15', 'parent_category' => 'Accessories']
];

$parent_categories_options = ['Ethnic Wear', 'Western Wear', 'Accessories'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management - ManavikFab</title>
    
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
        }
        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar h4.mb-0{ font-size:1.5rem; font-weight:800; }
        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }
        .admin-dropdown-item { font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i { width:20px; display:inline-flex; align-items:center; justify-content:center; }
        
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
                    <a class="nav-link active" href="categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
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
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle"><i class="bi bi-list"></i></button>
                        <h4 class="mb-0">Categories Management</h4>
                        <div class="d-flex align-items-center ms-auto">
                            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                <i class="bi bi-tags me-2"></i>Add Brand
                            </button>
                            <button class="btn btn-primary me-3" id="addCategoryBtn">
                                <i class="bi bi-plus-circle me-2"></i>Add Category
                            </button>
                            <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>Admin
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item admin-dropdown-item" href="settings.php"><i class="bi bi-gear"></i> <b>Settings</b></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> <b>Logout</b></a></li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </nav>

                <div class="p-4">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Parent</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $category): ?>
                                    <tr data-id="<?php echo $category['id']; ?>">
                                        <td><strong><?php echo $category['id']; ?></strong></td>
                                        <td class="category-name"><?php echo $category['name']; ?></td>
                                        <td class="category-description"><?php echo $category['description']; ?></td>
                                        <td class="parent-category">
                                            <?php if($category['parent_category']): ?>
                                                <span class="badge bg-light text-dark"><?php echo $category['parent_category']; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $category['products_count']; ?></td>
                                        <td class="category-status"><span class="badge bg-<?php echo $category['status'] == 'Active' ? 'success' : 'secondary'; ?>"><?php echo $category['status']; ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($category['created_date'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-success edit-btn" title="Edit"><i class="bi bi-pencil"></i></button>
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
    
    <div class="modal fade" id="categoryFormModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <input type="hidden" id="categoryId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category Name *</label>
                                <input type="text" id="categoryName" class="form-control" placeholder="Enter category name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" id="categorySlug" class="form-control" placeholder="e.g., ethnic-wear" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Enter a brief category description"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parent Category</label>
                                <select class="form-select" id="parentCategory">
                                    <option value="">No Parent</option>
                                    <?php foreach($parent_categories_options as $parent): ?>
                                        <option value="<?php echo $parent; ?>"><?php echo $parent; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="categoryStatus">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn"></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addBrandModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tags me-2"></i>Add New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addBrandForm">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name *</label>
                            <input type="text" id="brandName" class="form-control" placeholder="Enter brand name" required>
                        </div>
                        <div class="mb-3">
                            <label for="brandLogo" class="form-label">Brand Logo</label>
                            <input type="file" id="brandLogo" class="form-control" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveBrandBtn">Add Brand</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Universal Category Modal Logic ---
            const categoryModal = new bootstrap.Modal(document.getElementById('categoryFormModal'));
            const categoryModalTitle = document.getElementById('categoryModalTitle');
            const saveCategoryBtn = document.getElementById('saveCategoryBtn');
            const categoryForm = document.getElementById('categoryForm');
            const tableBody = document.querySelector('.table tbody');

            document.getElementById('addCategoryBtn').addEventListener('click', function() {
                categoryForm.reset();
                document.getElementById('categoryId').value = '';
                categoryModalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Add New Category';
                saveCategoryBtn.textContent = 'Add Category';
                saveCategoryBtn.className = 'btn btn-primary';
                categoryModal.show();
            });

            tableBody.addEventListener('click', function(event) {
                const editButton = event.target.closest('.edit-btn');
                if (!editButton) return;
                const row = editButton.closest('tr');
                document.getElementById('categoryId').value = row.dataset.id;
                document.getElementById('categoryName').value = row.querySelector('.category-name').textContent.trim();
                document.getElementById('categoryDescription').value = row.querySelector('.category-description').textContent.trim();
                document.getElementById('parentCategory').value = row.querySelector('.parent-category .badge')?.textContent.trim() || '';
                document.getElementById('categoryStatus').value = row.querySelector('.category-status .badge').textContent.trim();
                categoryModalTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Edit Category';
                saveCategoryBtn.textContent = 'Save Changes';
                saveCategoryBtn.className = 'btn btn-success';
                categoryModal.show();
            });

            saveCategoryBtn.addEventListener('click', function() {
                const id = document.getElementById('categoryId').value;
                const name = document.getElementById('categoryName').value;
                if (!name) {
                    alert('Category Name is required.');
                    return;
                }
                if (id) {
                    alert(`Simulating update for Category ID: ${id}`);
                } else {
                    alert(`Simulating adding new category: "${name}"`);
                }
                categoryModal.hide();
            });

            // --- "Add Brand" Modal Logic ---
            const saveBrandButton = document.getElementById('saveBrandBtn');
            const addBrandModal = new bootstrap.Modal(document.getElementById('addBrandModal'));
            const brandForm = document.getElementById('addBrandForm');

            saveBrandButton.addEventListener('click', function() {
                const brandName = document.getElementById('brandName').value;
                if(!brandName) {
                    alert('Brand Name is required.');
                    return;
                }
                alert('Brand "' + brandName + '" added successfully!');
                brandForm.reset();
                addBrandModal.hide();
            });

            // --- Sidebar toggle script ---
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