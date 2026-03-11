<?php
/**
 * AnalyticsModel - Tracking views, clicks, and admin statistics
 */
class AnalyticsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function recordView(int $carId, string $ip, ?int $userId = null): void
    {
        // Avoid duplicate views from same IP within 1 hour
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM car_views WHERE car_id = ? AND viewer_ip = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)"
        );
        $stmt->execute([$carId, $ip]);
        if ($stmt->fetchColumn() > 0)
            return;

        $stmt = $this->db->prepare("INSERT INTO car_views (car_id, viewer_ip, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$carId, $ip, $userId]);

        $stmt = $this->db->prepare("UPDATE cars SET views_count = views_count + 1 WHERE id = ?");
        $stmt->execute([$carId]);
    }

    public function getViewsForCar(int $carId): int
    {
        $stmt = $this->db->prepare("SELECT views_count FROM cars WHERE id = ?");
        $stmt->execute([$carId]);
        return (int) $stmt->fetchColumn();
    }

    public function incrementWhatsAppClick(int $carId): void
    {
        $stmt = $this->db->prepare("UPDATE cars SET whatsapp_clicks = whatsapp_clicks + 1 WHERE id = ?");
        $stmt->execute([$carId]);
    }

    public function getTopViewedCars(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.name as seller_name,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c JOIN users u ON c.user_id = u.id
             ORDER BY c.views_count DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getAdminStats(): array
    {
        $stats = [];
        $stats['total_cars'] = (int) $this->db->query("SELECT COUNT(*) FROM cars")->fetchColumn();
        $stats['total_users'] = (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['total_views'] = (int) $this->db->query("SELECT SUM(views_count) FROM cars")->fetchColumn();
        $stats['total_favorites'] = (int) $this->db->query("SELECT COUNT(*) FROM favorites")->fetchColumn();
        $stats['featured_count'] = (int) $this->db->query("SELECT COUNT(*) FROM cars WHERE is_featured = 1")->fetchColumn();
        return $stats;
    }

    public function getListingsPerBrand(): array
    {
        $stmt = $this->db->query("SELECT brand, COUNT(*) as count FROM cars GROUP BY brand ORDER BY count DESC");
        return $stmt->fetchAll();
    }

    public function getViewsPerDay(int $days = 30): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(viewed_at) as date, COUNT(*) as views FROM car_views WHERE viewed_at > DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY DATE(viewed_at) ORDER BY date"
        );
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
