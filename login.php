<!-- login.php -->

<?php
session_start();
require "db_connect.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // MySQLi Prepared Statement
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: index.php");
        exit;

    } else {
        $error = "Invalid Email or Password";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

    <div class="auth-box">

        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Login to continue</p>

        <?php if ($error): ?>
            <p class="auth-error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="auth-form">

            <label class="input-label">Email</label>
            <input type="email" name="email" class="input-field" required>

            <label class="input-label">Password</label>
            <input type="password" name="password" class="input-field" required>

            <button class="btn auth-btn" type="submit">Login</button>
        </form>

        <p class="auth-footer">
            New user? <a href="register.php">Register</a>
        </p>

    </div>

</body>

</html>
