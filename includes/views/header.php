<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="AutoMarket - Find your perfect used car. Browse thousands of quality pre-owned vehicles at the best prices.">
    <title>
        <?= isset($pageTitle) ? sanitize($pageTitle) . ' | ' : '' ?>AutoMarket
    </title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- App Styles -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
</head>

<body>

    <!-- ===== HEADER / NAVBAR ===== -->
    <header class="header" id="mainHeader">
        <nav class="nav">
            <a href="<?= baseUrl() ?>" class="nav-logo">
                <i class="fas fa-car-side"></i>
                AutoMarket
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="<?= baseUrl() ?>" class="nav-link">Home</a></li>
                <li><a href="<?= baseUrl('search') ?>" class="nav-link">Browse Cars</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="<?= baseUrl('dashboard') ?>" class="nav-link">Dashboard</a></li>
                    <li><a href="<?= baseUrl('cars/new') ?>" class="nav-link">Sell Car</a></li>
                    <?php if (($_SESSION['user_email'] ?? '') === 'demo@automarket.com'): ?>
                        <li><a href="<?= baseUrl('admin') ?>" class="nav-link" style="color:var(--warning);"><i
                                    class="fas fa-shield-alt" style="margin-right:4px;"></i>Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>

                <?php if (isLoggedIn()): ?>
                    <a href="<?= baseUrl('dashboard') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-user"></i>
                        <?= sanitize(currentUserName()) ?>
                    </a>
                    <a href="<?= baseUrl('logout') ?>" class="btn btn-outline btn-sm">Logout</a>
                <?php else: ?>
                    <a href="<?= baseUrl('login') ?>" class="btn btn-secondary btn-sm">Login</a>
                    <a href="<?= baseUrl('register') ?>" class="btn btn-primary btn-sm">Sign Up</a>
                <?php endif; ?>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <main>