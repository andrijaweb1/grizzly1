<?php
require_once "includes/header.php";
require_once "includes/sidebar.php";
require_once "app/classes/Membership_Plans.php";

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plan_name = filter_input(INPUT_POST, 'plan_name', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $duration_days = filter_input(INPUT_POST, 'duration_days', FILTER_VALIDATE_INT);

    if ($plan_name && $price !== false && $duration_days !== false) {
        $plan = new Plan();
        $created = $plan->create($plan_name, $price, $duration_days);
        if ($created) {
            $success = true;
        } else {
            $error = "Greška pri kreiranju plana. Pokušajte ponovo.";
        }
    } else {
        $error = "Molimo unesite validne podatke.";
    }
}
?>
<style>
.main-content {
    margin-left: 250px;
    flex-grow: 1;
    padding: 20px;
    width: calc(100% - 250px);
}
.container {
    max-width: 600px; /* Restrict form width for better readability */
}
.form-group label {
    font-weight: 500;
}
.alert {
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
    }
    .container {
        max-width: 100%;
        padding: 0 15px;
    }
    .form-group label {
        font-size: 0.9rem;
    }
    .form-control {
        font-size: 0.9rem;
    }
    .btn {
        width: 100%; /* Full-width button on mobile */
    }
}
</style>
<div class="main-content">
    <div class="container">
        <h1 class="mt-4 mb-3">Dodaj novi plan</h1>
        <?php if ($success): ?>
            <div class="alert alert-success">Plan uspešno kreiran! <a href="plans.php">Dodaj još jedan</a></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="plan_name">Ime plana</label>
                <input type="text" name="plan_name" id="plan_name" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="price">Cena (RSD)</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="form-group mb-3">
                <label for="duration_days">Trajanje (broj dana)</label>
                <input type="number" name="duration_days" id="duration_days" class="form-control" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj plan</button>
        </form>
    </div>
</div>
</body>
</html>