<?php
include '../MANAGER/connect.php';
session_start();

$user_id = $_SESSION['user_id']; 

$sql = "SELECT 
            restaurants.name AS restaurant_name,
            products.name AS product_name,
            products.price,
            orders.quantity,
            orders.total,
            orders.status
        FROM orders
        JOIN products ON orders.product_id = products.id
        JOIN restaurants ON orders.restaurant_id = restaurants.id
        WHERE orders.user_id = $user_id
        ORDER BY orders.id DESC";

$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include 'navbarcustomer.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">My Orders</h2>

    <?php if (mysqli_num_rows($res) === 0): ?>
        <div class="alert alert-warning">You haven't placed any orders yet.</div>
    <?php else: ?>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Restaurant</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td>₺<?= number_format($order['price'], 2) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td>₺<?= number_format($order['total'], 2) ?></td>
                        <?php
                        $status = strtolower(trim($order['status']));
                        $badgeColor = match ($status) {
                              'pending'   => 'warning',
                              'accepted'  => 'primary',
                              'shipping'  => 'info',
                              'delivered' => 'success',
                              default     => 'secondary',
                             };
                          ?>
                          <td>
                            <span class="badge bg-<?= $badgeColor ?>">
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'cusfooter.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
