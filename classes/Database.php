<?php
class Database {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli("127.0.0.1", "root", "", "o_s_m_s");
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
