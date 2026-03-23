<?php
include 'connect.php';
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    $update_sql = "UPDATE users SET 
        name = '$name', 
        username = '$username', 
        password = '$password', 
        role = '$role', 
        address = '$address' 
        WHERE id = $user_id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('User updated successfully'); window.location.href='users.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #FFA726;
        }
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
        .form-control {
            height: 40px;
            font-size: 14px;
        }
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
        .back-button:hover {
            background: #ddd;
        }
        img.preview {
            width: 100px;
            border-radius: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center bg-dark text-white">Edit User</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="<?= $user['name']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= $user['username']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password (Leave blank to keep old password)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" name="address" value="<?= $user['address']; ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="manager" <?= $user['role'] == 'manager' ? 'selected' : '' ?>>Manager</option>
                            <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="update" class="btn btn-custom btn-signup btn-block">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
