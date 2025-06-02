<?php
require_once "app/config/config.php";
require_once "app/classes/User.php";
require_once "app/classes/Membership_plans.php";

$user = new User();
$plans = new Plan();
$all_plans = $plans->fetch_all();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grizzly Gym - Članovi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/style.css" rel="stylesheet">
</head>
<body>
<?php require_once "includes/sidebar.php"; ?>
<div class="main-content">
    <?php
    $users = new User();
    $all_users = $users->fetch_all();
    ?>
    <div class="container">
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <a href="register_user.php" class="btn btn-primary w-100 w-md-auto">Dodaj člana</a>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Pretraži korisnike...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="d-none d-lg-table-cell">ID člana</th>
                        <th>Ime i prezime</th>
                        <th class="d-none d-md-table-cell">Broj telefona</th>
                        <th>Datum plaćanja/učlanjenja</th>
                        <th>Datum isteka članarine</th>
                        <th>Dug</th>
                        <th>Bar kod</th>
                        <th>Paket</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_users)): ?>
                        <?php foreach ($all_users as $user): ?>
                            <tr>
                                <th class="d-none d-lg-table-cell"><?php echo htmlspecialchars($user['user_id']); ?></th>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['join_date']); ?></td>
                                <td><?php echo htmlspecialchars($user['expiry_date']); ?></td>
                                <td><?php echo htmlspecialchars($user['debt']); ?></td>
                                <td><?php echo htmlspecialchars($user['code_name'] ?? 'Nije dodeljen'); ?></td>
                                <td>
                                    <?php
                                    $user_plan_id = $user['plan_id'];
                                    $plan_name = "Nepoznat plan";
                                    foreach ($all_plans as $plan) {
                                        if ($plan['plan_id'] == $user_plan_id) {
                                            $plan_name = $plan['plan_name'];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($plan_name);
                                    ?>
                                </td>
                                <td class="action-buttons">
                                    <a class="btn btn-primary btn-sm" href="extend_membership.php?id=<?php echo $user['user_id']; ?>">Produži</a>
                                    <a class="btn btn-primary btn-sm" href="user_profile.php?id=<?php echo $user['user_id']; ?>">Profil</a>
                                    <a class="btn btn-danger btn-sm" href="delete_user.php?id=<?php echo $user['user_id']; ?>">Obriši</a>
                                    <button class="btn btn-primary btn-sm" onclick="openPaymentModal('<?php echo htmlspecialchars($user['full_name']); ?>', '<?php echo $user['user_id']; ?>')">Plati</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Nema dostupnih članova.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <button onclick="closePaymentModal()" class="close-button">&times;</button>
        <h2>Plati članarinu</h2>
        <form action="new_transaction.php" method="POST">
            <input type="hidden" id="paymentUserId" name="user_id">
            <div class="mb-3">
                <label for="paymentUserName" class="form-label">Ime i prezime:</label>
                <input type="text" id="paymentUserName" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Iznos:</label>
                <input type="number" name="amount" class="form-control" placeholder="Unesite iznos" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="extend_membership" id="extend_membership" class="form-check-input" checked>
                <label for="extend_membership" class="form-check-label">Produži članarinu</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Potvrdi uplatu</button>
        </form>
    </div>
</div>

<style>
.main-content {
    margin-left: 250px;
    flex-grow: 1;
    padding: 20px;
    width: calc(100% - 250px);
}
.container {
    max-width: 100%;
}
.table-responsive {
    overflow-x: auto;
}
.action-buttons .btn {
    margin: 2px;
}
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1200;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 90%;
    width: 400px;
    position: relative;
}
.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}
@media (max-width: 1000px) {
    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
    }
    .container {
        padding: 0 10px;
    }
}
@media (max-width: 768px) {
    .table {
        font-size: 0.9rem;
    }
    .table th, .table td {
        padding: 0.5rem;
    }
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .action-buttons .btn {
        width: 45%;
        font-size: 0.85rem;
    }
    .modal-content {
        width: 90%;
        padding: 15px;
    }
    .form-control, .btn {
        font-size: 0.9rem;
    }
}
@media (max-width: 576px) {
    .action-buttons .btn {
        width: 100%;
    }
    .form-control, .btn {
        font-size: 0.85rem;
    }
    .modal-content {
        width: 95%;
    }
}
</style>

<script>
function openPaymentModal(name, user_id) {
    console.log("Otvaranje modal-a za:", name, "ID:", user_id);
    document.getElementById('paymentUserName').value = name;
    document.getElementById('paymentUserId').value = user_id;
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

document.getElementById('searchInput').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        row.style.display = fullName.includes(searchValue) ? '' : 'none';
    });
});
</script>
</body>
</html>