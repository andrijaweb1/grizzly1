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
        $sql = "SELECT * FROM scans";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function create( $user_id,$scan_datetime){
        $sql = "INSERT INTO scans (user_id, scan_datetime) VALUES (?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $scan_datetime);
        $stmt->execute();
        
    }
    public function delete_scans_by_user_id($user_id){
        $sql = "DELETE FROM scans WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bind_param("i", $user_id);
        $stmt->execute();
    }
    public function timeintervalvisits($start, $end, $month) {
        $sql = "SELECT COUNT(scan_id) AS scan_count FROM scans WHERE TIME(scan_datetime) BETWEEN ? AND ? AND MONTH(scan_datetime) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $start, $end, $month); // "ssi" because $month is likely an integer
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['scan_count'];
    }
    public function get_latest_id() {
        try {
            $sql = "SELECT MAX(scan_id) AS max_id FROM scans";
            $stmt = $this->conn->prepare($sql);
            
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return (int)$row['max_id']; // Cast to integer for safety
            }
            
            return null; // Return null if no scans are found
        } catch (Exception $e) {
            error_log("Error in get_latest_id: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
    
}