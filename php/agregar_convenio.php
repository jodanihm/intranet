<?php
// 👇 ¡No pongas nada antes de esta línea!

require_once("connect.php");

header('Content-Type: application/json');

// Evita que PHP imprima errores como HTML
ini_set('display_errors', 0);
error_reporting(0);

$nombre = trim($_POST['nombre'] ?? '');
$descuento = $_POST['descuento'] ?? '';

if ($nombre === '' || !is_numeric($descuento) || $descuento < 0 || $descuento > 100) {
  echo json_encode(["success" => false, "message" => "Nombre inválido o descuento fuera de rango (0-100%)"]);
  exit;
}

$query = $mysqli->prepare("INSERT INTO convenio (nombre, descuento) VALUES (?, ?)");
if (!$query) {
  echo json_encode(["success" => false, "message" => "Error al preparar la consulta"]);
  exit;
}

$query->bind_param("sd", $nombre, $descuento);
if ($query->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "message" => "Error al guardar el convenio"]);
}
?>