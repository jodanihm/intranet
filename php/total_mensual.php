<?php
include("connect.php");
header('Content-Type: application/json');

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion == 1) {
    $resultado = $mysqli->query("SELECT id, nombre FROM ciudad ORDER BY nombre");
    $ciudades = [];
    while ($row = $resultado->fetch_assoc()) {
        $ciudades[] = $row;
    }
    echo json_encode(['ciudades' => $ciudades]);
    exit;
}

if ($accion == 2) {
    $mes = intval($_POST['mes']);
    $anio = intval($_POST['anio']);
    $pago_impresor = floatval($_POST['pago_impresor']);
    $otros_gastos = floatval($_POST['otros_gastos']);
    $otros_ingresos = floatval($_POST['otros_ingresos']);
    $ciudades = json_decode($_POST['ciudades'], true);
    $ok = true;
    $errors = [];
    if (is_array($ciudades)) {
        foreach ($ciudades as $id => $valor) {
            $id = intval($id);
            $valor = floatval($valor);
            $sql = "INSERT INTO total_mensual (ciudad_id, mes, anio, valor_con_iva, pago_impresor, otros_gastos, otros_ingresos) VALUES ('$id', '$mes', '$anio', '$valor', '$pago_impresor', '$otros_gastos', '$otros_ingresos')";
            if (!$mysqli->query($sql)) {
                $ok = false;
                $errors[] = $mysqli->error;
            }
        }
    } else {
        $ok = false;
        $errors[] = 'Datos de ciudades inválidos';
    }
    echo json_encode(['success' => $ok, 'errors' => $errors]);
    exit;
}

echo json_encode(['success' => false]);
?>