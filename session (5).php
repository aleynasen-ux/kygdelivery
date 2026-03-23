
<?php
include 'connect.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../signup/login.php");
    exit();
}

$sql = "SELECT * FROM restaurants";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>


<?php include 'navbarcustomer.php'; ?>
 <h3 class="text-center mt-1">Available Restaurants</h3>

    <div class="container">
        <div class="row">
            <?php while($res = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
               <img src="../pics/restaurantlogos/<?php echo $res['logo']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Logo">

                        <div class="card-body">
                            <h5 class="card-title"><?= $res['name'] ?></h5>
                            <p class="card-text"><?= $res['location'] ?></p>
                            <a href="restaurant_products.php?rid=<?= $res['id'] ?>" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
   </div>
   </div>
    </div>
<?php include 'cusfooter.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

