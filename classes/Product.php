<?php
class Product {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store product
    public function create($name, $description, $price, $stock, $category_id) {
        $stmt = $this->connection->prepare("INSERT INTO product (name, description, price, brand, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $name, $description, $price, $stock, $category_id);
        return $stmt->execute();
    }

    // Find product by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM product WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    // Find all products
    public function all() {
        $stmt = $this->connection->prepare("SELECT * FROM product");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
    }
    // Update product
    public function update($id, $name, $description, $price, $stock, $category_id) {
        $stmt = $this->connection->prepare("UPDATE product SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdiii", $name, $description, $price, $stock, $category_id, $id);
        return $stmt->execute();
    }

    // Delete product
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM product WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
