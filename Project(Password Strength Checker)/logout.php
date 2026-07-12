<?php
require 'config.php';

// Clear all session data and destroy the session
$_SESSION = [];
session_destroy();

redirect('login.php');