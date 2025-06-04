<?php
require_once("connect.php");

$nombre = trim($_POST['nombre'] ?? '');
$imp_adulto = floatval($_POST['impuesto_adulto'] ?? 0);
$imp_infantil = floatval($_POST['impuesto_infantil'] ?? 0);

// Validación
if ($nombre === '') {
  echo json_encode(['success' => false, 'message' => 'El nombre de la ciudad no puede estar vacío.']);
  exit;
}

// Verificar duplicado
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM ciudad WHERE nombre = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$stmt->bind_result($existe);
$stmt->fetch();
$stmt->close();

if ($existe > 0) {
  echo json_encode(['success' => false, 'message' => 'La ciudad ya existe.']);
  exit;
}

// Insertar solo los tres campos obligatorios, y el resto se deja con valores por defecto
$stmt = $mysqli->prepare("INSERT INTO ciudad (nombre, impuesto_adulto, impuesto_infantil, nombre_centro, correo, fono, direccion, imagen, estado, precio_reserva) VALUES (?, ?, ?, '', '', '', '', '', 0, 0)");
$stmt->bind_param("sdd", $nombre, $imp_adulto, $imp_infantil);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $stmt->error]);
}
$stmt->close();
