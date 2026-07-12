<?php
include("login.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if ($email == "" || $password == "") {

        $error = "All fields are required.";
        echo $error;

    } else {

        $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {

            header("Location: success.php");
            exit();

        } else {

            echo "<div class='alert alert-danger'>Invalid email or password</div>";

        }
    }
}
?>