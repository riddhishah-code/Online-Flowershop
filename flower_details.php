<?php
session_start();
include "config/db.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$stmt = mysqli_prepare($conn, "SELECT * FROM flowers WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$flower = mysqli_fetch_assoc($result);

if (!$flower) {
    die("Flower not found.");
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $qty = max(1, intval($_POST["quantity"]));

    if ($qty > $flower["stock"]) {
        $message = "Only " . $flower["stock"] . " flowers are available.";
    } else {
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        if (isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id] += $qty;
        } else {
            $_SESSION["cart"][$id] = $qty;
        }

        header("Location: cart.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($flower["name"]); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">Bloom Basket</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="flowers.php">Flowers</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<div class="container">
    <?php if ($message): ?><div class="alert"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <div class="detail">
        <img src="<?php echo htmlspecialchars($flower['image']); ?>" alt="">
        <div>
            <h1><?php echo htmlspecialchars($flower["name"]); ?></h1>
            <p class="price">₹<?php echo number_format($flower["price"], 2); ?></p>
            <p><?php echo htmlspecialchars($flower["description"]); ?></p>
            <p style="margin:15px 0;">Available Stock: <?php echo $flower["stock"]; ?></p>
            <?php if ($flower["stock"] > 0): ?>
                <form method="post">
                    <label>Quantity</label>
                    <input style="padding:9px;width:80px;margin:10px 0;" type="number" name="quantity" value="1" min="1" max="<?php echo $flower['stock']; ?>">
                    <br>
                    <button class="btn" type="submit">Add to Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>