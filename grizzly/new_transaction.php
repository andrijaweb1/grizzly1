<?php
require_once "app/config/config.php";
require_once "app/classes/Transactions.php";
require_once "app/classes/User.php"; // Učitaj klasu User
require_once "app/classes/Membership_Plans.php";


$transaction = new Transaction();
$user = new User(); // Instanciraj User klasu
$plan = new Plan();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST); // Provera da li su podaci poslati
    $extend_membership = isset($_POST['extend_membership']) ? 1 : 0; // Ako je čekiran, postavlja na 1
    var_dump("vrednost = " . $extend_membership); // Provera gde je greska
    if (isset($_POST['user_id']) && isset($_POST['amount'])) {
        var_dump("bravo 1"); // Provera gde je greska
        $user_id = intval($_POST['user_id']);
        $amount = floatval($_POST['amount']);
        $plan_id = $user->get_plan_by_user_id($user_id);
        // Poziv transakcije
        $message = $transaction->new_transaction($user_id, $amount);

        // Ako je čekirano produženje članstva, pozovi renew_membership()
        if ($extend_membership==1 ) {// Ovaj deo koda se ne ispunjava:      && isset($_POST['plan_id'])
            var_dump("bravo 2"); // Provera gde je greska
            $user->renew_membership($user_id, $plan_id);
            
        }else{
            header("location: users.php");
        }

        echo $message;
    } else {
        echo "Nedostaju podaci.";
    }
} else {
    echo "Greška u metodi zahteva.";
}
//greska kod checkboxa