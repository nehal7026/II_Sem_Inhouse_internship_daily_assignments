<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | My Portal</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #eef2ff, #f8fafc);
        }

        .hero {
            padding: 100px 20px;
        }

        .feature-card {
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>

<body>

<!-- 🔝 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">MyPortal</a>

    <div class="ms-auto">
        <?php if (isset($_SESSION['user_email'])): ?>
            <a href="dashboard.php" class="btn btn-success me-2">Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="registration.php" class="btn btn-warning">Register</a>
        <?php endif; ?>
    </div>
</nav>

<!-- 🚀 HERO SECTION -->
<div class="container text-center hero">
    <h1 class="display-4 fw-bold">Welcome to Your Portal</h1>
    <p class="lead text-muted">
        Manage your account, update details, and explore your dashboard — all in one place.
    </p>

    <?php if (!isset($_SESSION['user_email'])): ?>
        <a href="registration.php" class="btn btn-primary btn-lg mt-3 me-2">Get Started</a>
        <a href="login.php" class="btn btn-outline-dark btn-lg mt-3">Login</a>
    <?php else: ?>
        <a href="dashboard.php" class="btn btn-success btn-lg mt-3">Go to Dashboard</a>
    <?php endif; ?>
</div>

<!-- 💡 FEATURES SECTION -->
<div class="container mb-5">
    <div class="row text-center">

        <div class="col-md-4">
            <div class="card p-4 shadow-sm feature-card">
                <h4>Secure Login</h4>
                <p class="text-muted">Your data stays protected with session-based authentication.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 shadow-sm feature-card">
                <h4>Dashboard</h4>
                <p class="text-muted">Access your personal dashboard after login.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 shadow-sm feature-card">
                <h4> Manage Account</h4>
                <p class="text-muted">Update password and manage your profile easily.</p>
            </div>
        </div>

    </div>
</div>

<!-- 🔻 FOOTER -->
<footer class="bg-dark text-white text-center p-3">
    <p class="mb-0">© <?php echo date("Y"); ?> MyPortal</p>
</footer>

</body>
</html>