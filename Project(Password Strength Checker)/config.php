<?php

session_start();


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          
define('DB_NAME', 'password_checker_db');

define('ENCRYPTION_KEY', 'change_this_to_a_long_random_string_32chars!');
define('ENCRYPTION_METHOD', 'AES-256-CBC');

// Connect 
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}


function redirect($path) {
    header('Location: ' . $path);
    exit();
}


function require_login() {
    if (!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

/**
 * Encrypt a string (used for saved passwords, NOT login passwords).
 */
function encrypt_string($plain_text) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($plain_text, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    return [
        'encrypted' => $encrypted,
        'iv'        => base64_encode($iv)
    ];
}

function decrypt_string($encrypted_text, $iv_base64) {
    $iv = base64_decode($iv_base64);
    return openssl_decrypt($encrypted_text, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
}