<?php
include '../MANAGER/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $new_address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $sql = "SELECT * FROM users WHERE id = $user_id";
    $res = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($res);

    $updates = [];
    $msgParts = [];

    if ($new_username !== $user['username']) {
        $updates[] = "username = '$new_username'";
        $msgParts[] = "Username updated";
    }

    if ($new_address !== $user['address']) {
        $updates[] = "address = '$new_address'";
        $msgParts[] = "Address updated";
    }

    if (!empty($current_password) && !empty($new_password)) {
        if (password_verify($current_password, $user['password'])) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $updates[] = "password = '$hashed'";
            $msgParts[] = "Password updated";
        } else {
            $msgParts[] = "Current password incorrect";
        }
    }

    if (!empty($updates)) {
        $update_sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = $user_id";
        mysqli_query($conn, $update_sql);
    }

    $_SESSION['msg'] = implode(", ", $msgParts);
    header("Location: personinfo.php");
    exit();
}

$sql_user = "SELECT * FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background-color: #FFA726;
        }
        .info-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 5px; 
    padding-bottom: 40px;
}

        .info-box {
            width: 400px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            height: 40px;
            font-size: 14px;
        }

        .btn-custom {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 24px;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }

        .btn-update {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }
    </style>
</head>
<body>

<?php include 'navbarcustomer.php'; ?>

<div class="info-container">
    <div class="info-box">
        <h4 class="text-center">Personal Info</h4>

        <?php if ($msg): ?>
            <div class="alert alert-info text-center"><?= $msg ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" >
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
            </div>
            <h5 class="text-center">Change Password</h5>

            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-custom btn-update w-100">Update</button>
        </form>
    </div>
</div>
</div>
<?php include 'cusfooter.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>