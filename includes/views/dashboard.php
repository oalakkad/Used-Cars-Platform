<?php $pageTitle = 'Dashboard'; ?>
<?php require __DIR__ . '/header.php'; ?>

<section class="dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header fade-in">
            <div>
                <h1 class="section-title">Dashboard</h1>
                <?php
                $hour = (int) date('G');
                if ($hour < 12) {
                    $greeting = 'Good Morning';
                    $greetIcon = 'fas fa-sun';
                } elseif ($hour < 17) {
                    $greeting = 'Good Afternoon';
                    $greetIcon = 'fas fa-cloud-sun';
                } else {
                    $greeting = 'Good Evening';
                    $greetIcon = 'fas fa-moon';
                }
                ?>
                <p class="section-subtitle" style="margin-bottom:0;">
                    <i class="<?= $greetIcon ?>" style="color:var(--accent);margin-right:6px;"></i>
                    <?= $greeting ?>,
                    <?= sanitize(currentUserName()) ?>!
                </p>
            </div>
            <a href="<?= baseUrl('cars/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> List a Car
            </a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success fade-in"><i class="fas fa-check-circle"></i>
                <?= sanitize($success) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error fade-in"><i class="fas fa-exclamation-circle"></i>
                <?= sanitize($error) ?>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="dashboard-stats fade-in">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                <div>
                    <div class="stat-value">
                        <?= $totalCars ?>
                    </div>
                    <div class="stat-label">Cars Listed</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-heart"></i></div>
                <div>
                    <div class="stat-value">
                        <?= $totalFavorites ?>
                    </div>
                    <div class="stat-label">Saved Favorites</div>
                </div>
            </div>
        </div>

        <!-- My Listings -->
        <div class="dashboard-section fade-in">
            <div class="dashboard-section-header">
                <h2><i class="fas fa-car" style="color:var(--primary);margin-right:8px;"></i> My Listings</h2>
            </div>

            <?php if (empty($myCars)): ?>
                <div class="empty-state"
                    style="background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border-color);">
                    <i class="fas fa-car-side"></i>
                    <h3>No listings yet</h3>
                    <p>Start selling by listing your first car</p>
                    <a href="<?= baseUrl('cars/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> List a Car
                    </a>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Car</th>
                                <th>Price</th>
                                <th>Year</th>
                                <th>Mileage</th>
                                <th>Views</th>
                                <th>WA Clicks</th>
                                <th>Listed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myCars as $car): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= sanitize($car['brand'] . ' ' . $car['model']) ?>
                                        </strong>
                                    </td>
                                    <td>$
                                        <?= number_format($car['price'], 0) ?>
                                    </td>
                                    <td>
                                        <?= $car['year'] ?>
                                    </td>
                                    <td>
                                        <?= number_format($car['mileage']) ?> km
                                    </td>
                                    <td>
                                        <i class="fas fa-eye" style="color:var(--info);margin-right:4px;"></i>
                                        <?= number_format($car['views_count'] ?? 0) ?>
                                    </td>
                                    <td>
                                        <i class="fab fa-whatsapp" style="color:#25d366;margin-right:4px;"></i>
                                        <?= number_format($car['whatsapp_clicks'] ?? 0) ?>
                                    </td>
                                    <td>
                                        <?= date('M j, Y', strtotime($car['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="<?= baseUrl('car/' . $car['id']) ?>" class="btn btn-secondary btn-sm"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= baseUrl('cars/' . $car['id'] . '/edit') ?>"
                                                class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?= baseUrl('cars/' . $car['id'] . '/delete') ?>" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- My Favorites -->
        <div class="dashboard-section fade-in">
            <div class="dashboard-section-header">
                <h2><i class="fas fa-heart" style="color:var(--danger);margin-right:8px;"></i> My Favorites</h2>
            </div>

            <?php if (empty($myFavorites)): ?>
                <div class="empty-state"
                    style="background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border-color);">
                    <i class="fas fa-heart"></i>
                    <h3>No favorites yet</h3>
                    <p>Browse cars and save the ones you love</p>
                    <a href="<?= baseUrl('search') ?>" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse Cars
                    </a>
                </div>
            <?php else: ?>
                <style>
                    .favorites-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 24px;
                    }

                    @media (max-width: 1024px) {
                        .favorites-grid {
                            grid-template-columns: repeat(2, 1fr);
                        }
                    }

                    @media (max-width: 576px) {
                        .favorites-grid {
                            grid-template-columns: 1fr;
                        }
                    }
                </style>
                <div class="favorites-grid">
                    <?php foreach ($myFavorites as $car): ?>
                        <div class="car-card">
                            <a href="<?= baseUrl('car/' . $car['id']) ?>" style="text-decoration:none;">
                                <div class="car-card-image">
                                    <?php if (!empty($car['primary_image'])): ?>
                                        <img src="<?= asset('uploads/' . $car['primary_image']) ?>"
                                            alt="<?= sanitize($car['brand'] . ' ' . $car['model']) ?>">
                                    <?php else: ?>
                                        <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=600&q=80"
                                            alt="Car placeholder">
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
                                </div>
                            </a>
                            <div style="padding:0 20px 16px;">
                                <form action="<?= baseUrl('favorites/' . $car['id'] . '/remove') ?>" method="POST">
                                    <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">
                                        <i class="fas fa-heart-broken"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>