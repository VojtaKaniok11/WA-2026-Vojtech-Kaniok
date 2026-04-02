<?php
require_once __DIR__ . '/Database.php';

class Book {
    private $conn;
    private $table_name = "books"; // Předpokládáme, že se tabulka jmenuje books

    public function __construct() {
        // Vytvoříme instanci připojení k databázi
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Metoda pro vložení nové knihy do databáze (s rozšířenými položkami)
     */
    public function create($data) {
        // SQL dotaz pro vložení všech dostupných dat z formuláře
        $query = "INSERT INTO " . $this->table_name . " 
                 (title, author, isbn, category, subcategory, year, price, link, description) 
                 VALUES 
                 (:title, :author, :isbn, :category, :subcategory, :year, :price, :link, :description)";
        
        $stmt = $this->conn->prepare($query);

        // Očištění dat v poli pomocí array_map
        $clean_data = array_map(function($item) {
            return htmlspecialchars(strip_tags($item));
        }, $data);

        // Navázání hodnot
        $stmt->bindParam(":title", $clean_data['title']);
        $stmt->bindParam(":author", $clean_data['author']);
        $stmt->bindParam(":isbn", $clean_data['isbn']);
        $stmt->bindParam(":category", $clean_data['category']);
        $stmt->bindParam(":subcategory", $clean_data['subcategory']);
        $stmt->bindParam(":year", $clean_data['year']);
        $stmt->bindParam(":price", $clean_data['price']);
        $stmt->bindParam(":link", $clean_data['link']);
        $stmt->bindParam(":description", $clean_data['description']);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Metoda pro získání všech knih z databáze
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vytáhne jednu konkrétní knihu podle ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Smaže záznam podle ID
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Upraví existující záznam knihy
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET 
                  title = :title, author = :author, isbn = :isbn, category = :category, 
                  subcategory = :subcategory, year = :year, price = :price, 
                  link = :link, description = :description 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $clean_data = array_map(function($item) {
            return htmlspecialchars(strip_tags($item));
        }, $data);

        $stmt->bindParam(":title", $clean_data['title']);
        $stmt->bindParam(":author", $clean_data['author']);
        $stmt->bindParam(":isbn", $clean_data['isbn']);
        $stmt->bindParam(":category", $clean_data['category']);
        $stmt->bindParam(":subcategory", $clean_data['subcategory']);
        $stmt->bindParam(":year", $clean_data['year']);
        $stmt->bindParam(":price", $clean_data['price']);
        $stmt->bindParam(":link", $clean_data['link']);
        $stmt->bindParam(":description", $clean_data['description']);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}
