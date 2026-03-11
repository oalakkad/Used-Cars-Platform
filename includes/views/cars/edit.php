<?php $pageTitle = 'Edit Listing'; ?>
<?php require __DIR__ . '/../header.php'; ?>

<section class="car-form-page">
    <div class="container">
        <div class="car-form-card fade-in">
            <h1><i class="fas fa-edit" style="color:var(--primary);margin-right:8px;"></i> Edit Listing</h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= baseUrl('cars/' . $car['id'] . '/edit') ?>" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="brand">Brand *</label>
                        <select id="brand" name="brand" class="form-select" required>
                            <?php foreach (['Toyota', 'BMW', 'Mercedes-Benz', 'Audi', 'Lexus', 'Honda', 'Ford', 'Nissan', 'Chevrolet', 'Hyundai', 'Kia', 'Volkswagen', 'Other'] as $b): ?>
                                <option value="<?= $b ?>" <?= $car['brand'] === $b ? 'selected' : '' ?>>
                                    <?= $b ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="model">Model *</label>
                        <input type="text" id="model" name="model" class="form-input"
                            value="<?= sanitize($car['model']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="year">Year *</label>
                        <input type="number" id="year" name="year" class="form-input" value="<?= $car['year'] ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="price">Price (KWD) *</label>
                        <input type="number" id="price" name="price" class="form-input" value="<?= $car['price'] ?>"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="mileage">Mileage (km)</label>
                        <input type="number" id="mileage" name="mileage" class="form-input"
                            value="<?= $car['mileage'] ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="condition_status">Condition</label>
                        <select id="condition_status" name="condition_status" class="form-select">
                            <?php foreach (['Used', 'Like New', 'Excellent', 'Good', 'Fair'] as $c): ?>
                                <option value="<?= $c ?>" <?= $car['condition_status'] === $c ? 'selected' : '' ?>>
                                    <?= $c ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="fuel_type">Fuel Type</label>
                        <select id="fuel_type" name="fuel_type" class="form-select">
                            <?php foreach (['Gasoline', 'Diesel', 'Hybrid', 'Electric'] as $f): ?>
                                <option value="<?= $f ?>" <?= $car['fuel_type'] === $f ? 'selected' : '' ?>>
                                    <?= $f ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="transmission">Transmission</label>
                        <select id="transmission" name="transmission" class="form-select">
                            <?php foreach (['Automatic', 'Manual'] as $t): ?>
                                <option value="<?= $t ?>" <?= $car['transmission'] === $t ? 'selected' : '' ?>>
                                    <?= $t ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="whatsapp_phone">WhatsApp Phone (+965)</label>
                        <input type="text" id="whatsapp_phone" name="whatsapp_phone" class="form-input"
                            value="<?= sanitize($car['whatsapp_phone'] ?? '') ?>" placeholder="e.g. 12345678">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="location">Location / Area</label>
                        <input type="text" id="location" name="location" class="form-input"
                            value="<?= sanitize($car['location'] ?? '') ?>">
                        <input type="hidden" name="location_lat" id="location_lat"
                            value="<?= $car['location_lat'] ?? '' ?>">
                        <input type="hidden" name="location_lng" id="location_lng"
                            value="<?= $car['location_lng'] ?? '' ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description"
                        class="form-textarea"><?= sanitize($car['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" id="hasDamage" name="has_damage" value="1"
                            onchange="toggleDamageInspection()" <?= !empty($car['damage_data']) && $car['damage_data'] !== '[]' ? 'checked' : '' ?>>
                        <span>Vehicle has damage to report</span>
                    </label>
                </div>

                <div id="damageInspectionArea"
                    style="display: <?= !empty($car['damage_data']) && $car['damage_data'] !== '[]' ? 'block' : 'none' ?>;">
                    <div class="form-group">
                        <label class="form-label">Damage Inspection</label>
                        <div class="map-hint" style="text-align:center; margin-top: 10px;">
                            <i class="fas fa-info-circle"></i> Click to add a red dot marking damage. Click a dot to
                            remove it.
                        </div>
                        <div class="inspection-container">
                            <input type="hidden" id="damage_data" name="damage_data"
                                value='<?= !empty($car['damage_data']) ? sanitize($car['damage_data']) : '[]' ?>'>
                            <div class="inspection-wireframe" id="inspectionWireframe" onclick="addDamageDot(event)">
                                <svg viewBox="0 0 100 240" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M25,20 L75,20 C85,20 90,30 90,45 L95,95 C98,110 98,160 95,190 L90,215 C85,230 75,235 50,235 C25,235 15,230 10,215 L5,190 C2,160 2,110 5,95 L10,45 C10,30 15,20 25,20 Z" />
                                    <!-- Windows -->
                                    <path d="M20,65 L80,65 L70,110 L30,110 Z" />
                                    <path d="M20,185 L80,185 L70,140 L30,140 Z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Container for dynamic file inputs for each damage dot -->
                        <div id="damageUploadsContainer" style="margin-top: 24px;"></div>
                    </div>
                </div>

                <!-- Existing Images -->
                <?php if (!empty($images)): ?>
                    <div class="form-group">
                        <label class="form-label">Current Photos</label>
                        <div class="image-previews">
                            <?php foreach ($images as $img): ?>
                                <div class="image-preview">
                                    <img src="<?= asset('uploads/' . $img['image_path']) ?>" alt="Car image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Add More Photos</label>
                    <div class="image-upload-area" onclick="document.getElementById('images').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload additional images</p>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                        style="display:none;" onchange="previewNewImages(this)">
                    <div class="image-previews" id="newImagePreviews"></div>
                </div>

                <div style="display:flex;gap:12px;margin-top:16px;">
                    <button type="submit" class="btn btn-primary btn-lg" style="flex:1;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="<?= baseUrl('dashboard') ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function previewNewImages(input) {
        const container = document.getElementById('newImagePreviews');
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'image-preview';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    /* ===== DAMAGE INSPECTION ===== */
    let damageDots = [];

    function toggleDamageInspection() {
        const checkbox = document.getElementById('hasDamage');
        const area = document.getElementById('damageInspectionArea');
        if (checkbox.checked) {
            area.style.display = 'block';
            // Do not clear existing damage dots immediately
            if (damageDots.length === 0) {
                // Only try to parse if empty and checkbox is checked
                try {
                    const dataStr = document.getElementById('damage_data').value;
                    if (dataStr && dataStr !== '[]') {
                        const decodedStr = document.createElement('textarea');
                        decodedStr.innerHTML = dataStr;
                        damageDots = JSON.parse(decodedStr.value);
                    }
                } catch (e) { }
            }
            renderDamageDots();
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

    document.addEventListener('DOMContentLoaded', () => {
        try {
            const dataStr = document.getElementById('damage_data').value;
            if (dataStr && dataStr !== '[]') {
                // Decode HTML entities (e.g., &quot;) back to quotes
                const decodedStr = document.createElement('textarea');
                decodedStr.innerHTML = dataStr;
                damageDots = JSON.parse(decodedStr.value);

                // Ensure all existing dots have IDs
                damageDots = damageDots.map(dot => ({
                    ...dot,
                    id: dot.id || generateId()
                }));

                renderDamageDots();
            }
        } catch (e) {
            console.error("Error parsing damage data:", e, document.getElementById('damage_data').value);
        }
    });

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
        setTimeout(function () {
            var fileInput = document.getElementById('file-' + dotId);
            if (fileInput) fileInput.click();
        }, 150);
    }

    function createDamageUploadCard(dotId, existingImagePath) {
        var container = document.getElementById('damageUploadsContainer');
        var card = document.createElement('div');
        card.id = 'damage-upload-' + dotId;
        card.style.cssText = 'background:var(--bg-input); border:2px solid var(--border-color); border-radius:var(--radius-lg); padding:16px; margin-bottom:16px; display:flex; align-items:center; gap:12px;';

        var inputId = 'file-' + dotId;
        var filenameId = 'filename-' + dotId;
        var statusText = existingImagePath ? '<i class="fas fa-check-circle" style="color:var(--success); margin-right:4px;"></i> Photo already attached' : 'No photo selected yet';
        if (existingImagePath) card.style.borderColor = 'var(--success)';

        card.innerHTML = '<div style="flex:1;">'
            + '<div style="font-weight:600; color:var(--text-primary); margin-bottom:6px;">'
            + '<i class="fas fa-map-marker-alt" style="color:var(--danger); margin-right:6px;"></i> Damage Point'
            + '</div>'
            + '<div id="' + filenameId + '" style="font-size:0.85rem; color:var(--text-muted);">' + statusText + '</div>'
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
        fileInput.addEventListener('change', function () {
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

            // Create upload card if it doesn't exist (for pre-loaded damage data)
            if (!document.getElementById('damage-upload-' + dot.id)) {
                createDamageUploadCard(dot.id, dot.image_path);
            }
        }

        document.getElementById('damage_data').value = JSON.stringify(damageDots);
    }
</script>

<?php require __DIR__ . '/../footer.php'; ?>