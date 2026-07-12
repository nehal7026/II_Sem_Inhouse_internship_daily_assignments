<?php
include("header.php");
?>  
<div class="container mt-5 text-center">
<div class="alert alert-warning">
<?php
$error="";

$name="";
$email="";
$password="";
$confirmpassword="";

 if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=$_POST["name"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $confirmpassword=$_POST["confirmPassword"];

    if($name==""|| $email==""||$password==""|| $confirmpassword==""){
        $error="All fields are required";
        echo $error;
    }
    else {
        header("Location: success.php");
        exit();
    }
}
?>
</div>
<a href="registration.php" class="btn btn-primary">Go back</a>
</div>
<?php
include("footer.php");
?>
