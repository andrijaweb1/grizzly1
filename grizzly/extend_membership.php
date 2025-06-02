<?php
require_once "app/config/config.php";
require_once "app/classes/Transactions.php";
require_once "app/classes/User.php"; 
require_once "app/classes/Membership_Plans.php";

$transaction = new Transaction();
$user = new User(); 
$users = new User(); 
$plan = new Plan();

$user_id = $_GET['id'];
$plan_id = $user->get_plan_by_user_id($user_id);

if ($plan_id === null) {
    // Redirect with an error message if no plan is found
    header("Location: users.php?error=No plan found for user");
    exit();
}

$users->extend_membership($user_id, $plan_id);
exit();
?>