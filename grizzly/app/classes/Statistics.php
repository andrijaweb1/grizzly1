<?php
require_once "app/config/config.php";
class Scan {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Ispisivanje korisnika
    public function fetch_all() {
        $sql = "SELECT * FROM statistics";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}