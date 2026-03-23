
<?php
include 'connect.php'; 
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];

    $logo = $_FILES['logo']['name'];
    $temp_name = $_FILES['logo']['tmp_name'];
   $folder = "../pics/restaurantlogos/" . $logo;

    if (move_uploaded_file($temp_name, $folder)) {
        $sql = "INSERT INTO restaurants (name, location, phone, logo)
                VALUES ('$name', '$location', '$phone', '$logo')";

        if (mysqli_query($conn, $sql)) {
            header("Location: home.php"); 
            exit;
        } else {
            echo "DB Error: " . mysqli_error($conn);
        }
    } else {
        echo "Logo upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add restaurant</title>

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
    </style>
</head>
<body>

<div class="signup-container">
    <div class="signup-box">
        
       <img src="../pics/restaurantlogos/<?php echo isset($logo) && $logo !== 'logo.png' ? $logo : 'logo.png'; ?>"
     alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">

    <button class="back-button" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> 
    </button>
        <form id="signupForm" method="POST" enctype="multipart/form-data">
            <div class="form-outline mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-outline mb-3">
                <input type="text"  name="location" class="form-control" placeholder="Location" required>
            </div>
            <div class="form-outline mb-3">
                <input type="number" name="phone" class="form-control" placeholder="Phone No" required>
            </div>
            <div class="form-outline mb-3">
                <input type="file" name="logo" class="form-control" placeholder="Add" required>
            </div>

            <button type="submit" class="btn btn-custom btn-signup btn-block">Add</button>
            <button type="button" class="btn btn-custom btn-clear btn-block mt-2" onclick="clearForm()">Clear</button>

            <div class="text-center mt-3">
               
            </div>
        </form>
    </div>
</div>

<script>
    function clearForm() {
        document.getElementById("signupForm").reset();
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>

</body>
</html>
