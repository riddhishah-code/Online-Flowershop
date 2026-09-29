<?php
include "config/db.php";
$result = mysqli_query($conn, "SELECT * FROM flowers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Flowers - Bloom Basket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">Bloom Basket</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="flowers.php">Flowers</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="orders.php">My Orders</a></li>
        <?php if (isset($_SESSION['user_id'])): ?><li><a href="logout.php">Logout</a></li><?php else: ?><li><a href="login.php">Login</a></li><?php endif; ?>
    </ul>
</nav>

<div class="container">
    <h2 class="section-title">Our Flowers</h2>
    <div class="flower-grid">
        <?php while ($flower = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img class="card-image" src="<?php echo htmlspecialchars($flower['image']); ?>" alt="">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($flower['name']); ?></h3>
                    <p><?php echo htmlspecialchars($flower['description']); ?></p>
                    <p class="price">₹<?php echo number_format($flower['price'], 2); ?></p>
                    <p>Stock: <?php echo $flower['stock']; ?></p>
                    <?php if ($flower['stock'] > 0): ?>
                        <a class="btn" href="flower_details.php?id=<?php echo $flower['id']; ?>">View & Add to Cart</a>
                    <?php else: ?>
                        <span class="btn btn-secondary">Out of Stock</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<footer class="footer">© 2026 Bloom Basket Flower Shop</footer>
</body>
</html>