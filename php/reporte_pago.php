<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    //$f_ini = $_POST['fecha_ini']." 00:00:00";
    //$f_fin = $_POST['fecha_fin']." 23:59:59";
    //echo date($f, 'Y-m-d H:i:s');
    //echo $_POST['fecha_ini'];

    $result=$mysqli->query("SELECT a.id, a.rut, a.nombre, a.orientacion, a.cantidad, a.gcode_der, a.gcode_par, a.gcode_iz, a.talla, a.dureza, a.forro, a.ciudad, a.peso, a.estado, a.convenio, a.pago, a.pago_imp, b.nombre as nombre_paciente
    FROM solicitud as a, paciente as b
    WHERE a.rut = b.rut AND a.ciudad = '".$_POST['ciudad']."' ");
    /*
    while($row = $result->fetch_assoc()){
        $total[] = $row;
        $id = $row['id'];
        $result2=$mysqli->query("SELECT * FROM historial_soli WHERE id_solicitud = '$id' ");
        while($row2 = $result2->fetch_array()){
            $historial[] =  $row2;
        }
        $total['historial'] = $historial;
    }
    */
    $total = []; // Inicializar el array para almacenar los resultados
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];

            // Obtener el historial para este ID
            $result2 = $mysqli->query("SELECT * FROM historial_soli WHERE id_solicitud = '$id' ORDER BY fecha");
            $historial = []; // Inicializar el historial para esta solicitud

            if ($result2) {
                $row['Pagado'] = "No";
                $row['fecha-ingresada'] = "";
                $row['fecha-solicita-pago'] = "";
                $row['fecha-pago'] = "";
                while ($row2 = $result2->fetch_assoc()) { // Usar fetch_assoc para consistencia
                    $historial[] = $row2;
                    if ($row2['estado'] == 'Pagado') {
                        $row['Pagado'] = "Si";
                    }
                    if ($row2['estado'] == 'Ingresada') {
                        $row['fecha-ingresada'] = $row2['fecha'] ;
                    }
                    if ($row2['estado'] == 'Solicita pago') {
                        $row['fecha-solicita-pago'] = $row2['fecha'] ;
                    }
                    if ($row2['estado'] == 'Pagado') {
                        $row['fecha-pago'] = $row2['fecha'] ;
                    }
                }
            }

            // Agregar historial al resultado actual
            $row['historial'] = $historial;
            $total[] = $row; // Agregar el resultado actual al array total
        }
    }

    echo json_encode($total);

    /*
    $result=$mysqli->query("SELECT
    hs.id_solicitud,
    CASE
      WHEN MAX(hs.estado = 'Pagado') THEN 'Pagado'
      ELSE 'No pagado'
    END AS estado_pago,
    MAX(CASE WHEN hs.estado = 'Pagado' THEN hs.fecha END) AS fecha_pago,
    MAX(CASE WHEN hs.estado = 'Pagado' THEN hs.comentario END) AS comentario_pago,
    SUM(CASE WHEN hs.estado = 'Reimpresion' THEN 1 ELSE 0 END) AS contador_reimpresion,
    COALESCE(st.pago, s.pago) AS pago,
    COALESCE(st.orientacion, s.orientacion) AS orientacion,
    COALESCE(st.cantidad, s.cantidad) AS cantidad,
    COALESCE(st.talla, s.talla) AS talla,
    COALESCE(st.ciudad, s.ciudad) AS ciudad
    FROM historial_soli hs
    LEFT JOIN solicitud s ON hs.id_solicitud = s.id
    LEFT JOIN solicitud_ter st ON hs.id_solicitud = st.id
    WHERE hs.fecha BETWEEN '$f_ini' AND '$f_fin'
    AND COALESCE(st.ciudad, s.ciudad) = '".$_SESSION['ciudad']."'  
    GROUP BY hs.id_solicitud;");
    
    
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
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>

                                    <th scope="col">ID</th>
                                    <th scope="col">estado</th>
                                    <th scope="col">Fecha y hora de pago</th>
                                    <th scope="col">talla</th>
                                    <th scope="col">Orientación</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Re impresiones</th>
                                    <th scope="col">Monto pago</th>
                                    <th scope="col">Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_imp = 0;
                                //$total_pago = 0;
                                while($row = $result->fetch_array()){
                                    $total_imp = $total_imp + $row[5];
                                    //$total_pago = $total_pago + $row[3];
                                ?>
                                <tr>
                                    <th>
                                        <button class="btn btn-danger btn-sm" onclick="detalle_solcitud_na('<?php echo $row[0]?>')">
                                        <?php echo $row[0]?>
                                        </button>
                                    </th>
                                    <td><?php echo $row[1]?></td>
                                    <td><?php echo $row[2]?></td>
                                    <td><?php echo $row[8]?></td>
                                    <td><?php echo $row[6]?></td>
                                    <td><?php echo $row[7]?></td>

                                    <td><?php echo $row[4]?></td>
                                    <td><?php echo "$ ".number_format($row[5], 0, ',', '.');?></td>
                                    
                                    <td><?php echo $row[3]?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <th colspan="7" class="text-center">TOTAL</th>
                                    
                                    <td><?php echo "$ ".number_format($total_imp, 0, ',', '.');?></td>
                                    
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

    */
}


