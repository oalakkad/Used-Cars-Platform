<?php
/**
 * AdminController - Admin dashboard and management
 */
class AdminController
{
    private CarModel $carModel;
    private AnalyticsModel $analyticsModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->analyticsModel = new AnalyticsModel();
    }

    private function isAdmin(): bool
    {
        if (!isset($_SESSION['user_id']))
            return false;
        $email = $_SESSION['user_email'] ?? '';
        return $email === 'demo@automarket.com';
    }

    public function index(): void
    {
        if (!$this->isAdmin()) {
            header('Location: ' . baseUrl());
            exit;
        }

        $stats = $this->analyticsModel->getAdminStats();
        $brandsData = $this->analyticsModel->getListingsPerBrand();
        $topViewed = $this->analyticsModel->getTopViewedCars(10);
        $allCars = $this->carModel->getAllForAdmin();

        require __DIR__ . '/../views/admin.php';
    }

    public function toggleFeatured(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $carId = (int) ($data['car_id'] ?? 0);

        if ($carId > 0) {
            $this->carModel->toggleFeatured($carId);
            $car = $this->carModel->findById($carId);
            echo json_encode(['success' => true, 'is_featured' => (bool) $car['is_featured']]);
        } else {
            echo json_encode(['error' => 'Invalid car ID']);
        }
    }
}
