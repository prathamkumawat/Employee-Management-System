<!-- register.php -->

<?php
require "db_connect.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
        $stmt->execute([$name, $email, $password]);
        $msg = "Account Created! <a href='login.php'>Login Now</a>";
    } catch (Exception $e) {
        $msg = "Email already registered!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<div class="auth-box">

    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Join Employee System</p>

    <?php if ($msg): ?>
        <p class="auth-success"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post" class="auth-form">

        <label class="input-label">Name</label>
        <input type="text" name="name" class="input-field" required>

        <label class="input-label">Email</label>
        <input type="email" name="email" class="input-field" required>

        <label class="input-label">Password</label>
        <input type="password" name="password" class="input-field" required>

        <button class="btn auth-btn" type="submit">Register</button>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>
