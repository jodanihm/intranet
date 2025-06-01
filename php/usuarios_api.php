<?php
require "connect.php";
header('Content-Type: application/json');

$resultado = $mysqli->query("SELECT rut, name, user_name, mail, type, ciudad, estado, bloqueado FROM user");

$usuarios = [];

while ($row = $resultado->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
exit;
?>