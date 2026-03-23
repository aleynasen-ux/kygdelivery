<?php
include 'connect.php';
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../signup/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Product ID missing.";
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$res_query = "SELECT id FROM restaurants WHERE user_id = $user_id";
$res_result = mysqli_query($conn, $res_query);
$res_row = mysqli_fetch_assoc($res_result);
$restaurant_id = $res_row['id'];

$sql = "SELECT * FROM products WHERE id = $product_id AND restaurant_id = $restaurant_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Unauthorized or product not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (!empty($_FILES['product_photo']['name'])) {
        $image_name = $_FILES['product_photo']['name'];
        $image_tmp = $_FILES['product_photo']['tmp_name'];
        $image_path = "../pics/restaurantlogos/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $image_name = $product['image'];
    }

    $update_sql = "UPDATE products 
                   SET name = '$name', price = $price, description = '$description', image = '$image_name' 
                   WHERE id = $product_id AND restaurant_id = $restaurant_id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Product updated!'); window.location.href='manager.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #FFA726; }
        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-box {
            width: 350px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        .form-control { height: 40px; font-size: 14px; }
        .btn-custom {
            font-size: 16px;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 30px;
            border: none;
        }
        .btn-signup {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
        }
        .back-button {
            position: absolute;
            top: 0px;
            left: 10px;
            background: white;
            border: none;
            color: black;
            font-size: 18px;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        img.preview {
            width: 100px;
            border-radius: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <div class="signup-box">
        <h5 class="mb-3 fw-bold">Edit Product</h5>

        <button class="back-button" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> 
        </button>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-outline mb-3">
                <input type="text" name="product_name" class="form-control" value="<?= $product['name']; ?>" required>
            </div>
            <div class="form-outline mb-3">
                <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" step="0.01" required>
            </div>
            <div class="form-outline mb-3">
                <input type="text" name="description" class="form-control" value="<?= $product['description']; ?>" required>
            </div>
            <div class="form-outline mb-3">
                <input type="file" name="product_photo" class="form-control">
            </div>
            <img src="../pics/restaurantlogos/<?= $product['image']; ?>" alt="Current Image" class="preview">

            <button type="submit" class="btn btn-custom btn-signup btn-block mt-4">Update</button>
            <a href="manager.php" class="btn btn-custom btn-cancel btn-block mt-2">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
