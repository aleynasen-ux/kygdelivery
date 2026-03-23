<?php
include 'connect.php';
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $role = $_POST['role'];

    $restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null;

    $insert_sql = "INSERT INTO users (name, username, password, role, address) 
                   VALUES ('$name', '$username', '$password', '$role', '$address')";

    if (mysqli_query($conn, $insert_sql)) {
        $user_id = mysqli_insert_id($conn);

        if ($role === 'manager' && !empty($restaurant_id)) {
            $update_sql = "UPDATE restaurants SET user_id = $user_id WHERE id = $restaurant_id";
            mysqli_query($conn, $update_sql);
        }

        echo "<script>alert('User added successfully'); 
        window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add manager</title>

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
        
        .social-buttons a {
            font-size: 20px;
            color: #555;
            margin: 0 8px;
        }

        .social-buttons a:hover {
            color: #E65100;
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
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }

        .btn-clear {
            background: transparent;
            color: #dc3545;
            border: 2px solid #dc3545;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }
        .back-button {
            position: absolute;
            top: 10px;
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
    </style>
</head>
<body>
<div class="signup-container">
    <div class="signup-box">
        <img src="../logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">
       
    <button class="back-button" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> 
    </button>
       <form id="signupForm" method="POST" action="">

            <div class="form-outline mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-outline mb-3">
    <input type="text" name="address" class="form-control" placeholder="Address">
</div>

            <div class="form-outline mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-outline mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class=" mb-3">
                <select class="form-control" name="role"id="roleSelect"  onchange="toggleRestaurantList()" required>

                    <option value="" disabled selected>Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                </select>
            </div>
<div class="mb-3" id="restaurantList" style="display: none;">
    <select class="form-control" name="restaurant_id" id="restaurantSelect" disabled>

        <option value="" disabled selected>List of restaurants</option>
        <?php
        $res_sql = "SELECT * FROM restaurants WHERE user_id IS NULL";
        $res_result = mysqli_query($conn, $res_sql);
        if ($res_result) {
            while ($row = mysqli_fetch_assoc($res_result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        ?>
    </select>
</div>

            <button type="submit" name="submit" class="btn btn-custom btn-signup btn-block">Add</button>
            <button type="button" class="btn btn-custom btn-clear btn-block mt-2" onclick="clearForm()">Clear</button>

            <div class="text-center mt-3">
               
            </div>
        </form>
    </div>
</div>

<script>
function toggleRestaurantList() {
    const role = document.getElementById("roleSelect").value;
    const restaurantList = document.getElementById("restaurantList");
    const restaurantSelect = document.getElementById("restaurantSelect");

    if (role === "manager") {
        restaurantList.style.display = "block";
        restaurantSelect.disabled = false;
    } else {
        restaurantList.style.display = "none";
        restaurantSelect.disabled = true;
    }
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>