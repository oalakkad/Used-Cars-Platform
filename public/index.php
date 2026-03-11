<?php
/**
 * Front Controller / Router
 * All requests are routed through this file.
 */

require_once __DIR__ . '/../includes/config/app.php';
require_once __DIR__ . '/../includes/config/db.php';

// Load models
require_once __DIR__ . '/../includes/models/UserModel.php';
require_once __DIR__ . '/../includes/models/CarModel.php';
require_once __DIR__ . '/../includes/models/CarImageModel.php';
require_once __DIR__ . '/../includes/models/FavoriteModel.php';
require_once __DIR__ . '/../includes/models/AnalyticsModel.php';

// Load controllers
require_once __DIR__ . '/../includes/controllers/AuthController.php';
require_once __DIR__ . '/../includes/controllers/CarController.php';
require_once __DIR__ . '/../includes/controllers/FavoriteController.php';
require_once __DIR__ . '/../includes/controllers/DashboardController.php';
require_once __DIR__ . '/../includes/controllers/AdminController.php';

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/Used-Cars-Platform/public';
// Handle the case where the URL might include /index.php or different casing
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_ireplace($basePath, '', $path);
$path = str_ireplace('/index.php', '', $path);
$path = '/' . trim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];

// ========== ROUTING ==========

// Home
if ($path === '/' || $path === '') {
    $controller = new CarController();
    $controller->homepage();
}
// Search / Listings
elseif ($path === '/search') {
    $controller = new CarController();
    $controller->search();
}
// Car Details
elseif (preg_match('#^/car/(\d+)$#', $path, $matches)) {
    $controller = new CarController();
    $controller->details((int) $matches[1]);
}
// Brand Listings
elseif (preg_match('#^/brand/(.+)$#', $path, $matches)) {
    $controller = new CarController();
    $controller->brandListings(urldecode($matches[1]));
}
// Login
elseif ($path === '/login') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->loginPost();
    } else {
        $controller->loginForm();
    }
}
// Register
elseif ($path === '/register') {
    $controller = new AuthController();
    if ($method === 'POST') {
        $controller->registerPost();
    } else {
        $controller->registerForm();
    }
}
// Logout
elseif ($path === '/logout') {
    $controller = new AuthController();
    $controller->logout();
}
// Dashboard
elseif ($path === '/dashboard') {
    $controller = new DashboardController();
    $controller->index();
}
// Add Car
elseif ($path === '/cars/new') {
    $controller = new CarController();
    if ($method === 'POST') {
        $controller->createPost();
    } else {
        $controller->createForm();
    }
}
// Edit Car
elseif (preg_match('#^/cars/(\d+)/edit$#', $path, $matches)) {
    $controller = new CarController();
    if ($method === 'POST') {
        $controller->updatePost((int) $matches[1]);
    } else {
        $controller->editForm((int) $matches[1]);
    }
}
// Delete Car
elseif (preg_match('#^/cars/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    $controller = new CarController();
    $controller->delete((int) $matches[1]);
}
// Toggle Favorite (AJAX)
elseif ($path === '/favorites/toggle' && $method === 'POST') {
    $controller = new FavoriteController();
    $controller->toggle();
}
// Remove Favorite
elseif (preg_match('#^/favorites/(\d+)/remove$#', $path, $matches) && $method === 'POST') {
    $controller = new FavoriteController();
    $controller->remove((int) $matches[1]);
}
// API: Get models by brand (AJAX)
elseif ($path === '/api/models' && $method === 'GET') {
    $controller = new CarController();
    $controller->getModelsByBrand();
}
// Compare Page
elseif ($path === '/compare') {
    $controller = new CarController();
    $controller->comparePage();
}
// API: Compare cars data (AJAX fallback)
elseif ($path === '/api/compare' && $method === 'GET') {
    $controller = new CarController();
    $controller->compareApi();
}
// Seller Profile
elseif (preg_match('#^/seller/(\d+)$#', $path, $matches)) {
    $controller = new CarController();
    $controller->sellerProfile((int) $matches[1]);
}
// Admin Dashboard
elseif ($path === '/admin') {
    $controller = new AdminController();
    $controller->index();
}
// Admin: Toggle Featured
elseif ($path === '/admin/toggle-featured' && $method === 'POST') {
    $controller = new AdminController();
    $controller->toggleFeatured();
}
// API: Track WhatsApp Click
elseif ($path === '/api/whatsapp-click' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $carId = (int) ($data['car_id'] ?? 0);
    if ($carId > 0) {
        $analytics = new AnalyticsModel();
        $analytics->incrementWhatsAppClick($carId);
        echo json_encode(['success' => true]);
    }
}
// API: Get cars by IDs (for recently viewed)
elseif ($path === '/api/cars-by-ids' && $method === 'GET') {
    $ids = $_GET['ids'] ?? '';
    $ids = array_filter(array_map('intval', explode(',', $ids)));
    if (!empty($ids)) {
        $carModel = new CarModel();
        $result = [];
        foreach ($ids as $id) {
            $car = $carModel->findById($id);
            if ($car) {
                $imgModel = new CarImageModel();
                $images = $imgModel->getByCarId($id);
                $car['primary_image'] = !empty($images) ? $images[0]['image_path'] : null;
                $result[] = $car;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        echo json_encode([]);
    }
}
// 404
else {
    http_response_code(404);
    require __DIR__ . '/../includes/views/404.php';
}
