
<?php
include 'connect.php'; 
require_once 'session.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../ADMIN/home.php");
            } elseif ($user['role'] === 'manager') {
                header("Location: ../MANAGER/manager.php");
            } else {
                header("Location: ../CUSTOMER/product.php");
            }
            exit();
        } else {
            echo "<script>alert('Wrong password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color: #FFA726;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-box {
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
            transition: all 0.3s ease-in-out;
            border: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
        }

        .btn-login {
            background: linear-gradient(135deg, #0056b3, #003d80);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.6);
        }

        .btn-signup {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }

        .btn-signup :hover{
            background: linear-gradient(135deg, #1e7e34, #155d24);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.6);
        }

        .btn-clear {
            background: transparent;
            color: #dc3545;
            border: 2px solid #dc3545;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        .btn-clear :hover{
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <img src="../logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">

        <div class="social-buttons mb-3">
            <a href="https://www.facebook.com/login"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.google.co.uk/"><i class="fab fa-google"></i></a>
            <a href="https://twitter.com/i/flow/login"><i class="fab fa-twitter"></i></a>
        </div>

        <form id="loginForm" method="POST" action="">

            <div class="form-outline mb-3">
               <input type="text" name="username" class="form-control" placeholder="Username" required>


            </div>
            <div class="form-outline mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>

            </div>

            <button type="submit" class="btn btn-custom btn-login btn-block">Log In</button>

            <button type="button" class="btn btn-custom btn-clear btn-block mt-2" onclick="clearForm()">Clear</button>

            <div class="text-center mt-3">
                <p>Register now: <a href="signup.php">Register</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    function clearForm() {
        document.getElementById("loginForm").reset();
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>

</body>
</html>
