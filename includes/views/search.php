<?php $pageTitle = 'Search Cars'; ?>
<?php require __DIR__ . '/header.php'; ?>

<section class="search-page">
    <div class="container">
        <h1 class="section-title fade-in" style="margin-top:8px;">Browse Cars</h1>
        <p class="section-subtitle fade-in">Find the perfect car that fits your needs</p>

        <!-- Filters Bar -->
        <form class="search-filters-bar fade-in" action="<?= baseUrl('search') ?>" method="GET">
            <div class="form-group">
                <label class="form-label">Brand</label>
                <select name="brand" class="form-select">
                    <option value="">All Brands</option>
                    <?php
                    $brandList = ['Toyota', 'BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Honda', 'Ford', 'Nissan'];
                    foreach ($brandList as $b):
                        ?>
                        <option value="<?= $b ?>" <?= ($filters['brand'] ?? '') === $b ? 'selected' : '' ?>>
                            <?= $b ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-input" placeholder="Any Model"
                    value="<?= sanitize($filters['model'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Min Price</label>
                <input type="number" name="min_price" class="form-input" placeholder="0 KWD"
                    value="<?= sanitize($filters['min_price'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Max Price</label>
                <input type="number" name="max_price" class="form-input" placeholder="Any"
                    value="<?= sanitize($filters['max_price'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Fuel Type</label>
                <select name="fuel_type" class="form-select">
                    <option value="">All</option>
                    <option value="Gasoline" <?= ($filters['fuel_type'] ?? '') === 'Gasoline' ? 'selected' : '' ?>>Gasoline
                    </option>
                    <option value="Diesel" <?= ($filters['fuel_type'] ?? '') === 'Diesel' ? 'selected' : '' ?>>Diesel
                    </option>
                    <option value="Hybrid" <?= ($filters['fuel_type'] ?? '') === 'Hybrid' ? 'selected' : '' ?>>Hybrid
                    </option>
                    <option value="Electric" <?= ($filters['fuel_type'] ?? '') === 'Electric' ? 'selected' : '' ?>>Electric
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Transmission</label>
                <select name="transmission" class="form-select">
                    <option value="">All</option>
                    <option value="Automatic" <?= ($filters['transmission'] ?? '') === 'Automatic' ? 'selected' : '' ?>>
                        Automatic</option>
                    <option value="Manual" <?= ($filters['transmission'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Min Year</label>
                <input type="number" name="min_year" class="form-input" placeholder="e.g. 2018"
                    value="<?= sanitize($filters['min_year'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Max Mileage</label>
                <input type="number" name="max_mileage" class="form-input" placeholder="e.g. 50000"
                    value="<?= sanitize($filters['max_mileage'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Sort By</label>
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest First
                    </option>
                    <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low
                        to High</option>
                    <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Price:
                        High to Low</option>
                    <option value="mileage_low" <?= ($filters['sort'] ?? '') === 'mileage_low' ? 'selected' : '' ?>>Lowest
                        Mileage</option>
                    <option value="year_new" <?= ($filters['sort'] ?? '') === 'year_new' ? 'selected' : '' ?>>Newest Year
                    </option>
                    <option value="most_viewed" <?= ($filters['sort'] ?? '') === 'most_viewed' ? 'selected' : '' ?>>Most
                        Viewed</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:flex-end;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>

        <!-- Results Header -->
        <div class="search-results-header fade-in">
            <div class="search-results-count">
                Showing <strong>
                    <?= count($cars) ?>
                </strong> of <strong>
                    <?= $total ?>
                </strong> results
            </div>
        </div>

        <!-- Car Grid -->
        <div class="cars-grid stagger-children">
            <?php if (empty($cars)): ?>
                <div class="empty-state" style="grid-column:1/-1;">
                    <i class="fas fa-search"></i>
                    <h3>No cars found</h3>
                    <p>Try adjusting your search filters</p>
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
                            <?php if (isLoggedIn()): ?>
                                <button class="car-card-fav"
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

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $queryParams = $filters;
                    $queryParams['page'] = $i;
                    $url = baseUrl('search') . '?' . http_build_query(array_filter($queryParams));
                    ?>
                    <?php if ($i === $page): ?>
                        <span class="active-page">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="<?= $url ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>