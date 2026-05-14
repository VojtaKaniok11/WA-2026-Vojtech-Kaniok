<?php
require_once __DIR__ . '/Database.php';

class Comment
{
    private $conn;
    private $table_name = "user_comments";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($profileId, $authorId, $text)
    {
        $query = "INSERT INTO " . $this->table_name . " (profile_id, author_id, comment_text) VALUES (:profile_id, :author_id, :comment_text)";
        $stmt = $this->conn->prepare($query);

        $text = htmlspecialchars(strip_tags($text));

        $stmt->bindParam(':profile_id', $profileId);
        $stmt->bindParam(':author_id', $authorId);
        $stmt->bindParam(':comment_text', $text);

        return $stmt->execute();
    }

    public function getByProfile($profileId)
    {
        $query = "SELECT c.*, u.username as author_name, u.first_name, u.last_name
                  FROM " . $this->table_name . " c
                  JOIN users u ON c.author_id = u.id
                  WHERE c.profile_id = :profile_id
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':profile_id', $profileId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(int $id, string $text): bool
    {
        $text = htmlspecialchars(strip_tags($text));
        $query = "UPDATE " . $this->table_name . " SET comment_text = :text WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':text' => $text, ':id' => $id]);
    }

    public function deleteById(int $id): bool
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
