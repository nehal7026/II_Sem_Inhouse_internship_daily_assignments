<?php

const DB_HOST = "localhost";
const DB_NAME = "password_checker_db";
const DB_USER = "root";
const DB_PASS = "";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());
}