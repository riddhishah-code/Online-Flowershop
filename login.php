<?php
session_start();
include "config/db.php";

$message = isset($_GET["registered"]) ? "Registration successful. Please login." : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] == "admin") {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-box">
    <h2>Login</h2>
    <?php if ($message): ?><div class="alert <?php echo isset($_GET['registered']) ? 'success' : ''; ?>"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button class="btn" type="submit">Login</button>
    </form>
    <p style="margin-top:15px;">New customer? <a href="register.php">Create account</a></p>
    <p style="margin-top:8px;"><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>