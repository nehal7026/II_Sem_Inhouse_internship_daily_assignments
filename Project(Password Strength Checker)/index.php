<?php
require 'config.php';

$logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Vault — Check Your Password Strength</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-vault navbar-expand">
    <div class="container">
        <span class="navbar-brand">🔒 Password Vault</span>
        <div class="d-flex align-items-center gap-3">
            <?php if ($logged_in): ?>
                <span class="text-dim">Hi, <?= htmlspecialchars($username) ?></span>
                <a href="dashboard.php" class="btn btn-sm btn-outline-accent">Dashboard</a>
                <a href="logout.php" class="btn btn-sm btn-outline-accent">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-sm btn-outline-accent">Login</a>
                <a href="register.php" class="btn btn-sm btn-accent">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="hero">
        <h1 class="hero-title">How strong is your password?</h1>
        <p class="hero-subtitle">
            Type any password below to see an instant strength check —
            no account needed. Register for free to save your passwords
            (encrypted) and build up your own vault.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="panel-card">
                <h5 class="mb-3">Check a password's strength</h5>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="passwordInput" class="form-control" placeholder="Type a password to test" autocomplete="off">
                        <button class="btn btn-outline-accent" type="button" data-toggle-password="passwordInput">Show</button>
                    </div>
                    <div class="strength-meter" id="checkMeter">
                        <div class="bar"></div><div class="bar"></div><div class="bar"></div>
                        <div class="bar"></div><div class="bar"></div>
                    </div>
                    <div class="strength-label" id="checkLabel">Enter a password</div>
                    <ul class="criteria-list" id="checkCriteria"></ul>
                </div>

                <?php if (!$logged_in): ?>
                    <div class="cta-box">
                        <span class="text-dim">Want to save this password to your vault?</span>
                        <a href="register.php" class="link-accent fw-bold">Create a free account →</a>
                    </div>
                <?php else: ?>
                    <div class="cta-box">
                        <span class="text-dim">Ready to save it?</span>
                        <a href="dashboard.php" class="link-accent fw-bold">Go to your dashboard →</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel-card h-100">
                <h5 class="mb-3">What makes a password strong?</h5>
                <ul class="tips-list">
                    <li><strong>Length matters most.</strong> Aim for 12+ characters — longer beats cleverer.</li>
                    <li><strong>Mix character types.</strong> Uppercase, lowercase, numbers, and symbols together.</li>
                    <li><strong>Avoid common passwords.</strong> Words like "password" or "123456" are guessed instantly.</li>
                    <li><strong>Don't reuse passwords.</strong> A breach on one site shouldn't compromise every account.</li>
                    <li><strong>Consider a passphrase.</strong> Something like a random string of unrelated words is both strong and memorable.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
    initStrengthChecker({
        inputId: 'passwordInput',
        meterId: 'checkMeter',
        labelId: 'checkLabel',
        listId: 'checkCriteria'
    });
</script>
</body>
</html>