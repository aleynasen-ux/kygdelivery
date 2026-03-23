<?php
include 'connect.php'; 
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $del_sql = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $del_sql);
    header("Location: users.php");
    exit;
}

$sql = "SELECT users.*, restaurants.name AS restaurant_name 
        FROM users 
        LEFT JOIN restaurants ON users.id = restaurants.user_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    

</head>
<body>

<?php include 'navbar.php'; ?>  

<div class="container mt-1" style="max-width: 900px; margin: auto;">
    <div class="d-flex justify-content-end mb-3">
    <a href="adduser.php" class="btn btn-light add-restaurant-btn">
        Add User
    </a>
</div>

<table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Address</th>
                <th>Restaurant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['username']; ?></td>
            <td><?= ucfirst($row['role']); ?></td>
            <td><?= $row['address'] ?? '-'; ?></td>
            <td><?= $row['restaurant_name']; ?></td>
            <td>
               <a href="users.php?delete=<?= $row['id']; ?>" 
   class="btn btn-sm btn-danger"
   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
<a href="edituser.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
</td>
        </tr>
    <?php } ?>
</tbody>

    </table>
</div>
<?php include 'footer.php'; ?>  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
