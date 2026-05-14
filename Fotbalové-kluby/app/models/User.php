<?php
require_once __DIR__ . '/Database.php';

class User
{
    private $conn;
    private $table_name = "users";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register(string $username, string $email, string $password, ?string $firstName = null, ?string $lastName = null, ?string $nickname = null): bool
    {
        if ($this->findByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO " . $this->table_name . " (username, email, password, first_name, last_name, nickname)
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':username'   => $username,
            ':email'      => $email,
            ':password'   => $hashedPassword,
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':nickname'   => $nickname
        ]);
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "SELECT id, username, email, first_name, last_name, nickname, role, created_at FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT id, username, email, first_name, last_name, nickname, role, created_at FROM " . $this->table_name . " ORDER BY username ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $id, string $username, string $email, ?string $firstName, ?string $lastName, ?string $nickname): bool
    {
        $existing = $this->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $id) {
            return false;
        }

        $sql = "UPDATE " . $this->table_name . "
                SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, nickname = :nickname
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':username'   => $username,
            ':email'      => $email,
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':nickname'   => $nickname,
            ':id'         => $id
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':password' => $hashed, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
