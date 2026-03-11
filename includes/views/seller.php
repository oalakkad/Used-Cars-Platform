<?php $pageTitle = 'Seller: ' . sanitize($seller['name']); ?>
<?php require __DIR__ . '/header.php'; ?>

<style>
    .seller-page {
        padding: 60px 0;
    }

    .seller-header {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 40px;
        text-align: center;
        margin-bottom: 40px;
    }

    .seller-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--cta));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 2rem;
        color: #fff;
        font-weight: 700;
    }

    .seller-name {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .seller-meta {
        color: var(--text-muted);
        font-size: 0.9rem;
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 12px;
    }

    .seller-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .seller-listings-title {
        font-family: var(--font-display);
        font-size: 1.3rem;
        margin-bottom: 24px;
    }
</style>

<section class="seller-page">
    <div class="container">
        <div class="seller-header fade-in">
            <div class="seller-avatar">
                <?= strtoupper(substr($seller['name'], 0, 1)) ?>
            </div>
            <h1 class="seller-name">
                <?= sanitize($seller['name']) ?>
            </h1>
            <div class="seller-meta">
                <span><i class="fas fa-calendar-alt"></i> Member since
                    <?= date('M Y', strtotime($seller['created_at'])) ?>
                </span>
                <span><i class="fas fa-car"></i>
                    <?= $listingCount ?> listing
                    <?= $listingCount !== 1 ? 's' : '' ?>
                </span>
            </div>
        </div>

        <h2 class="seller-listings-title"><i class="fas fa-th-large" style="color:var(--primary);margin-right:8px;"></i>
            Listings by
            <?= sanitize($seller['name']) ?>
        </h2>

        <?php if (empty($cars)): ?>
            <div style="text-align:center; padding:60px 0; color:var(--text-muted);">
                <i class="fas fa-inbox" style="font-size:3rem; margin-bottom:12px; display:block;"></i>
                This seller has no active listings.
            </div>
        <?php else: ?>
            <div class="cars-grid stagger-children">
                <?php foreach ($cars as $car): ?>
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
                                    style="position:absolute;top:12px;left:12px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:4px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;z-index:2;">
                                    <i class="fas fa-star" style="margin-right:4px;"></i>Featured
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="car-card-body">
                            <div class="car-card-price">
                                <?= number_format($car['price'], 0) ?> KWD
                            </div>
                            <div class="car-card-title">
                                <?= sanitize($car['brand'] . ' ' . $car['model']) ?>         <?= $car['year'] ?>
                            </div>
                            <div class="car-card-specs">
                                <span class="car-card-spec"><i class="fas fa-tachometer-alt"></i>
                                    <?= number_format($car['mileage']) ?> km</span>
                                <span class="car-card-spec"><i class="fas fa-gas-pump"></i>
                                    <?= sanitize($car['fuel_type']) ?></span>
                                <span class="car-card-spec"><i class="fas fa-cog"></i>
                                    <?= sanitize($car['transmission']) ?></span>
                            </div>
                            <?php if (!empty($car['location'])): ?>
                                <div class="car-card-location">
                                    <i class="fas fa-map-marker-alt"></i> <?= sanitize($car['location']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>