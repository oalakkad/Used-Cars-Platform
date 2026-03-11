<?php $pageTitle = 'Cars by ' . sanitize($brandName); ?>
<?php require __DIR__ . '/header.php'; ?>

<section class="search-page">
    <div class="container">
        <h1 class="section-title fade-in" style="margin-top:8px;">
            <?= sanitize($brandName) ?> Cars
        </h1>
        <p class="section-subtitle fade-in">Explore all
            <?= sanitize($brandName) ?> listings
        </p>

        <div class="cars-grid stagger-children">
            <?php if (empty($cars)): ?>
                <div class="empty-state" style="grid-column:1/-1;">
                    <i class="fas fa-car"></i>
                    <h3>No
                        <?= sanitize($brandName) ?> cars available
                    </h3>
                    <p>Check back later for new listings</p>
                    <a href="<?= baseUrl() ?>" class="btn btn-primary">Back to Home</a>
                </div>
            <?php else: ?>
                <?php foreach ($cars as $car): ?>
                    <a href="<?= baseUrl('car/' . $car['id']) ?>" class="car-card fade-in" style="text-decoration:none;">
                        <div class="car-card-image">
                            <?php if (!empty($car['primary_image'])): ?>
                                <img src="<?= asset('uploads/' . $car['primary_image']) ?>"
                                    alt="<?= sanitize($car['brand'] . ' ' . $car['model']) ?>">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600&q=80"
                                    alt="Car placeholder">
                            <?php endif; ?>
                            <span class="car-card-badge">
                                <?= sanitize($car['condition_status']) ?>
                            </span>
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
                                <div class="car-card-location"><i class="fas fa-map-marker-alt"></i>
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

<?php require __DIR__ . '/footer.php'; ?>