<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

if (!isset($_GET['rid'])) {
    echo "Restaurant ID missing!";
    exit();
}

$restaurant_id = $_GET['rid'];

$sql_restaurant = "SELECT name FROM restaurants WHERE id = $restaurant_id";
$res_restaurant = mysqli_query($conn, $sql_restaurant);
$row_restaurant = mysqli_fetch_assoc($res_restaurant);
$restaurant_name = $row_restaurant['name'] ?? 'Unknown';

$sql_products = "SELECT * FROM products WHERE restaurant_id = $restaurant_id";
$res_products = mysqli_query($conn, $sql_products);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $restaurant_name ?> - Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
     <link rel="stylesheet" href="../style.css">
    <style>
    .card {
        height: 100%; 
    }
    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .card-text {
        flex-grow: 1;
    }
</style>
</head>
<body>
<?php include 'navbarcustomer.php'; ?>

<h2 class="text-center mb-4"><?= $restaurant_name ?> - Menu</h2>

<div class="container">
    <div class="row">
            <?php while ($product = mysqli_fetch_assoc($res_products)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                         <img src="../pics/restaurantlogos/<?= $product['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Product">

                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name'] ?></h5>
                            <p class="card-text"><?= $product['description'] ?></p>
                            <p class="card-text"><strong>Price:</strong> ₺<?= $product['price'] ?></p>

                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width: 100px;">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
    </div>
</div>

<?php include 'cusfooter.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
