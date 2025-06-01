<?php
require_once("connect.php");

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? '';
$descuento = $_POST['descuento'] ?? '';

// Validar datos
if (empty($nombre) || !is_numeric($descuento)) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// Preparar consulta
$stmt = $mysqli->prepare("INSERT INTO convenio (nombre, descuento) VALUES (?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $mysqli->error]);
    exit;
}
//asegura que no pase el 100% del convenio
$descuento = floatval($_POST['descuento']);
if ($descuento < 0 || $descuento > 100) {
    echo json_encode(['success' => false, 'message' => 'El descuento debe ser entre 1 y 100.']);
    exit;
}

// Enlazar parámetros
$stmt->bind_param("sd", $nombre, $descuento);

// Ejecutar y responder
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $stmt->error]);
}

$stmt->close();
?>