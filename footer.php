<?php
include 'connect.php';
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}
$id = $_GET['id'];
$sql = "SELECT * FROM restaurants WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$oldLogo = $row['logo'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];

    if ($_FILES['logo']['name']) {
        $logo = $_FILES['logo']['name'];
        $temp_name = $_FILES['logo']['tmp_name'];
        $folder = "../pics/restaurantlogos/" . $logo;
        move_uploaded_file($temp_name, $folder);
    } else {
        $logo = $oldLogo;
    }

    $update = "UPDATE restaurants SET 
                name = '$name', 
                location = '$location', 
                phone = '$phone', 
                logo = '$logo' 
                WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: home.php");
        exit;
    } else {
        echo "Update Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
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

<div class="signup-container">
    <div class="signup-box">
        <h5 class="mb-3 fw-bold">Edit Restaurant</h5>

        <button class="back-button" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> 
        </button>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-outline mb-3">
                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="form-outline mb-3">
                <input type="text" name="location" class="form-control" value="<?php echo $row['location']; ?>" required>
            </div>
            <div class="form-outline mb-3">
                <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
            </div>
            <div class="form-outline mb-3">
                <input type="file" name="logo" class="form-control">
            </div>
            <img src="../pics/restaurantlogos/<?php echo $row['logo']; ?>" alt="Current Logo" class="preview">

            <button type="submit" class="btn btn-custom btn-signup btn-block mt-4">Update</button>
            <a href="home.php" class="btn btn-custom btn-cancel btn-block mt-2">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
