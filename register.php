<?php
include 'db.php';
if (isset($_POST['reg'])) {
    $u = mysqli_real_escape_string($conn, $_POST['u']);
    $p = password_hash($_POST['p'], PASSWORD_DEFAULT);
    
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$u'");
    if(mysqli_num_rows($check) > 0) {
        $e = "Username already taken!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$u', '$p')");
        header("Location: login.php?msg=success");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Budget Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.dark-mode { background-color: #121212; color: #e0e0e0; }
        .dark-mode .card { background-color: #1e1e1e; color: #ffffff; border: 1px solid #333; }
        .dark-mode .form-control { background-color: #2b2b2b; color: #fff; border: 1px solid #444; }
    </style>
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="card mx-auto p-4 shadow-lg border-0" style="width:350px;">
        <h3 class="text-center mb-4">Create Account</h3>
        <?php if(isset($e)) echo "<div class='alert alert-danger p-2 small'>$e</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="small fw-bold">New Username</label>
                <input type="text" name="u" class="form-control" placeholder="Choose username" required>
            </div>
            <div class="mb-4">
                <label class="small fw-bold">New Password</label>
                <input type="password" name="p" class="form-control" placeholder="Create password" required>
            </div>
            <button name="reg" class="btn btn-success w-100 shadow-sm">Register</button>
            <div class="text-center mt-3 small">
                Meron na? <a href="login.php" class="text-decoration-none">Back to Login</a>
            </div>
        </form>
    </div>

    <script>
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>