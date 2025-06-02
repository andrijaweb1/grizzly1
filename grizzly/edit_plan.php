<?php
require_once "includes/header.php";
require_once "app/classes/Membership_Plans.php";

$plans = new Plan();
$plan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($plan_id <= 0) {
    header("Location: plans.php?message=invalid_id");
    exit();
}

$plan = $plans->fetch_by_id($plan_id);

if (!$plan) {
    header("Location: plans.php?message=plan_not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_name = $_POST['plan_name'];
    $price = $_POST['price'];
    $duration_days = $_POST['duration_days'];

    if ($plans->edit($plan_id, $plan_name, $price, $duration_days)) {
        header("Location: plans.php?message=edit_success");
        exit();
    } else {
        header("Location: plans.php?message=edit_error");
        exit();
    }
}
?>

<style>
    .form-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .btn-primary {
        background-color: #ff4500; /* Grizzly Gym orange */
        border-color: #ff4500;
    }
    .btn-primary:hover {
        background-color: #e03e00;
        border-color: #e03e00;
    }
</style>

<div class="container mt-4">
    <h2>Izmeni Plan (ID: <?php echo htmlspecialchars($plan_id); ?>)</h2>
    <div class="form-container">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Ime Plana</label>
                <input type="text" name="plan_name" class="form-control" value="<?php echo htmlspecialchars($plan['plan_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cena</label>
                <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($plan['price']); ?>" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Trajanje (dani)</label>
                <input type="number" name="duration_days" class="form-control" value="<?php echo htmlspecialchars($plan['duration_days']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Sačuvaj</button>
            <a href="plans.php" class="btn btn-secondary">Nazad</a>
        </form>
    </div>
</div>

