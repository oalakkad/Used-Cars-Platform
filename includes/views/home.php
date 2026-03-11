<?php $pageTitle = 'Home'; ?>
<?php require __DIR__ . '/header.php'; ?>

<!-- ===== HERO SECTION ===== -->
<section class="hero" id="hero">
    <div class="hero-bg"></div>
    <div class="hero-content fade-in">
        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i>
            Trusted by thousands of car buyers
        </div>
        <h1>Find Your <span>Perfect Drive</span></h1>
        <p>Browse thousands of quality pre-owned vehicles from trusted sellers. Your next ride is just a search away.
        </p>

        <!-- Hero Search -->
        <form class="hero-search" action="<?= baseUrl('search') ?>" method="GET">
            <div class="search-grid">
                <select name="brand" class="form-select" id="heroSearchBrand">
                    <option value="">All Brands</option>
                    <option value="Toyota">Toyota</option>
                    <option value="BMW">BMW</option>
                    <option value="Mercedes-Benz">Mercedes-Benz</option>
                    <option value="Audi">Audi</option>
                    <option value="Lexus">Lexus</option>
                    <option value="Honda">Honda</option>
                    <option value="Ford">Ford</option>
                    <option value="Nissan">Nissan</option>
                    <option value="Porsche">Porsche</option>
                    <option value="Volkswagen">Volkswagen</option>
                    <option value="Chevrolet">Chevrolet</option>
                    <option value="Hyundai">Hyundai</option>
                </select>
                <input type="text" name="model" class="form-input" placeholder="Model (e.g. Camry)">
                <select name="max_price" class="form-select">
                    <option value="">Max Price</option>
                    <option value="20000">Under $20,000</option>
                    <option value="30000">Under $30,000</option>
                    <option value="40000">Under $40,000</option>
                    <option value="50000">Under $50,000</option>
                    <option value="75000">Under $75,000</option>
                    <option value="100000">Under $100,000</option>
                </select>
                <input type="text" name="location" class="form-input" placeholder="Location">
            </div>
            <div class="search-btn-row">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Search Cars
                </button>
            </div>
        </form>
    </div>
    <div class="scroll-indicator"
        onclick="document.querySelector('.brands-section').scrollIntoView({behavior:'smooth'})">
        <span>Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- ===== POPULAR BRANDS ===== -->
