<?php $pageTitle = 'Admin Dashboard'; ?>
<?php require __DIR__ . '/header.php'; ?>

<style>
    .admin-page {
        padding: 60px 0;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .admin-header h1 {
        font-family: var(--font-display);
        font-size: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card .stat-icon {
        font-size: 2rem;
        margin-bottom: 12px;
    }

    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        font-family: var(--font-display);
        color: var(--text-primary);
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    .admin-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 28px;
        margin-bottom: 32px;
    }

    .admin-section h2 {
        font-family: var(--font-display);
        font-size: 1.3rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-chart {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .brand-bar {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-bar .bar-label {
        min-width: 120px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    .brand-bar .bar-fill {
        height: 28px;
        border-radius: var(--radius-md);
        background: linear-gradient(90deg, var(--primary), var(--cta));
        transition: width 0.8s ease;
        display: flex;
        align-items: center;
        padding-left: 10px;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .listings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .listings-table th,
    .listings-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
    }

    .listings-table th {
        background: var(--bg-input);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: var(--text-muted);
    }

    .listings-table tr:hover {
        background: var(--bg-input);
    }

    .listings-table img {
        width: 50px;
        height: 35px;
        object-fit: cover;
        border-radius: var(--radius-sm);
    }

    .featured-btn {
        background: none;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 6px 14px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
        color: var(--text-secondary);
    }

    .featured-btn.active {
        border-color: var(--warning);
        color: var(--warning);
        background: rgba(245, 158, 11, 0.1);
    }

    .featured-btn:hover {
        border-color: var(--warning);
    }

    .badge-featured {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .listings-table {
            font-size: 0.8rem;
        }

        .listings-table th:nth-child(4),
        .listings-table td:nth-child(4),
        .listings-table th:nth-child(5),
        .listings-table td:nth-child(5) {
            display: none;
        }
    }
</style>

<section class="admin-page">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-shield-alt" style="color:var(--primary);margin-right:8px;"></i> Admin Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--primary);"><i class="fas fa-car"></i></div>
                <div class="stat-value">
                    <?= number_format($stats['total_cars']) ?>
                </div>
                <div class="stat-label">Total Listings</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--cta);"><i class="fas fa-users"></i></div>
                <div class="stat-value">
                    <?= number_format($stats['total_users']) ?>
                </div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--info);"><i class="fas fa-eye"></i></div>
                <div class="stat-value">
                    <?= number_format($stats['total_views']) ?>
                </div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--danger);"><i class="fas fa-heart"></i></div>
                <div class="stat-value">
                    <?= number_format($stats['total_favorites']) ?>
                </div>
                <div class="stat-label">Total Favorites</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--warning);"><i class="fas fa-star"></i></div>
                <div class="stat-value">
                    <?= number_format($stats['featured_count']) ?>
                </div>
                <div class="stat-label">Featured</div>
            </div>
        </div>

        <!-- Listings Per Brand Chart -->
        <div class="admin-section">
            <h2><i class="fas fa-chart-bar" style="color:var(--primary);"></i> Listings Per Brand</h2>
            <div class="brand-chart">
                <?php
                $maxCount = max(array_column($brandsData, 'count'));
                foreach ($brandsData as $bd):
                    $pct = $maxCount > 0 ? ($bd['count'] / $maxCount) * 100 : 0;
                    ?>
                    <div class="brand-bar">
                        <span class="bar-label">
                            <?= sanitize($bd['brand']) ?>
                        </span>
                        <div class="bar-fill" style="width: <?= $pct ?>%; min-width: 40px;">
                            <?= $bd['count'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Viewed -->
        <div class="admin-section">
            <h2><i class="fas fa-fire" style="color:var(--danger);"></i> Top Viewed Listings</h2>
            <div style="overflow-x:auto;">
                <table class="listings-table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Car</th>
                            <th>Price</th>
                            <th>Views</th>
                            <th>WA Clicks</th>
                            <th>Seller</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topViewed as $tv): ?>
                            <tr>
                                <td>
                                    <?php if ($tv['primary_image']): ?>
                                        <img src="<?= baseUrl('uploads/' . $tv['primary_image']) ?>" alt="">
                                    <?php else: ?>
                                        <div
                                            style="width:50px;height:35px;background:var(--bg-input);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-image" style="color:var(--text-muted);"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><strong>
                                        <?= sanitize($tv['brand']) ?>
                                        <?= sanitize($tv['model']) ?>
                                    </strong> (
                                    <?= $tv['year'] ?>)
                                </td>
                                <td>
                                    <?= number_format($tv['price']) ?> KWD
                                </td>
                                <td><i class="fas fa-eye" style="color:var(--info);margin-right:4px;"></i>
                                    <?= number_format($tv['views_count']) ?>
                                </td>
                                <td><i class="fab fa-whatsapp" style="color:#25d366;margin-right:4px;"></i>
                                    <?= number_format($tv['whatsapp_clicks']) ?>
                                </td>
                                <td>
                                    <?= sanitize($tv['seller_name']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Listings Management -->
        <div class="admin-section">
            <h2><i class="fas fa-list" style="color:var(--cta);"></i> All Listings</h2>
            <div style="overflow-x:auto;">
                <table class="listings-table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Car</th>
                            <th>Price</th>
                            <th>Views</th>
                            <th>Seller</th>
                            <th>Featured</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allCars as $ac): ?>
                            <tr>
                                <td>
                                    <?php if ($ac['primary_image']): ?>
                                        <img src="<?= baseUrl('uploads/' . $ac['primary_image']) ?>" alt="">
                                    <?php else: ?>
                                        <div
                                            style="width:50px;height:35px;background:var(--bg-input);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-image" style="color:var(--text-muted);"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= baseUrl('car/' . $ac['id']) ?>"
                                        style="color:var(--text-primary); font-weight:600;">
                                        <?= sanitize($ac['brand']) ?>
                                        <?= sanitize($ac['model']) ?>
                                    </a>
                                    <div style="font-size:0.8rem;color:var(--text-muted);">
                                        <?= $ac['year'] ?> ·
                                        <?= number_format($ac['mileage']) ?> km
                                    </div>
                                </td>
                                <td style="font-weight:600;">
                                    <?= number_format($ac['price']) ?> KWD
                                </td>
                                <td>
                                    <?= number_format($ac['views_count']) ?>
                                </td>
                                <td>
                                    <a href="<?= baseUrl('seller/' . $ac['user_id']) ?>" style="color:var(--primary);">
                                        <?= sanitize($ac['seller_name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <button class="featured-btn <?= $ac['is_featured'] ? 'active' : '' ?>"
                                        onclick="toggleFeatured(<?= $ac['id'] ?>, this)">
                                        <i class="fas fa-star"></i>
                                        <?= $ac['is_featured'] ? 'Featured' : 'Feature' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFeatured(carId, btn) {
        fetch('<?= baseUrl('admin/toggle-featured') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ car_id: carId })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    btn.classList.toggle('active');
                    btn.innerHTML = data.is_featured
                        ? '<i class="fas fa-star"></i> Featured'
                        : '<i class="fas fa-star"></i> Feature';
                }
            });
    }
</script>

<?php require __DIR__ . '/footer.php'; ?>