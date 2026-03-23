<?php
include 'connect.php';
require_once 'session.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../signup/login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("No user session.");
}

$res_sql = "SELECT id, name FROM restaurants WHERE user_id = $user_id";
$res_result = mysqli_query($conn, $res_sql);

if (!$res_result || mysqli_num_rows($res_result) === 0) {
    die("No restaurant found for this manager.");
}

$res_row = mysqli_fetch_assoc($res_result);
$restaurant_id = $res_row['id'];
$restaurant_name = $res_row['name'];

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $check_sql = "SELECT * FROM products WHERE id = $delete_id AND restaurant_id = $restaurant_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        mysqli_query($conn, "DELETE FROM products WHERE id = $delete_id");
        echo "<script>alert('Product deleted successfully.'); window.location.href='manager.php';</script>";
        exit;
    } else {
        echo "<script>alert('Unauthorized deletion.');</script>";
    }
}

$product_sql = "SELECT * FROM products WHERE restaurant_id = $restaurant_id";
$product_result = mysqli_query($conn, $product_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include 'navbarmanager.php'; ?>  
<h3 class="text-center mt-1">
    Welcome to <?php echo $restaurant_name; ?>
</h3>
<div class="logout-container">
<a href="addproduct.php" class="btn btn-light add-product-btn" onclick="window.location='addproduct.php'; return false;">Add Product</a>
</div>
<div class="container mt-n1">
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="../pics/restaurantlogos/<?php echo $product['image']; ?>" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text">Price: ₺<?php echo $product['price']; ?></p>
                        <p class="card-text">Description: <?php echo $product['description']; ?></p>
                         <a href="editproduct.php?id=<?php echo $product['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="manager.php?delete_id=<?= $product['id']; ?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'navfooter.php'; ?>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>