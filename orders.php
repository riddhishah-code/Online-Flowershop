<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">Bloom Basket</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="flowers.php">Flowers</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2 class="section-title">My Orders</h2>
    <?php if (isset($_GET["success"])): ?><div class="alert success">Order placed successfully!</div><?php endif; ?>

    <div class="table-box">
        <table>
            <tr><th>Order ID</th><th>Total</th><th>Address</th><th>Status</th><th>Date</th></tr>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?php echo $order["id"]; ?></td>
                    <td>₹<?php echo number_format($order["total_amount"], 2); ?></td>
                    <td><?php echo htmlspecialchars($order["address"]); ?></td>
                    <td><?php echo htmlspecialchars($order["status"]); ?></td>
                    <td><?php echo htmlspecialchars($order["order_date"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>