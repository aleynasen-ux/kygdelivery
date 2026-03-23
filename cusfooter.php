<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    $sql_price = "SELECT price FROM products WHERE id = $product_id";
    $res_price = mysqli_query($conn, $sql_price);
    $product = mysqli_fetch_assoc($res_price);
    $price = $product['price'];
    $total = $price * $quantity;

    $check_sql = "SELECT * FROM basket WHERE user_id = $user_id AND product_id = $product_id";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        $existing = mysqli_fetch_assoc($check_res);
        $new_qty = $existing['quantity'] + $quantity;
        $new_total = $price * $new_qty;

        $update_sql = "UPDATE basket SET quantity = $new_qty, total = $new_total 
                       WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $update_sql);
    } else {
        $insert_sql = "INSERT INTO basket (user_id, product_id, quantity, total)
                       VALUES ($user_id, $product_id, $quantity, $total)";
        mysqli_query($conn, $insert_sql);
    }

    header("Location: basket.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
