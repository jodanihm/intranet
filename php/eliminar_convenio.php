<?php
require_once("connect.php");

$id = $_POST['id'] ?? null;

if ($id !== null) {
  $stmt = $mysqli->prepare("DELETE FROM convenio WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar convenio']);
  }

  $stmt->close();
} else {
  echo json_encode(['success' => false, 'message' => 'ID no recibido']);
}
