<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    $f_ini = $_POST['fecha_ini']." 00:00:00";
    
    $f_fin = $_POST['fecha_fin']." 23:59:59";
    //echo date($f, 'Y-m-d H:i:s');
    echo $f_ini;
    
    $result=$mysqli->query("SELECT
    s.id AS id_solicitud,
    h.fecha,
    p.nombre AS nombre_paciente,
    s.pago,
    s.pago_imp
FROM
    solicitud_ter s
JOIN (
    SELECT
        id_solicitud,
        MAX(fecha) AS fecha
    FROM
        historial_soli
    WHERE
        estado = 'Finalizado'
    AND
        fecha BETWEEN '$f_ini' AND '$f_fin'
    GROUP BY
        id_solicitud
) h ON s.id = h.id_solicitud
JOIN paciente p ON s.rut = p.rut;");
    
    
    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }else{
        $row_cnt = $result->num_rows;
        if ($row_cnt != 0) {
        ?>

                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="text-center">
                            Resultado busqueda
                        </h5>
                        <table class="table">
                            <thead>
                                <tr>

                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre Paciente</th>
                                    <th scope="col">Pago impresor (-)</th>
                                    <th scope="col">Pago kine</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $total_imp = 0;
                                $total_pago = 0;
                                while($row = $result->fetch_array()){
                                    $total = $total + 1;
                                    $total_imp = $total_imp + $row[4];
                                    $total_pago = $total_pago + $row[3];
                                ?>
                                <tr>
                                    <th><?php echo $row[0];?></th>
                                    <td><?php echo $row[2]?></td>
                                    
                                    <td><?php echo "$ ".number_format($row[4], 0, ',', '.');?></td>
                                    <td><?php echo "$ ".number_format($row[3], 0, ',', '.');?></td>
                                    <td><?php echo $row[1]?></td>
                                    <td><button class="btn btn-danger" onclick="detalle_solcitud_general2('<?php echo $row[0];?>')">Ver detalles</button></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan='2' class="text-center"> <strong>TOTALES (<?php echo $total?> solicitudes): </strong></th>
                                    <td><?php echo "$ ".number_format($total_imp, 0, ',', '.');?></td>
                                    <td><?php echo "$ ".number_format($total_pago, 0, ',', '.');?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan='2' class="text-center"> <strong>Utilidad bruta: </strong></th>
                                    <td colspan='2' class="text-center"><strong><?php echo "$ ".number_format($total_pago - $total_imp , 0, ',', '.');?></strong></td>
                                    
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            
            
            <?php
        }else{
            ?>
            
            <?php
        }
    }
}

if ($_POST['accion'] == 2) {

   
    $f_ini = $_POST['fecha_ini']." 00:00:00";
    
    $f_fin = $_POST['fecha_fin']." 23:59:59";

    $result=$mysqli->query("SELECT b.forro, c.tipo, COUNT(b.forro) AS cantidad
    FROM solicitud_ter AS b
    JOIN forro AS c ON b.forro = c.id
    WHERE b.id IN (
        SELECT id_solicitud
        FROM historial_soli
        WHERE estado = 'Finalizado'
        GROUP BY id_solicitud
        HAVING MAX(fecha) BETWEEN '$f_ini' AND '$f_fin'
    )
    GROUP BY b.forro;
    ");

    while($row = $result->fetch_array()){
        $var[$row[0]]=$row[2];
    }

    $result=$mysqli->query("SELECT * FROM forro");
    $cant_forro = array();
    while($row = $result->fetch_array()){

        

        if (isset($var[$row[0]] )) {
            $cant_forro[$row[0]] = array($row[1] ,$var[$row[0]], $row[2]);
        }else{
            $cant_forro[$row[0]] = array($row[1] ,0, $row[2]);
        }
        
    }

    //echo "<pre>";
    //print_r($cant_forro);
    //echo "</pre>";
    echo json_encode($cant_forro);
}

if ($_POST['accion'] == 3) {
    $f_ini = $_POST['fecha_ini']." 00:00:00";
    
    $f_fin = $_POST['fecha_fin']." 23:59:59";
    $result=$mysqli->query("SELECT b.ciudad, c.nombre, COUNT(b.ciudad) AS cantidad
    FROM solicitud_ter AS b
    JOIN ciudad AS c ON b.ciudad = c.id
    WHERE b.id IN (
        SELECT id_solicitud
        FROM historial_soli
        WHERE estado = 'Finalizado'
        GROUP BY id_solicitud
        HAVING MAX(fecha) BETWEEN '$f_ini' AND '$f_fin'
    )
    GROUP BY b.ciudad;
    ");

    while($row = $result->fetch_array()){
        $var[$row[0]]=$row[2];
    }

    $result=$mysqli->query("SELECT * FROM ciudad");
    $cant_ciudad = array();
    while($row = $result->fetch_array()){

        if (isset($var[$row[0]] )) {
            $cant_ciudad[$row[0]] = array($row[1] ,$var[$row[0]]);
        }else{
            $cant_ciudad[$row[0]] = array($row[1] ,0);
        }
        
    }

    //echo "<pre>";
    //print_r($cant_ciudad);
    //echo "</pre>";
    echo json_encode($cant_ciudad);
}

?>