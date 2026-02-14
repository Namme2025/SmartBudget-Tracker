<?php 
session_start();
include 'db.php';
$uid = $_SESSION['user_id'];
$m = $_GET['month'] ?? date('m');
$y = $_GET['year'] ?? date('Y');

header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename=budget_report_'.$m.'_'.$y.'.csv');

$out = fopen('php://output', 'w'); 
fputcsv($out, array('ID', 'Title', 'Amount', 'Type', 'Category', 'Date'));

$rows = mysqli_query($conn, "SELECT id, title, amount, type, category, created_at FROM transactions WHERE user_id = '$uid' AND MONTH(created_at) = '$m' AND YEAR(created_at) = '$y'");
while($r = mysqli_fetch_assoc($rows)) fputcsv($out, $r);
fclose($out); 
?>