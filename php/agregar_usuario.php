<?php
require_once("connect.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

$campos = ['rut', 'nombre', 'usuario', 'mail', 'tipo', 'ciudad'];
foreach ($campos as $campo) {
    if (!isset($input[$campo])) {
        echo json_encode(['success' => false, 'message' => "Falta campo: $campo"]);
        exit;
    }
}

$rut     = $mysqli->real_escape_string($input['rut']);
$nombre  = $mysqli->real_escape_string($input['nombre']);
$usuario = $mysqli->real_escape_string($input['usuario']);
$mail    = $mysqli->real_escape_string($input['mail']);
$tipo    = (int) $input['tipo'];
$ciudad  = (int) $input['ciudad'];
$pass_temporal = '123456';
$img_default = 'default.png';

$existe = $mysqli->query("SELECT rut FROM user WHERE rut = '$rut'");
if ($existe && $existe->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
    exit;
}

$sql = "INSERT INTO user (rut, name, user_name, pass, type, mail, ciudad, session_token, estado, img, token_recuperacion, token_expira, intentos_fallidos, bloqueado)
        VALUES ('$rut', '$nombre', '$usuario', '$pass_temporal', $tipo, '$mail', $ciudad, NULL, 1, '$img_default', NULL, NULL, 0, 0)";

if ($mysqli->query($sql)) {
    $query = $mysqli->query("SELECT u.*, c.nombre AS ciudad_nombre FROM user u LEFT JOIN ciudad c ON u.ciudad = c.id WHERE u.rut = '$rut'");
    $nuevo_usuario = $query->fetch_assoc();
    echo json_encode(['success' => true, 'usuario' => $nuevo_usuario]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $mysqli->error]);
}
