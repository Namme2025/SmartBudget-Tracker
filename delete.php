<?php 
session_start(); include 'db.php';
if(isset($_GET['id'])){
    $id = $_GET['id']; $uid = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM transactions WHERE id=$id AND user_id=$uid");
    header("Location: index.php");
} ?>