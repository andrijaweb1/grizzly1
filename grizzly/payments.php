
<?php
require_once "app/config/config.php";
require_once "app/classes/Transactions.php";
require_once "app/classes/User.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transakcije</title>
    
    <!-- Privatni CSS -->
    <link href="./public/css/style.css" rel="stylesheet">
</head>
<?php require_once "includes/header.php";?>

</head>
<body>
<style>
        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }

    

        .user-section {
            position: absolute;
            bottom: 15px;
            left: 15px;
            display: flex;
            align-items: center;
        }

        .user-section img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px; /* Ostavlja prostor za sidebar */
            flex-grow: 1;
            padding: 20px;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            background-color: #0d6efd;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .text-center {
            text-align: center;
        }
        .check{

        }
    </style>
<?php require_once "includes/sidebar.php";?>

<div class="main-content">
    <?php

    $transactions = new Transaction();
    $all_tranasctions = $transactions->fetch_all();
    $user = new User();


    ?>
  
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Transakcije</th>
                    <th>Ime i prezime</th>
                    <th>Datum i vreme</th>
                    <th>Iznos</th>
                    <th>Akcije</th>

                   
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_tranasctions)): ?>
                    <?php foreach ($all_tranasctions as $transaction): ?>   
                        <tr>
                            <th><?php echo $transaction['transaction_id']; ?></th>
                            <td><?php $row = $user->get_user_by_id($transaction['user_id']); $user_name = $row['full_name']; echo $user_name;?></td>
                            <td><?php echo $transaction['payment_date']; ?></td>
                            <td><?php echo $transaction['amount'];?></td>
                            <td>
                                <?php if($transaction['status']=="completed"):?>
                                    <p style="color:green;">transakcija kompletirana</p>
                                <?php endif;  if($transaction['status']=="reverted"):?>
                                    <p style="color:red;">transakcija otkazana</p><?php endif; if($transaction['status']==NULL):?>
                            <a class="btn btn-danger" href="approve_payment.php?id=<?php echo "0" . $transaction['transaction_id']; ?>">Otkazi transakciju</a>
                            <a class="btn btn-success" href="approve_payment.php?id=<?php echo "1" . $transaction['transaction_id']; ?>">Odobri transakciju</a>
                                        <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nema transakcija.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

