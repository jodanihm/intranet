<?php
require "connect.php";
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['rut'])) {
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

$result = $mysqli->query("SELECT rut, name, user_name, mail, type FROM user");
$usuarios = [];

while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
?>
