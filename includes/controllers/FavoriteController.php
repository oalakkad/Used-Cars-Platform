<?php
/**
 * FavoriteController - Handles favorite toggling (supports AJAX)
 */
class FavoriteController
{

    private FavoriteModel $favModel;

    public function __construct()
    {
        $this->favModel = new FavoriteModel();
    }

    public function toggle(): void
    {
        if (!isLoggedIn()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['error' => 'Please log in to save favorites.']);
            exit;
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        if ($carId <= 0) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => 'Invalid car ID.']);
            exit;
        }

        $this->favModel->toggle(currentUserId(), $carId);
        $isFavorited = $this->favModel->isFavorited(currentUserId(), $carId);

        header('Content-Type: application/json');
        echo json_encode(['favorited' => $isFavorited]);
        exit;
    }

    public function remove(int $carId): void
    {
        if (!isLoggedIn()) {
            redirect(baseUrl('login'));
        }
        $this->favModel->remove(currentUserId(), $carId);
        flash('success', 'Removed from favorites.');
        redirect(baseUrl('dashboard'));
    }
}
