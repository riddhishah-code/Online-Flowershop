<?php
session_start();
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {
        $message = "Please fill all fields.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($exists) > 0) {
            $message = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
            mysqli_stmt_execute($stmt);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-box">
    <h2>Create Account</h2>
    <?php if ($message): ?><div class="alert"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button class="btn" type="submit">Register</button>
    </form>
    <p style="margin-top:15px;">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>