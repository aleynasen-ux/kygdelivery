<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_cart = "SELECT * FROM basket WHERE user_id = $user_id";
$res_cart = mysqli_query($conn, $sql_cart);

if (mysqli_num_rows($res_cart) === 0) {
    header("Location: basket.php");
    exit();
}

while ($row = mysqli_fetch_assoc($res_cart)) {
    $cart_id = $row['id'];
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $total = $row['total'];

    $sql_rest = "SELECT restaurant_id FROM products WHERE id = $product_id";
    $res_rest = mysqli_query($conn, $sql_rest);
    $product = mysqli_fetch_assoc($res_rest);
    $restaurant_id = $product['restaurant_id'];
    
    $sql_order = "INSERT INTO orders (cart_id, user_id, product_id, restaurant_id, quantity, total, status)
              VALUES ($cart_id, $user_id, $product_id, $restaurant_id, $quantity, $total, 'Pending')";

    mysqli_query($conn, $sql_order);
}

$clear_sql = "DELETE FROM basket WHERE user_id = $user_id";
mysqli_query($conn, $clear_sql);

header("Location: orderscustomer.php");
exit();
?>
