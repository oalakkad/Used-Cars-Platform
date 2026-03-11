<?php
/**
 * CarController - Handles car listing pages, CRUD, and search
 */
class CarController
{

    private CarModel $carModel;
    private CarImageModel $imageModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->imageModel = new CarImageModel();
    }

    public function homepage(): void
    {
        $latestCars = $this->carModel->getLatest(6);
        $brands = [
            ['name' => 'Toyota', 'slug' => 'Toyota', 'icon' => 'T', 'color' => '#EB0A1E', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/e/e7/Toyota.svg'],
            ['name' => 'BMW', 'slug' => 'BMW', 'icon' => 'B', 'color' => '#0066B1', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/4/44/BMW.svg'],
            ['name' => 'Mercedes-Benz', 'slug' => 'Mercedes-Benz', 'icon' => 'M', 'color' => '#333333', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/90/Mercedes-Logo.svg'],
            ['name' => 'Audi', 'slug' => 'Audi', 'icon' => 'A', 'color' => '#BB0A30', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a8/Audi_Logo_2016.svg'],
            ['name' => 'Lexus', 'slug' => 'Lexus', 'icon' => 'L', 'color' => '#4B4B4B', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/d/d1/Lexus_division_emblem.svg'],
            ['name' => 'Honda', 'slug' => 'Honda', 'icon' => 'H', 'color' => '#CC0000', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/3/38/Honda.svg'],
            ['name' => 'Ford', 'slug' => 'Ford', 'icon' => 'F', 'color' => '#003478', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a0/Ford_Motor_Company_Logo.svg'],
            ['name' => 'Nissan', 'slug' => 'Nissan', 'icon' => 'N', 'color' => '#C3002F', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Nissan_logo.png'],
            ['name' => 'Porsche', 'slug' => 'Porsche', 'icon' => 'P', 'color' => '#D5001C', 'logo' => 'https://upload.wikimedia.org/wikipedia/en/8/8c/Porsche_logo.svg'],
            ['name' => 'Volkswagen', 'slug' => 'Volkswagen', 'icon' => 'V', 'color' => '#001E50', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/a/a1/Volkswagen_Logo_till_1995.svg'],
            ['name' => 'Chevrolet', 'slug' => 'Chevrolet', 'icon' => 'C', 'color' => '#CDA434', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Chevrolet-logo.png'],
            ['name' => 'Hyundai', 'slug' => 'Hyundai', 'icon' => 'H', 'color' => '#002C5F', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/4/44/Hyundai_Motor_Company_logo.svg'],
        ];
        $success = flash('success');
        require __DIR__ . '/../views/home.php';
    }

    public function search(): void
    {
        $filters = [
            'brand' => $_GET['brand'] ?? '',
            'model' => $_GET['model'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'year' => $_GET['year'] ?? '',
            'fuel_type' => $_GET['fuel_type'] ?? '',
            'transmission' => $_GET['transmission'] ?? '',
            'location' => $_GET['location'] ?? '',
            'q' => $_GET['q'] ?? '',
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $result = $this->carModel->search($filters, $limit, $offset);
        $cars = $result['cars'];
        $total = $result['total'];
        $totalPages = ceil($total / $limit);
        $allBrands = $this->carModel->getDistinctBrands();

        require __DIR__ . '/../views/search.php';
    }

    public function details(int $id): void
    {
        $car = $this->carModel->findById($id);
        if (!$car) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }
        $images = $this->imageModel->getByCarId($id);
        $isFavorited = false;
        if (isLoggedIn()) {
            $favModel = new FavoriteModel();
            $isFavorited = $favModel->isFavorited(currentUserId(), $id);
        }

        // Track view
        $analytics = new AnalyticsModel();
        $analytics->recordView($id, $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', isLoggedIn() ? currentUserId() : null);

        // Market price analysis
        $priceData = $this->carModel->getAvgPriceByBrandModel($car['brand'], $car['model'], (int) $car['year']);

        require __DIR__ . '/../views/car-details.php';
    }

    public function brandListings(string $brand): void
    {
        $cars = $this->carModel->getByBrand($brand);
        $brandName = $brand;
        require __DIR__ . '/../views/brand-listings.php';
    }

    public function sellerProfile(int $userId): void
    {
        $seller = $this->carModel->getSellerProfile($userId);
        if (!$seller) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }
        $cars = $this->carModel->getByUser($userId);
        $listingCount = $this->carModel->countByUser($userId);
        require __DIR__ . '/../views/seller.php';
    }

    public function createForm(): void
    {
        if (!isLoggedIn()) {
            flash('error', 'Please log in to list a car.');
            redirect(baseUrl('login'));
        }
        $error = flash('error');
        require __DIR__ . '/../views/cars/create.php';
    }

    public function createPost(): void
    {
        if (!isLoggedIn()) {
            redirect(baseUrl('login'));
        }

        $data = [
            'user_id' => currentUserId(),
            'brand' => trim($_POST['brand'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'year' => (int) ($_POST['year'] ?? 0),
            'mileage' => (int) ($_POST['mileage'] ?? 0),
            'price' => (float) ($_POST['price'] ?? 0),
            'fuel_type' => trim($_POST['fuel_type'] ?? 'Gasoline'),
            'transmission' => trim($_POST['transmission'] ?? 'Automatic'),
            'condition_status' => trim($_POST['condition_status'] ?? 'Used'),
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'location_lat' => $_POST['location_lat'] ?: null,
            'location_lng' => $_POST['location_lng'] ?: null,
            'whatsapp_phone' => trim($_POST['whatsapp_phone'] ?? ''),
            'damage_data' => trim($_POST['damage_data'] ?? '[]'),
        ];

        // Handle Damage Photos Uploads
        if (!empty($_POST['has_damage']) && isset($_FILES['damage_photos'])) {
            $damageData = json_decode($data['damage_data'], true);
            if (is_array($damageData)) {
                $uploadDir = __DIR__ . '/../../public/assets/uploads/damage/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['damage_photos']['name'] as $dotId => $filename) {
                    if ($_FILES['damage_photos']['error'][$dotId] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['damage_photos']['tmp_name'][$dotId];
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $newFilename = uniqid('dmg_') . '.' . $extension;
                            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                                // Find the dot in damageData and add the image path
                                foreach ($damageData as &$dot) {
                                    if ($dot['id'] === $dotId) {
                                        $dot['image_path'] = 'damage/' . $newFilename;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                $data['damage_data'] = json_encode($damageData);
            }
        } else if (empty($_POST['has_damage'])) {
            $data['damage_data'] = '[]';
        }

        // Validation
        if (empty($data['brand']) || empty($data['model']) || $data['year'] < 1900 || $data['price'] <= 0) {
            flash('error', 'Please fill in all required fields correctly.');
            redirect(baseUrl('cars/new'));
        }

        $carId = $this->carModel->create($data);

        // Handle image uploads
        if (isset($_FILES['images']) && $_FILES['images']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $this->imageModel->uploadImages($carId, $_FILES['images']);
        }

        flash('success', 'Car listing created successfully!');
        redirect(baseUrl('dashboard'));
    }

    public function editForm(int $id): void
    {
        if (!isLoggedIn()) {
            redirect(baseUrl('login'));
        }
        if (!$this->carModel->belongsToUser($id, currentUserId())) {
            redirect(baseUrl('dashboard'));
        }
        $car = $this->carModel->findById($id);
        $images = $this->imageModel->getByCarId($id);
        $error = flash('error');
        require __DIR__ . '/../views/cars/edit.php';
    }

    public function updatePost(int $id): void
    {
        if (!isLoggedIn() || !$this->carModel->belongsToUser($id, currentUserId())) {
            redirect(baseUrl('dashboard'));
        }

        $data = [
            'brand' => trim($_POST['brand'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'year' => (int) ($_POST['year'] ?? 0),
            'mileage' => (int) ($_POST['mileage'] ?? 0),
            'price' => (float) ($_POST['price'] ?? 0),
            'fuel_type' => trim($_POST['fuel_type'] ?? 'Gasoline'),
            'transmission' => trim($_POST['transmission'] ?? 'Automatic'),
            'condition_status' => trim($_POST['condition_status'] ?? 'Used'),
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'location_lat' => $_POST['location_lat'] ?: null,
            'location_lng' => $_POST['location_lng'] ?: null,
            'whatsapp_phone' => trim($_POST['whatsapp_phone'] ?? ''),
            'damage_data' => trim($_POST['damage_data'] ?? '[]'),
        ];

        // Handle Damage Photos Uploads
        if (!empty($_POST['has_damage']) && isset($_FILES['damage_photos'])) {
            $damageData = json_decode($data['damage_data'], true);
            if (is_array($damageData)) {
                $uploadDir = __DIR__ . '/../../public/assets/uploads/damage/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['damage_photos']['name'] as $dotId => $filename) {
                    if ($_FILES['damage_photos']['error'][$dotId] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['damage_photos']['tmp_name'][$dotId];
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $newFilename = uniqid('dmg_') . '.' . $extension;
                            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                                // Find the dot in damageData and add the image path
                                foreach ($damageData as &$dot) {
                                    if ($dot['id'] === $dotId) {
                                        $dot['image_path'] = 'damage/' . $newFilename;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                $data['damage_data'] = json_encode($damageData);
            }
        } else if (empty($_POST['has_damage'])) {
            $data['damage_data'] = '[]';
        }

        $this->carModel->update($id, $data);

        // Handle new image uploads
        if (isset($_FILES['images']) && $_FILES['images']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $this->imageModel->uploadImages($id, $_FILES['images']);
        }

        flash('success', 'Car listing updated successfully!');
        redirect(baseUrl('dashboard'));
    }

    public function delete(int $id): void
    {
        if (!isLoggedIn() || !$this->carModel->belongsToUser($id, currentUserId())) {
            redirect(baseUrl('dashboard'));
        }

        // Delete images from filesystem
        $this->imageModel->deleteByCarId($id);
        $this->carModel->delete($id);

        flash('success', 'Car listing deleted successfully!');
        redirect(baseUrl('dashboard'));
    }

    public function getModelsByBrand(): void
    {
        $brand = $_GET['brand'] ?? '';
        $models = $this->carModel->getModelsByBrand($brand);
        header('Content-Type: application/json');
        echo json_encode($models);
        exit;
    }

    public function comparePage(): void
    {
        $idsParam = $_GET['ids'] ?? '';
        $ids = array_filter(array_map('intval', explode(',', $idsParam)));

        $compareCars = [];
        foreach ($ids as $id) {
            $car = $this->carModel->findById($id);
            if ($car) {
                $images = $this->imageModel->getByCarId($id);
                $car['primary_image'] = !empty($images) ? $images[0]['image_path'] : null;
                $compareCars[] = $car;
            }
        }

        require __DIR__ . '/../views/compare.php';
    }

    public function compareApi(): void
    {
        $idsParam = $_GET['ids'] ?? '';
        $ids = array_filter(array_map('intval', explode(',', $idsParam)));

        if (count($ids) < 2 || count($ids) > 3) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        $cars = [];
        foreach ($ids as $id) {
            $car = $this->carModel->findById($id);
            if ($car) {
                $images = $this->imageModel->getByCarId($id);
                $car['primary_image'] = !empty($images) ? $images[0]['image_path'] : null;
                $cars[] = $car;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($cars);
        exit;
    }
}
