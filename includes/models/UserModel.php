<?php
/**
 * UserModel - Database operations for users
 */
class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function create(string $name, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
