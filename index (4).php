<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT basket.*, products.name AS product_name, products.price 
        FROM basket
        JOIN products ON basket.product_id = products.id 
        WHERE basket.user_id = $user_id";

$result = mysqli_query($conn, $sql);

$grand_total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Basket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
     <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include 'navbarcustomer.php'; ?>

<h2 class="text-center mb-4">My Basket</h2>

<div class="container">
    <?php if (mysqli_num_rows($result) === 0): ?>
        <div class="alert alert-warning">Your basket is empty.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $grand_total += $row['total'];
                ?>
                <tr>
                    <td><?= $row['product_name'] ?></td>
                    <td>₺<?= $row['price'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>₺<?= $row['total'] ?></td>
                    <td>
                        <a href="remove_from_cart.php?pid=<?= $row['product_id'] ?>" class="btn btn-danger btn-sm">X</a>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total: ₺<?= $grand_total ?></h4>
            <a href="place_order.php" class="btn btn-success">Place Order</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'cusfooter.php'; ?>
</body>
</html>
