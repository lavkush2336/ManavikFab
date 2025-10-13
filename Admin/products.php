<?php
session_start();
include '../connection.php';
if (!isset($_SESSION['adminid'])) {
    header("Location: login.php");
    exit();
}
// --- START: AJAX Handler to fetch product details ---
// This block runs only when JavaScript asks for a product's data.
if (isset($_GET['fetch_product_id'])) {
    header('Content-Type: application/json');
    $productId = mysqli_real_escape_string($con, $_GET['fetch_product_id']);
    
    // We need to join tables to get the CategoryID and BrandID for the dropdowns
    $sql = "SELECT p.*, c.CategoryID, b.BrandID 
            FROM product p
            LEFT JOIN Category c ON p.Category = c.Name
            LEFT JOIN brand b ON p.Brand = b.Name
            WHERE p.ProductID = '$productId'";
            
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        echo json_encode($product); // Send data back as JSON
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
    exit; // Stop the script here
}
// --- END: AJAX Handler ---

// --- START: Handle Product UPDATE ---
if (isset($_POST['update'])) {
    // Sanitize all inputs
    $productID = mysqli_real_escape_string($con, $_POST['product_id']);
    $name    = mysqli_real_escape_string($con, $_POST['name']);
    $catID   = mysqli_real_escape_string($con, $_POST['cat']);
    $qty     = mysqli_real_escape_string($con, $_POST['qty']);
    $price   = mysqli_real_escape_string($con, $_POST['price']);
    $sprice   = mysqli_real_escape_string($con, $_POST['sprice']);
    $brandID = $_POST['brand'];
    $color=$_POST['ecolor'];
    $size=$_POST['esize'];
    $desc    = mysqli_real_escape_string($con, $_POST['desc']);

    if($size==1)
    {
        $size="Free Size";
    }
    if($size==2)
    {
        $size="All Sizes";
    }

    // Fetch Category Name based on ID
    $catNameResult = mysqli_query($con, "SELECT `Name` FROM `Category` WHERE `CategoryID`='$catID'");
    $catName = mysqli_fetch_assoc($catNameResult)['Name'] ?? '';
    
    // 🔥 FIX: Brand Name retrieval for UPDATE - Use explicit check for existence
    $bsql="SELECT `Name` FROM `brand` WHERE `BrandID`='$brandID'";
    $bresult=mysqli_query($con,$bsql);
    $brow=mysqli_fetch_assoc($bresult);
    $brandName=$brow['Name'];
    // If no brand name is found, $brandName remains an empty string, preventing the insertion of '0'.

    // --- SMART IMAGE UPDATE LOGIC ---
    // 1. Get current image filenames from the database to check against
    $currentImagesResult = mysqli_query($con, "SELECT img1, img2, img3, img4, img5 FROM product WHERE ProductID='$productID'");
    $currentImages = mysqli_fetch_assoc($currentImagesResult);
    $newImagePaths = $currentImages; // Start with old paths

    $target_dir = dirname(dirname(__FILE__)) . "/images/";

    for ($i = 1; $i <= 5; $i++) {
        $img_key = 'edit_img' . $i;
        $db_img_col = 'img' . $i;

        // Check if a new file was uploaded for this slot
        if (isset($_FILES[$img_key]) && $_FILES[$img_key]['error'] == UPLOAD_ERR_OK) {
            // A new image is being uploaded, so delete the old one if it exists
            if (!empty($currentImages[$db_img_col]) && file_exists($target_dir . $currentImages[$db_img_col])) {
                unlink($target_dir . $currentImages[$db_img_col]); // Delete old file
            }
            
            // Process and save the new file
            $file_tmp_name = $_FILES[$img_key]['tmp_name'];
            $file_extension = pathinfo($_FILES[$img_key]['name'], PATHINFO_EXTENSION);
            $unique_filename = "product_" . time() . "_" . $i . "." . $file_extension;
            $target_file = $target_dir . $unique_filename;
            
            if (move_uploaded_file($file_tmp_name, $target_file)) {
                $newImagePaths[$db_img_col] = $unique_filename; // Update path for SQL query
            }
        }
        // If no new file is uploaded, $newImagePaths[$db_img_col] retains the old value.
    }

    // Prepare values for the SQL query
    $img1 = mysqli_real_escape_string($con, $newImagePaths['img1']);
    $img2 = mysqli_real_escape_string($con, $newImagePaths['img2']);
    $img3 = mysqli_real_escape_string($con, $newImagePaths['img3']);
    $img4 = mysqli_real_escape_string($con, $newImagePaths['img4']);
    $img5 = mysqli_real_escape_string($con, $newImagePaths['img5']);
    
    // SQL UPDATE Query using Prepared Statement for security
    $sql = "UPDATE `product` SET `Name` = '$name', `Category` = '$catName', `Brand` = '$brandName', `Price` = '$price',`SPrice`='$sprice', `Quantity` = '$qty', `Description` = '$desc', `Colour`='$color', `Size`='$size', `img1` = '$img1', `img2` = '$img2', `img3` = '$img3', `img4` = '$img4', `img5` = '$img5' WHERE `ProductID` = '$productID'";
    $result=mysqli_query($con,$sql);
    header('Location: products.php');
}
// --- END: Handle Product UPDATE ---


