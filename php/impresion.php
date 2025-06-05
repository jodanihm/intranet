<?php
include ("connect.php");
 
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
    (SELECT fecha FROM historial_soli WHERE id_solicitud = s.id AND estado = 'Enviado' ORDER BY fecha ASC LIMIT 1) AS fecha_enviado,
    f.id
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
    }
    if ($_POST['data'] == 'En proceso') {
        $titulo = 'En proceso de impresión';
        $color = 'info';
    }
    if ($_POST['data'] == 'Producto impreso') {
        $titulo = 'Producto impreso listos para envío';
        $color = 'success';
    }
    if ($_POST['data'] == 'En prensa') {
        $titulo = 'Producto en prensa';
        $color = 'secondary';
    }
    if ($_POST['data'] == 'Reparacion') {
        $titulo = 'Solicitud de reparación de producto';
        $color = 'danger';
    }
    if ($_POST['data'] == 'Reimpresion') {
        $titulo = 'Solicitud de re impresión de producto';
        $color = 'danger';
    }
    if ($_POST['data'] == 'Garantia') {
        $titulo = 'Reparación por garantía';
        $color = 'danger';
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
                            <th scope="col"></th>
                            

                            <th scope="col">Fecha máxima de entrega:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_array()){
                            ?>
                            <tr>
                                <td scope="col"><?php echo $row['0']?></cite></td>
                                <td><?php echo $row['1']?></td>
                                <td><?php echo $row['2']?></td>
                                <td><?php echo $row['3']?></td>
                                <td><?php 
                                if ($row['8'] == 13) {
                                    echo '<strong><i class="bi bi-exclamation-triangle-fill text-danger"></i>'.$row['4']."</strong>";
                                }else{
                                    echo $row['4'];
                                }
                                    ?>
                                </td>
                                <td><?php echo $row['5']?></td>
                                <td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')">Ver detalles de la solicitud</button></td>

                                <td >
                                    <?php
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
FROM solicitud;
    ");
    $array_num = array();
    while($row = $result->fetch_array()){
        $array_num[$row[0]] = array($row[1]);
    }
    echo json_encode($array_num);
} 


?>