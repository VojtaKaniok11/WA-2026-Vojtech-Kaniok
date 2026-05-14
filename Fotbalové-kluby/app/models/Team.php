<?php
require_once __DIR__ . '/Database.php';

class Team {
    private $conn;
    private $table_name = "favorite_teams";

    public function __construct() {
        // Vytvoříme instanci připojení k databázi
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Vložení nového oblíbeného týmu do databáze
     */
    public function create($data, $userId) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (team_name, country, league, founded_year, description, user_id) 
                 VALUES 
                 (:team_name, :country, :league, :founded_year, :description, :user_id)";
        
        $stmt = $this->conn->prepare($query);

        // Očištění dat
        $clean_data = array_map(function($item) {
            return htmlspecialchars(strip_tags($item));
        }, $data);

        $stmt->bindParam(":team_name", $clean_data['team_name']);
        $stmt->bindParam(":country", $clean_data['country']);
        $stmt->bindParam(":league", $clean_data['league']);
        $stmt->bindParam(":founded_year", $clean_data['founded_year']);
        $stmt->bindParam(":description", $clean_data['description']);
        $stmt->bindParam(":user_id", $userId);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Získání všech oblíbených týmů konkrétního uživatele
     */
    public function getAllByUser($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Získání jednoho týmu podle ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Smazání záznamu podle ID
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Úprava existujícího záznamu týmu
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET 
                  team_name = :team_name, country = :country, league = :league, 
                  founded_year = :founded_year, description = :description 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $clean_data = array_map(function($item) {
            return htmlspecialchars(strip_tags($item));
        }, $data);

        $stmt->bindParam(":team_name", $clean_data['team_name']);
        $stmt->bindParam(":country", $clean_data['country']);
        $stmt->bindParam(":league", $clean_data['league']);
        $stmt->bindParam(":founded_year", $clean_data['founded_year']);
        $stmt->bindParam(":description", $clean_data['description']);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}
