<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {   

   

    $result=$mysqli->query("SELECT 
    s.id,
    p.nombre AS nombre_paciente,
    s.talla,
    s.dureza,
    f.tipo AS nombre_forro,
    c.nombre AS nombre_ciudad,
    (SELECT fecha FROM historial_soli WHERE id_solicitud = s.id AND estado = 'Pendiente' ORDER BY fecha ASC LIMIT 1) AS fecha_pendiente,
    (SELECT fecha FROM historial_soli WHERE id_solicitud = s.id AND estado = 'Enviado' ORDER BY fecha ASC LIMIT 1) AS fecha_enviado
    FROM 
        solicitud s
    JOIN 
        paciente p ON s.rut = p.rut
    JOIN 
        forro f ON s.forro = f.id
    JOIN 
        ciudad c ON s.ciudad = c.id
    WHERE 
        s.estado = '".$_POST['data']."' 
    ");
    
    if ($_POST['data'] == 'Pendiente') {
        $titulo = 'Solicitudes pendientes de impresión';
        $color = 'warning';
        $matrix = 0;
    }
    if ($_POST['data'] == 'En proceso') {
        $titulo = 'En proceso de impresión';
        $color = 'info';
        $matrix = 0;
    }
    if ($_POST['data'] == 'Producto impreso') {
        $titulo = 'Producto impreso listos para envío';
        $color = 'success';
        $matrix = 0;
    }
    if ($_POST['data'] == 'En prensa') {
        $titulo = 'Producto en prensa';
        $color = 'secondary';
        $matrix = 0;
    }
    if ($_POST['data'] == 'Reparacion') {
        $titulo = 'Solicitud de reparación de producto';
        $color = 'danger';
        $matrix = 0;
    }
    if ($_POST['data'] == 'Reimpresion') {
        $titulo = 'Solicitud de re impresión de producto';
        $color = 'danger';
        $matrix = 0;
    }
    if ($_POST['data'] == 'Garantia') {
        $titulo = 'Reparación por garantía';
        $color = 'danger';
        $matrix = 0;
    }

    if ($_POST['data'] == 'Enviado') {
        $titulo = 'Enviado';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Recepcionado') {
        $titulo = 'Recepcionados por kine';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Entregado') {
        $titulo = 'Entregado a cliente final';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Solicita pago') {
        $titulo = 'Solicita el pago a centros';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Pagado') {
        $titulo = 'Pagado por centros';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Pagado a impresor') {
        $titulo = 'Pagado a impresor';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Finzalizdo') {
        $titulo = 'Solicitudes finalizadas';
        $color = 'dark';
        $matrix = 1;
    }
    if ($_POST['data'] == 'Ingresada') {
        $titulo = 'Ingresadas';
        $color = 'dark';
        $matrix = 1;
    }

    
    
    $row_cnt2 = $result->num_rows;
    if ($row_cnt2 != 0) {
        ?>
        <div class="card shadow">
            <div class="card-header text-center bg-<?php echo $color?> text-light">
                <strong><?php echo $titulo?></strong>
            </div>
            <div class="card-body">
                <?php
                $result2=$mysqli->query("SELECT tiempo FROM plazo WHERE id = '3' ");
                $row2 = $result2->fetch_array();
                $calculo = $row2[0];
                ?>
                <input type="text" class="d-none" id="tiempo_calculo" value="<?php echo $calculo?>">
                <table class="table table-sm text-center" >
                    <thead >
                        <tr>
                            <th scope="col">Id solicitud</th>
                            <th scope="col">Nombre paciente</th>
                            <th scope="col">Talla</th>
                            <th scope="col">Dureza</th>
                            <th scope="col">Forro</th>
                            <th scope="col">Ciudad</th>
                            
                            <?php
                                if ($matrix == 0) {
                            ?>
                                <th scope="col">Fecha estimada de entrega en:</th>
                            <?php
                                }     
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_array()){
                            ?>
                            <tr>
                                <?php
                                    if ($matrix == 0) {
                                ?>
                                    <td scope="col"><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')"><?php echo $row['0']?></button></td>
                                <?php
                                    }  elseif ($matrix == 1) {
                                        ?>
                                        <td scope="col"><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')"><?php echo $row['0']?></button></td>
                                        <?php
                                    }  
                                ?>
                                
                                <td><?php echo $row['1']?></td>
                                <td><?php echo $row['2']?></td>
                                <td><?php echo $row['3']?></td>
                                <td><?php echo $row['4']?></td>
                                <td><?php echo $row['5']?></td>

                                <td >
                                    <?php
                                    if ($matrix == 0) {
                                        if ($row['7'] == "" || $row['7'] == null) {
                                            ?>
                                            <div id="td_tiempo_<?php echo $row['0']?>"></div>
                                            <input type="text" class="d-none" id="input_time_<?php echo $row['0']?>" value="<?php echo $row['6']?>">
                                            <?php
                                        }else{
                                            ?>
                                            <button class="btn btn-danger btn-sm">URGENTE</button>
                                            <?php
                                        }
                                    }
                                    
                                    ?>
                                    
                                </td>
                            </tr>
                            
                            <?php
                        }
                        ?>    
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    
   
}

if ($_POST['accion'] == 2) {  
    $result=$mysqli->query("SELECT
    'pendiente' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'ingresada' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'ingresada' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'en proceso' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'en proceso' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'producto impreso' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'producto impreso' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'en prensa' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'en prensa' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'reparacion' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'reparacion' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'garantia' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'garantia' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'reimpresion' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'reimpresion' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'enviado' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'enviado' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'recepcionado' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'recepcionado' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'entregado' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'entregado' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'solicita pago' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'solicita pago' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'pagado' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'pagado' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'pagado a impresor' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'pagado a impresor' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud
UNION
SELECT
    'finalizado' AS estado,
    COALESCE(SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END), 0) AS cantidad
FROM solicitud_ter;
    ");
    $array_num = array();
    while($row = $result->fetch_array()){
        $array_num[$row[0]] = array($row[1]);
    }
    echo json_encode($array_num);
} 


?>