<?php
include 'connect.php';
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signup/login.php");
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM restaurants WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: home.php"); 
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "ID not set.";
}
?>
