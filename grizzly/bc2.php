<?php
require_once "app/config/config.php";
require_once "app/classes/BarcodeManager.php";
require_once "app/classes/User.php";

$barcodeManager = new BarcodeManager();
$userManager = new User();

// Dohvati sve bar kodove
$barcodes = $barcodeManager->fetch_all();
?>
<?php require_once "includes/sidebar.php"; ?>
<div class="main-content">
    <h1>Lista svih bar kodova</h1>
    <table>
        <thead>
            <tr>
                <th>Vrednost bar koda</th>
                <th>Korisnik</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($barcodes as $barcode): ?>
                <tr>
                    <td><?php echo htmlspecialchars($barcode['code_name']); ?></td>
                    <td>
                    
                    </td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .status-assigned {
        color: green;
        font-weight: bold;
    }
    .status-free {
        color: red;
        font-weight: bold;
    }
</style>
</body>
</html>