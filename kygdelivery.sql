<?php
include 'connect.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $role = 'customer';
    

    if ($password !== $confirm) {
        $msg = "Passwords do not match.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

      $sql = "INSERT INTO users (name, username, password, role, address)
        VALUES ('$name', '$username', '$hashed', '$role', '$address')";


        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit;
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>

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
    </style>
</head>
<body>

<div class="signup-container">
    <div class="signup-box">
        <img src="../logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">

        <div class="social-buttons mb-3">
            <a href="https://www.facebook.com/login"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.google.co.uk/"><i class="fab fa-google"></i></a>
            <a href="https://twitter.com/i/flow/login"><i class="fab fa-twitter"></i></a>
        </div>

        <form id="signupForm" method="POST">
            <div class="form-outline mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-outline mb-3">
    <input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

            <div class="form-outline mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email or phone number" required>
            </div>
            <div class="form-outline mb-3">
    <input type="text" name="address" class="form-control" placeholder="Address" required>
</div>
            <div class="form-outline mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-outline mb-3">
                <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn btn-custom btn-signup btn-block">Sign Up</button>
            <button type="button" class="btn btn-custom btn-clear btn-block mt-2" onclick="clearForm()">Clear</button>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Log In</a></p>
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
