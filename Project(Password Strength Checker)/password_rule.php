<?php

const COMMON_PASSWORDS = [
    "123456", "123456789", "password", "12345678", "qwerty", "123123",
    "111111", "abc123", "password1", "iloveyou", "admin", "welcome",
    "monkey", "letmein", "dragon", "football", "000000", "qwerty123",
    "1q2w3e4r", "zaq12wsx"
];

function validatePasswordStrength(string $password): array
{
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include a lowercase letter.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include an uppercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must include a number.";
    }
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = "Password must include a special character (e.g. ! @ # $).";
    }
    if (in_array(strtolower($password), COMMON_PASSWORDS, true)) {
        $errors[] = "This password is too common and easily guessed.";
    }
    if (preg_match('/(.)\1{2,}/', $password)) {
        $errors[] = "Avoid repeating the same character three or more times in a row.";
    }

    return [
        'valid'  => empty($errors),
        'errors' => $errors,
    ];
}