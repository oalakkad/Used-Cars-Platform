<?php $pageTitle = 'Compare Cars'; ?>
<?php require __DIR__ . '/header.php'; ?>

<style>
    .compare-page {
        padding: 40px 0 80px;
        padding-top: calc(var(--header-height) + 40px);
    }

    .compare-empty {
        text-align: center;
        padding: 80px 20px;
    }

    .compare-empty i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        display: block;
    }

    .compare-empty h2 {
        font-size: 1.5rem;
        margin-bottom: 8px;
    }

    .compare-empty p {
        color: var(--text-secondary);
        margin-bottom: 24px;
    }

    .compare-table-wrapper {
        overflow-x: auto;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        background: var(--bg-card);
    }

    .compare-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .compare-table th,
    .compare-table td {
        padding: 16px 20px;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .compare-table th {
        background: var(--bg-secondary);
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .compare-table td {
        font-size: 0.95rem;
        color: var(--text-primary);
    }

    .compare-table tr:last-child td {
        border-bottom: none;
    }

    .compare-table .spec-label {
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-secondary);
        width: 160px;
    }

    .compare-table tr:hover td:not(.spec-label) {
        background: rgba(79, 70, 229, 0.03);
    }

    .compare-car-header {
        padding: 24px 16px;
    }

    .compare-car-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin-bottom: 12px;
    }

    .compare-car-name {
        font-weight: 800;
        font-size: 1.05rem;
        font-family: var(--font-display);
        margin-bottom: 4px;
    }

    .compare-car-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .compare-remove {
        margin-top: 10px;
    }

    .compare-best {
        background: rgba(34, 197, 94, 0.08) !important;
        color: var(--success);
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .compare-table .spec-label {
            width: 120px;
            font-size: 0.8rem;
            padding: 12px;
        }

        .compare-table td {
            padding: 12px;
            font-size: 0.85rem;
        }
    }
</style>

<section class="compare-page">
    <div class="container">
        <div class="fade-in" style="text-align:center;margin-bottom:2rem;">
            <h1 class="section-title"><i class="fas fa-columns" style="color:var(--text-primary);"></i> Compare Cars
            </h1>
            <p class="section-subtitle">Side-by-side comparison of your selected vehicles</p>
        </div>

        <?php if (empty($compareCars) || count($compareCars) < 2): ?>
            <div class="compare-empty fade-in">
                <i class="fas fa-exchange-alt"></i>
                <h2>No cars to compare</h2>
                <p>Browse car listings and click "Compare" to add cars here. You need at least 2 cars.</p>
                <a href="<?= baseUrl('search') ?>" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Cars
                </a>
            </div>
        <?php else: ?>
            <?php
            // Helper to find best value
            function findBest($cars, $key, $direction)
            {
                $best = null;
                $bestIdx = -1;
                foreach ($cars as $i => $car) {
                    $val = floatval($car[$key] ?? 0);
                    if (
                        $best === null ||
                        ($direction === 'low' && $val < $best) ||
                        ($direction === 'high' && $val > $best)
                    ) {
                        $best = $val;
                        $bestIdx = $i;
                    }
                }
                return $bestIdx;
            }

            $specs = [
                ['key' => 'price', 'label' => 'Price', 'icon' => 'tag', 'best' => 'low'],
                ['key' => 'year', 'label' => 'Year', 'icon' => 'calendar-alt', 'best' => 'high'],
                ['key' => 'mileage', 'label' => 'Mileage', 'icon' => 'tachometer-alt', 'best' => 'low'],
                ['key' => 'fuel_type', 'label' => 'Fuel Type', 'icon' => 'gas-pump', 'best' => null],
                ['key' => 'transmission', 'label' => 'Transmission', 'icon' => 'cog', 'best' => null],
                ['key' => 'condition_status', 'label' => 'Condition', 'icon' => 'check-circle', 'best' => null],
                ['key' => 'location', 'label' => 'Location', 'icon' => 'map-marker-alt', 'best' => null],
            ];
            ?>

            <div class="compare-table-wrapper fade-in">
                <table class="compare-table">
                    <!-- Header: Car images & names -->
                    <tr>
                        <th class="spec-label">Vehicle</th>
                        <?php foreach ($compareCars as $car): ?>
                            <th class="compare-car-header">
                                <?php if (!empty($car['primary_image'])): ?>
                                    <img src="<?= asset('uploads/' . $car['primary_image']) ?>" class="compare-car-img"
                                        alt="<?= sanitize($car['brand'] . ' ' . $car['model']) ?>">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=400&q=80"
                                        class="compare-car-img" alt="Car placeholder">
                                <?php endif; ?>
                                <div class="compare-car-name"><?= sanitize($car['brand'] . ' ' . $car['model']) ?></div>
                                <div class="compare-car-price"><?= number_format($car['price'], 0) ?> KWD</div>
                                <a href="<?= baseUrl('car/' . $car['id']) ?>" class="btn btn-secondary btn-sm compare-remove"
                                    style="margin-top:8px;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Spec rows -->
                    <?php foreach ($specs as $spec): ?>
                        <?php $bestIdx = $spec['best'] ? findBest($compareCars, $spec['key'], $spec['best']) : -1; ?>
                        <tr>
                            <td class="spec-label">
                                <i class="fas fa-<?= $spec['icon'] ?>" style="margin-right:6px;color:var(--primary);"></i>
                                <?= $spec['label'] ?>
                            </td>
                            <?php foreach ($compareCars as $i => $car): ?>
                                <?php
                                $val = $car[$spec['key']] ?? '—';
                                if ($spec['key'] === 'price')
                                    $val = '$' . number_format($car['price'], 0);
                                if ($spec['key'] === 'mileage')
                                    $val = number_format($car['mileage']) . ' km';
                                $isBest = ($i === $bestIdx && $spec['best']);
                                ?>
                                <td class="<?= $isBest ? 'compare-best' : '' ?>">
                                    <?= sanitize($val) ?>
                                    <?= $isBest ? ' <i class="fas fa-trophy" style="font-size:0.7rem;"></i>' : '' ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div style="text-align:center;margin-top:24px;">
                <button class="btn btn-secondary"
                    onclick="localStorage.removeItem('automarket_compare');window.location.href='<?= baseUrl('search') ?>';">
                    <i class="fas fa-trash"></i> Clear & Browse More
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>