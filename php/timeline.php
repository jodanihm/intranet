<?php
include ("connect.php");


function randomDate($start, $end) {
    $startTimestamp = strtotime($start);
    $endTimestamp = strtotime($end);
    
    $randomTimestamp = rand($startTimestamp, $endTimestamp);
    
    return date("Y-m-d", $randomTimestamp);
}

// Función para generar una hora aleatoria
function randomTime() {
    return rand(0, 23) . ':' . rand(0, 59) . ':' . rand(0, 59);
}

if ($_POST['accion'] == 1) {   
    //echo $_POST['anio']." ".$_POST['mes'];
    $result=$mysqli->query("SELECT 
    id_solicitud,
    MAX(CASE WHEN estado = 'Pendiente' THEN 'Pendiente' END) AS estado_pendiente,
    MAX(CASE WHEN estado = 'Pendiente' THEN fecha END) AS fecha_pendiente,
    'Enviado' AS estado_enviado,
    (SELECT fecha FROM historial_soli WHERE id_solicitud = main.id_solicitud AND estado = 'Enviado' LIMIT 1) AS fecha_enviado
FROM 
    historial_soli AS main
WHERE 
    estado = 'Pendiente'
    AND YEAR(fecha) = '".$_POST['anio']."'
    AND MONTH(fecha) = '".$_POST['mes']."'
GROUP BY 
    id_solicitud
ORDER BY 
    id_solicitud;
    ");
    $array = array();
    while($row = $result->fetch_array()){
        $fecha1 = new DateTime($row[2]);
        $fecha2 = new DateTime($row[4]);
        $diferencia = $fecha1->diff($fecha2);
        $totalHoras = $diferencia->days * 24 + $diferencia->h;
        $array[]=array($row[2],$totalHoras);
       
    }

    echo json_encode($array);


/*
    Registros al azar!!
    for ($i = 0; $i < 10; $i++) {
        $fechaHora = randomDate('2023-01-01', '2023-01-31') . ' ' . randomTime();
        $numero = rand(0, 100);
        $registros[] = [$fechaHora, $numero];
    }
    usort($registros, function($a, $b) {
        return strtotime($a[0]) - strtotime($b[0]);
    });
 */   
    //echo json_encode($registros);
    

}

?>