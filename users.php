<?php session_start(); include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
if(isset($_GET['del'])){
    $id=$_GET['del']; if($id != $_SESSION['user_id']) mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: users.php");
}
$list = mysqli_query($conn, "SELECT id, username FROM users");
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <a href="index.php" class="btn btn-secondary mb-3">Back</a>
        <div class="card p-3 shadow-sm border-0">
            <h5>Registered Users</h5>
            <table class="table">
                <thead><tr><th>Username</th><th>Action</th></tr></thead>
                <tbody>
                    <?php while($u = mysqli_fetch_assoc($list)): ?>
                    <tr>
                        <td><?php echo $u['username']; ?></td>
                        <td><?php if($u['id']!=$_SESSION['user_id']){ ?><a href="users.php?del=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger">Delete</a><?php } ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>