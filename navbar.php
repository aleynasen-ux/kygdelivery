<?php
include 'connect.php'; 
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}
$sql = "SELECT restaurants.*, users.name AS manager_name 
        FROM restaurants 
        LEFT JOIN users ON restaurants.user_id = users.id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">

    <style>
        .card {
    height: 420px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>

</head>
<body>

<?php include 'navbar.php'; ?>  
<div class="logout-container">
    <a href="addrestaurant.php" class="btn btn-light add-restaurant-btn">
        Add Restaurant
    </a>
</div>

<div class="container">
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-3 mb-4">
                <div class="card ">
                    <img src="../pics/restaurantlogos/<?php echo $row['logo']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Logo">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text">
                         Location:   <?php echo $row['location']; ?><br>
                          Phone No:  <?php echo $row['phone']; ?>
                        </p>
                        <p class="card-text text-center">
     Manager: <?php echo $row['manager_name'] ? $row['manager_name'] : 'No manager'; ?>
</p>

                        <a href="editrestaurant.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Edit</a>

                        <a href="deleterestaurant.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
