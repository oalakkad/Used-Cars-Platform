<?php $pageTitle = 'List a Car'; ?>
<?php require __DIR__ . '/../header.php'; ?>

<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* ===== Multi-Step Wizard Styles ===== */
    .wizard-container {
        max-width: 860px;
        margin: 0 auto;
    }

    /* Progress Tracker */
    .wizard-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 48px;
        padding: 0 20px;
    }

    .wizard-step-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-dot {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        border: 3px solid var(--border-color);
        background: var(--bg-secondary);
        color: var(--text-muted);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
    }

    .step-dot i {
        display: none;
        font-size: 1.1rem;
    }

    .wizard-step-indicator.active .step-dot {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
        box-shadow: 0 0 0 6px var(--primary-glow), 0 4px 15px var(--primary-glow);
        transform: scale(1.1);
    }

    .wizard-step-indicator.completed .step-dot {
        border-color: var(--success);
        background: var(--success);
        color: #fff;
    }

    .wizard-step-indicator.completed .step-dot .step-number {
        display: none;
    }

    .wizard-step-indicator.completed .step-dot i {
        display: block;
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        text-align: center;
        white-space: nowrap;
        transition: color 0.3s ease;
    }

    .wizard-step-indicator.active .step-label {
        color: var(--primary);
    }

    .wizard-step-indicator.completed .step-label {
        color: var(--success);
    }

    /* Connector Line */
    .step-connector {
        flex: 1;
        height: 3px;
        background: var(--border-color);
        margin: 0 -4px;
        margin-bottom: 28px;
        position: relative;
        z-index: 1;
        border-radius: 2px;
        overflow: hidden;
    }

    .step-connector .connector-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--success), var(--primary));
        border-radius: 2px;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-connector.filled .connector-fill {
        width: 100%;
    }

    /* Step Panels */
    .wizard-panel {
        display: none;
        animation: wizardFadeIn 0.4s ease;
    }

    .wizard-panel.active {
        display: block;
    }

    @keyframes wizardFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .panel-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 40px;
        box-shadow: var(--shadow-md);
    }

    .panel-header {
        margin-bottom: 32px;
    }

    .panel-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 6px;
        font-family: var(--font-display);
    }

    .panel-header p {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    /* Wizard Navigation */
    .wizard-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
    }

    .wizard-nav .btn {
        min-width: 140px;
    }

    /* Map */
    #locationMap {
        width: 100%;
        height: 350px;
        border-radius: var(--radius-lg);
        border: 2px solid var(--border-color);
        margin-top: 12px;
        z-index: 1;
    }

    /* Image Upload Enhanced */
    .upload-zone {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 48px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--bg-input);
        position: relative;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: var(--cta);
        background: rgba(5, 150, 105, 0.05);
        transform: translateY(-2px);
    }

    .upload-zone .upload-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        background: var(--primary-glow);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--text-primary);
    }

    .upload-zone h3 {
        font-size: 1.1rem;
        margin-bottom: 6px;
    }

    .upload-zone p {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .image-previews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .preview-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        aspect-ratio: 4/3;
        border: 2px solid var(--border-color);
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-item .remove-preview {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 24px;
        height: 24px;
        background: rgba(239, 68, 68, 0.9);
        border: none;
        border-radius: 50%;
        color: #fff;
        font-size: 0.7rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-item:first-child::after {
        content: 'Primary';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--primary);
        color: #fff;
        text-align: center;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Review Summary */
    .review-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .review-item {
        padding: 14px 18px;
        background: var(--bg-input);
        border-radius: var(--radius-md);
    }

    .review-item-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .review-item-value {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-primary);
    }

    .review-images {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .review-thumb {
        width: 80px;
        height: 60px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .review-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .panel-card {
            padding: 24px;
        }

        .review-grid {
            grid-template-columns: 1fr;
        }

        .step-label {
            font-size: 0.7rem;
        }

        .step-dot {
            width: 40px;
            height: 40px;
            font-size: 0.85rem;
        }
    }
</style>

<section class="car-form-page">
    <div class="container">
        <div class="wizard-container">
            <!-- Page Title -->
            <div class="fade-in" style="text-align:center;margin-bottom:12px;">
                <h1 class="section-title" style="margin-top:8px;"><i class="fas fa-plus-circle"
                        style="color:var(--primary);"></i> List Your Car</h1>
                <p class="section-subtitle">Complete the steps below to publish your listing</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?></div>
            <?php endif; ?>

            <!-- ===== PROGRESS TRACKER ===== -->
            <div class="wizard-progress fade-in">
                <div class="wizard-step-indicator active" data-step="1">
                    <div class="step-dot">
                        <span class="step-number">1</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Car Details</span>
                </div>
                <div class="step-connector">
                    <div class="connector-fill"></div>
                </div>
                <div class="wizard-step-indicator" data-step="2">
                    <div class="step-dot">
                        <span class="step-number">2</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Specs & Price</span>
                </div>
                <div class="step-connector">
                    <div class="connector-fill"></div>
                </div>
                <div class="wizard-step-indicator" data-step="3">
                    <div class="step-dot">
                        <span class="step-number">3</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Location</span>
                </div>
                <div class="step-connector">
                    <div class="connector-fill"></div>
                </div>
                <div class="wizard-step-indicator" data-step="4">
                    <div class="step-dot">
                        <span class="step-number">4</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Photos</span>
                </div>
                <div class="step-connector">
                    <div class="connector-fill"></div>
                </div>
                <div class="wizard-step-indicator" data-step="5">
                    <div class="step-dot">
                        <span class="step-number">5</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Inspection</span>
                </div>
                <div class="step-connector">
                    <div class="connector-fill"></div>
                </div>
                <div class="wizard-step-indicator" data-step="6">
                    <div class="step-dot">
                        <span class="step-number">6</span>
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="step-label">Review</span>
                </div>
            </div>

            <!-- ===== FORM ===== -->
            <form action="<?= baseUrl('cars/new') ?>" method="POST" enctype="multipart/form-data" id="wizardForm"
                onsubmit="return handleFormSubmit(event)">

                <!-- STEP 1: Car Details -->
                <div class="wizard-panel active" data-panel="1">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-car" style="color:var(--primary);margin-right:8px;"></i> Car Details
                            </h2>
                            <p>Tell us about the car you're selling</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="brand">Brand *</label>
                                <select id="brand" name="brand" class="form-select">
                                    <option value="">Select Brand</option>
                                    <option value="Toyota">Toyota</option>
                                    <option value="BMW">BMW</option>
                                    <option value="Mercedes-Benz">Mercedes-Benz</option>
                                    <option value="Audi">Audi</option>
                                    <option value="Lexus">Lexus</option>
                                    <option value="Honda">Honda</option>
                                    <option value="Ford">Ford</option>
                                    <option value="Nissan">Nissan</option>
                                    <option value="Chevrolet">Chevrolet</option>
                                    <option value="Hyundai">Hyundai</option>
                                    <option value="Kia">Kia</option>
                                    <option value="Volkswagen">Volkswagen</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="model">Model *</label>
                                <input type="text" id="model" name="model" class="form-input"
                                    placeholder="e.g. Camry, 3 Series">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="year">Year *</label>
                                <input type="number" id="year" name="year" class="form-input" placeholder="e.g. 2022"
                                    min="1950" max="<?= date('Y') + 1 ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="condition_status">Condition</label>
                                <select id="condition_status" name="condition_status" class="form-select">
                                    <option value="Used">Used</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                </select>
                            </div>
                        </div>

                        <div class="wizard-nav">
                            <div></div>
                            <button type="button" class="btn btn-primary" onclick="wizardNext()">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Specs & Price -->
                <div class="wizard-panel" data-panel="2">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-sliders-h" style="color:var(--primary);margin-right:8px;"></i> Specs &
                                Pricing</h2>
                            <p>Set the technical details and your asking price</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="price">Price (KWD) *</label>
                                <input type="number" id="price" name="price" class="form-input" placeholder="e.g. 25000"
                                    min="1" step="0.01">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="mileage">Mileage (km)</label>
                                <input type="number" id="mileage" name="mileage" class="form-input"
                                    placeholder="e.g. 50000" min="0" value="0">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="fuel_type">Fuel Type</label>
                                <select id="fuel_type" name="fuel_type" class="form-select">
                                    <option value="Gasoline">Gasoline</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Electric">Electric</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="transmission">Transmission</label>
                                <select id="transmission" name="transmission" class="form-select">
                                    <option value="Automatic">Automatic</option>
                                    <option value="Manual">Manual</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea"
                                placeholder="Describe your car's features, condition, and selling points..."></textarea>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-secondary" onclick="wizardPrev()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="wizardNext()">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Location (Map) -->
                <div class="wizard-panel" data-panel="3">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-map-marker-alt" style="color:var(--primary);margin-right:8px;"></i>
                                Location</h2>
                            <p>Set the car's location by clicking on the map or typing the address</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="whatsapp_phone">WhatsApp Phone (+965)</label>
                                <input type="text" id="whatsapp_phone" name="whatsapp_phone" class="form-input"
                                    placeholder="e.g. 12345678">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="location">Location / Area</label>
                                <input type="text" id="location" name="location" class="form-input"
                                    placeholder="e.g. Salmiya, Kuwait">
                                <input type="hidden" name="location_lat" id="location_lat">
                                <input type="hidden" name="location_lng" id="location_lng">
                            </div>
                        </div>

                        <div id="locationMap"></div>
                        <div class="map-hint">
                            <i class="fas fa-info-circle"></i>
                            Click anywhere on the map to set the exact location, or type the city name above.
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-secondary" onclick="wizardPrev()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="wizardNext()">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Photos -->
                <div class="wizard-panel" data-panel="4">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-camera" style="color:var(--primary);margin-right:8px;"></i> Photos</h2>
                            <p>Upload high-quality photos to attract more buyers</p>
                        </div>

                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('images').click()">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <h3>Drag & drop your photos here</h3>
                            <p>or click to browse — JPG, PNG, WebP — max 5MB each</p>
                        </div>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                            style="display:none;" onchange="handleImageSelect(this)">

                        <div class="image-previews-grid" id="imagePreviews"></div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-secondary" onclick="wizardPrev()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="wizardNext()">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: Damage Inspection -->
                <div class="wizard-panel" data-panel="5">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-search" style="color:var(--primary);margin-right:8px;"></i> Damage
                                Inspection</h2>
                            <p>Click on the car wireframe to mark damaged areas</p>
                        </div>

                        <div class="form-group">
                            <label class="form-checkbox">
                                <input type="checkbox" id="hasDamage" name="has_damage" value="1"
                                    onchange="toggleDamageInspection()">
                                <span>Vehicle has damage to report</span>
                            </label>
                        </div>

                        <div id="damageInspectionArea" style="display: none;">
                            <div class="inspection-container">
                                <input type="hidden" id="damage_data" name="damage_data" value="[]">
                                <div class="inspection-wireframe" id="inspectionWireframe"
                                    onclick="addDamageDot(event)">
                                    <svg viewBox="0 0 100 240" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M25,20 L75,20 C85,20 90,30 90,45 L95,95 C98,110 98,160 95,190 L90,215 C85,230 75,235 50,235 C25,235 15,230 10,215 L5,190 C2,160 2,110 5,95 L10,45 C10,30 15,20 25,20 Z" />
                                        <!-- Windows -->
                                        <path d="M20,65 L80,65 L70,110 L30,110 Z" />
                                        <path d="M20,185 L80,185 L70,140 L30,140 Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="map-hint" style="text-align:center; margin-top: 10px;">
                                <i class="fas fa-info-circle"></i> Click on the car to mark a damaged area. A photo
                                upload will open automatically.
                            </div>

                            <!-- Container for dynamic file inputs for each damage dot -->
                            <div id="damageUploadsContainer" style="margin-top: 24px;"></div>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-secondary" onclick="wizardPrev()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary" onclick="wizardNext()">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 6: Review -->
                <div class="wizard-panel" data-panel="6">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h2><i class="fas fa-clipboard-check" style="color:var(--success);margin-right:8px;"></i>
                                Review & Publish</h2>
                            <p>Review your listing details before publishing</p>
                        </div>

                        <div class="review-grid" id="reviewGrid">
                            <!-- Populated by JS -->
                        </div>

                        <div class="review-images" id="reviewImages">
                            <!-- Populated by JS -->
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn-secondary" onclick="wizardPrev()">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" style="min-width:200px;"
                                onclick="submitWizardForm()">
                                <i class="fas fa-rocket"></i> Publish Listing
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    /* ===== WIZARD LOGIC ===== */
    let currentStep = 1;
    const totalSteps = 6;

    /* Prevent Enter key from submitting the form on steps 1-5 */
    document.getElementById('wizardForm').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && currentStep < totalSteps) {
            e.preventDefault();
            wizardNext();
        }
    });

    /* Only allow form submission from step 6 */
    function handleFormSubmit(e) {
        if (currentStep !== totalSteps) {
            e.preventDefault();
            return false;
        }
        // Validate all steps before final submit
        for (let s = 1; s <= 5; s++) {
            if (!validateStep(s)) {
                e.preventDefault();
                goToStep(s);
                return false;
            }
        }
        return true;
    }

    /* Programmatic submit from Publish button */
    function submitWizardForm() {
        // Validate all steps
        for (let s = 1; s <= 5; s++) {
            if (!validateStep(s)) {
                goToStep(s);
                return;
            }
        }
        document.getElementById('wizardForm').submit();
    }

    function wizardNext() {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) {
            goToStep(currentStep + 1);
        }
    }

    function wizardPrev() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    }

    function goToStep(step) {
        // Update panels
        document.querySelectorAll('.wizard-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`.wizard-panel[data-panel="${step}"]`).classList.add('active');

        // Update indicators
        document.querySelectorAll('.wizard-step-indicator').forEach(ind => {
            const s = parseInt(ind.dataset.step);
            ind.classList.remove('active', 'completed');
            if (s < step) ind.classList.add('completed');
            else if (s === step) ind.classList.add('active');
        });

        // Update connectors
        document.querySelectorAll('.step-connector').forEach((conn, idx) => {
            if (idx < step - 1) conn.classList.add('filled');
            else conn.classList.remove('filled');
        });

        currentStep = step;

        // Initialize map when entering step 3
        if (step === 3 && !mapInitialized) {
            initMap();
        }

        // Populate review when entering step 6
        if (step === 6) {
            populateReview();
        }

        // Scroll to top of form
        document.querySelector('.wizard-progress').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function validateStep(step) {
        if (step === 1) {
            const brand = document.getElementById('brand').value;
            const model = document.getElementById('model').value.trim();
            const year = document.getElementById('year').value;
            if (!brand || !model || !year) {
                alert('Please fill in Brand, Model, and Year.');
                return false;
            }
        }
        if (step === 2) {
            const price = document.getElementById('price').value;
            if (!price || parseFloat(price) <= 0) {
                alert('Please enter a valid price.');
                return false;
            }
        }
        return true;
    }

    /* ===== MAP (Leaflet.js) ===== */
    let map, marker;
    let mapInitialized = false;

    function initMap() {
        map = L.map('locationMap').setView([29.3759, 47.9774], 10); // Default: Kuwait
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Click to place marker
        map.on('click', function (e) {
            placeMarker(e.latlng.lat, e.latlng.lng);
            // Reverse geocode
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        mapInitialized = true;

        // Fix map rendering inside hidden panel
        setTimeout(() => map.invalidateSize(), 200);
    }

    function placeMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        document.getElementById('location_lat').value = lat.toFixed(7);
        document.getElementById('location_lng').value = lng.toFixed(7);

        marker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            document.getElementById('location_lat').value = pos.lat.toFixed(7);
            document.getElementById('location_lng').value = pos.lng.toFixed(7);
            reverseGeocode(pos.lat, pos.lng);
        });
    }

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
            .then(r => r.json())
            .then(data => {
                if (data && data.display_name) {
                    const parts = data.display_name.split(',');
                    const short = parts.slice(0, 3).join(',').trim();
                    document.getElementById('location').value = short;
                }
            })
            .catch(() => { });
    }

    /* ===== IMAGE UPLOAD ===== */
    let selectedFiles = [];

    function handleImageSelect(input) {
        selectedFiles = Array.from(input.files);
        renderPreviews();
    }

    function renderPreviews() {
        const container = document.getElementById('imagePreviews');
        container.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-preview" onclick="removePreview(${idx})">
                    <i class="fas fa-times"></i>
                </button>
            `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removePreview(idx) {
        selectedFiles.splice(idx, 1);
        // Update the file input (can't directly, so we render previews only)
        renderPreviews();
    }

    // Drag & drop
    const uploadZone = document.getElementById('uploadZone');
    if (uploadZone) {
        ['dragenter', 'dragover'].forEach(evt => {
            uploadZone.addEventListener(evt, (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
        });
        ['dragleave', 'drop'].forEach(evt => {
            uploadZone.addEventListener(evt, (e) => { e.preventDefault(); uploadZone.classList.remove('dragover'); });
        });
        uploadZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (dt.files.length) {
                document.getElementById('images').files = dt.files;
                selectedFiles = Array.from(dt.files);
                renderPreviews();
            }
        });
    }

    /* ===== DAMAGE INSPECTION ===== */
    let damageDots = [];

    function toggleDamageInspection() {
        const checkbox = document.getElementById('hasDamage');
        const area = document.getElementById('damageInspectionArea');
        if (checkbox.checked) {
            area.style.display = 'block';
        } else {
            area.style.display = 'none';
            // Clear damage if unchecked
            damageDots = [];
            renderDamageDots();
            document.getElementById('damageUploadsContainer').innerHTML = '';
        }
    }

    // Generate a simple unique ID for each dot
    function generateId() {
        return Math.random().toString(36).substr(2, 9);
    }

    function addDamageDot(e) {
        if (e.target.classList.contains('damage-dot')) {
            var index = parseInt(e.target.getAttribute('data-index'));
            var dotId = damageDots[index].id;
            damageDots.splice(index, 1);
            var card = document.getElementById('damage-upload-' + dotId);
            if (card) card.remove();
            renderDamageDots();
            return;
        }

        var wireframe = document.getElementById('inspectionWireframe');
        var rect = wireframe.getBoundingClientRect();
        var x = ((e.clientX - rect.left) / rect.width) * 100;
        var y = ((e.clientY - rect.top) / rect.height) * 100;
        var dotId = generateId();

        damageDots.push({ id: dotId, x: x, y: y });
        renderDamageDots();
        createDamageUploadCard(dotId);

        // Auto-open the file picker immediately
        setTimeout(function() {
            var fileInput = document.getElementById('file-' + dotId);
            if (fileInput) fileInput.click();
        }, 150);
    }

    function createDamageUploadCard(dotId) {
        var container = document.getElementById('damageUploadsContainer');
        var card = document.createElement('div');
        card.id = 'damage-upload-' + dotId;
        card.style.cssText = 'background:var(--bg-input); border:2px solid var(--border-color); border-radius:var(--radius-lg); padding:16px; margin-bottom:16px; display:flex; align-items:center; gap:12px;';

        var inputId = 'file-' + dotId;
        var filenameId = 'filename-' + dotId;

        card.innerHTML = '<div style="flex:1;">'
            + '<div style="font-weight:600; color:var(--text-primary); margin-bottom:6px;">'
            + '<i class="fas fa-map-marker-alt" style="color:var(--danger); margin-right:6px;"></i> Damage Point'
            + '</div>'
            + '<div id="' + filenameId + '" style="font-size:0.85rem; color:var(--text-muted);">No photo selected yet</div>'
            + '</div>'
            + '<label for="' + inputId + '" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:var(--cta); color:#fff; border-radius:var(--radius-md); font-weight:600; font-size:0.95rem; cursor:pointer; white-space:nowrap; box-shadow:0 4px 12px rgba(5,150,105,0.3);">'
            + '<i class="fas fa-camera"></i> Choose Photo'
            + '</label>'
            + '<input id="' + inputId + '" type="file" name="damage_photos[' + dotId + ']" accept="image/jpeg,image/png,image/webp" style="display:none;">'
            + '<button type="button" onclick="removeDamageDot(\'' + dotId + '\')" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:1.1rem; padding:8px;" title="Remove">'
            + '<i class="fas fa-trash-alt"></i>'
            + '</button>';

        container.appendChild(card);

        var fileInput = document.getElementById(inputId);
        fileInput.addEventListener('change', function() {
            var label = document.getElementById(filenameId);
            if (this.files && this.files[0]) {
                label.innerHTML = '<i class="fas fa-check-circle" style="color:var(--success); margin-right:4px;"></i> ' + this.files[0].name;
                card.style.borderColor = 'var(--success)';
            } else {
                label.textContent = 'No photo selected yet';
                card.style.borderColor = 'var(--border-color)';
            }
        });
    }

    function removeDamageDot(dotId) {
        var idx = -1;
        for (var i = 0; i < damageDots.length; i++) {
            if (damageDots[i].id === dotId) { idx = i; break; }
        }
        if (idx !== -1) damageDots.splice(idx, 1);
        var card = document.getElementById('damage-upload-' + dotId);
        if (card) card.remove();
        renderDamageDots();
    }

    function renderDamageDots() {
        var wireframe = document.getElementById('inspectionWireframe');
        var existingDots = wireframe.querySelectorAll('.damage-dot');
        for (var i = 0; i < existingDots.length; i++) existingDots[i].remove();

        for (var j = 0; j < damageDots.length; j++) {
            var dot = damageDots[j];
            var div = document.createElement('div');
            div.className = 'damage-dot';
            div.style.left = dot.x + '%';
            div.style.top = dot.y + '%';
            div.setAttribute('data-index', j);
            div.setAttribute('title', 'Click to remove');
            wireframe.appendChild(div);
        }

        document.getElementById('damage_data').value = JSON.stringify(damageDots);
    }

    /* ===== REVIEW PANEL ===== */
    function populateReview() {
        const fields = [
            { label: 'Brand', id: 'brand' },
            { label: 'Model', id: 'model' },
            { label: 'Year', id: 'year' },
            { label: 'Condition', id: 'condition_status' },
            { label: 'Price', id: 'price', suffix: ' KWD' },
            { label: 'Mileage', id: 'mileage', suffix: ' km' },
            { label: 'Fuel Type', id: 'fuel_type' },
            { label: 'Transmission', id: 'transmission' },
            { label: 'Location', id: 'location' },
        ];

        const grid = document.getElementById('reviewGrid');
        grid.innerHTML = '';
        fields.forEach(f => {
            const val = document.getElementById(f.id).value || '—';
            const display = (f.prefix || '') + val + (f.suffix || '');
            grid.innerHTML += `
            <div class="review-item">
                <div class="review-item-label">${f.label}</div>
                <div class="review-item-value">${display}</div>
            </div>
        `;
        });

        // Description
        const desc = document.getElementById('description').value;
        if (desc) {
            grid.innerHTML += `
            <div class="review-item" style="grid-column:1/-1;">
                <div class="review-item-label">Description</div>
                <div class="review-item-value" style="font-weight:400;font-size:0.9rem;">${desc}</div>
            </div>
        `;
        }

        // Images
        const imgContainer = document.getElementById('reviewImages');
        imgContainer.innerHTML = '';
        if (selectedFiles.length > 0) {
            imgContainer.innerHTML = `<div class="review-item-label" style="width:100%;margin-top:8px;">Photos (${selectedFiles.length})</div>`;
            selectedFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'review-thumb';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    imgContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>

<?php require __DIR__ . '/../footer.php'; ?>