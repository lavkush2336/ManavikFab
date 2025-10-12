<?php
session_start();
include '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: login.php");
    exit();
}

// ==========================================================
// PHP LOGIC BLOCKS (All centralized at the top for stability)
// ==========================================================

// --- 1. ADD CATEGORY LOGIC (CSAVE) ---
if(isset($_POST['csave']))
{
    // FIX: Sanitize all string inputs to prevent SQL errors
    $cname=mysqli_real_escape_string($con, $_POST['cname']);
    $slug=mysqli_real_escape_string($con, $_POST['slug']);
    $desc=mysqli_real_escape_string($con, $_POST['desc']);
    $pcat=mysqli_real_escape_string($con, $_POST['pcat']);
    $status=$_POST['status'];
    
    // Convert Status string to database integer (1 or 0)
    $status_int=($status=="Active") ? 1 : 0;

    // Execute insertion query
    $sql="INSERT INTO `Category`(`Name`, `Slug`, `Description`, `PCategory`, `Status`) VALUES ('$cname','$slug','$desc','$pcat','$status_int')";
    $result=mysqli_query($con,$sql);
    
    if($result) {
        header("Location: categories.php");
    } else {
        header("Location: categories.php?status=db_error");
    }
    exit(); 
}

// --- 2. EDIT CATEGORY LOGIC (CATEGORY_UPDATE) ---
if(isset($_POST['category_update']))
{
    // Sanitize all inputs 
    $id = mysqli_real_escape_string($con, $_POST['categoryId']);
    $cname = mysqli_real_escape_string($con, $_POST['cname']);
    $slug = mysqli_real_escape_string($con, $_POST['slug']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);
    $pcat = mysqli_real_escape_string($con, $_POST['pcat']);
    $status = $_POST['status'];
    
    // Convert Status string to database integer (1 or 0)
    if($status == "Active")
    {
        $status_int = 1;
    }
    else
    {
        $status_int = 0;
    }

    // Perform SQL UPDATE query
    $sql = "UPDATE `Category` SET 
                `Name` = '$cname', 
                `Slug` = '$slug', 
                `Description` = '$desc', 
                `PCategory` = '$pcat', 
                `Status` = '$status_int' 
            WHERE `CategoryID` = '$id'";
            
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        header("Location: categories.php");
    } 
    exit(); 
}

