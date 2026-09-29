<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: admin_login.php");
    exit;
}

$flowers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM flowers"))["total"];
$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'"))["total"];
$orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))["total"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<nav class="admin-nav">
    <strong>Bloom Basket Admin</strong>
    <div>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_flowers.php">Flowers</a>
        <a href="manage_orders.php">Orders</a>
        <a href="../logout.php">Logout</a>
    </div>
</nav>

<div class="admin-container">
    <h1 class="admin-title">Dashboard</h1>
    <div class="dashboard-grid">
        <div class="stat-card"><h3>Total Flowers</h3><p><?php echo $flowers; ?></p></div>
        <div class="stat-card"><h3>Customers</h3><p><?php echo $users; ?></p></div>
        <div class="stat-card"><h3>Total Orders</h3><p><?php echo $orders; ?></p></div>
    </div>

    <div class="admin-box">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!</h2>
        <p style="margin-top:10px;">Use the menu above to manage flowers, stock and customer orders.</p>
    </div>
</div>
</body>
</html>