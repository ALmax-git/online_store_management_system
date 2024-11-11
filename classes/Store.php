<?php
class Store {

    private $connection;

    public $store = '';
    public $name = '';
    public $location = '';
    public $number_staff = '';
    public $count_orders = '';
    public $type = '';
    public $email = '';
    public $user_id = '';

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Create and store
    // Create and store
   public function create() {
       $stmt = $this->connection->prepare("INSERT INTO stores (name, location, number_staff, count_orders, type, email, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
       $stmt->bind_param("ssssssi", $this->name, $this->location, $this->number_staff, $this->count_orders, $this->type, $this->email, $this->user_id);
       
       if ($stmt->execute()) {
           return $this->connection->insert_id; // Return the last inserted ID on success
       } else {
           return false; // Return false on failure
       }
   }

    // Find store by ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM stores WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $this->store = $stmt->get_result()->fetch_assoc();
            $this->name = $this->store['name'];
            $this->location = $this->store['location'];
            $this->number_staff = $this->store['number_staff'];
            $this->count_orders = $this->store['count_orders'];
            $this->type = $this->store['type'];
            $this->email = $this->store['email'];
            $this->user_id = $this->store['user_id'];
            return true;
        }else{
            return false;
        }

    }

    // Update category
    public function update($id) {
        $stmt = $this->connection->prepare("UPDATE stores SET name = ?, location = ?, number_staff = ?, count_orders = ?, type = ?, email = ?  WHERE id = ?");
        $stmt->bind_param("ssssssi", $this->name, $this->location, $this->number_staff, $this->count_orders, $this->type,  $this->email, $id);
        return $stmt->execute();
    }

    // Delete category
    public function delete($id, $user_id) {
        $store = $this->find($id);
        if($store->user_id == $user_id){
            $stmt = $this->connection->prepare("DELETE FROM stores WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }else{
            return false;
        }
    }
}
