<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION["cart"])) {
    header("Location: cart.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = trim($_POST["address"]);
    $total = 0;
    $cartItems = [];

    foreach ($_SESSION["cart"] as $id => $qty) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM flowers WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $flower = mysqli_fetch_assoc($result);

        if (!$flower || $qty > $flower["stock"]) {
            $message = "One or more flowers do not have enough stock.";
            break;
        }

        $subtotal = $flower["price"] * $qty;
        $total += $subtotal;
        $cartItems[] = ["flower" => $flower, "qty" => $qty, "subtotal" => $subtotal];
    }

    if ($message == "" && $address != "") {
        mysqli_begin_transaction($conn);

        $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, address, status) VALUES (?, ?, ?, 'Pending')");
        mysqli_stmt_bind_param($stmt, "ids", $_SESSION["user_id"], $total, $address);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($conn);

        foreach ($cartItems as $item) {
            $flower_id = $item["flower"]["id"];
            $qty = $item["qty"];
            $price = $item["flower"]["price"];

            $stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, flower_id, quantity, price) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiid", $order_id, $flower_id, $qty, $price);
            mysqli_stmt_execute($stmt);

            $stmt = mysqli_prepare($conn, "UPDATE flowers SET stock = stock - ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $qty, $flower_id);
            mysqli_stmt_execute($stmt);
        }

        mysqli_commit($conn);
        $_SESSION["cart"] = [];
        header("Location: orders.php?success=1");
        exit;
    } elseif ($address == "") {
        $message = "Please enter your delivery address.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-box">
    <h2>Checkout</h2>
    <?php if ($message): ?><div class="alert"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Delivery Address</label>
            <textarea name="address" rows="5" required></textarea>
        </div>
        <button class="btn" type="submit">Place Order</button>
        <a class="btn btn-secondary" href="cart.php">Back to Cart</a>
    </form>
</div>
</body>
</html>