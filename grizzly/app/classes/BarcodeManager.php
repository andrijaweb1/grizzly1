<?php
require_once "app/config/config.php";

class BarcodeManager {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Ispisivanje svih bar kodova
    public function fetch_all() {
        $sql = "SELECT * FROM codes";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Pronalazi slobodan bar kod
    public function getAvailableBarcode() {
        $sql = "SELECT * FROM codes WHERE user_id = 0 LIMIT 1";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Dodeljuje bar kod korisniku
    public function assignBarcode($code_id, $user_id) {
        $sql = "UPDATE codes SET user_id = ? WHERE code_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $code_id);
        $stmt->execute();

        $sql = "UPDATE users SET code_id = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $code_id, $user_id);
        $stmt->execute();
    }

    // Oslobađa bar kod
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

    // Opcionalno: Generisanje slike bar koda
    public function generateBarcodeImage($code_name) {
        require_once 'vendor/autoload.php';
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodeImage = $generator->getBarcode($code_name, $generator::TYPE_CODE_128);
        $filePath = "barcodes/{$code_name}.png";
        file_put_contents($filePath, $barcodeImage);
        return $filePath;
    }

    // Provera bar koda prilikom skeniranja
    public function verifyBarcode($code_name) {
        $sql = "SELECT c.*, u.full_name, u.expiry_date FROM codes c 
                LEFT JOIN users u ON c.user_id = u.user_id 
                WHERE c.code_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $code_name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>