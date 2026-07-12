<?php
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='PSOT') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $POST['email']);
    $branch = mysqli_real_escape_string($conn, $POST['Branch']);
    $cgpa= $_POST['cgpa'];

    $sqli="INSERT INTO students (name,email,branch,cgpa),
            VALUES ('$name','$email,'$branch','$cgpa')";

    if(mysqli_query($conn,$sqli)) {
        echo"student registered successfully";
    } else{
        echo"error:", mysqli_error($conn);
    }       
}
?>

<?php

$errors = array();

if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $father = $_POST['father'];
    $mother = $_POST['mother'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];

    // Email Validation
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errors[] = "Please enter a valid email address.";
    }

    // Phone Validation
    if(!is_numeric($phone))
    {
        $errors[] = "Phone number should contain only digits.";
    }

    if(strlen($phone)!=10)
    {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    // Password Validation
    if($password != $confirm_password)
    {
        $errors[] = "Passwords do not match.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h1>College Registration Form</h1>
<p>Fill in your details to register.</p>

<?php

if(!empty($errors))
{
    echo "<div style='background:#ffd6d6;color:red;padding:15px;border-radius:10px;margin-bottom:20px;'>";
    echo "<ul>";

    foreach($errors as $error)
    {
        echo "<li>$error</li>";
    }

    echo "</ul>";
    echo "</div>";
}
elseif(isset($_POST['submit']))
{
    echo "<div style='background:#d4edda;color:green;padding:15px;border-radius:10px;margin-bottom:20px;'>";
    echo "<b>Registration Successful!</b>";
    echo "</div>";
}

?>

<form action="" method="post">

<div class="input-box">
<label>Full Name</label>
<input type="text" name="name" placeholder="Enter your name" required>
</div>

<div class="input-box">
<label>Email</label>
<input type="email" name="email" placeholder="Enter your email" required>
</div>

<div class="input-box">
<label>Phone Number</label>
<input type="tel" name="phone" placeholder="Enter your phone number" required>
</div>

<div class="input-box">
<label>Father's Name</label>
<input type="text" name="father" placeholder="Enter father's name" required>
</div>

<div class="input-box">
<label>Mother's Name</label>
<input type="text" name="mother" placeholder="Enter mother's name" required>
</div>

<div class="input-box">
<label>Gender</label><br>

<input type="radio" name="gender" value="Male" required> Male
<input type="radio" name="gender" value="Female"> Female
<input type="radio" name="gender" value="Transgender"> Transgender
<input type="radio" name="gender" value="Other"> Other

</div>

<div class="input-box">
<label>Date of Birth</label>
<input type="date" name="dob" required>
</div>

<div class="input-box">
<label>Department</label>

<select name="department" required>
<option value="">Select Department</option>
<option>Computer Science</option>
<option>Artificial Intelligence</option>
<option>Information Technology</option>
<option>Electronics</option>
<option>Mechanical</option>
<option>Civil</option>
</select>

</div>

<div class="input-box">
<label>Password</label>
<input type="password" name="password" placeholder="Enter password" required>
</div>

<div class="input-box">
<label>Confirm Password</label>
<input type="password" name="confirm_password" placeholder="Confirm password" required>
</div>

<div class="input-box">
<label>Address</label>
<textarea name="address" placeholder="Enter your address" required></textarea>
</div>

<input type="submit" name="submit" value="Register" class="btn">
<form  action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="myfile" accept="image/*" required>
    <button type="submit">upload</button>



</form>


</div>

</body>

</html>