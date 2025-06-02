<?php
require_once "includes/header.php";
require_once "app/classes/User.php";
require_once "app/classes/Membership_Plans.php";



$users = new User();
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;



if ($user_id <= 0) {
    header("Location: users.php?message=invalid_id");
    exit();
}

$user = $users->get_user_by_id($user_id);

if (!$user) {
    header("Location: users.php?message=user_not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $birth_year = $_POST['birth_year'];
    $phone = $_POST['phone'];
    $plan_id = $_POST['plan_id'];
    $join_date = $_POST['join_date'];
    $expiry_date = $_POST['expiry_date'];
    $debt = $_POST['debt'];
    $user_code = $_POST['user_code'];



    if ($users->edit_user($user_id,$full_name,$birth_year,$phone,$plan_id,$join_date,$expiry_date,$debt,$user_code)) {
        header("Location: users.php?message=edit_success");
        exit();
    } else {
        header("Location: users.php?message=edit_error");
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
<?php 
$user_plan = new User();
$plan = new Plan();

$plan_id = $user_plan->get_plan_by_user_id($user_id);
$row = $plan->fetch_all();

?>
<div class="container mt-4">
    <h2>Izmeni Plan (ID: <?php echo htmlspecialchars($user_id); ?>)</h2>
    <div class="form-container">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Ime i Prezime</label>
                <!--kod staje sa izvrsavanjem ovde-->
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Godina rodjenja</label>
                <input type="text" name="birth_year" class="form-control" value="<?php echo htmlspecialchars($user['birth_year']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">telefon</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Plan</label>
                <select name="plan_id" class="form-control" required>
                    <?php foreach ($row as $plans): ?>
                        <option value="<?php echo htmlspecialchars($plans['plan_id']); ?>" <?php echo $plans['plan_id'] == $plan_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($plans['plan_name']); ?>
                        </option>
                    <?php endforeach; ?>
            </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Datum placanja/uclanjenja</label>
                <input type="date" name="join_date" class="form-control" value="<?php echo htmlspecialchars($user['join_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Datum isteka</label>
                <input type="date" name="expiry_date" class="form-control" value="<?php echo htmlspecialchars($user['expiry_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dug</label>
                <input type="text" name="debt" class="form-control" value="<?php echo htmlspecialchars($user['debt']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Bar kod</label>
                <input type="text" name="user_code" class="form-control" value="<?php echo htmlspecialchars($user['user_code']); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Sačuvaj</button>
            <a href="users.php" class="btn btn-secondary">Nazad</a>
        </form>
    </div>
</div>



