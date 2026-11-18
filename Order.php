<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Product.php';

/**
 * Order.php
 * Handles order placement (checkout), order tracking, and admin order management.
 */
class Order
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Place an order from cart items.
     * $cartItems = [[product => [...], qty => n, subtotal => n], ...]
     * Returns the new order_id on success, or false on failure (e.g. insufficient stock).
     */
    public function placeOrder(int $userId, array $cartItems, string $shippingAddress)
    {
        try {
            $this->conn->beginTransaction();

            $productModel = new Product();
            $total = 0;
            foreach ($cartItems as $item) {
                if ($item['product']['stock_qty'] < $item['qty']) {
                    $this->conn->rollBack();
                    return false; // insufficient stock
                }
                $total += $item['subtotal'];
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO orders (user_id, total_amount, shipping_address, status)
                 VALUES (:uid, :total, :address, 'Pending')"
            );
            $stmt->execute([':uid' => $userId, ':total' => $total, ':address' => $shippingAddress]);
            $orderId = (int) $this->conn->lastInsertId();

            $itemStmt = $this->conn->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity, unit_price)
                 VALUES (:oid, :pid, :qty, :price)"
            );

            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    ':oid'   => $orderId,
                    ':pid'   => $item['product']['product_id'],
                    ':qty'   => $item['qty'],
                    ':price' => $item['product']['price'],
                ]);
                $productModel->reduceStock((int) $item['product']['product_id'], (int) $item['qty']);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getOrdersByUser(int $userId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM orders WHERE user_id = :uid ORDER BY order_date DESC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getOrderById(int $orderId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_id = :id");
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetch();
    }

    public function getOrderItems(int $orderId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, p.product_name, p.image FROM order_items oi
             JOIN products p ON oi.product_id = p.product_id
             WHERE oi.order_id = :id"
        );
        $stmt->execute([':id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getAllOrders(): array
    {
        $sql = "SELECT o.*, u.full_name, u.email FROM orders o
                JOIN users u ON o.user_id = u.user_id
                ORDER BY o.order_date DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE order_id = :id");
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }

    public function countAll(): int
    {
        return (int) $this->conn->query("SELECT COUNT(*) AS total FROM orders")->fetch()['total'];
    }

    public function totalRevenue(): float
    {
        $stmt = $this->conn->query(
            "SELECT SUM(total_amount) AS revenue FROM orders WHERE status != 'Cancelled'"
        );
        return (float) ($stmt->fetch()['revenue'] ?? 0);
    }
}
