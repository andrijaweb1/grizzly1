<?php
require_once "app/config/config.php";
require_once "app/classes/BarcodeManager.php";
require_once "app/classes/User.php";

$barcodeManager = new BarcodeManager();
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$user = (new User())->get_user_by_id($user_id);
if ($user && $user['code_id']) {
    $barcode = $barcodeManager->verifyBarcode($user['code_name']);
    if ($barcode) {
        $barcodeImagePath = $barcodeManager->generateBarcodeImage($barcode['code_name']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prikaz bar koda</title>
</head>
<body>
    <?php if (isset($barcodeImagePath)): ?>
        <h2>Bar kod za: <?php echo htmlspecialchars($user['full_name']); ?></h2>
        <img src="<?php echo $barcodeImagePath; ?>" alt="Bar kod">
        <p>Kod: <?php echo htmlspecialchars($barcode['code_name']); ?></p>
    <?php else: ?>
        <p>Bar kod nije pronađen ili nije dodeljen.</p>
    <?php endif; ?>
</body>
</html>