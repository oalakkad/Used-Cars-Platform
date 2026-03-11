<?php
/**
 * CarModel - Database operations for car listings
 */
class CarModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO cars (user_id, brand, model, year, mileage, price, fuel_type, transmission, condition_status, description, location, location_lat, location_lng, whatsapp_phone, damage_data)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['user_id'],
            $data['brand'],
            $data['model'],
            $data['year'],
            $data['mileage'],
            $data['price'],
            $data['fuel_type'],
            $data['transmission'],
            $data['condition_status'],
            $data['description'],
            $data['location'] ?? null,
            $data['location_lat'] ?? null,
            $data['location_lng'] ?? null,
            $data['whatsapp_phone'] ?? null,
            $data['damage_data'] ?? null
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE cars SET brand=?, model=?, year=?, mileage=?, price=?, fuel_type=?, transmission=?, condition_status=?, description=?, location=?, location_lat=?, location_lng=?, whatsapp_phone=?, damage_data=?
             WHERE id=?"
        );
        return $stmt->execute([
            $data['brand'],
            $data['model'],
            $data['year'],
            $data['mileage'],
            $data['price'],
            $data['fuel_type'],
            $data['transmission'],
            $data['condition_status'],
            $data['description'],
            $data['location'] ?? null,
            $data['location_lat'] ?? null,
            $data['location_lng'] ?? null,
            $data['whatsapp_phone'] ?? null,
            $data['damage_data'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM cars WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.name as seller_name, u.email as seller_email,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c
             JOIN users u ON c.user_id = u.id
             WHERE c.id = ?"
        );
        $stmt->execute([$id]);
        $car = $stmt->fetch();
        return $car ?: null;
    }

    public function getLatest(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c ORDER BY c.created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function search(array $filters, int $limit = 20, int $offset = 0): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['brand'])) {
            $where[] = "c.brand = ?";
            $params[] = $filters['brand'];
        }
        if (!empty($filters['model'])) {
            $where[] = "c.model LIKE ?";
            $params[] = '%' . $filters['model'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $where[] = "c.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "c.price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['min_year'])) {
            $where[] = "c.year >= ?";
            $params[] = $filters['min_year'];
        }
        if (!empty($filters['max_year'])) {
            $where[] = "c.year <= ?";
            $params[] = $filters['max_year'];
        }
        if (!empty($filters['year'])) {
            $where[] = "c.year = ?";
            $params[] = $filters['year'];
        }
        if (!empty($filters['max_mileage'])) {
            $where[] = "c.mileage <= ?";
            $params[] = $filters['max_mileage'];
        }
        if (!empty($filters['fuel_type'])) {
            $where[] = "c.fuel_type = ?";
            $params[] = $filters['fuel_type'];
        }
        if (!empty($filters['transmission'])) {
            $where[] = "c.transmission = ?";
            $params[] = $filters['transmission'];
        }
        if (!empty($filters['location'])) {
            $where[] = "c.location LIKE ?";
            $params[] = '%' . $filters['location'] . '%';
        }
        if (!empty($filters['q'])) {
            $where[] = "(c.brand LIKE ? OR c.model LIKE ? OR c.description LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        $whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM cars c $whereClause");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        $orderBy = match ($sort) {
            'price_low' => 'c.price ASC',
            'price_high' => 'c.price DESC',
            'mileage_low' => 'c.mileage ASC',
            'year_new' => 'c.year DESC',
            'most_viewed' => 'c.views_count DESC',
            default => 'c.is_featured DESC, c.created_at DESC',
        };

        // Fetch results
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c $whereClause
             ORDER BY $orderBy LIMIT ? OFFSET ?"
        );
        $stmt->execute($params);
        $cars = $stmt->fetchAll();

        return ['cars' => $cars, 'total' => (int) $total];
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c WHERE c.user_id = ? ORDER BY c.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getByBrand(string $brand): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c WHERE c.brand = ? ORDER BY c.created_at DESC"
        );
        $stmt->execute([$brand]);
        return $stmt->fetchAll();
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function getDistinctBrands(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT brand FROM cars ORDER BY brand");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getModelsByBrand(string $brand): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT model FROM cars WHERE brand = ? ORDER BY model");
        $stmt->execute([$brand]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function belongsToUser(int $carId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cars WHERE id = ? AND user_id = ?");
        $stmt->execute([$carId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c WHERE c.is_featured = 1 ORDER BY c.created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function toggleFeatured(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE cars SET is_featured = NOT is_featured WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAvgPriceByBrandModel(string $brand, string $model, int $year): array
    {
        $stmt = $this->db->prepare(
            "SELECT AVG(price) as avg_price, MIN(price) as min_price, MAX(price) as max_price, COUNT(*) as count
             FROM cars WHERE brand = ? AND model = ? AND year BETWEEN ? AND ?"
        );
        $stmt->execute([$brand, $model, $year - 2, $year + 2]);
        return $stmt->fetch() ?: ['avg_price' => 0, 'min_price' => 0, 'max_price' => 0, 'count' => 0];
    }

    public function getAllForAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, u.name as seller_name, u.email as seller_email,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
             FROM cars c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getSellerProfile(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }
}
