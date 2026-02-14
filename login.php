<?php
session_start(); include 'db.php';
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $data = mysqli_fetch_assoc($res);
    if ($data && password_verify($pass, $data['password'])) {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        header("Location: index.php");
    } else { $error = "Invalid Username or Password!"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Budget Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.dark-mode { background-color: #121212; color: #e0e0e0; }
        .dark-mode .card { background-color: #1e1e1e; color: #ffffff; border: 1px solid #333; }
        .dark-mode .form-control { background-color: #2b2b2b; color: #fff; border: 1px solid #444; }
        .dark-mode .alert { background-color: #442222; color: #ff9999; border: 0; }
    </style>
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="card mx-auto p-4 shadow-lg border-0" style="width:350px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger p-2 small'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="small fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="mb-4">
                <label class="small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button name="login" class="btn btn-primary w-100 shadow-sm">Sign In</button>
            <div class="text-center mt-3 small">
                Walang account? <a href="register.php" class="text-decoration-none">Register here</a>
            </div>
        </form>
    </div>

    <script>
        // Apply dark mode if enabled in local storage
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>