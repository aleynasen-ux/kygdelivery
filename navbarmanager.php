<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../signup/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];

$sql_rest = "SELECT id FROM restaurants WHERE user_id = $manager_id";
$res_rest = mysqli_query($conn, $sql_rest);
$row_rest = mysqli_fetch_assoc($res_rest);
$restaurant_id = $row_rest['id'] ?? 0;

$sql = "SELECT orders.*, users.name AS customer_name, products.name AS product_name, products.price 
        FROM orders
        JOIN users ON orders.user_id = users.id
        JOIN products ON orders.product_id = products.id
        WHERE orders.status = 'Delivered' AND orders.restaurant_id = $restaurant_id
        ORDER BY orders.id DESC";

$res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deliveries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include 'navbarmanager.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Delivered Orders</h2>

    <?php if (mysqli_num_rows($res) === 0): ?>
        <div class="alert alert-warning">No delivered orders found.</div>
    <?php else: ?>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['customer_name'] ?></td>
                        <td><?= $order['product_name'] ?></td>
                        <td>₺<?= number_format($order['price'], 2) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td>₺<?= number_format($order['total'], 2) ?></td>
                        <td><span class="badge bg-success"><?= $order['status'] ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'navfooter.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