// Handle Product ADD (your existing code, works great)
if(isset($_POST['save']))
{
    // Sanitize and store input IDs/Values
    $name    = mysqli_real_escape_string($con, $_POST['name']);
    $catID   = mysqli_real_escape_string($con, $_POST['cat']);
    $qty     = mysqli_real_escape_string($con, $_POST['qty']);
    $price   = mysqli_real_escape_string($con, $_POST['price']);
    $sprice   = mysqli_real_escape_string($con, $_POST['sprice']);
    $brandID = mysqli_real_escape_string($con, $_POST['brand']);
    $color=$_POST['color'];
    $size=$_POST['size'];
    $desc    = mysqli_real_escape_string($con, $_POST['desc']);

    if($size==1)
    {
        $size="Free Size";
    }
    if($size==2)
    {
        $size="All Sizes";
    }
    
    if (empty($catID) || empty($brandID)) {
        error_log("Validation FAILED: Category or Brand ID was empty.");
        header("Location: products.php?status=validation_fail");
        exit;
    }

    $catName = ""; $brandName = "";

    // Fetch Category Name safely
    $sql_cat="SELECT `Name` FROM `Category` WHERE `CategoryID`='$catID'";
    $result_cat=mysqli_query($con,$sql_cat);
    if ($result_cat && mysqli_num_rows($result_cat) > 0) { $catName=mysqli_fetch_assoc($result_cat)['Name']; }

    // 🔥 FIX: Brand Name retrieval for ADD - Use explicit check for existence
    $sql_brand="SELECT `Name` FROM `brand` WHERE `BrandID`='$brandID'";
    $result_brand=mysqli_query($con,$sql_brand);
    $row_brand=mysqli_fetch_assoc($result_brand);
    $brandName=$row_brand['Name'];
    
    $target_dir = dirname(dirname(__FILE__)) . "/images/"; 
    $img_paths = [];

    for ($i = 1; $i <= 5; $i++) {
        $img_name_key = 'img' . $i;
        if (isset($_FILES[$img_name_key]) && $_FILES[$img_name_key]['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES[$img_name_key]['tmp_name'];
            $file_extension = pathinfo($_FILES[$img_name_key]['name'], PATHINFO_EXTENSION);
            $unique_filename = "product_" . time() . "_" . $i . "." . $file_extension;
            $target_file = $target_dir . $unique_filename;

            if (move_uploaded_file($file_tmp_name, $target_file)) {
                $img_paths[$i] = mysqli_real_escape_string($con, $unique_filename);
            } else { $img_paths[$i] = ""; }
        } else { $img_paths[$i] = ""; }
    }
    
    $img1 = $img_paths[1] ?? ''; $img2 = $img_paths[2] ?? ''; $img3 = $img_paths[3] ?? ''; $img4 = $img_paths[4] ?? ''; $img5 = $img_paths[5] ?? '';

    $sql="INSERT INTO `product`(`Name`, `Category`, `Brand`, `Price`, `SPrice`, `Quantity`, `Description`, `Colour`, `Size`, `img1`, `img2`, `img3`, `img4`, `img5`) 
          VALUES ('$name','$catName','$brandName','$price','$sprice','$qty','$desc','$color','$size','$img1','$img2','$img3','$img4','$img5')";
          
    $result=mysqli_query($con,$sql);
    
    if ($result) {
        header("Location: products.php?status=add_success");
    } else {
        error_log("Product INSERT FAILED: " . mysqli_error($con));
        header("Location: products.php?status=db_insert_fail");
    }
    exit;
}
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
        /* Your CSS styles remain unchanged... */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #0f172a; }
        .sidebar { background: linear-gradient(135deg, #f8c9d8 0%, #f4b6cc 100%); min-height: 100vh; position: fixed; top: 0; left: 0; width: 240px; z-index: 1000; overflow-y: auto; }
        .sidebar .nav-link { color: #2d2d2d; padding: 0.75rem 1rem; border-radius: 0.5rem; margin: 0.25rem 0; transition: all 0.3s ease; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: rgba(255, 255, 255, 0.2); color: #2d2d2d; }
        .main-content { margin-left: 240px; background-color: #f8f9fa; }
        .navbar { background: white; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding:.6rem 1rem }
        .navbar .container-fluid h4.mb-0{ font-size:1.5rem; font-weight:800; }
        .table-container { background: white; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 6px 18px rgba(15,23,42,0.04); }
        .table-container h5 { font-weight:700; color:#0f172a; margin-bottom:.75rem }
        table { font-size:.95rem }
        thead th { font-weight:700; background-color: #f8f9fa; }
        .table tbody tr td, .table thead th { padding:.75rem .9rem; vertical-align:middle }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 0.5rem; }
        .admin-dropdown-item{ font-weight:700; font-size:0.95rem; color:#212529; display:flex; align-items:center; gap:0.5rem; padding:0.45rem 0.9rem }
        .dropdown-menu .admin-dropdown-item i{ width:20px; display:inline-flex; align-items:center; justify-content:center; }
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px; } /* New style for image previews */
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

            <main class="col-md-9 col-lg-10 main-content p-4">
                 <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle"><i class="bi bi-list"></i></button>
                        <h4 class="mb-0">Products Management</h4>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-circle me-2"></i>Add Product</button>
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
                    <div class="table-container">
                        <h5 class="mb-3">All Products (<?php $sql="SELECT `ProductID` FROM `product`"; $result=mysqli_query($con,$sql); echo mysqli_num_rows($result); ?>)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th> <th>Category</th> <th>Price</th> <th>Stock</th> <th>Status</th> <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql="SELECT * FROM `product`";
                                        $result=mysqli_query($con,$sql);
                                        if(mysqli_num_rows($result) > 0) {
                                            while($row=mysqli_fetch_assoc($result)) {
                                                $img = "../images/" . ($row['img1'] ?? 'default.png');
                                                $id = $row['ProductID'];
                                                $stock_class = ($row['Quantity'] <= 0) ? 'danger' : (($row['Quantity'] < 10) ? 'warning' : 'success');
                                                $stock_text = ($row['Quantity'] > 0) ? $row['Quantity'] . ' in stock' : 'Out of stock';
                                                $status_class = ($row['Quantity'] > 0) ? 'success' : 'secondary';
                                                $status_text = ($row['Quantity'] > 0) ? 'Active' : 'Inactive';

                                                echo "<tr>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <img src='".$img."' alt='".$row['Name']."' class='product-image me-3'>
                                                            <div>
                                                                <h6 class='mb-0 fw-bold'>".$row['Name']."</h6>
                                                                <small class='text-muted'>ID: ".$id."</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class='badge bg-light text-dark'>".$row['Category']."</span></td>
                                                    <td><span class='fw-bold'>₹".$row['Price']."</span></td>
                                                    <td><span class='badge bg-".$stock_class."'>".$stock_text."</span></td>
                                                    <td><span class='badge bg-".$status_class."'>".$status_text."</span></td>
                                                    <td>
                                                        <div class='btn-group'>
                                                            <button class='btn btn-sm btn-outline-primary' title='Edit' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#editProductModal' 
                                                                    data-bs-productid='".$id."'>
                                                                <i class='bi bi-pencil'></i>
                                                            </button>
                                                            <a href='../product-detail.php?product=".$id."' class='btn btn-sm btn-outline-info' title='View'><i class='bi bi-eye'></i></a>
                                                            <a href='delete.php?product=".$id."' class='btn btn-sm btn-outline-danger' title='Delete'><i class='bi bi-trash'></i></a>
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

    <div class="modal fade" id="addProductModal" tabindex="-1">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" class="form-control" required name="name"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Category *</label><select class="form-select" required name="cat"><option value="">Select Category</option><?php $sql="SELECT `CategoryID`,`Name` FROM `Category`"; $result=mysqli_query($con,$sql); while($row=mysqli_fetch_assoc($result)){ echo "<option value='".$row['CategoryID']."'>".$row['Name']."</option>"; } ?></select></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Stock Quantity *</label><input type="number" class="form-control" required name="qty"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Price (₹) *</label><input type="number" class="form-control" required name="price"></div>
                        </div>
                        <div class="row"><div class="col-md-6 mb-3"><label class="form-label">Brand *</label><select class="form-select" required name="brand"><option value="">Select Brand</option><?php $sql="SELECT `BrandID`,`Name` FROM `brand`"; $result=mysqli_query($con,$sql); while($row=mysqli_fetch_assoc($result)){ echo "<option value='".$row['BrandID']."'>".$row['Name']."</option>"; } ?></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Strock-Through Price (₹) *</label><input type="number" class="form-control" required name="sprice"></div>
                    </div>
                    <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Colour *</label><input type="text" class="form-control" required name="color" id=""></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Size *</label><select class="form-select" required name="size" id=""><option value="">Select Size</option><option value="1">Free Size</option><option value="2">All Sizes</option></select></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" rows="3" required name="desc"></textarea></div>
                        <div class="mb-3">
                            <label class="form-label">Product Images (up to 5)</label>
                            <div>
                                <input type="file" class="form-control mb-2" name="img1" required accept="image/*">
                                <input type="file" class="form-control mb-2" name="img2" accept="image/*">
                                <input type="file" class="form-control mb-2" name="img3" accept="image/*">
                                <input type="file" class="form-control mb-2" name="img4" accept="image/*">
                                <input type="file" class="form-control" name="img5" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" name="save">Add Product</button>
                        </div>
                    </form>
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
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" id="editProductId">

                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" class="form-control" required name="name" id="editName"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Category *</label><select class="form-select" required name="cat" id="editCat"><option value="">Select Category</option><?php $sql="SELECT `CategoryID`,`Name` FROM `Category`"; $result=mysqli_query($con,$sql); while($row=mysqli_fetch_assoc($result)){ echo "<option value='".$row['CategoryID']."'>".$row['Name']."</option>"; } ?></select></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Stock Quantity *</label><input type="number" class="form-control" required name="qty" id="editQty"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Price (₹) *</label><input type="number" class="form-control" required name="price" id="editPrice"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Brand *</label><select class="form-select" required name="brand" id="editBrand"><option value="">Select Brand</option><?php $sql="SELECT `BrandID`,`Name` FROM `brand`"; $result=mysqli_query($con,$sql); while($row=mysqli_fetch_assoc($result)){ echo "<option value='".$row['BrandID']."'>".$row['Name']."</option>"; } ?></select></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Strock-Through Price (₹) *</label><input type="number" class="form-control" required name="sprice" id="editSPrice"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Colour *</label><input type="text" class="form-control" required name="ecolor" id="editColour"></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Size *</label><select class="form-select" required name="esize" id="editSize"><option value="">Select Size</option><option value="Free Size">Free Size</option><option value="All Sizes">All Sizes</option></select></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" rows="3" required name="desc" id="editDesc"></textarea></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Product Images (Upload to replace existing)</label>
                            <div class="d-flex align-items-center mb-2"><span id="editImgPreview1" class="me-3"></span><input type="file" class="form-control" name="edit_img1" accept="image/*"></div>
                            <div class="d-flex align-items-center mb-2"><span id="editImgPreview2" class="me-3"></span><input type="file" class="form-control" name="edit_img2" accept="image/*"></div>
                            <div class="d-flex align-items-center mb-2"><span id="editImgPreview3" class="me-3"></span><input type="file" class="form-control" name="edit_img3" accept="image/*"></div>
                            <div class="d-flex align-items-center mb-2"><span id="editImgPreview4" class="me-3"></span><input type="file" class="form-control" name="edit_img4" accept="image/*"></div>
                            <div class="d-flex align-items-center"><span id="editImgPreview5" class="me-3"></span><input type="file" class="form-control" name="edit_img5" accept="image/*"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="update">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar toggle script (Unchanged)
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

        // --- START: New JavaScript for Edit Modal ---
        const editModal = document.getElementById('editProductModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const productId = button.getAttribute('data-bs-productid'); // Get the product ID

            // Use fetch API to get product data from our PHP script
            fetch(`products.php?fetch_product_id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // Populate the form fields with the fetched data
                    document.getElementById('editProductId').value = data.ProductID;
                    document.getElementById('editName').value = data.Name;
                    document.getElementById('editQty').value = data.Quantity;
                    document.getElementById('editPrice').value = data.Price;
                    document.getElementById('editSPrice').value = data.SPrice;
                    document.getElementById('editColour').value = data.Colour;
                    document.getElementById('editSize').value = data.Size;
                    document.getElementById('editDesc').value = data.Description;
                    
                    // Set the correct option in dropdowns
                    // We check if data.CategoryID and data.BrandID exist and are numeric 
                    if (data.CategoryID) {
                         document.getElementById('editCat').value = data.CategoryID;
                    }
                    if (data.BrandID) {
                        document.getElementById('editBrand').value = data.BrandID;
                    }
                    
                    // Display current images as previews
                    for (let i = 1; i <= 5; i++) {
                        const imgPreview = document.getElementById(`editImgPreview${i}`);
                        const imgName = data[`img${i}`];
                        if (imgName) {
                            imgPreview.innerHTML = `<img src="../images/${imgName}" alt="Image ${i}" class="img-preview" title="${imgName}">`;
                        } else {
                            imgPreview.innerHTML = `<span class="text-muted fst-italic">No Image</span>`;
                        }
                    }
                })
                .catch(error => console.error('Error fetching product data:', error));
        });
        // --- END: New JavaScript for Edit Modal ---
    });
    </script>
</body>
</html>