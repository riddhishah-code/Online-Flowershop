<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    mysqli_query($conn, "DELETE FROM flowers WHERE id = $id");
    header("Location: manage_flowers.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM flowers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Flowers</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<nav class="admin-nav">
    <strong>Bloom Basket Admin</strong>
    <div><a href="admin_dashboard.php">Dashboard</a><a href="manage_flowers.php">Flowers</a><a href="manage_orders.php">Orders</a><a href="../logout.php">Logout</a></div>
</nav>

<div class="admin-container">
    <h1 class="admin-title">Manage Flowers</h1>
    <a class="btn btn-success" href="add_flower.php">+ Add New Flower</a><br><br>
    <div class="admin-box" style="overflow-x:auto;">
        <table>
            <tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Category</th><th>Actions</th></tr>
            <?php while ($flower = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $flower["id"]; ?></td>
                    <td><?php echo htmlspecialchars($flower["name"]); ?></td>
                    <td>₹<?php echo number_format($flower["price"],2); ?></td>
                    <td><?php echo $flower["stock"]; ?></td>
                    <td><?php echo htmlspecialchars($flower["category"]); ?></td>
                    <td>
                        <a class="btn" href="edit_flower.php?id=<?php echo $flower['id']; ?>">Edit</a>
                        <a class="btn btn-danger" href="manage_flowers.php?delete=<?php echo $flower['id']; ?>" onclick="return confirm('Delete this flower?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>