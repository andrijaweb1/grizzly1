<?php
require_once "app/config/config.php";
require_once "app/classes/Scans.php";
require_once "app/classes/User.php";
require_once "app/classes/Membership_Plans.php"; // Assuming a Plan class exists for plan details

try {
    $scan = new Scan();
    $scan_id = $scan->get_latest_id();

    if ($scan_id === null) {
        throw new Exception("No scans found.");
    }

    $user = new User();
    $user_id = $user->get_user_by_scan_id($scan_id);

    if ($user_id === null) {
        throw new Exception("User not found for scan ID: $scan_id");
    }

    $user_data = $user->get_user_by_id($user_id);

    if ($user_data === null || empty($user_data)) {
        throw new Exception("User data not found for user ID: $user_id");
    }

    $full_name = $user_data['full_name'] ?? 'Nepoznato';
    $plan_id = $user_data['plan_id'] ?? null;
    $expiry_date = $user_data['expiry_date'] ?? 'N/A';
    $debt = $user_data['debt'] ?? 0;

    // Fetch plan name (assuming Plan class exists)
    $plan = new Plan();
    $data = $plan_id ? $plan->fetch_by_id($plan_id) : 'Nema plana';
    $plan_name = $data['plan_name'];
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $full_name = "Greška";
    $plan_name = "N/A";
    $expiry_date = "N/A";
    $debt = 0;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prikaz korisnika teretane</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black flex items-center justify-center h-screen">
  <div class="bg-black rounded-lg shadow-lg p-8 max-w-2xl w-full text-center border border-red-600">
    <!-- Logo teretane -->
    <img src="public/images/logo.jpeg" alt="Logo teretane" class="mx-auto mb-6 w-32 h-32">

    <!-- Ime i prezime -->
    <h1 class="text-4xl font-bold text-white mb-4"><?php echo htmlspecialchars($full_name); ?></h1>

    <!-- Paket -->
    <p class="text-xl text-white mb-2">Trenutni paket: <span class="font-semibold text-red-600"><?php echo htmlspecialchars($plan_name); ?></span></p>

    <!-- Preostalo vreme članarine -->
    <p class="text-xl text-white mb-2">Članarina važi do: <span class="font-semibold text-red-600"><?php echo htmlspecialchars($expiry_date); ?></span></p>

    <!-- Dug -->
    <p class="text-xl text-white mb-2">Trenutni dug: <span class="font-semibold text-red-600"><?php echo htmlspecialchars(number_format($debt, 2)); ?> RSD</span></p>
  </div>

  <!-- Developed By -->
  <p class="fixed bottom-4 right-4 text-sm text-red-600">Developed By<br> Andrija Gojaković Web</p>
</body>
</html>