<?php
class Order {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store order
    public function create($user_id, $total, $status) {
        $stmt = $this->connection->prepare("INSERT INTO `order` (user_id, total, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total, $status);
        return $stmt->execute();
    }

    // Find order by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM `order` WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update order
    public function update($id, $status) {
        $stmt = $this->connection->prepare("UPDATE `order` SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // Delete order
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM `order` WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
