<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_GET["remove"])) {
    $remove = intval($_GET["remove"]);
    unset($_SESSION["cart"][$remove]);
    header("Location: cart.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_cart"])) {
    foreach ($_POST["qty"] as $id => $qty) {
        $id = intval($id);
        $qty = max(0, intval($qty));
        if ($qty == 0) {
            unset($_SESSION["cart"][$id]);
        } else {
            $_SESSION["cart"][$id] = $qty;
        }
    }
    header("Location: cart.php");
    exit;
}

$items = [];
$total = 0;

foreach ($_SESSION["cart"] as $id => $qty) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM flowers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $flower = mysqli_fetch_assoc($result);

    if ($flower) {
        $subtotal = $flower["price"] * $qty;
        $total += $subtotal;
        $items[] = ["flower" => $flower, "qty" => $qty, "subtotal" => $subtotal];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">Bloom Basket</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="flowers.php">Flowers</a></li>
        <li><a href="orders.php">My Orders</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<div class="container">
    <h2 class="section-title">Shopping Cart</h2>

    <?php if (empty($items)): ?>
        <div class="form-box">
            <p>Your cart is empty.</p>
            <br>
            <a class="btn" href="flowers.php">Browse Flowers</a>
        </div>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="update_cart" value="1">
            <div class="table-box">
                <table>
                    <tr><th>Flower</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item["flower"]["name"]); ?></td>
                            <td>₹<?php echo number_format($item["flower"]["price"], 2); ?></td>
                            <td><input style="width:70px;padding:7px;" type="number" name="qty[<?php echo $item['flower']['id']; ?>]" value="<?php echo $item['qty']; ?>" min="0"></td>
                            <td>₹<?php echo number_format($item["subtotal"], 2); ?></td>
                            <td><a class="btn btn-danger" href="cart.php?remove=<?php echo $item['flower']['id']; ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="cart-total">Total: ₹<?php echo number_format($total, 2); ?></div>
            <button class="btn btn-secondary" type="submit">Update Cart</button>
            <a class="btn" href="checkout.php">Proceed to Checkout</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>