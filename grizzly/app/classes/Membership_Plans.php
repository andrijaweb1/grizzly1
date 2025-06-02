<?php 
class Plan {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Fetch all plans
    public function fetch_all() {
        $sql = "SELECT * FROM membership_plans";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Create a new plan
    public function create($plan_name, $price, $duration_days) {
        $sql = "INSERT INTO membership_plans (plan_name, price, duration_days) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $plan_name, $price, $duration_days);
        $result = $stmt->execute();
        if ($result) {
            $_SESSION['plan_id'] = $this->conn->insert_id;
            return true;
        } else {
            return false;
        }
    }

    // Edit an existing plan
    public function edit($plan_id, $plan_name, $price, $duration_days) {
        $sql = "UPDATE membership_plans SET plan_name = ?, price = ?, duration_days = ? WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siid", $plan_name, $price, $duration_days, $plan_id);
        $result = $stmt->execute();
        return $result ? true : false;
    }

    // Check if a plan is in use by any users
    public function is_plan_in_use($plan_id) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Delete a plan (with check for usage)
    public function delete($plan_id) {
        if ($this->is_plan_in_use($plan_id)) {
            return "cannot_delete_in_use";
        }
        $sql = "DELETE FROM membership_plans WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $plan_id);
        $result = $stmt->execute();
        return $result ? true : false;
    }

    // Fetch a single plan by ID
    public function fetch_by_id($plan_id) {
        $sql = "SELECT * FROM membership_plans WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Returns a single row as an associative array, or NULL if no row is found
    }
}