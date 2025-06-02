<?php     
require_once "includes/header.php";
require_once "app/Classes/Membership_plans.php";
require_once "includes/sidebar.php";
$plans = new Plan();
$all_plans = $plans->fetch_all(); // Preuzmi sve planove iz baze

    ?>


<?php
// Ucitaj sve planove iz baze
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $full_name = $_POST["full_name"];
        $phone = $_POST["phone"];
        $plan_id = $_POST["plan_id"];
        $join_date = $_POST["join_date"];
        $birth_year = $_POST["birth_year"];


        $created = $user->create($full_name,$phone,$plan_id,$join_date,$birth_year);
// registracija
        if($created){
            $_SESSION['message']['type'] = "success"; //danger ili success
            $_SESSION['message']['text'] = "Uspesno kreiran nalog!";
            header("Location: index.php");
        }
        else{
            $_SESSION['message']['type'] = "danger"; //danger ili success
            $_SESSION['message']['text'] = "Greska!";
            header("Location: register.php");
        }
    }
?>

<div class="container">
    <!--forma za registraciju-->
<h1 class="mt-5 mb-3">Registracija</h1>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="name">Ime i Prezime</label>
                <input type="text" name="full_name" id="full_name" class="form-control" required>

            </div>
            
            <div class="form-group mb-3">
                <label for="email">Broj Telefona</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
                
            </div>
            <div class="form-group mb-3">
                <label for="plan_id">Mesečni Plan</label>
                <select name="plan_id" id="plan_id" class="form-control" required>
                    <?php //ispisivanje planova u padajucem meniju 

                    if (!empty($all_plans)) {
                        foreach ($all_plans as $plan) {
                            echo "<option value='{$plan['plan_id']}'>{$plan['plan_name']} ({$plan['price']} RSD)</option>";
                        }
                    } else {
                        echo "<option value=''>Nema dostupnih planova</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-3">
                <!-- datumu treba promeniti redosled u dan, mesec, godina-->
                <label for="join_date">Datum Učlanjenja</label>
                <input type="date" name="join_date" id="join_date" class="form-control" required>
                <small id="formatted_date" class="form-text text-muted"></small>
            </div>
            <div class="form-group mb-3">
                <label for="name">Godina Rodjenja</label>
                <input type="number" name="birth_year" id="birth_year" class="form-control" required>
                
            </div>
            <button type="submit" class="btn btn-primary">Registruj</button>
        </form>
</div>

<?php require_once "public/js/date_formatter.php";?>
<script>
        // kod ne radi
        const dateFormatter = new DateFormatter('join_date', 'formatted_date');
    </script>
    
</script>