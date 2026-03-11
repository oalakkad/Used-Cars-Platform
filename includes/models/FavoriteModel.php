<?php
/**
 * FavoriteModel - Database operations for user favorites
 */
class FavoriteModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function toggle(int $userId, int $carId): bool
    {
        if ($this->isFavorited($userId, $carId)) {
            return $this->remove($userId, $carId);
        } else {
            return $this->add($userId, $carId);
        }
    }

    public function add(int $userId, int $carId): bool
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO favorites (user_id, car_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $carId]);
    }

    public function remove(int $userId, int $carId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND car_id = ?");
        return $stmt->execute([$userId, $carId]);
    }

    public function isFavorited(int $userId, int $carId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND car_id = ?");
        $stmt->execute([$userId, $carId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUserFavorites(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, f.id as favorite_id,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM favorites f
             JOIN cars c ON f.car_id = c.id
             WHERE f.user_id = ?
             ORDER BY f.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}
