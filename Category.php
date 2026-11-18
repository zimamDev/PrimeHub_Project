<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Category.php
 * CRUD operations for product categories.
 */
class Category
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM categories ORDER BY category_name ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $name, string $description): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (category_name, description) VALUES (:name, :description)"
        );
        return $stmt->execute([':name' => $name, ':description' => $description]);
    }

    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE categories SET category_name = :name, description = :description WHERE category_id = :id"
        );
        return $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE category_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
