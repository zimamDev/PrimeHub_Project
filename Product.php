<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Product.php
 * Handles product catalog browsing, searching, and admin CRUD/inventory logic.
 */
class Product
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT p.*, c.category_name FROM products p
                JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.created_at DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.category_name FROM products p
             JOIN categories c ON p.category_id = c.category_id
             WHERE p.product_id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.category_name FROM products p
             JOIN categories c ON p.category_id = c.category_id
             WHERE p.category_id = :cid
             ORDER BY p.product_name ASC"
        );
        $stmt->execute([':cid' => $categoryId]);
        return $stmt->fetchAll();
    }

    /** Search catalog by keyword (product name or description) and optional category filter. */
    public function search(string $keyword, int $categoryId = 0): array
    {
        $sql = "SELECT p.*, c.category_name FROM products p
                JOIN categories c ON p.category_id = c.category_id
                WHERE (p.product_name LIKE :kw OR p.description LIKE :kw)";
        $params = [':kw' => "%{$keyword}%"];

        if ($categoryId > 0) {
            $sql .= " AND p.category_id = :cid";
            $params[':cid'] = $categoryId;
        }

        $sql .= " ORDER BY p.product_name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(int $categoryId, string $name, string $description, float $price, int $stock, string $image): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (category_id, product_name, description, price, stock_qty, image)
             VALUES (:cid, :name, :description, :price, :stock, :image)"
        );
        return $stmt->execute([
            ':cid'         => $categoryId,
            ':name'        => $name,
            ':description' => $description,
            ':price'       => $price,
            ':stock'       => $stock,
            ':image'       => $image,
        ]);
    }

    public function update(int $id, int $categoryId, string $name, string $description, float $price, int $stock, ?string $image = null): bool
    {
        if ($image !== null) {
            $stmt = $this->conn->prepare(
                "UPDATE products SET category_id = :cid, product_name = :name, description = :description,
                 price = :price, stock_qty = :stock, image = :image WHERE product_id = :id"
            );
            return $stmt->execute([
                ':cid' => $categoryId, ':name' => $name, ':description' => $description,
                ':price' => $price, ':stock' => $stock, ':image' => $image, ':id' => $id,
            ]);
        }

        $stmt = $this->conn->prepare(
            "UPDATE products SET category_id = :cid, product_name = :name, description = :description,
             price = :price, stock_qty = :stock WHERE product_id = :id"
        );
        return $stmt->execute([
            ':cid' => $categoryId, ':name' => $name, ':description' => $description,
            ':price' => $price, ':stock' => $stock, ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Reduce stock when an order is placed. */
    public function reduceStock(int $productId, int $qty): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE products SET stock_qty = stock_qty - :qty WHERE product_id = :id AND stock_qty >= :qty2"
        );
        return $stmt->execute([':qty' => $qty, ':id' => $productId, ':qty2' => $qty]);
    }

    public function countAll(): int
    {
        return (int) $this->conn->query("SELECT COUNT(*) AS total FROM products")->fetch()['total'];
    }

    public function lowStock(int $threshold = 5): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM products WHERE stock_qty <= :t ORDER BY stock_qty ASC"
        );
        $stmt->execute([':t' => $threshold]);
        return $stmt->fetchAll();
    }
}
