<?PHP
	session_start();
	include '../connection.php';
	if (!isset($_SESSION['adminid'])) {
    	header("Location: login.php");
    	exit();
    }
    if(isset($_GET['catid']))
    {
    	$id=$_GET['catid'];
    	$sql="DELETE FROM `Category` WHERE `CategoryID`='$id'";
    	$result=mysqli_query($con,$sql);
    	header("Location: categories.php");
    	exit();
    }
    if(isset($_GET['product']))
    {
        $id=$_GET['product'];
        $sql="DELETE FROM `product` WHERE `ProductID`='$id'";
        $result=mysqli_query($con,$sql);
        header("Location: products.php");
        exit();
    }
?>