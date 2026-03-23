<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['pid'] ?? null;

if ($product_id) {
    $sql = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($conn, $sql);
}

header("Location: basket.php");
exit();
?>