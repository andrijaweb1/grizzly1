<?php
session_start();
$servername = "localhost";
$db_usermame = "root";
$db_password = "";
$database_name = "grizzly";

$conn = mysqli_connect($servername,$db_usermame,$db_password,$database_name);

if(!$conn){
    die("Neuspesna konekcija");
}
?>