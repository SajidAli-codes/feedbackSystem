<?php
require_once 'config.php';

if (isAdminLoggedIn()) {
    redirect('admin_dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM Admin WHERE Email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    
    // For demo purposes, using plain password check
    // In production, use password_verify()
    if ($admin && $password == 'admin123') { // Demo check - change this!
        $_SESSION['admin_id'] = $admin['AdminID'];
        $_SESSION['admin_name'] = $admin['AdminName'];
        redirect('admin_dashboard.php');
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Login</button>
            </form>
            <p style="margin-top: 20px; text-align: center;">Demo credentials: admin@feedback.com / admin123</p>
        </div>
    </div>
</body>
</html>