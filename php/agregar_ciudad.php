<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "message" => "No se recibieron datos válidos"]);
    exit;
}

$nombre = trim($input['nombre']);
$adulto = floatval($input['impuesto_adulto']);
$infantil = floatval($input['impuesto_infantil']);

if ($nombre === '') {
    echo json_encode(["success" => false, "message" => "El nombre de la ciudad no puede estar vacío"]);
    exit;
}

// Verifica si ya existe
$query = $mysqli->prepare("SELECT COUNT(*) FROM ciudad WHERE nombre = ?");
$query->bind_param("s", $nombre);
$query->execute();
$query->bind_result($existe);
$query->fetch();
$query->close();

if ($existe > 0) {
    echo json_encode(["success" => false, "message" => "La ciudad ya está registrada"]);
    exit;
}

// Insertar la nueva ciudad
$stmt = $mysqli->prepare("INSERT INTO ciudad (nombre, impuesto_adulto, impuesto_infantil, nombre_centro, correo, fono, direccion, imagen, estado, precio_reserva) VALUES (?, ?, ?, '', '', '', '', '', 0, 0)");
$stmt->bind_param("sdd", $nombre, $adulto, $infantil);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Ciudad agregada correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al agregar ciudad: " . $stmt->error]);
}

$stmt->close();
?>
