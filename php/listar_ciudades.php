<?php
require_once("connect.php");
header('Content-Type: application/json');

$result = $mysqli->query("SELECT id, nombre FROM ciudad ORDER BY nombre");
$ciudades = [];

while ($row = $result->fetch_assoc()) {
    $ciudades[] = $row;
}

echo json_encode($ciudades);
?>
