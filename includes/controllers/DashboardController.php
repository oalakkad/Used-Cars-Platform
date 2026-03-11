<?php
/**
 * DashboardController - User dashboard with stats, listings, and favorites
 */
class DashboardController
{

    public function index(): void
    {
        if (!isLoggedIn()) {
            flash('error', 'Please log in to access your dashboard.');
            redirect(baseUrl('login'));
        }

        $carModel = new CarModel();
        $favModel = new FavoriteModel();

        $userId = currentUserId();
        $myCars = $carModel->getByUser($userId);
        $myFavorites = $favModel->getUserFavorites($userId);
        $totalCars = $carModel->countByUser($userId);
        $totalFavorites = $favModel->countByUser($userId);
        $success = flash('success');
        $error = flash('error');

        require __DIR__ . '/../views/dashboard.php';
    }
}
