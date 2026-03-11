<?php
/**
 * CarImageModel - Database operations for car images
 */
class CarImageModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function addImage(int $carId, string $imagePath, bool $isPrimary = false): int
    {
        $stmt = $this->db->prepare("INSERT INTO car_images (car_id, image_path, is_primary) VALUES (?, ?, ?)");
        $stmt->execute([$carId, $imagePath, $isPrimary ? 1 : 0]);
        return (int) $this->db->lastInsertId();
    }

    public function getByCarId(int $carId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM car_images WHERE car_id = ? ORDER BY is_primary DESC, id ASC");
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    public function deleteByCarId(int $carId): bool
    {
        // Get images first to delete files
        $images = $this->getByCarId($carId);
        foreach ($images as $img) {
            $filepath = UPLOAD_DIR . $img['image_path'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
        $stmt = $this->db->prepare("DELETE FROM car_images WHERE car_id = ?");
        return $stmt->execute([$carId]);
    }

    public function uploadImages(int $carId, array $files): array
    {
        $uploaded = [];
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $isFirst = true;
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK)
                continue;
            if ($files['size'][$i] > MAX_UPLOAD_SIZE)
                continue;

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($files['tmp_name'][$i]);
            if (!in_array($mimeType, ALLOWED_IMAGE_TYPES))
                continue;

            $ext = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg'
            };

            $filename = 'car_' . $carId . '_' . uniqid() . '.' . $ext;
            $destination = UPLOAD_DIR . $filename;

            if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
                $this->addImage($carId, $filename, $isFirst);
                $uploaded[] = $filename;
                $isFirst = false;
            }
        }
        return $uploaded;
    }
}
