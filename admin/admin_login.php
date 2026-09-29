<?php
session_start();
include "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ? AND role = 'admin'");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["user_id"] = $admin["id"];
        $_SESSION["user_name"] = $admin["name"];
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $message = "Invalid admin login details.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-box" style="max-width:500px;margin:80px auto;">
        <h2>Bloom Basket Admin Login</h2><br>
        <?php if ($message): ?><div class="message error"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <form class="admin-form" method="post">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Login</button>
        </form>
        <br><a href="../index.php">Back to Shop</a>
    </div>
</div>
</body>
</html>