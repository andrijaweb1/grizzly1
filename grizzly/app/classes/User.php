<?php
class User {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Ispisivanje korisnika
    public function fetch_all() {
        $sql = "SELECT u.*, c.code_name FROM users u LEFT JOIN codes c ON u.code_id = c.code_id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function user_code_generator($user_id, $join_date) {
        $random = rand(100, 999); // 3-digit random number
        $date = new DateTime($join_date);  // Convert string to DateTime
        $year = $date->format("Y");       // Now format() works
        $padded_user_id = str_pad($user_id, 4, "0", STR_PAD_LEFT); // Pad user_id to 4 digits
        $user_code = "{$padded_user_id}-GRIZZLY-{$year}-{$random}"; // Format: 0001-GRIZ-2025-456
        return $user_code;
    }

    // Pronalazi slobodan bar kod
    private function getAvailableBarcode() {
        $sql = "SELECT * FROM codes WHERE user_id = 0 LIMIT 1";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Dodeljuje bar kod korisniku
    private function assignBarcode($code_id, $user_id) {
        $sql = "UPDATE codes SET user_id = ? WHERE code_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $code_id);
        $stmt->execute();

        $sql = "UPDATE users SET code_id = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $code_id, $user_id);
        $stmt->execute();
    }

    public function create($full_name, $phone, $plan_id, $join_date, $birth_year) {
        // Fetch membership plan details
        $sql = "SELECT duration_days, price FROM membership_plans WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false; // Failed to prepare query
        }
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();
        $stmt->close();

        if (!$plan) {
            return false; // Plan not found
        }

        $duration_days = $plan['duration_days'];
        $debt = $plan['price']; // Set debt to plan price

        // Calculate expiry date
        $expiry_date = date('Y-m-d', strtotime($join_date . " + $duration_days days"));

        // Insert user without user_code first
        $sql = "INSERT INTO users (full_name, phone, plan_id, join_date, expiry_date, birth_year, debt) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false; // Failed to prepare query
        }
        $stmt->bind_param("ssissid", $full_name, $phone, $plan_id, $join_date, $expiry_date, $birth_year, $debt);
        $result = $stmt->execute();

        if ($result) {
            // Get the auto-incremented user_id from the last insert
            $user_id = $this->conn->insert_id;

            // Generate user_code using the new user_id
            $user_code = $this->user_code_generator($user_id, $join_date);

            // Update the user with the user_code
            $sql = "UPDATE users SET user_code = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return false; // Failed to prepare update query
            }
            $stmt->bind_param("si", $user_code, $user_id);
            $stmt->execute();

            // Assign a barcode
            $barcode = $this->getAvailableBarcode();
            if ($barcode) {
                $this->assignBarcode($barcode['code_id'], $user_id);
            } else {
                // Opcionalno: Obradi slučaj kada nema slobodnih bar kodova
                $stmt->close();
                return false;
            }

            $stmt->close();
        } else {
            $stmt->close();
            return false; // Insert failed
        }

        return $result; // True if insertion succeeded, false otherwise
    }

    public function delete($user_id) {
        // Oslobodi bar kod pre brisanja
        $sql = "UPDATE codes SET user_id = 0 WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Obriši korisnika
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $result = $stmt->execute();
        return $result;
    }

    // Oslobađa bar kod kada članstvo istekne ili se deaktivira
    public function releaseBarcode($user_id) {
        $sql = "UPDATE codes SET user_id = 0 WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $sql = "UPDATE users SET code_id = NULL WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    public function get_plan_by_user_id($user_id) {
        $sql = "SELECT plan_id FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['plan_id']; // Return the plan_id integer
        }
        return null; // Return null if no plan is found
    }

    // Traženje korisnika po ID-u
    public function get_user_by_id($user_id) {
        $sql = "SELECT u.*, c.code_name FROM users u LEFT JOIN codes c ON u.code_id = c.code_id WHERE u.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id); // "i" označava integer (broj)
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Vrati podatke o korisniku kao asocijativni niz
    }

    public function renew_membership($user_id, $plan_id) {
        // Get the membership duration from the membership plan
        $sql = "SELECT duration_days FROM membership_plans WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $days = $row['duration_days'];

            // Update user's expire_date by adding the membership duration
            $sql2 = "UPDATE users SET join_date = ?, expiry_date = DATE_ADD(expiry_date, INTERVAL ? DAY) WHERE user_id = ?";
            $stmt2 = $this->conn->prepare($sql2);
            $today = date("Y-m-d");
            $stmt2->bind_param("sii", $today, $days, $user_id);

            if ($stmt2->execute()) {
                header("location: users.php");
                exit();
            } else {
                return "Greška pri ažuriranju članstva.";
            }
        } else {
            return "Plan članstva nije pronađen.";
        }
    }

    public function extend_membership($user_id, $plan_id) {
        $sql = "SELECT duration_days, price FROM membership_plans WHERE plan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $days = $row['duration_days'];
            $price = $row['price'];

            $sql2 = "UPDATE users SET expiry_date = DATE_ADD(expiry_date, INTERVAL ? DAY), debt = debt + ? WHERE user_id = ?";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bind_param("idi", $days, $price, $user_id);
            if ($stmt2->execute()) {
                header("location: users.php");
                exit();
            } else {
                return "Greška pri ažuriranju članstva.";
            }
        } else {
            return "Plan članstva nije pronađen.";
        }
    }

    public function edit_user($user_id, $full_name, $birth_year, $phone, $plan_id, $join_date, $expiry_date, $debt, $user_code) {
        $sql = "UPDATE users SET full_name = ?, birth_year = ?, phone = ?, plan_id = ?, join_date = ?, expiry_date = ?, debt = ?, user_code = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisissdsi", $full_name, $birth_year, $phone, $plan_id, $join_date, $expiry_date, $debt, $user_code, $user_id);
        $result = $stmt->execute();
        return $result ? true : false;
    }

    public function increse_debt($user_id, $debt) {
        $sql = "UPDATE users SET debt = debt + ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $debt, $user_id);
        $result = $stmt->execute();
        return $result ? true : false;
    }

    public function get_user_by_scan_id($scan_id) {
        $sql = "SELECT user_id FROM scans WHERE scan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $scan_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['user_id']; // Return the user_id
        }
        return null; // Return null if no scan is found
    }
}
?>