<section class="brands-section">
    <div class="container">
        <div class="fade-in">
            <h2 class="section-title">Popular Brands</h2>
            <p class="section-subtitle">Explore cars from the world's most trusted automakers</p>
        </div>
        <div class="brands-grid stagger-children">
            <?php foreach ($brands as $brand): ?>
                <a href="<?= baseUrl('brand/' . urlencode($brand['slug'])) ?>" class="brand-card fade-in">
                    <div class="brand-logo"
                        style="background:<?= $brand['color'] ?>15; display:flex; align-items:center; justify-content:center; width:72px; height:72px; border-radius:50%; border:2px solid <?= $brand['color'] ?>25; overflow:hidden;">
                        <img src="<?= $brand['logo'] ?>" alt="<?= sanitize($brand['name']) ?>"
                            style="width:44px;height:44px;object-fit:contain;"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                        <span
                            style="display:none;font-family:var(--font-display);font-weight:900;font-size:1.8rem;color:<?= $brand['color'] ?>;line-height:1;letter-spacing:-1px;"><?= $brand['icon'] ?></span>
                    </div>
                    <span class="brand-name"><?= sanitize($brand['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== STATS COUNTER ===== -->
<section
    style="padding:60px 0;background:linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);position:relative;overflow:hidden;">
    <div
        style="position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 80 80%22><circle cx=%2240%22 cy=%2240%22 r=%221%22 fill=%22rgba(255,255,255,0.05)%22/></svg>') repeat;">
    </div>
    <div class="container" style="position:relative;z-index:2;">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px;text-align:center;"
            class="stats-counter-grid">
            <style>
                .stats-counter-grid {
                    display: grid !important;
                }

                @media(max-width:768px) {
                    .stats-counter-grid {
                        grid-template-columns: repeat(2, 1fr) !important;
                        gap: 20px !important;
                    }

                    .stats-counter-grid .counter-value {
                        font-size: 2rem !important;
                    }
                }
            </style>
            <div class="fade-in">
                <div class="counter-value" data-target="<?= count($latestCars) > 0 ? '2500' : '0' ?>"
                    style="font-family:var(--font-display);font-size:3rem;font-weight:900;color:#fff;line-height:1;">0
                </div>
                <div style="color:rgba(255,255,255,0.6);font-size:0.9rem;font-weight:500;margin-top:8px;"><i
                        class="fas fa-car" style="margin-right:6px;"></i>Cars Listed</div>
            </div>
            <div class="fade-in">
                <div class="counter-value" data-target="25"
                    style="font-family:var(--font-display);font-size:3rem;font-weight:900;color:#fff;line-height:1;">0
                </div>
                <div style="color:rgba(255,255,255,0.6);font-size:0.9rem;font-weight:500;margin-top:8px;"><i
                        class="fas fa-tags" style="margin-right:6px;"></i>Brands Available</div>
            </div>
            <div class="fade-in">
                <div class="counter-value" data-target="1800"
                    style="font-family:var(--font-display);font-size:3rem;font-weight:900;color:#fff;line-height:1;">0
                </div>
                <div style="color:rgba(255,255,255,0.6);font-size:0.9rem;font-weight:500;margin-top:8px;"><i
                        class="fas fa-smile" style="margin-right:6px;"></i>Happy Buyers</div>
            </div>
            <div class="fade-in">
                <div class="counter-value" data-target="12"
                    style="font-family:var(--font-display);font-size:3rem;font-weight:900;color:#fff;line-height:1;">0
                </div>
                <div style="color:rgba(255,255,255,0.6);font-size:0.9rem;font-weight:500;margin-top:8px;"><i
                        class="fas fa-map-marker-alt" style="margin-right:6px;"></i>Cities Covered</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== LATEST CAR LISTINGS ===== -->
<section class="listings-section">
    <div class="container">
        <div class="fade-in"
            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.5rem;">
            <div>
                <h2 class="section-title">Latest Listings</h2>
                <p class="section-subtitle" style="margin-bottom:0;">Newly listed cars waiting for you</p>
            </div>
            <a href="<?= baseUrl('search') ?>" class="btn btn-outline">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="cars-grid stagger-children">
            <?php if (empty($latestCars)): ?>
                <div class="empty-state" style="grid-column:1/-1;">
                    <i class="fas fa-car-side"></i>
                    <h3>No cars listed yet</h3>
                    <p>Be the first to list your car!</p>
                    <a href="<?= baseUrl('cars/new') ?>" class="btn btn-primary">Sell a Car</a>
                </div>
            <?php else: ?>
                <?php foreach ($latestCars as $car): ?>
                    <a href="<?= baseUrl('car/' . $car['id']) ?>" class="car-card fade-in" style="text-decoration:none;">
                        <div class="car-card-image">
                            <?php if (!empty($car['primary_image'])): ?>
                                <img src="<?= asset('uploads/' . $car['primary_image']) ?>"
                                    alt="<?= sanitize($car['brand'] . ' ' . $car['model']) ?>"
                                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600&q=80';">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600&q=80"
                                    alt="Car placeholder">
                            <?php endif; ?>
                            <span class="car-card-badge">
                                <?= sanitize($car['condition_status']) ?>
                            </span>
                            <?php if (!empty($car['is_featured'])): ?>
                                <span
                                    style="position:absolute;top:12px;left:12px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:4px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;z-index:2;"><i
                                        class="fas fa-star" style="margin-right:4px;"></i>Featured</span>
                            <?php endif; ?>
                            <?php if (isLoggedIn()): ?>
                                <button class="car-card-fav" data-car-id="<?= $car['id'] ?>"
                                    onclick="event.preventDefault();toggleFavorite(<?= $car['id'] ?>, this);">
                                    <i class="fas fa-heart"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="car-card-body">
                            <div class="car-card-price">
                                <?= number_format($car['price'], 0) ?> KWD
                            </div>
                            <div class="car-card-title">
                                <?= sanitize($car['brand'] . ' ' . $car['model']) ?>
                                <?= $car['year'] ?>
                            </div>
                            <div class="car-card-specs">
                                <span class="car-card-spec"><i class="fas fa-tachometer-alt"></i>
                                    <?= number_format($car['mileage']) ?> km
                                </span>
                                <span class="car-card-spec"><i class="fas fa-gas-pump"></i>
                                    <?= sanitize($car['fuel_type']) ?>
                                </span>
                                <span class="car-card-spec"><i class="fas fa-cog"></i>
                                    <?= sanitize($car['transmission']) ?>
                                </span>
                            </div>
                            <?php if (!empty($car['location'])): ?>
                                <div class="car-card-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= sanitize($car['location']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Removed Recently Viewed Section -->

<?php require __DIR__ . '/footer.php'; ?>