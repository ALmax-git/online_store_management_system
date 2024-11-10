<?php
class User {
    private $connection;
    public $user;
    public $id;
    public $name;
    public $username;
    public $email;
    public $store_id;
    public $phone;
    public $role;


    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store user
    public function create($name, $username, $password, $email, $phone) {
        $stmt = $this->connection->prepare("INSERT INTO users (name, username, password, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, password_hash($password, PASSWORD_DEFAULT), $email, $phone);
        return $stmt->execute();
    }

    // Find user by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $this->user = $stmt->get_result()->fetch_assoc();
            $this->name = $this->user['name'];
            $this->id = $this->user['id'];
            $this->role = $this->user['role'];
            $this->name = $this->user['name'] ?? 'Name not set';
            $this->email = $this->user['email'] ?? 'Email not set';
            $this->store_id = $this->user['store_id'] ?? null;
            $this->phone = $this->user['phone'] ?? 'Phone Number not Set';
           return true;
        }else{
            return false;
        }
    }

    // Update user
    public function update($id) {
        if($this->find($id)){
            $stmt = $this->connection->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $this->name, $this->username, $this->email, $this->phone, $this->store_id, $id);
            return $stmt->execute();
        }
    }

    // Delete user
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
