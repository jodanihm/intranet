<?php
require_once("connect.php");

header('Content-Type: application/json');

// Recibir y decodificar entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
$accion = $_POST["accion"] ?? $input["accion"] ?? null;

switch ($accion) {
  case 'listar':
    $sql = "SELECT user.*, ciudad.nombre AS ciudad_nombre FROM user LEFT JOIN ciudad ON user.ciudad = ciudad.id";
    $result = $mysqli->query($sql);
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
      $usuarios[] = $row;
    }
    echo json_encode($usuarios);
    break;

  case 'ciudades':
    $sql = "SELECT id, nombre FROM ciudad ORDER BY nombre";
    $result = $mysqli->query($sql);
    $ciudades = [];
    while ($row = $result->fetch_assoc()) {
      $ciudades[] = $row;
    }
    echo json_encode($ciudades);
    break;

  case 'agregar':
    $rut = $input['rut'];
    $nombre = $input['nombre'];
    $usuario = $input['usuario'];
    $mail = $input['mail'];
    $tipo = $input['tipo'];
    $ciudad = $input['ciudad'];
    $estado = isset($input['estado']) ? (int)$input['estado'] : 1;
    $pass = password_hash('123456', PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO user (rut, name, user_name, pass, mail, type, ciudad, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiii", $rut, $nombre, $usuario, $pass, $mail, $tipo, $ciudad, $estado);
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "message" => "Error al agregar"]);
    }
    break;

  case 'editar':
    $rut = $input['rut'];
    $nombre = $input['nombre'];
    $usuario = $input['usuario'];
    $mail = $input['mail'];
    $tipo = $input['tipo'];
    $ciudad = $input['ciudad'];
    $estado = isset($input['estado']) ? (int)$input['estado'] : 1;

    $stmt = $mysqli->prepare("UPDATE user SET name=?, user_name=?, mail=?, type=?, ciudad=?, estado=? WHERE rut=?");
    $stmt->bind_param("sssiiis", $nombre, $usuario, $mail, $tipo, $ciudad, $estado, $rut);
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "message" => "Error al editar"]);
    }
    break;

  case 'eliminar':
    $rut = $input['rut'];
    $stmt = $mysqli->prepare("DELETE FROM user WHERE rut=?");
    $stmt->bind_param("s", $rut);
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "message" => "Error al eliminar"]);
    }
    break;

  default:
    echo json_encode(["success" => false, "message" => "Acción no reconocida"]);
    break;
}
?>
