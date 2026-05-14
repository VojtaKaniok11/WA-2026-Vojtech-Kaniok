<?php
require_once __DIR__ . '/Database.php';

class Team {
    private $conn;
    private $table_name = "favorite_teams";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($data, $userId) {
        $query = "INSERT INTO " . $this->table_name . "
                 (team_name, country, league, founded_year, description, image, user_id)
                 VALUES
                 (:team_name, :country, :league, :founded_year, :description, :image, :user_id)";

        $stmt = $this->conn->prepare($query);

        $clean = array_map(fn($v) => htmlspecialchars(strip_tags((string)$v)), $data);

        $stmt->bindParam(':team_name',    $clean['team_name']);
        $stmt->bindParam(':country',      $clean['country']);
        $stmt->bindParam(':league',       $clean['league']);
        $stmt->bindParam(':founded_year', $clean['founded_year']);
        $stmt->bindParam(':description',  $clean['description']);
        $stmt->bindParam(':image',        $data['image']);
        $stmt->bindParam(':user_id',      $userId);

        return $stmt->execute();
    }

    public function getAllByUser($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT ft.*, u.username AS updated_by_name
                  FROM " . $this->table_name . " ft
                  LEFT JOIN users u ON ft.updated_by = u.id
                  WHERE ft.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function update($id, $data, $updatedBy, ?string $image = null) {
        if ($image !== null) {
            $query = "UPDATE " . $this->table_name . " SET
                      team_name = :team_name, country = :country, league = :league,
                      founded_year = :founded_year, description = :description,
                      image = :image, updated_by = :updated_by
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . " SET
                      team_name = :team_name, country = :country, league = :league,
                      founded_year = :founded_year, description = :description,
                      updated_by = :updated_by
                      WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $clean = array_map(fn($v) => htmlspecialchars(strip_tags((string)$v)), $data);

        $stmt->bindParam(':team_name',    $clean['team_name']);
        $stmt->bindParam(':country',      $clean['country']);
        $stmt->bindParam(':league',       $clean['league']);
        $stmt->bindParam(':founded_year', $clean['founded_year']);
        $stmt->bindParam(':description',  $clean['description']);
        $stmt->bindParam(':updated_by',   $updatedBy);
        $stmt->bindParam(':id',           $id);

        if ($image !== null) {
            $stmt->bindParam(':image', $image);
        }

        return $stmt->execute();
    }
}
