<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST["order_id"]);
    $status = $_POST["status"];

    $allowed = ["Pending", "Confirmed", "Delivered", "Cancelled"];
    if (in_array($status, $allowed)) {
        $stmt = mysqli_prepare($conn, "UPDATE orders SET status=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        mysqli_stmt_execute($stmt);
    }
}

$query = "SELECT orders.*, users.name, users.email FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head><title>Manage Orders</title><link rel="stylesheet" href="../css/admin.css"></head>
<body>
<nav class="admin-nav"><strong>Bloom Basket Admin</strong><div><a href="admin_dashboard.php">Dashboard</a><a href="manage_flowers.php">Flowers</a><a href="manage_orders.php">Orders</a><a href="../logout.php">Logout</a></div></nav>
<div class="admin-container">
    <h1 class="admin-title">Customer Orders</h1>
    <div class="admin-box" style="overflow-x:auto;">
        <table>
            <tr><th>Order ID</th><th>Customer</th><th>Email</th><th>Total</th><th>Address</th><th>Status</th><th>Date</th></tr>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?php echo $order["id"]; ?></td>
                    <td><?php echo htmlspecialchars($order["name"]); ?></td>
                    <td><?php echo htmlspecialchars($order["email"]); ?></td>
                    <td>₹<?php echo number_format($order["total_amount"],2); ?></td>
                    <td><?php echo htmlspecialchars($order["address"]); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <?php foreach (["Pending","Confirmed","Delivered","Cancelled"] as $status): ?>
                                    <option value="<?php echo $status; ?>" <?php if ($order["status"] == $status) echo "selected"; ?>><?php echo $status; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn" type="submit">Update</button>
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($order["order_date"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>