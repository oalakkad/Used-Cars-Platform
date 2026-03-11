<?php $pageTitle = 'Car Details'; ?>
<?php require __DIR__ . '/header.php'; ?>

<?php if ($car): ?>
    <section class="car-details">
        <div class="container">
            <div class="details-grid">
                <!-- Gallery Column -->
                <div class="fade-in">
                    <!-- Main Image -->
                    <div class="gallery-main" id="galleryMain">
                        <?php if (!empty($images)): ?>
                            <img src="<?= asset('uploads/' . $images[0]['image_path']) ?>"
                                alt="<?= sanitize($car['brand'] . ' ' . $car['model']) ?>" id="mainImage">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&q=80"
                                alt="Car placeholder" id="mainImage">
                        <?php endif; ?>
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($images) > 1): ?>
                        <div class="gallery-thumbs">
                            <?php foreach ($images as $index => $img): ?>
                                <div class="gallery-thumb <?= $index === 0 ? 'active' : '' ?>"
                                    onclick="changeMainImage('<?= asset('uploads/' . $img['image_path']) ?>', this)">
                                    <img src="<?= asset('uploads/' . $img['image_path']) ?>" alt="Thumbnail">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Description -->
                    <div class="details-description" style="margin-top:24px;">
                        <h3>Description</h3>
                        <p>
                            <?= nl2br(sanitize($car['description'] ?? 'No description provided.')) ?>
                        </p>
                    </div>

                    <!-- Map -->
                    <?php if (!empty($car['location'])): ?>
                        <div class="details-map" style="margin-top:24px;">
                            <iframe src="https://maps.google.com/maps?q=<?= urlencode($car['location']) ?>&output=embed"
                                allowfullscreen loading="lazy"></iframe>
                        </div>
                    <?php endif; ?>

                    <!-- Damage Inspection -->
                    <?php
                    $damageData = $car['damage_data'] ?? '[]';
                    if (!empty($damageData) && $damageData !== '[]'):
                        $dots = json_decode($damageData, true);
                        if (is_array($dots) && count($dots) > 0):
                            ?>
                            <div class="details-description" style="margin-top:24px;">
                                <h3>Damage Inspection</h3>
                                <p class="text-secondary" style="font-size:0.9rem; margin-bottom:16px;">
                                    <i class="fas fa-info-circle text-primary"></i> Red pulsing dots indicate reported damage areas
                                    by the seller.
                                </p>
                                <div class="inspection-container" style="background:var(--bg-primary);">
                                    <div class="inspection-wireframe" style="cursor:default;">
                                        <svg viewBox="0 0 100 240" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M25,20 L75,20 C85,20 90,30 90,45 L95,95 C98,110 98,160 95,190 L90,215 C85,230 75,235 50,235 C25,235 15,230 10,215 L5,190 C2,160 2,110 5,95 L10,45 C10,30 15,20 25,20 Z" />
                                            <!-- Windows -->
                                            <path d="M20,65 L80,65 L70,110 L30,110 Z" />
                                            <path d="M20,185 L80,185 L70,140 L30,140 Z" />
                                        </svg>
                                        <?php foreach ($dots as $index => $dot): ?>
                                            <?php
                                            $hasImage = !empty($dot['image_path']);
                                            $onClick = $hasImage ? "onclick=\"openDamageModal('" . asset('uploads/' . $dot['image_path']) . "')\"" : "";
                                            $cursor = $hasImage ? "pointer" : "default";
                                            $title = $hasImage ? "Reported Damage (Click to view photo)" : "Reported Damage";
                                            ?>
                                            <div class="damage-dot <?= $hasImage ? 'has-photo' : '' ?>"
                                                style="left: <?= (float) $dot['x'] ?>%; top: <?= (float) $dot['y'] ?>%; cursor:<?= $cursor ?>;"
                                                title="<?= $title ?>" <?= $onClick ?>>
                                                <?php if ($hasImage): ?>
                                                    <i class="fas fa-camera"
                                                        style="font-size: 8px; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    endif;
                    ?>
                </div>

                <!-- Info Panel -->
                <div class="details-info fade-in" style="transition-delay:0.15s;">
                    <div class="details-price">
                        <?= number_format($car['price'], 0) ?> KWD
                    </div>
                    <h1 class="details-title">
                        <?= sanitize($car['brand'] . ' ' . $car['model']) ?>
                        <?= $car['year'] ?>
                    </h1>

                    <!-- Specs Grid -->
                    <div class="details-specs-grid">
                        <div class="detail-spec">
                            <div class="detail-spec-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="detail-spec-text">
                                <span class="detail-spec-label">Year</span>
                                <span class="detail-spec-value">
                                    <?= $car['year'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-spec">
                            <div class="detail-spec-icon"><i class="fas fa-tachometer-alt"></i></div>
                            <div class="detail-spec-text">
                                <span class="detail-spec-label">Mileage</span>
                                <span class="detail-spec-value">
                                    <?= number_format($car['mileage']) ?> km
                                </span>
                            </div>
                        </div>
                        <div class="detail-spec">
                            <div class="detail-spec-icon"><i class="fas fa-gas-pump"></i></div>
                            <div class="detail-spec-text">
                                <span class="detail-spec-label">Fuel Type</span>
                                <span class="detail-spec-value">
                                    <?= sanitize($car['fuel_type']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-spec">
                            <div class="detail-spec-icon"><i class="fas fa-cog"></i></div>
                            <div class="detail-spec-text">
                                <span class="detail-spec-label">Transmission</span>
                                <span class="detail-spec-value">
                                    <?= sanitize($car['transmission']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-spec">
                            <div class="detail-spec-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="detail-spec-text">
                                <span class="detail-spec-label">Condition</span>
                                <span class="detail-spec-value">
                                    <?= sanitize($car['condition_status']) ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($car['location'])): ?>
                            <div class="detail-spec">
                                <div class="detail-spec-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="detail-spec-text">
                                    <span class="detail-spec-label">Location</span>
                                    <span class="detail-spec-value">
                                        <?= sanitize($car['location']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Seller Info -->
                    <div class="details-seller">
                        <div class="seller-avatar">
                            <?= strtoupper(substr($car['seller_name'], 0, 1)) ?>
                        </div>
                        <div class="seller-info">
                            <a href="<?= baseUrl('seller/' . $car['user_id']) ?>" class="seller-name"
                                style="color:var(--text-primary); text-decoration:none;">
                                <?= sanitize($car['seller_name']) ?>
                            </a>
                            <div class="seller-label">Private Seller · <a href="<?= baseUrl('seller/' . $car['user_id']) ?>"
                                    style="color:var(--primary); font-size:0.8rem;">View all listings →</a></div>
                        </div>
                    </div>

                    <!-- Market Price Analysis -->
                    <?php if (isset($priceData) && $priceData['count'] > 1): ?>
                        <div
                            style="background:var(--bg-input); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:20px; margin-bottom:16px;">
                            <h4
                                style="margin:0 0 12px; font-size:1rem; display:flex; align-items:center; gap:8px; color:var(--text-primary);">
                                <i class="fas fa-chart-line" style="color:var(--primary);"></i> Market Price Analysis
                            </h4>
                            <?php
                            $avgPrice = (float) $priceData['avg_price'];
                            $carPrice = (float) $car['price'];
                            $diff = $carPrice - $avgPrice;
                            $diffPct = $avgPrice > 0 ? ($diff / $avgPrice) * 100 : 0;
                            $priceLabel = $diffPct < -5 ? 'Below Market' : ($diffPct > 5 ? 'Above Market' : 'Fair Price');
                            $priceColor = $diffPct < -5 ? 'var(--success)' : ($diffPct > 5 ? 'var(--danger)' : 'var(--warning)');
                            ?>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <span style="font-size:0.85rem; color:var(--text-muted);">Based on <?= $priceData['count'] ?>
                                    similar listings</span>
                                <span
                                    style="font-weight:700; color:<?= $priceColor ?>; font-size:0.9rem;"><?= $priceLabel ?></span>
                            </div>
                            <div
                                style="background:var(--bg-card); border-radius:var(--radius-md); padding:12px; display:flex; justify-content:space-around; text-align:center;">
                                <div>
                                    <div
                                        style="font-size:0.7rem; text-transform:uppercase; color:var(--text-muted); letter-spacing:0.5px;">
                                        This Car</div>
                                    <div style="font-weight:700; color:var(--text-primary);"><?= number_format($carPrice) ?>
                                    </div>
                                </div>
                                <div style="border-left:1px solid var(--border-color);"></div>
                                <div>
                                    <div
                                        style="font-size:0.7rem; text-transform:uppercase; color:var(--text-muted); letter-spacing:0.5px;">
                                        Avg Price</div>
                                    <div style="font-weight:700; color:var(--text-primary);"><?= number_format($avgPrice) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Car History Timeline -->
                    <div
                        style="background:var(--bg-input); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:20px; margin-bottom:16px;">
                        <h4
                            style="margin:0 0 12px; font-size:1rem; display:flex; align-items:center; gap:8px; color:var(--text-primary);">
                            <i class="fas fa-history" style="color:var(--primary);"></i> Listing Timeline
                        </h4>
                        <?php $daysOnMarket = max(1, (int) ((time() - strtotime($car['created_at'])) / 86400)); ?>
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div
                                    style="width:10px; height:10px; border-radius:50%; background:var(--cta); flex-shrink:0;">
                                </div>
                                <span style="font-size:0.85rem; color:var(--text-secondary);">Listed on
                                    <strong><?= date('M j, Y', strtotime($car['created_at'])) ?></strong></span>
                            </div>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div
                                    style="width:10px; height:10px; border-radius:50%; background:var(--primary); flex-shrink:0;">
                                </div>
                                <span style="font-size:0.85rem; color:var(--text-secondary);">
                                    <strong><?= $daysOnMarket ?></strong> day<?= $daysOnMarket !== 1 ? 's' : '' ?> on market
                                </span>
                            </div>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div
                                    style="width:10px; height:10px; border-radius:50%; background:var(--info); flex-shrink:0;">
                                </div>
                                <span style="font-size:0.85rem; color:var(--text-secondary);">
                                    <strong><?= number_format($car['views_count'] ?? 0) ?></strong> views
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="details-actions">
                        <?php if (!empty($car['whatsapp_phone'])): ?>
                            <?php
                            // Ensure the phone has the Kuwait country code prefix if it's not already there
                            $cleanPhone = preg_replace('/[^0-9]/', '', $car['whatsapp_phone']);
                            if (strlen($cleanPhone) === 8) {
                                $cleanPhone = '965' . $cleanPhone;
                            }
                            ?>
                            <a href="https://wa.me/<?= $cleanPhone ?>?text=<?= urlencode('Hi, I\'m interested in your ' . $car['brand'] . ' ' . $car['model'] . ' ' . $car['year'] . ' listing: ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>"
                                target="_blank" class="btn btn-primary btn-lg"
                                style="width:100%;background:#25D366;color:#fff;border:none;">
                                <i class="fab fa-whatsapp"></i> WhatsApp Seller
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-secondary btn-lg" style="width:100%;" onclick="openEMICalculator()">
                            <i class="fas fa-calculator"></i> EMI Calculator
                        </button>
                        <button class="btn btn-secondary btn-lg" style="width:100%;"
                            onclick="addToCompare(<?= $car['id'] ?>, '<?= sanitize($car['brand'] . ' ' . $car['model']) ?>')">
                            <i class="fas fa-exchange-alt"></i> Compare
                        </button>
                        <?php if (isLoggedIn()): ?>
                            <button class="btn btn-secondary btn-lg <?= $isFavorited ? 'active' : '' ?>" style="width:100%;"
                                onclick="toggleFavorite(<?= $car['id'] ?>, this)" id="favBtn">
                                <i class="fas fa-heart"></i>
                                <?= $isFavorited ? 'Saved to Favorites' : 'Add to Favorites' ?>
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-secondary btn-lg" style="width:100%;" onclick="shareListing()">
                            <i class="fas fa-share-alt"></i> Share Listing
                        </button>
                    </div>

                    <!-- Share Dropdown -->
                    <div id="shareDropdown"
                        style="display:none; background:var(--bg-input); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:12px; margin-top:8px;">
                        <div style="display:flex; gap:8px; justify-content:center;">
                            <a href="https://wa.me/?text=<?= urlencode('Check out this ' . $car['brand'] . ' ' . $car['model'] . ' ' . $car['year'] . ': ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                                target="_blank"
                                style="display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;background:#25D366;color:#fff;font-size:1.2rem;"><i
                                    class="fab fa-whatsapp"></i></a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode('Check out this ' . $car['brand'] . ' ' . $car['model'] . ' ' . $car['year']) ?>&url=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                                target="_blank"
                                style="display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;background:#1DA1F2;color:#fff;font-size:1.2rem;"><i
                                    class="fab fa-twitter"></i></a>
                            <button onclick="copyLink()"
                                style="display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;background:var(--bg-card);border:1px solid var(--border-color);color:var(--text-primary);font-size:1.2rem;cursor:pointer;"
                                title="Copy Link"><i class="fas fa-link"></i></button>
                        </div>
                        <div id="copyMsg"
                            style="text-align:center; font-size:0.8rem; color:var(--success); margin-top:8px; display:none;">
                            Link copied!</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ===== EMI CALCULATOR MODAL ===== -->
<div class="emi-overlay" id="emiOverlay" onclick="if(event.target===this)closeEMICalculator()">
    <div class="emi-modal">
        <div class="emi-header">
            <h2><i class="fas fa-calculator" style="color:var(--text-primary);margin-right:8px;"></i> EMI Calculator
            </h2>
            <button class="emi-close" onclick="closeEMICalculator()"><i class="fas fa-times"></i></button>
        </div>
        <div class="emi-body">
            <div class="emi-result-card">
                <div class="emi-monthly-label">Estimated Monthly Payment</div>
                <div class="emi-monthly-value" id="emiResult">$0</div>
                <div class="emi-total" id="emiTotal">Total payable: $0</div>
            </div>

            <div class="emi-slider-group">
                <div class="emi-slider-header">
                    <label>Car Price</label>
                    <span class="emi-slider-value" id="priceDisplay"><?= number_format($car['price'] ?? 0, 0) ?>
                        KWD</span>
                </div>
                <input type="range" class="emi-slider" id="emiPrice" min="1000" max="500000"
                    value="<?= $car['price'] ?? 25000 ?>" step="500" oninput="calculateEMI()">
            </div>

            <div class="emi-slider-group">
                <div class="emi-slider-header">
                    <label>Down Payment</label>
                    <span class="emi-slider-value"
                        id="downDisplay">$<?= number_format(($car['price'] ?? 25000) * 0.2, 0) ?></span>
                </div>
                <input type="range" class="emi-slider" id="emiDown" min="0" max="<?= ($car['price'] ?? 25000) * 0.8 ?>"
                    value="<?= ($car['price'] ?? 25000) * 0.2 ?>" step="500" oninput="calculateEMI()">
            </div>

            <div class="emi-slider-group">
                <div class="emi-slider-header">
                    <label>Interest Rate (%)</label>
                    <span class="emi-slider-value" id="rateDisplay">5%</span>
                </div>
                <input type="range" class="emi-slider" id="emiRate" min="1" max="20" value="5" step="0.5"
                    oninput="calculateEMI()">
            </div>

            <div class="emi-slider-group">
                <div class="emi-slider-header">
                    <label>Loan Term</label>
                    <span class="emi-slider-value" id="termDisplay">60 months</span>
                </div>
                <input type="range" class="emi-slider" id="emiTerm" min="12" max="84" value="60" step="6"
                    oninput="calculateEMI()">
            </div>

            <div class="emi-breakdown" id="emiBreakdown">
                <div class="emi-breakdown-row">
                    <span>Principal Amount</span>
                    <span id="emiPrincipal">$0</span>
                </div>
                <div class="emi-breakdown-row">
                    <span>Total Interest</span>
                    <span id="emiInterest">$0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== COMPARE FLOATING BAR ===== -->
<div class="compare-bar" id="compareBar" style="display:none;">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <strong><i class="fas fa-exchange-alt" style="margin-right:6px;"></i> <span id="compareCount">0</span> cars
                selected</strong>
            <span style="color:var(--text-muted);margin-left:8px;font-size:0.85rem;">(select 2-3 to compare)</span>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="<?= baseUrl('compare') ?>" class="btn btn-primary btn-sm" id="compareBtn">
                <i class="fas fa-columns"></i> Compare Now
            </a>
            <button class="btn btn-secondary btn-sm" onclick="clearCompare()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>
</div>

<!-- ===== DAMAGE PHOTO MODAL ===== -->
<div class="emi-overlay" id="damageModalOverlay" onclick="if(event.target===this)closeDamageModal()">
    <div class="emi-modal"
        style="max-width: 600px; padding: 0; overflow: hidden; background: transparent; box-shadow: none;">
        <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; background: var(--bg-card);">
            <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                <button onclick="closeDamageModal()"
                    style="background: rgba(0,0,0,0.5); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; backdrop-filter: blur(4px);">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <img id="damageModalImage" src="" alt="Damage Photo"
                style="width: 100%; height: auto; max-height: 80vh; object-fit: contain; display: block; background: #000;">
        </div>
    </div>
</div>

<style>
    /* EMI Modal */
    .emi-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .emi-overlay.open {
        display: flex;
    }

    .emi-modal {
        background: var(--bg-card);
        border-radius: var(--radius-xl);
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .emi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color);
    }

    .emi-header h2 {
        font-size: 1.2rem;
        font-weight: 800;
        margin: 0;
        font-family: var(--font-display);
    }

    .emi-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: var(--text-muted);
        cursor: pointer;
        padding: 8px;
        border-radius: var(--radius-md);
        transition: all 0.2s;
    }

    .emi-close:hover {
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .emi-body {
        padding: 28px;
    }

    .emi-result-card {
        text-align: center;
        padding: 28px;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        border-radius: var(--radius-lg);
        margin-bottom: 28px;
        box-shadow: 0 8px 30px var(--primary-glow);
    }

    .emi-monthly-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .emi-monthly-value {
        color: #fff;
        font-size: 2.8rem;
        font-weight: 900;
        font-family: var(--font-display);
        line-height: 1.2;
        margin: 4px 0;
    }

    .emi-total {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
    }

    .emi-slider-group {
        margin-bottom: 22px;
    }

    .emi-slider-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .emi-slider-header label {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    .emi-slider-value {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .emi-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: var(--border-color);
        outline: none;
        transition: all 0.2s;
    }

    .emi-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--cta);
        cursor: pointer;
        box-shadow: 0 2px 8px var(--cta-glow);
        transition: transform 0.2s;
    }

    .emi-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }

    .emi-slider::-moz-range-thumb {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--cta);
        cursor: pointer;
        border: none;
    }

    .emi-breakdown {
        border-top: 1px solid var(--border-color);
        padding-top: 16px;
        margin-top: 8px;
    }

    .emi-breakdown-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .emi-breakdown-row span:last-child {
        font-weight: 700;
    }

    /* Compare Bar */
    .compare-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--bg-card);
        border-top: 2px solid var(--primary);
        padding: 14px 0;
        z-index: 900;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.3s ease;
    }

    @media(max-width:768px) {
        .emi-modal {
            max-width: 100%;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .emi-monthly-value {
            font-size: 2rem;
        }
    }
</style>

<script>
    function changeMainImage(src, thumbEl) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        thumbEl.classList.add('active');
    }

    /* Lightbox for main gallery */
    (function () {
        const galleryImages = [];
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                galleryImages.push('<?= asset('uploads/' . $img['image_path']) ?>');
            <?php endforeach; ?>
        <?php else: ?>
            galleryImages.push('https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&q=80');
        <?php endif; ?>

        const mainImg = document.getElementById('mainImage');
        if (mainImg) {
            mainImg.addEventListener('click', () => {
                const currentSrc = mainImg.src;
                const startIdx = galleryImages.findIndex(s => currentSrc.includes(s.split('/').pop())) || 0;
                initLightbox(galleryImages, Math.max(0, startIdx));
            });
        }
    })();
    function shareListing() {
        if (navigator.share) {
            navigator.share({ title: document.title, url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    }

    /* ===== EMI CALCULATOR ===== */
    function openEMICalculator() {
        document.getElementById('emiOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
        calculateEMI();
    }
    function closeEMICalculator() {
        document.getElementById('emiOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    function calculateEMI() {
        const price = parseFloat(document.getElementById('emiPrice').value);
        const down = parseFloat(document.getElementById('emiDown').value);
        const rate = parseFloat(document.getElementById('emiRate').value);
        const term = parseInt(document.getElementById('emiTerm').value);

        const principal = price - down;
        const monthlyRate = rate / 100 / 12;
        let emi = 0;
        if (monthlyRate > 0) {
            emi = principal * monthlyRate * Math.pow(1 + monthlyRate, term) / (Math.pow(1 + monthlyRate, term) - 1);
        } else {
            emi = principal / term;
        }
        const totalPayable = emi * term;
        const totalInterest = totalPayable - principal;

        document.getElementById('emiResult').textContent = Math.round(emi).toLocaleString() + ' KWD';
        document.getElementById('emiTotal').textContent = 'Total payable: ' + Math.round(totalPayable).toLocaleString() + ' KWD';
        document.getElementById('priceDisplay').textContent = price.toLocaleString() + ' KWD';
        document.getElementById('downDisplay').textContent = down.toLocaleString() + ' KWD';
        document.getElementById('rateDisplay').textContent = rate + '%';
        document.getElementById('termDisplay').textContent = term + ' months';
        document.getElementById('emiPrincipal').textContent = '$' + Math.round(principal).toLocaleString();
        document.getElementById('emiInterest').textContent = '$' + Math.round(totalInterest).toLocaleString();
    }

    /* ===== COMPARE SYSTEM ===== */
    function getCompareList() {
        return JSON.parse(localStorage.getItem('automarket_compare') || '[]');
    }
    function saveCompareList(list) {
        localStorage.setItem('automarket_compare', JSON.stringify(list));
    }
    function addToCompare(carId, carName) {
        let list = getCompareList();
        if (list.find(c => c.id === carId)) {
            list = list.filter(c => c.id !== carId);
            saveCompareList(list);
            updateCompareBar();
            alert('Removed from comparison');
            return;
        }
        if (list.length >= 3) {
            alert('You can compare up to 3 cars. Remove one first.');
            return;
        }
        list.push({ id: carId, name: carName });
        saveCompareList(list);
        updateCompareBar();
    }
    function clearCompare() {
        saveCompareList([]);
        updateCompareBar();
    }
    function updateCompareBar() {
        const list = getCompareList();
        const bar = document.getElementById('compareBar');
        const count = document.getElementById('compareCount');
        const btn = document.getElementById('compareBtn');
        if (list.length > 0) {
            bar.style.display = 'block';
            count.textContent = list.length;
            // Build compare URL with IDs
            const ids = list.map(c => c.id).join(',');
            btn.href = '<?= baseUrl('compare') ?>?ids=' + ids;
            btn.style.pointerEvents = list.length >= 2 ? 'auto' : 'none';
            btn.style.opacity = list.length >= 2 ? '1' : '0.5';
        } else {
            bar.style.display = 'none';
        }
    }
    updateCompareBar();

    /* ===== DAMAGE MODAL ===== */
    function openDamageModal(imageSrc) {
        document.getElementById('damageModalImage').src = imageSrc;
        const modal = document.getElementById('damageModalOverlay');
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeDamageModal() {
        const modal = document.getElementById('damageModalOverlay');
        modal.classList.remove('open');
        document.body.style.overflow = '';
        setTimeout(() => {
            document.getElementById('damageModalImage').src = '';
        }, 300);
    }

    /* ===== SHARE SYSTEM ===== */
    function shareListing() {
        var dd = document.getElementById('shareDropdown');
        dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
    }
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(function () {
            var msg = document.getElementById('copyMsg');
            msg.style.display = 'block';
            setTimeout(function () { msg.style.display = 'none'; }, 2000);
        });
    }

    /* ===== WHATSAPP CLICK TRACKING ===== */
    document.querySelectorAll('a[href*="wa.me"]').forEach(function (link) {
        link.addEventListener('click', function () {
            fetch('<?= baseUrl("api/whatsapp-click") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ car_id: <?= $car['id'] ?> })
            });
        });
    });

    /* ===== RECENTLY VIEWED TRACKER ===== */
    (function () {
        var key = 'automarket_recently_viewed';
        var viewed = JSON.parse(localStorage.getItem(key) || '[]');
        var carId = <?= $car['id'] ?>;
        viewed = viewed.filter(function (id) { return id !== carId; });
        viewed.unshift(carId);
        if (viewed.length > 10) viewed = viewed.slice(0, 10);
        localStorage.setItem(key, JSON.stringify(viewed));
    })();
</script>

<?php require __DIR__ . '/footer.php'; ?>