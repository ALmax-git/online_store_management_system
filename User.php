<?php
// User.php - User Model
require_once 'db.php'; // Include the database connection

class User {
    private $connection;

    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    // Create a new user
    public function createUser($name, $username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $sql = "INSERT INTO user (name, username, password) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sss", $name, $username, $hashedPassword);

        return $stmt->execute();
    }

    // Get user by ID
    public function getUserById($id) {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get user by username
    public function getUserByUsername($username) {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

// Instantiate the User model with the database connection
$userModel = new User($connection);
?>