// --- 3. BRAND SUBMISSION LOGIC (BSAVE) ---
if(isset($_POST['bsave']))
{
    $name = mysqli_real_escape_string($con, $_POST['bname']);
    $logo_path_for_db = ''; 

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp_name = $_FILES['logo']['tmp_name'];
        $original_file_name = basename($_FILES['logo']['name']);
        $target_dir = dirname(dirname(__FILE__)) . "/images/"; 
        $new_file_name = time() . "_brand_" . $original_file_name; 
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_name, $target_file)) {
            $logo_path_for_db = $new_file_name; 
        } 
    }
    
    $sql = "INSERT INTO `brand`(`Name`, `Logo`) VALUES ('$name','$logo_path_for_db')";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        header("Location: categories.php");
    } 
    exit(); 
}
// ==========================================================

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
        /* ... (CSS unchanged) ... */
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
                            <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal"> 
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
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <?PHP
                    $sql="SELECT * FROM `Category`";
                    $result=mysqli_query($con,$sql);
                    $rows=mysqli_num_rows($result);
                    ?>
                                <tbody>
                                    <?PHP
                    if($rows>0)
                    {
                        while($row=mysqli_fetch_assoc($result))
                        {
                            $id=$row['CategoryID'];
                            $name=$row['Name'];
                            $slug=$row['Slug'];
                            $desc=$row['Description'];
                            $pcat=$row['PCategory'];
                            $status_int=$row['Status'];
                            $status_text=($status_int==1) ? "Active" : "InActive";
                            $status_badge=($status_int==1) ? "success" : "secondary";
                            if($pcat==0)
                            {
                                $pcat="No Parent";
                            }
                            else if($pcat==1)
                            {
                                $pcat="Ethnic Wear";
                            }
                            else if($pcat==2)
                            {
                                $pcat="Western Wear";
                            }
                            else
                            {
                                $pcat="Accessories";
                            }
                            ?>
                                    <tr data-id="<?PHP echo $id; ?>" 
                                        data-name="<?PHP echo htmlspecialchars($name); ?>"
                                        data-slug="<?PHP echo htmlspecialchars($slug); ?>"
                                        data-desc="<?PHP echo htmlspecialchars($desc); ?>"
                                        data-pcat="<?PHP echo htmlspecialchars($pcat); ?>"
                                        data-status="<?PHP echo htmlspecialchars($status_text); ?>">
                                        <td><strong><?PHP echo $id; ?></strong></td>
                                        <td class="category-name"><?PHP echo $name; ?></td>
                                        <td class="category-description"><?PHP echo $desc; ?></td>
                                        <td class="parent-category">
                                                <span class="badge bg-light text-dark"><?PHP echo $pcat; ?></span>
                                        </td>
                                        <td>0</td>
                                        <td><?PHP echo $slug; ?></td>
                                        <td class="category-status"><span class="badge bg-<?PHP echo $status_badge; ?>"><?PHP echo $status_text; ?></span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-success edit-btn" title="Edit" 
                                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                                        onclick="populateEditModal(this)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="delete.php?catid=<?PHP echo $id;?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?PHP
                        }
                    }
                    else
                    {
                        echo "<td colspan='8'><div class='alert alert-warning mt-3' role='alert'>
  No Category Added Yet!
</div></td>";
                    }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalTitle"><i class="bi bi-plus-circle me-2"></i>Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm" method="POST" action="categories.php"> 
                        <input type="hidden" name="categoryId"> 
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category Name *</label>
                                <input type="text" id="addCategoryName" class="form-control" placeholder="Enter category name" required name="cname">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug *</label>
                                <input type="text" id="addCategorySlug" class="form-control" placeholder="e.g., ethnic-wear" required name="slug">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="addCategoryDescription" rows="3" placeholder="Enter a brief category description" required name="desc"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parent Category</label>
                                <select class="form-select" id="addParentCategory" name="pcat">
                                    <option value="0">No Parent</option>
                                    <option value="1">Ethnic Wear</option>
                                    <option value="2">Western Wear</option>
                                    <option value="3">Accessories</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="addCategoryStatus" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="csave">Add Category</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalTitle"><i class="bi bi-pencil-square me-2"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" method="POST" action="categories.php"> 
                        <input type="hidden" id="editCategoryId" name="categoryId"> 
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category Name *</label>
                                <input type="text" id="editCategoryName" class="form-control" required name="cname">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug *</label>
                                <input type="text" id="editCategorySlug" class="form-control" required name="slug">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="editCategoryDescription" rows="3" required name="desc"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parent Category</label>
                                <select class="form-select" id="editParentCategory" name="pcat">
                                    <option value="0">No Parent</option>
                                    <option value="1">Ethnic Wear</option>
                                    <option value="2">Western Wear</option>
                                    <option value="3">Accessories</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editCategoryStatus" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="category_update">Save Changes</button>
                </div>
                    </form>
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
                    <form  id="addBrandForm" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name *</label>
                            <input type="text" id="brandName" class="form-control" placeholder="Enter brand name" required name="bname">
                        </div>
                        <div class="mb-3">
                            <label for="brandLogo" class="form-label">Brand Logo</label>
                            <input type="file" id="brandLogo" class="form-control" accept="image/*" required name="logo">
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="bsave">Add Brand</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- JAVASCRIPT FOR EDIT MODAL POPULATION ---
            const editModalElement = document.getElementById('editCategoryModal');

            // Function to populate the edit modal fields
            window.populateEditModal = function(button) {
                const row = button.closest('tr');
                
                // Populate the form fields using the data attributes
                document.getElementById('editCategoryId').value = row.dataset.id;
                document.getElementById('editCategoryName').value = row.dataset.name;
                document.getElementById('editCategorySlug').value = row.dataset.slug;
                document.getElementById('editCategoryDescription').value = row.dataset.desc; 
                
                // Set SELECT fields using .value
                document.getElementById('editParentCategory').value = row.dataset.pcat;
                document.getElementById('editCategoryStatus').value = row.dataset.status;
            }

            // Optional: Attach listener to the whole table for slightly better performance/cleaner HTML
            document.querySelector('.table tbody').addEventListener('click', function(event) {
                const editButton = event.target.closest('.edit-btn');
                if (editButton) {
                    populateEditModal(editButton);
                }
            });


            // --- Sidebar toggle script (unchanged) ---
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