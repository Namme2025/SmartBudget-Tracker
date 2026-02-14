<?php 
session_start(); include 'db.php';
if(isset($_POST['save'])){
    $t = mysqli_real_escape_string($conn, $_POST['t']);
    $a = $_POST['a']; $ty = $_POST['ty']; $cat = $_POST['cat'];
    $uid = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO transactions (user_id, title, amount, category, type) VALUES ('$uid', '$t', '$a', '$cat', '$ty')");
    header("Location: index.php");
} ?>