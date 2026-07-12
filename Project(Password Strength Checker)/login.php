<?php
require 'config.php';

if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$errors = [];
$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please enter both username/email and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('dashboard.php');
        } else {
            $errors[] = 'Invalid username/email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Password Vault</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="auth-card">
        <div class="brand-lockup">
            <div class="glyph">PV</div>
            <div>
                <div class="fw-bold">Password Vault</div>
                <div class="text-dim" style="font-size:0.8rem;">Welcome back</div>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-vault"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="mb-3">
                <label class="form-label">Username or Email</label>
                <input type="text" name="username" class="form-control" placeholder="you@example.com" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Your password" required>
                    <button class="btn btn-outline-accent" type="button" data-toggle-password="loginPassword">Show</button>
                </div>
            </div>

            <button type="submit" class="btn btn-accent w-100 py-2">Log In</button>
        </form>

        <p class="text-center text-dim mt-4 mb-0">
            Don't have an account? <a href="register.php" class="link-accent">Register</a>
        </p>
    </div>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>