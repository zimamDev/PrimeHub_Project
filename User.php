<?php
require_once __DIR__ . '/../config/database.php';

/**
 * User.php
 * Handles customer/admin registration, login, and profile data.
 */
class User
{
    private PDO $conn;

    public int $user_id = 0;
    public string $full_name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $role = 'customer';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /** Register a new customer. Returns true on success, string error message on failure. */
    public function register(string $fullName, string $email, string $password, string $phone, string $address)
    {
        if ($this->emailExists($email)) {
            return 'An account with this email already exists.';
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (full_name, email, password, phone, address, role)
                VALUES (:full_name, :email, :password, :phone, :address, 'customer')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':full_name' => $fullName,
            ':email'     => $email,
            ':password'  => $hashedPassword,
            ':phone'     => $phone,
            ':address'   => $address,
        ]);

        return true;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetch();
    }

    /** Attempt login. Returns the user array on success, or false on failure. */
    public function login(string $email, string $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getById(int $userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }

    public function updateProfile(int $userId, string $fullName, string $phone, string $address): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users SET full_name = :full_name, phone = :phone, address = :address WHERE user_id = :id"
        );
        return $stmt->execute([
            ':full_name' => $fullName,
            ':phone'     => $phone,
            ':address'   => $address,
            ':id'        => $userId,
        ]);
    }

    public function getAllCustomers(): array
    {
        $stmt = $this->conn->query(
            "SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function countCustomers(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'customer'");
        return (int) $stmt->fetch()['total'];
    }
}
