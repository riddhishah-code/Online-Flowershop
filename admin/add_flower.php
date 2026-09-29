<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);
    $category = trim($_POST["category"]);
    $image = trim($_POST["image"]);

    if ($name == "" || $price <= 0 || $stock < 0) {
        $message = "Please enter valid flower details.";
    } else {
        # Insert the new flower into the database.
        $stmt = mysqli_prepare($conn, "INSERT INTO flowers (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdiss", $name, $description, $price, $stock, $category, $image);
        mysqli_stmt_execute($stmt);
        header("Location: manage_flowers.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Flower</title><link rel="stylesheet" href="../css/admin.css"></head>
<body>
<nav class="admin-nav"><strong>Bloom Basket Admin</strong><div><a href="admin_dashboard.php">Dashboard</a><a href="manage_flowers.php">Flowers</a><a href="manage_orders.php">Orders</a></div></nav>
<div class="admin-container">
    <div class="admin-box">
        <h1>Add Flower</h1><br>
        <?php if ($message): ?><div class="message error"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <form class="admin-form" method="post">
            <label>Flower Name</label><input type="text" name="name" required>
            <label>Description</label><textarea name="description" rows="4" required></textarea>
            <label>Price</label><input type="number" step="0.01" name="price" required>
            <label>Stock</label><input type="number" name="stock" min="0" required>
            <label>Category</label><input type="text" name="category" placeholder="Rose, Bouquet, etc." required>
            <label>Image URL</label><input type="text" name="image" placeholder="https://..." required>
            <button class="btn btn-success" type="submit">Add Flower</button>
            <a class="btn btn-secondary" href="manage_flowers.php">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>