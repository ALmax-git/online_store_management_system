<?php
class User {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store user
    public function create($name, $username, $password, $email, $phone) {
        $stmt = $this->connection->prepare("INSERT INTO user (name, username, password, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, password_hash($password, PASSWORD_DEFAULT), $email, $phone);
        return $stmt->execute();
    }

    // Find user by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update user
    public function update($id, $name, $username, $email, $phone) {
        $stmt = $this->connection->prepare("UPDATE user SET name = ?, username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $username, $email, $phone, $id);
        return $stmt->execute();
    }

    // Delete user
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
