<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "expense_tracker";
$port = 3307; // Heto ang binago natin

// Nilagay natin ang $port sa dulo ng connection string
$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>