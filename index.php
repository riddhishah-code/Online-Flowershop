<?php
include "config/db.php";
$result = mysqli_query($conn, "SELECT * FROM flowers WHERE stock > 0 ORDER BY id DESC LIMIT 6");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bloom Basket - Online Flower Shop</title>
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
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
    <section class="hero">
        <h1>Fresh Flowers for Every Occasion</h1>
        <p>Choose beautiful flowers and send a little happiness to someone special.</p>
        <a class="btn" href="flowers.php">Shop Flowers</a>
    </section>

    <h2 class="section-title">Popular Flowers</h2>
    <div class="flower-grid">
        <?php while ($flower = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img class="card-image" src="<?php echo htmlspecialchars($flower['image']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($flower['name']); ?></h3>
                    <p><?php echo htmlspecialchars($flower['description']); ?></p>
                    <p class="price">₹<?php echo number_format($flower['price'], 2); ?></p>
                    <a class="btn" href="flower_details.php?id=<?php echo $flower['id']; ?>">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Bloom Basket. All rights reserved.</p>
</footer>
</body>
</html>