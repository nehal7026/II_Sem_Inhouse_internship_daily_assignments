<?php
require 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $user_id = $_SESSION['user_id'];

    // The user_id check ensures a user can only delete their own entries.
    $stmt = $conn->prepare('DELETE FROM saved_passwords WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

redirect('dashboard.php');