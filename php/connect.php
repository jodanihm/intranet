<?php
//$mysqli = new mysqli("localhost", "plantifl_user", "Plantiflex.2023", "plantifl_plantiflex");
$mysqli = new mysqli("localhost", "root", "", "plantifl_plantiflex");
if ($mysqli->connect_errno) {
   die("error de conexión: " . $mysqli->connect_error);
}

session_start();

if (!isset($_SESSION['rut'])){
   
   echo '<script>window.location.href = "index.html";</script>';
}
?>