if ($_POST['accion'] == 2) {  

    //echo date($f, 'Y-m-d H:i:s');
    //echo $_POST['fecha_ini'];

    $result=$mysqli->query("SELECT a.id, a.rut, a.nombre, a.orientacion, a.cantidad, a.gcode_der, a.gcode_par, a.gcode_iz, a.talla, a.dureza, a.forro, a.ciudad, a.peso, a.estado, a.convenio, a.pago, a.pago_imp, b.nombre as nombre_paciente
    FROM solicitud_ter as a, paciente as b
    WHERE a.rut = b.rut AND a.ciudad = '".$_POST['ciudad']."' ");
    /*
    while($row = $result->fetch_assoc()){
        $total[] = $row;
        $id = $row['id'];
        $result2=$mysqli->query("SELECT * FROM historial_soli WHERE id_solicitud = '$id' ");
        while($row2 = $result2->fetch_array()){
            $historial[] =  $row2;
        }
        $total['historial'] = $historial;
    }
    */
    $total = []; // Inicializar el array para almacenar los resultados
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];

            // Obtener el historial para este ID
            $result2 = $mysqli->query("SELECT * FROM historial_soli WHERE id_solicitud = '$id' ORDER BY fecha");
            $historial = []; // Inicializar el historial para esta solicitud

            if ($result2) {
                $row['Pagado'] = "No";
                $row['fecha-ingresada'] = "";
                $row['fecha-solicita-pago'] = "";
                $row['fecha-pago'] = "";
                while ($row2 = $result2->fetch_assoc()) { // Usar fetch_assoc para consistencia
                    $historial[] = $row2;
                    if ($row2['estado'] == 'Pagado') {
                        $row['Pagado'] = "Si";
                    }
                    if ($row2['estado'] == 'Ingresada') {
                        $row['fecha-ingresada'] = $row2['fecha'] ;
                    }
                    if ($row2['estado'] == 'Solicita pago') {
                        $row['fecha-solicita-pago'] = $row2['fecha'] ;
                    }
                    if ($row2['estado'] == 'Pagado') {
                        $row['fecha-pago'] = $row2['fecha'] ;
                    }
                }
            }

            // Agregar historial al resultado actual
            $row['historial'] = $historial;
            $total[] = $row; // Agregar el resultado actual al array total
        }
    }

    echo json_encode($total);

    /*
    $result=$mysqli->query("SELECT
    hs.id_solicitud,
    CASE
      WHEN MAX(hs.estado = 'Pagado') THEN 'Pagado'
      ELSE 'No pagado'
    END AS estado_pago,
    MAX(CASE WHEN hs.estado = 'Pagado' THEN hs.fecha END) AS fecha_pago,
    MAX(CASE WHEN hs.estado = 'Pagado' THEN hs.comentario END) AS comentario_pago,
    SUM(CASE WHEN hs.estado = 'Reimpresion' THEN 1 ELSE 0 END) AS contador_reimpresion,
    COALESCE(st.pago, s.pago) AS pago,
    COALESCE(st.orientacion, s.orientacion) AS orientacion,
    COALESCE(st.cantidad, s.cantidad) AS cantidad,
    COALESCE(st.talla, s.talla) AS talla,
    COALESCE(st.ciudad, s.ciudad) AS ciudad
    FROM historial_soli hs
    LEFT JOIN solicitud s ON hs.id_solicitud = s.id
    LEFT JOIN solicitud_ter st ON hs.id_solicitud = st.id
    WHERE hs.fecha BETWEEN '$f_ini' AND '$f_fin'
    AND COALESCE(st.ciudad, s.ciudad) = '".$_SESSION['ciudad']."'  
    GROUP BY hs.id_solicitud;");
    
    
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
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>

                                    <th scope="col">ID</th>
                                    <th scope="col">estado</th>
                                    <th scope="col">Fecha y hora de pago</th>
                                    <th scope="col">talla</th>
                                    <th scope="col">Orientación</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Re impresiones</th>
                                    <th scope="col">Monto pago</th>
                                    <th scope="col">Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_imp = 0;
                                //$total_pago = 0;
                                while($row = $result->fetch_array()){
                                    $total_imp = $total_imp + $row[5];
                                    //$total_pago = $total_pago + $row[3];
                                ?>
                                <tr>
                                    <th>
                                        <button class="btn btn-danger btn-sm" onclick="detalle_solcitud_na('<?php echo $row[0]?>')">
                                        <?php echo $row[0]?>
                                        </button>
                                    </th>
                                    <td><?php echo $row[1]?></td>
                                    <td><?php echo $row[2]?></td>
                                    <td><?php echo $row[8]?></td>
                                    <td><?php echo $row[6]?></td>
                                    <td><?php echo $row[7]?></td>

                                    <td><?php echo $row[4]?></td>
                                    <td><?php echo "$ ".number_format($row[5], 0, ',', '.');?></td>
                                    
                                    <td><?php echo $row[3]?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <th colspan="7" class="text-center">TOTAL</th>
                                    
                                    <td><?php echo "$ ".number_format($total_imp, 0, ',', '.');?></td>
                                    
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

    */
}

if ($_POST['accion'] == 3) {
    $resultado=$mysqli->query("SELECT id, nombre FROM ciudad ");
    
    while($row = $resultado->fetch_array()){
        if ($_SESSION['tipo'] == '0') {
            if ($_SESSION['ciudad'] == $row[0]) {
                ?>
                <option value="<?php echo $row[0]?>" selected><?php echo $row[1]?></option>
                <?php
            }else{
                ?>
                <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
                <?php
            }
        }else{
            if ($_SESSION['ciudad'] == $row[0]){
                ?>
                <option value="<?php echo $row[0]?>" selected><?php echo $row[1]?></option>
                <?php
            }
        }
        
        
    }
}

?>