<?php
require_once "app/classes/Membership_Plans.php";
require_once "app/config/config.php";

$plans = new Plan();
$plan_id = $_GET['id'];

if ($plans->delete($plan_id)) {
    header("Location: plans.php?message=success");
} else {
    header("Location: plans.php?message=error");
}
exit();
?>