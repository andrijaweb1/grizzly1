<?php
require_once "includes/header.php";
require_once "app/classes/Membership_Plans.php";
require_once "includes/sidebar.php";
$plans = new Plan();
$all_plans = $plans->fetch_all();
?>

<style>
    .plan-table-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .table thead th {
        background-color: #ff4500; /* Grizzly Gym orange */
        color: white;
        border: none;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .btn-custom {
        padding: 8px 15px;
        font-weight: bold;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }
    .no-plans {
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }
    .alert {
        margin-bottom: 20px;
    }
</style>

<div class="container mt-4">
    <!-- Display Feedback Messages -->
    <?php if (isset($_GET['message'])): ?>
        <?php if ($_GET['message'] == 'success'): ?>
            <div class="alert alert-success">Plan uspešno obrisan!</div>
        <?php elseif ($_GET['message'] == 'error'): ?>
            <div class="alert alert-danger">Greška pri brisanju plana.</div>
        <?php elseif ($_GET['message'] == 'plan_in_use'): ?>
            <div class="alert alert-warning">Ovaj plan koriste korisnici i ne može biti obrisan. Molimo prvo premestite korisnike na drugi plan.</div>
        <?php elseif ($_GET['message'] == 'invalid_id'): ?>
            <div class="alert alert-danger">Nevalidan ID plana.</div>
        <?php elseif ($_GET['message'] == 'plan_not_found'): ?>
            <div class="alert alert-danger">Plan nije pronađen.</div>
        <?php elseif ($_GET['message'] == 'edit_success'): ?>
            <div class="alert alert-success">Plan uspešno izmenjen!</div>
        <?php elseif ($_GET['message'] == 'edit_error'): ?>
            <div class="alert alert-danger">Greška pri izmeni plana.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Button for creating new plans -->
    <div class="mb-4">
        <a href="add_plans.php" class="btn btn-warning btn-custom">Novi Plan</a>
    </div>

    <!-- Plans Table -->
    <div class="plan-table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Plan ID</th>
                    <th scope="col">Ime Plana</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Trajanje (dani)</th>
                    <th scope="col">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_plans)): ?>
                    <?php foreach ($all_plans as $plan): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($plan['plan_id']); ?></th>
                            <td><?php echo htmlspecialchars($plan['plan_name']); ?></td>
                            <td><?php echo number_format($plan['price'], 2) . ' RSD'; ?></td>
                            <td><?php echo htmlspecialchars($plan['duration_days']); ?></td>
                            <td>
                                <a class="btn btn-success btn-custom" href="edit_plan.php?id=<?php echo htmlspecialchars($plan['plan_id']); ?>">Edit</a>
                                <a class="btn btn-danger btn-custom" href="delete_plan.php?id=<?php echo htmlspecialchars($plan['plan_id']); ?>" onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj plan?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-plans">Nema dostupnih planova.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

