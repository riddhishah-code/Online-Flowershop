CREATE DATABASE IF NOT EXISTS flower_shop;
USE flower_shop;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS flowers;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE flowers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(500) NOT NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    address TEXT NOT NULL,
    status ENUM('Pending','Confirmed','Delivered','Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    flower_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (flower_id) REFERENCES flowers(id) ON DELETE CASCADE
);

-- Demo admin: admin@bloombasket.com / admin123
-- Demo customer: riya@gmail.com / user123
INSERT INTO users (name, email, password, role) VALUES
('Shop Admin', 'admin@bloombasket.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2Q4Q8y8nqH5Q5q5q5q', 'admin'),
('Riya Patel', 'riya@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2Q4Q8y8nqH5Q5q5q5q', 'customer'),
('Aarav Shah', 'aarav@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC2Q4Q8y8nqH5Q5q5q5q', 'customer');

INSERT INTO flowers (name, description, price, stock, category, image) VALUES
('Red Rose Bouquet', 'A classic bouquet of fresh red roses, perfect for expressing love and care.', 599.00, 25, 'Roses', 'https://images.unsplash.com/photo-1496062031456-07b8f162a322?w=800'),
('Pink Rose Bouquet', 'Soft pink roses arranged beautifully for birthdays and special moments.', 549.00, 18, 'Roses', 'https://images.unsplash.com/photo-1559563362-c667ba5f5480?w=800'),
('White Lily Bouquet', 'Elegant white lilies with a fresh and graceful appearance.', 699.00, 15, 'Lilies', 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=800'),
('Sunflower Bunch', 'Bright yellow sunflowers that bring warmth and happiness.', 449.00, 20, 'Sunflowers', 'https://images.unsplash.com/photo-1597848212624-e19a99c6d1c6?w=800'),
('Mixed Flower Basket', 'A colorful mix of seasonal flowers arranged in a simple basket.', 799.00, 12, 'Mixed Flowers', 'https://images.unsplash.com/photo-1523438885200-e635ba2c371e?w=800'),
('Orchid Pot', 'Beautiful purple orchids suitable for home, office and gifting.', 899.00, 10, 'Orchids', 'https://images.unsplash.com/photo-1563241527-3004b7be0ffd?w=800'),
('Tulip Bouquet', 'Fresh-looking tulips in bright colors for cheerful occasions.', 749.00, 14, 'Tulips', 'https://images.unsplash.com/photo-1520763185298-1b434c919102?w=800'),
('Marigold Garland', 'Traditional orange marigold flowers suitable for festive decorations.', 299.00, 30, 'Marigold', 'https://images.unsplash.com/photo-1602279967187-6b6f8e2d6e4e?w=800'),
('Lavender Bouquet', 'A simple lavender bouquet with a pleasant and calming look.', 499.00, 16, 'Lavender', 'https://images.unsplash.com/photo-1499002238440-d264edd596ec?w=800'),
('Premium Flower Box', 'A premium box containing a colorful arrangement of fresh flowers.', 1199.00, 8, 'Flower Box', 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=800');

-- Demo order for customer Riya
INSERT INTO orders (user_id, total_amount, address, status) VALUES
(2, 1048.00, 'Ahmedabad, Gujarat', 'Confirmed');

INSERT INTO order_items (order_id, flower_id, quantity, price) VALUES
(1, 1, 1, 599.00),
(1, 4, 1, 449.00);
