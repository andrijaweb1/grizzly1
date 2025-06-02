<?php
require_once "app/classes/Transactions.php";
require_once "app/config/config.php";
require_once "app/classes/User.php";

$transaction = new Transaction();
$user_transaction = new Transaction();
$amm = new Transaction();
$korisnik = new User();
$row = $_GET['id'];
$bool = substr($row,0,1);
$tran_id = substr($row,1,4);
if($bool == 1){
$transaction->approve("completed", $tran_id); 
}else if($bool == 0){
    $transaction->approve("reverted", $tran_id); 
    $user_id = $user_transaction->get_user_by_tran_id($tran_id);
    $amount = $amm->get_amount_by_tran_id($tran_id);
    $korisnik->increse_debt($user_id,$amount);
    
}

header("Location:payments.php");
?>