
<?php
session_start();


if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}


include("headerdashboard.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 text-center">
    <div class="card p-5 shadow-sm">

        <h1>Welcome, <?php echo $_SESSION['user_name']; ?>! 🎉</h1>

        <p class="text-muted">
            Logged in as: <?php echo $_SESSION['user_email']; ?>
        </p>

        <br>

        <a href="update_password.php" class="btn btn-warning w-50 mb-3">
            Update Password
        </a>

        <br>

        <a href="logout.php" class="btn btn-danger w-50">
            Logout
        </a>

    </div>
</div>

</body>
</html>

<?php
include("Footer.php");
?>