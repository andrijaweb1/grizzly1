<?php
require_once "includes/header.php";
require_once "includes/sidebar.php";
require_once "app/config/config.php";
require_once "includes/sidebar.php";
require_once "app/classes/User.php";
require_once "app/classes/Scans.php";
require_once "app/classes/Transactions.php";

// Initialize classes
$user = new User();
$scan = new Scan();
$transaction = new Transaction();

// Fetch data for div1: Number of Active Users
$current_date = date('Y-m-d');
$all_users = $user->fetch_all();
$active_users = 0;
foreach ($all_users as $u) {
    if ($u['expiry_date'] >= $current_date) {
        $active_users++;
    }
}

// Fetch data for div2: Number of Checked-In Users Today
$today = date('Y-m-d');
$all_scans = $scan->fetch_all();
$checked_in_users = [];
foreach ($all_scans as $s) {
    if (date('Y-m-d', strtotime($s['scan_datetime'])) == $today) {
        $checked_in_users[$s['user_id']] = true; // Use array to count unique users
    }
}
$checked_in_count = count($checked_in_users);

// Fetch data for div3: Income This Month
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');
$all_transactions = $transaction->fetch_all();
$total_income = 0;
foreach ($all_transactions as $t) {
    $payment_date = date('Y-m-d', strtotime($t['payment_date']));
    if ($payment_date >= $month_start && $payment_date <= $month_end && $t['status'] == 'completed') {
        $total_income += $t['amount'];
    }
}
?>

<div class="main-content">
    <div class="parent">
        <!-- div1: Number of Active Users (Display Only) -->
        <div class="div1">
            <div class="card">
                <h3>Active Users</h3>
                <p><?php echo $active_users; ?></p>
            </div>
        </div>

        <!-- div2: Number of Checked-In Users Today (Display Only) -->
        <div class="div2">
            <div class="card">
                <h3>Checked-In Today</h3>
                <p><?php echo $checked_in_count; ?></p>
            </div>
        </div>

        <!-- div3: Income This Month (Display Only) -->
        <div class="div3">
            <div class="card">
                <h3>Income This Month</h3>
                <p><?php echo number_format($total_income, 2); ?> RSD</p>
            </div>
        </div>

        <!-- div4: Adding New User (Link to register_user.php) -->
        <div class="div4">
            <a href="register_user.php" class="card-link">
                <div class="card">
                    <h3>Add New User</h3>
                    <p>Register a new member</p>
                </div>
            </a>
        </div>

        <!-- div5: List of Users (Link to users.php) -->
        <div class="div5">
            <a href="users.php" class="card-link">
                <div class="card">
                    <h3>User List</h3>
                    <p>View all members</p>
                </div>
            </a>
        </div>

        <!-- div6: Statistics (Link to statistics.php) -->
        <div class="div6">
            <a href="statistics.php" class="card-link">
                <div class="card">
                    <h3>Statistics</h3>
                    <?php
                    $query = "SELECT * FROM statistics ORDER BY stat_date DESC LIMIT 1";
                    $result = $conn->query($query);
                    if ($row = $result->fetch_assoc()) {
                        echo "<p>Latest Date: {$row['stat_date']}</p>";
                        echo "<p>Total Visits: {$row['total_visits']}</p>";
                        echo "<p>Total Income: {$row['total_income']} RSD</p>";
                    } else {
                        echo "<p>No statistics available.</p>";
                    }
                    ?>
                </div>
            </a>
        </div>

        <!-- div7: Adding Admin (Link to add_admin.php) -->
        <div class="div7">
            <a href="add_admin.php" class="card-link">
                <div class="card">
                    <h3>Add New Admin</h3>
                    <p>Add a new admin account</p>
                </div>
            </a>
        </div>

        <!-- div8: Membership Plans (Link to plans.php) -->
        <div class="div8">
            <a href="plans.php" class="card-link">
                <div class="card">
                    <h3>Membership Plans</h3>
                    <p>View available plans</p>
                </div>
            </a>
        </div>
    </div>
</div>



<style>
/* Grid layout based on the new image */
.parent {
    display: grid;
    grid-template-columns: repeat(6, 1fr); /* 6 equal columns */
    grid-template-rows: 1fr 1fr 1fr 1fr 1fr 1fr 1fr; /* 7 rows to accommodate the layout */
    gap: 8px;
    padding: 20px;
    background-color: #fff;
}

/* Grid positions based on the image */
.div1 { grid-column: 1 / span 2; grid-row: 1 / span 1; }
.div2 { grid-column: 3 / span 2; grid-row: 1 / span 1; }
.div3 { grid-column: 5 / span 2; grid-row: 1 / span 1; }
.div4 { grid-column: 2 / span 4; grid-row: 2 / span 3; } /* Larger central area */
.div5 { grid-column: 1 / span 3; grid-row: 5 / span 2; }
.div6 { grid-column: 4 / span 3; grid-row: 5 / span 2; }
.div7 { grid-column: 1 / span 3; grid-row: 7 / span 1; }
.div8 { grid-column: 4 / span 3; grid-row: 7 / span 1; }

/* Card styling (overriding style.css where needed) */
.card {
    background-color: #f9f9f9;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: background-color 0.3s;
}

.card h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #333;
}

.card p {
    font-size: 1.5rem;
    color: #ff4500; /* Grizzly Gym orange accent */
    margin: 10px 0 0;
}

/* Link styling */
.card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.card-link:hover .card {
    background-color: #e9ecef;
}

/* Ensure compatibility with sidebar and style.css */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* Override sidebar styles from style.css to match your provided sidebar */

</style>