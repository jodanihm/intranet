<?php
require "connect.php";
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['rut'])) {
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

// Obtener datos JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['rut'])) {
    echo json_encode(["success" => false, "message" => "RUT no proporcionado"]);
    exit;
}

$rut = $data['rut'];

$stmt = $mysqli->prepare("DELETE FROM user WHERE rut = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en prepare: " . $mysqli->error]);
    exit;
}

$stmt->bind_param("s", $rut);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar el usuario"]);
}

$stmt->close();
?>
