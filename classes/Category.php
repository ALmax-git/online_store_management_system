<?php
class Category {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store category
    public function create($name, $description) {
        $stmt = $this->connection->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Find category by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update category
    public function update($id, $name, $description) {
        $stmt = $this->connection->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Delete category
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM category WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
