<?php
session_start();
include 'db.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transactions WHERE id=$id"));
if(isset($_POST['up'])) {
    $t = $_POST['t']; $a = $_POST['a']; $ty = $_POST['ty'];
    mysqli_query($conn, "UPDATE transactions SET title='$t', amount='$a', type='$ty' WHERE id=$id");
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-5 bg-light">
    <div class="card p-4 mx-auto shadow" style="max-width:400px;">
        <form method="POST">
            <input type="text" name="t" class="form-control mb-2" value="<?php echo $data['title']; ?>">
            <input type="number" name="a" class="form-control mb-2" value="<?php echo $data['amount']; ?>">
            <select name="ty" class="form-select mb-3">
                <option value="income" <?php echo ($data['type']=='income')?'selected':''; ?>>Income</option>
                <option value="expense" <?php echo ($data['type']=='expense')?'selected':''; ?>>Expense</option>
            </select>
            <button name="up" class="btn btn-success w-100">Update</button>
        </form>
    </div>
</body>
</html>