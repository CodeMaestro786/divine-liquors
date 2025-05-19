<?php
require_once 'includes/config.php';

try {
    // Create database connection without selecting database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if ($conn->query($sql) {
        echo "Database created successfully<br>";
    } else {
        die("Error creating database: " . $conn->error);
    }
    
    // Select the database
    $conn->select_db(DB_NAME);
    
    // Create tables
    $tables = [
        "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(255),
            category VARCHAR(50),
            stock_quantity INT NOT NULL DEFAULT 0,
            status ENUM('available', 'sold_out', 'coming_soon', 'deleted') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS branches (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            address TEXT NOT NULL,
            phone VARCHAR(20),
            hours TEXT,
            latitude DECIMAL(10,8),
            longitude DECIMAL(11,8),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            branch_id INT NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            payment_method ENUM('pay_now', 'pay_later') NOT NULL,
            payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
            status ENUM('pending', 'processing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
            otp_code VARCHAR(6),
            otp_expires DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (branch_id) REFERENCES branches(id)
        )",
        
        "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )",
        
        "CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $sql) {
        if ($conn->query($sql)) {
            echo "Table created successfully<br>";
        } else {
            echo "Error creating table: " . $conn->error . "<br>";
        }
    }
    
    // Create initial admin user
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $fullName = 'Administrator';
    
    $sql = "INSERT INTO admin_users (username, password_hash, full_name) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), full_name = VALUES(full_name)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $fullName);
    
    if ($stmt->execute()) {
        echo "Admin user created/updated successfully<br>";
    } else {
        echo "Error creating admin user: " . $stmt->error . "<br>";
    }
    
    // Insert sample branches
    $branches = [
        [
            'name' => 'Downtown',
            'address' => '123 Main Street, City Center',
            'phone' => '+27 11 123 4567',
            'hours' => 'Mon-Fri: 9AM-9PM, Sat: 10AM-8PM, Sun: 10AM-6PM',
            'latitude' => -26.2041,
            'longitude' => 28.0473
        ],
        [
            'name' => 'Northside',
            'address' => '456 Oak Avenue, Northern Suburbs',
            'phone' => '+27 11 234 5678',
            'hours' => 'Mon-Fri: 9AM-8PM, Sat: 9AM-7PM, Sun: Closed',
            'latitude' => -26.0945,
            'longitude' => 28.0012
        ],
        [
            'name' => 'Westend',
            'address' => '789 Pine Road, Western District',
            'phone' => '+27 11 345 6789',
            'hours' => 'Mon-Sat: 10AM-9PM, Sun: 10AM-5PM',
            'latitude' => -26.1847,
            'longitude' => 27.9098
        ]
    ];
    
    foreach ($branches as $branch) {
        $sql = "INSERT INTO branches (name, address, phone, hours, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE address = VALUES(address), phone = VALUES(phone), hours = VALUES(hours)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssdd", 
            $branch['name'], 
            $branch['address'], 
            $branch['phone'], 
            $branch['hours'], 
            $branch['latitude'], 
            $branch['longitude']
        );
        
        if ($stmt->execute()) {
            echo "Branch '{$branch['name']}' created/updated successfully<br>";
        } else {
            echo "Error creating branch: " . $stmt->error . "<br>";
        }
    }
    
    // Insert sample products
    $products = [
        [
            'name' => 'Johnnie Walker Black Label',
            'description' => 'A rich, smooth and complex blend of single malt and grain whiskies from across Scotland.',
            'price' => 499.99,
            'category' => 'whiskey',
            'stock_quantity' => 50,
            'status' => 'available'
        ],
        [
            'name' => 'Absolut Vodka',
            'description' => 'A premium vodka known for its purity, made from winter wheat and pure water.',
            'price' => 299.99,
            'category' => 'vodka',
            'stock_quantity' => 75,
            'status' => 'available'
        ],
        // Add more sample products...
    ];
    
    foreach ($products as $product) {
        $sql = "INSERT INTO products (name, description, price, category, stock_quantity, status) 
                VALUES (?, ?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                    description = VALUES(description), 
                    price = VALUES(price), 
                    stock_quantity = VALUES(stock_quantity), 
                    status = VALUES(status)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssdsis", 
            $product['name'], 
            $product['description'], 
            $product['price'], 
            $product['category'], 
            $product['stock_quantity'], 
            $product['status']
        );
        
        if ($stmt->execute()) {
            echo "Product '{$product['name']}' created/updated successfully<br>";
        } else {
            echo "Error creating product: " . $stmt->error . "<br>";
        }
    }
    
    echo "<h2>Installation completed successfully!</h2>";
    echo "<p>You can now access the website and admin panel.</p>";
    echo "<p><strong>Admin Login:</strong> admin / admin123</p>";
    echo "<p>Remember to change the admin password immediately.</p>";
    
    $conn->close();
} catch (Exception $e) {
    die("Error during installation: " . $e->getMessage());
}
?>