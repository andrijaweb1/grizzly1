<?php
require_once "includes/header.php";
require_once "includes/sidebar.php";
require_once "app/classes/Scans.php";
require_once "app/classes/Transactions.php";

$scan = new Scan();
$transaction = new Transaction();
$year = date('Y');
$today = date("Y-m-d");
$this_month = date("m");
$this_year = date("Y");

$todays_income = $transaction->get_amount_for_day($today);
$monthly_income = $transaction->get_amount_for_month($this_month);
$yearly_income = $transaction->get_amount_for_year($this_year);

// Initialize variables for form
$mesec = '';
$godina = '';
$zarada = '';
$error = '';
$no_data = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mesec = filter_input(INPUT_POST, 'months', FILTER_VALIDATE_INT);
    $godina = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);

    if ($mesec === false || $mesec < 1 || $mesec > 12) {
        $error = "Molimo unesite validan mesec (1-12).";
    } elseif ($godina === false || $godina < 1900 || $godina > 2100) {
        $error = "Molimo unesite validnu godinu (1900-2100).";
    } else {
        try {
            $zarada = $transaction->get_amount_for_month_and_year($mesec, $godina);
            if ($zarada === null || $zarada === '' || $zarada == 0) {
                $no_data = true;
            }
        } catch (Exception $e) {
            $error = "Došlo je do greške: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistika poseta - Grizzly Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .chart-container {
            max-width: 100%;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        canvas {
            width: 100% !important;
            max-height: 400px;
        }
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
            color: #ff4500;
            margin: 10px 0 0;
        }
        .card:hover {
            background-color: #e9ecef;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            
            .chart-container {
                padding: 10px;
            }
            canvas {
                max-height: 300px;
            }
        }
        @media (max-width: 576px) {
            .card h3 {
                font-size: 1rem;
            }
            .card p {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="main-content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <h3>Godišnja zarada</h3>
                    <p><?php echo htmlspecialchars($yearly_income); ?> RSD</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>Zarada ovog meseca</h3>
                    <p><?php echo htmlspecialchars($monthly_income); ?> RSD</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>Današnja zarada</h3>
                    <p><?php echo htmlspecialchars($todays_income); ?> RSD</p>
                </div>
            </div>
        </div>

        <h1 class="mt-5 mb-3">Zarada za mesec</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($no_data): ?>
            <div class="alert alert-warning">Nema podataka o zaradi za <?php echo htmlspecialchars($mesec); ?>. mesec <?php echo htmlspecialchars($godina); ?>. godine.</div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="row g-3 mb-4">
            <div class="col-md-5">
                <label for="months" class="form-label">Broj meseca</label>
                <input type="number" name="months" id="months" class="form-control" min="1" max="12" required>
            </div>
            <div class="col-md-5">
                <label for="year" class="form-label">Godina</label>
                <input type="number" name="year" id="year" class="form-control" min="1900" max="2100" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Izračunaj</button>
            </div>
        </form>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error && !$no_data && $zarada !== ''): ?>
            <h3>Zarada za <?php echo htmlspecialchars($mesec); ?>. mesec <?php echo htmlspecialchars($godina); ?>. godine je <?php echo htmlspecialchars($zarada); ?> RSD</h3>
        <?php endif; ?>
    </div>

    <?php
    $month_names = [
        1 => "Januar", 2 => "Februar", 3 => "Mart", 4 => "April", 5 => "Maj", 6 => "Jun",
        7 => "Jul", 8 => "Avgust", 9 => "Septembar", 10 => "Oktobar", 11 => "Novembar", 12 => "Decembar"
    ];
    for ($j = 1; $j <= 12; $j++):
        $labels = [];
        $values = [];
        $start = "08:00:00";
        $end = "10:00:00";
        for ($i = 8; $i <= 20; $i += 2) {
            $visits = $scan->timeintervalvisits($start, $end, $j);
            $labels[] = ($i < 10 ? "0" . $i : $i) . ":00–" . ($i + 2) . ":00";
            $values[] = $visits;
            $start = date("H:i:s", strtotime($start . "+2 hours"));
            $end = date("H:i:s", strtotime($end . "+2 hours"));
        }
        $labels_json = json_encode($labels);
        $values_json = json_encode($values);
    ?>
    <div class="chart-container">
        <h2>Broj poseta za <?php echo $month_names[$j]; ?> (<?php echo $year; ?>)</h2>
        <canvas id="chart_<?php echo $j; ?>"></canvas>
    </div>
    <script>
        const labels_<?php echo $j; ?> = <?php echo $labels_json; ?>;
        const dataValues_<?php echo $j; ?> = <?php echo $values_json; ?>;
        const ctx_<?php echo $j; ?> = document.getElementById('chart_<?php echo $j; ?>').getContext('2d');
        const myChart_<?php echo $j; ?> = new Chart(ctx_<?php echo $j; ?>, {
            type: 'bar',
            data: {
                labels: labels_<?php echo $j; ?>,
                datasets: [{
                    label: 'Broj poseta',
                    data: dataValues_<?php echo $j; ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });
    </script>
    <?php endfor; ?>
</div>
</body>
</html>