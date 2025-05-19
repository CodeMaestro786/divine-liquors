<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$featuredProducts = getFeaturedProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIVINE LIQUORS - Premium Spirits & Craft Beers</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="hero">
        <div class="bottle-animation">
            <img src="assets/images/bottle.png" alt="Divine Liquors" id="floating-bottle">
        </div>
        <div class="hero-content">
            <h1>Experience the Divine Taste</h1>
            <p>Premium spirits curated for the discerning palate</p>
            <a href="#featured" class="btn btn-primary scroll-down">Explore Our Collection</a>
        </div>
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <section id="featured" class="featured-products">
        <h2>Featured Spirits</h2>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <?php echo renderProductCard($product); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="branches">
        <h2>Our Locations</h2>
        <div class="branch-cards">
            <?php foreach (getBranches() as $branch): ?>
                <div class="branch-card">
                    <h3><?php echo htmlspecialchars($branch['name']); ?></h3>
                    <p><?php echo htmlspecialchars($branch['address']); ?></p>
                    <p><?php echo htmlspecialchars($branch['hours']); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($branch['phone']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>