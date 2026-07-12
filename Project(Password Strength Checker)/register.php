<?php
require 'config.php';

$errors = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Check for existing username/email
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'Username or email is already registered.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        // Never store the raw password — hash it.
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $email, $hashed);

        if ($stmt->execute()) {
            $_SESSION['flash_success'] = 'Account created! You can log in now.';
            redirect('login.php');
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Password Vault</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="auth-card">
        <div class="brand-lockup">
            <div class="glyph">PV</div>
            <div>
                <div class="fw-bold">Password Vault</div>
                <div class="text-dim" style="font-size:0.8rem;">Create your account</div>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       value="<?= htmlspecialchars($username) ?>" placeholder="e.g. rahul_23" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($email) ?>" placeholder="you@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="regPassword" name="password" class="form-control"
                           placeholder="Create a strong password" required>
                    <button class="btn btn-outline-accent" type="button" data-toggle-password="regPassword">Show</button>
                </div>
                <div class="strength-meter" id="regMeter">
                    <div class="bar"></div><div class="bar"></div><div class="bar"></div>
                    <div class="bar"></div><div class="bar"></div>
                </div>
                <div class="strength-label" id="regLabel">Enter a password</div>
                <ul class="criteria-list" id="regCriteria"></ul>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control"
                       placeholder="Re-enter your password" required>
            </div>

            <button type="submit" class="btn btn-accent w-100 py-2">Create Account</button>
        </form>

        <p class="text-center text-dim mt-4 mb-0">
            Already have an account? <a href="login.php" class="link-accent">Log in</a>
        </p>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
    initStrengthChecker({
        inputId: 'regPassword',
        meterId: 'regMeter',
        labelId: 'regLabel',
        listId: 'regCriteria'
    });
</script>
</body>
</html>