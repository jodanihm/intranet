<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("connect.php");

$tipo = $_POST['tipo'] ?? '';

if ($tipo === 'precio') {
    $talla = $_POST['id'] ?? '';
    $imp_par = $_POST['imp_par'] ?? 0;
    $imp_unit = $_POST['imp_unit'] ?? 0;
    $reimp_par = $_POST['reimp_par'] ?? 0;
    $reimp_unit = $_POST['reimp_unit'] ?? 0;
    $pago_imp_par = $_POST['pago_imp_par'] ?? 0;
    $pago_imp_unit = $_POST['pago_imp_unit'] ?? 0;

    $query = $mysqli->prepare("UPDATE precios SET imp_par=?, imp_unit=?, reimp_par=?, reimp_unit=?, pago_imp_par=?, pago_imp_unit=? WHERE Talla=?");
    if (!$query) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
        exit;
    }

    $query->bind_param("iiiiids", $imp_par, $imp_unit, $reimp_par, $reimp_unit, $pago_imp_par, $pago_imp_unit, $talla);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta']);
    }

} elseif ($tipo === 'ciudad') {
    $id = $_POST['id'] ?? '';
    $imp_adulto = $_POST['impuesto_adulto'] ?? 0;
    $imp_infantil = $_POST['impuesto_infantil'] ?? 0;

    $query = $mysqli->prepare("UPDATE ciudad SET impuesto_adulto=?, impuesto_infantil=? WHERE id=?");
    $query->bind_param("ddi", $imp_adulto, $imp_infantil, $id);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar ciudad']);
    }

} elseif ($tipo === 'convenio') {
    $id = $_POST['id'] ?? '';
    $descuento = $_POST['descuento'] ?? 0;

    $query = $mysqli->prepare("UPDATE convenio SET descuento=? WHERE id=?");
    $query->bind_param("di", $descuento, $id);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar convenio']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Tipo no válido']);
}
?>
