<?php
require "connect.php";
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['rut'])) {
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

// Leer los datos enviados desde fetch (JSON)
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['rut'], $data['nombre'], $data['usuario'], $data['mail'], $data['tipo'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$rut = $data['rut'];
$nombre = $data['nombre'];
$usuario = $data['usuario'];
$mail = $data['mail'];
$tipo = $data['tipo'];

$stmt = $mysqli->prepare("UPDATE user SET name = ?, user_name = ?, mail = ?, type = ? WHERE rut = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en prepare: " . $mysqli->error]);
    exit;
}

$stmt->bind_param("sssis", $nombre, $usuario, $mail, $tipo, $rut);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el usuario"]);
}

$stmt->close();
?>
