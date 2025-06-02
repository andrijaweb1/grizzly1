<?php
require_once 'app/classes/User.php';
require_once 'app/classes/Transactions.php';
require_once 'app/config/config.php';
require_once 'app/classes/Scans.php';


    $user = new User();
    $transaction = new Transaction();
    $scan = new Scan();

    $user_id = $_GET['id'];
    $transaction->delete_transactions_by_user_id($user_id);
    $scan->delete_scans_by_user_id($user_id);
    if ($user->delete($user_id)) {
        header("Location: users.php?message=success");
    } else {
        header("Location: users.php?message=error");
    }


?>