<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: admin_login.php");
    exit;
}

$id = intval($_GET["id"] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT * FROM flowers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$flower = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$flower) {
    die("Flower not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);
    $category = trim($_POST["category"]);
    $image = trim($_POST["image"]);

    $stmt = mysqli_prepare($conn, "UPDATE flowers SET name=?, description=?, price=?, stock=?, category=?, image=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssdissi", $name, $description, $price, $stock, $category, $image, $id);
    mysqli_stmt_execute($stmt);

    header("Location: manage_flowers.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Flower</title><link rel="stylesheet" href="../css/admin.css"></head>
<body>
<nav class="admin-nav"><strong>Bloom Basket Admin</strong><div><a href="admin_dashboard.php">Dashboard</a><a href="manage_flowers.php">Flowers</a><a href="manage_orders.php">Orders</a></div></nav>
<div class="admin-container">
    <div class="admin-box">
        <h1>Edit Flower</h1><br>
        <form class="admin-form" method="post">
            <label>Flower Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($flower['name']); ?>" required>
            <label>Description</label><textarea name="description" rows="4" required><?php echo htmlspecialchars($flower['description']); ?></textarea>
            <label>Price</label><input type="number" step="0.01" name="price" value="<?php echo $flower['price']; ?>" required>
            <label>Stock</label><input type="number" name="stock" min="0" value="<?php echo $flower['stock']; ?>" required>
            <label>Category</label><input type="text" name="category" value="<?php echo htmlspecialchars($flower['category']); ?>" required>
            <label>Image URL</label><input type="text" name="image" value="<?php echo htmlspecialchars($flower['image']); ?>" required>
            <button class="btn btn-success" type="submit">Update Flower</button>
            <a class="btn btn-secondary" href="manage_flowers.php">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>