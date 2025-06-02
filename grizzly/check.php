<?php
require_once "app/classes/Scans.php";
$scan = new Scan();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_code = $_GET['user_code'] ?? ''; // Get the user_code from the input
    $date = date('Y-m-d H:i:s');
    
    $user_id = substr($user_code,0,4);
    $user_id = intval($user_id);
    echo "User code entered: " . htmlspecialchars($user_id);
    $scan->create($user_id,$date);
    header('Location: tablet.php');
}
?>