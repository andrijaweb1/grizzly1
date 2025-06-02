<?php
require_once "app/config/config.php"; // Učitavanje konekcije

class Transaction {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Ispisivanje svih transakcija
    public function fetch_all() {
        $sql = "SELECT * FROM transactions";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Kreiranje nove transakcije
    public function new_transaction($user_id, $amount) {
        // Proveri da li korisnik postoji i dobavi trenutni balance
        $sql = "SELECT debt FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

            if (!$user) {
                return "Korisnik nije pronađen";
            }

        // Dodaj transakciju u bazu sa statusom "pending"
        $sql = "INSERT INTO transactions (user_id, amount, payment_date, status) VALUES (?, ?, NOW(), 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("id", $user_id, $amount);
        //test kod 1
        $sql1 = "UPDATE users SET debt = debt - ? WHERE user_id = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("di", $amount, $user_id); 
        
        //end of test 1
        if ($stmt->execute() && $stmt1->execute()) {
           //header("location:users.php");//vrati se na ovu liniju
        } else {
            return "Greška pri dodavanju transakcije.";
        }
        
    }
    public function delete_transactions_by_user_id($user_id){
        $sql = ' DELETE FROM transactions WHERE user_id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
    }
    public function approve($status,$tran_id){
        $sql = "UPDATE transactions SET status = ? WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si",$status,$tran_id);
        $result = $stmt->execute();
        return $result ? true : false;
    }
    public function get_user_by_tran_id($tran_id) {
        $sql = "SELECT user_id FROM transactions WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tran_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            
            return $row['user_id'];  // Return the plan_id integer
        }
        return null; // Return null if no plan is found
    }
    public function get_amount_by_tran_id($tran_id) {
        $sql = "SELECT amount FROM transactions WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $tran_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            
            return $row['amount'];  // Return the plan_id integer
        }
        return null; // Return null if no plan is found
    }
    public function get_amount_for_day($date){
        $sql = "SELECT SUM(amount) AS daily_amount FROM transactions WHERE DATE(payment_date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['daily_amount'];
    }
    public function get_amount_for_month($month){
        $sql = "SELECT SUM(amount) AS daily_amount FROM transactions WHERE MONTH(payment_date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['daily_amount'];
    }
    public function get_amount_for_year($year){
        $sql = "SELECT SUM(amount) AS daily_amount FROM transactions WHERE YEAR(payment_date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $year);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['daily_amount'];
    }
    public function get_amount_for_month_and_year($month,$year){
        $sql = "SELECT SUM(amount) AS daily_amount FROM transactions WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $month,$year);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['daily_amount'];
    }

}
