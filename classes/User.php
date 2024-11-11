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
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $this->id = $user_data['id'];
            $this->name = $user_data['name'];
            $this->username = $user_data['username'];
            $this->email = $user_data['email'];
            $this->store_id = $user_data['store_id'];
            $this->phone = $user_data['phone'];
            $this->role = $user_data['role'];
            return $this;
        } else {
            return null; // Return null if no user is found
        }
    }

 public function update($id) {
    // Fetch current user data for fallback values
    $stmt1 = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $result = $stmt1->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        // Assign default values if properties are not set
        $name = $this->name ?? $user_data['name'];
        $username = $this->username ?? $user_data['username'];
        $email = $this->email ?? $user_data['email'];
        $phone = $this->phone ?? $user_data['phone'];
        $store_id = $this->store_id ?? $user_data['store_id'];

        // Prepare the update statement
        $stmt = $this->connection->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, store_id = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $name, $username, $email, $phone, $store_id, $id);

        return $stmt->execute();
    } else {
        return 'An error occurred: User not found';
    }
}


    // Delete user
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
