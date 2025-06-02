<?php
include ("connect.php");

/*
echo $_POST['rut'];
echo $_POST['nombre'];
echo $_POST['orientacion'];
echo $_POST['cantidad'];
echo $_FILES['file-iz']['name'];
echo $_FILES['file-par']['name'];
echo $_FILES['file-der']['name'];
echo $_POST['talla'];
echo $_POST['dureza'];
echo $_POST['c_forro'];
*/
if ($_POST['accion'] == 2) {
    $resultado=$mysqli->query("SELECT id, tipo, estado FROM forro ");
    ?>
    <option value="" selected disabled="true"></option>
    <?php
    while($row = $resultado->fetch_array()){
        if ($row[2] == 1) {
            ?>
            <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
            <?php
        }
        if ($row[2] == 0) {
            ?>
            <option value="<?php echo $row[0]?>" disabled><?php echo $row[1]?> (Sin stock)</option>
            <?php
        }
    }
}
if ($_POST['accion'] == 4) {
    if ($_SESSION['tipo'] == 0) {
        $resultado=$mysqli->query("SELECT id, nombre FROM ciudad ");
    
        while($row = $resultado->fetch_array()){
            ?>
            <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
            <?php
        }
    }else{
        $resultado=$mysqli->query("SELECT id, nombre FROM ciudad WHERE id = '".$_SESSION['ciudad']."' ");
    
        $row = $resultado->fetch_array()
        ?>
        <option value="<?php echo $row[0]?>" selected><?php echo $row[1]?></option>
        <?php
    }
}

if ($_POST['accion'] == 1) {
    
    if ($_POST['rut'] == ""
    || $_POST['orientacion'] == ""
    || $_POST['cantidad'] == ""
    || $_POST['peso'] == ""
    || $_POST['talla'] == ""
    || $_POST['dureza'] == "" 
    || $_POST['c_forro'] == ""
    || $_POST['ciudad'] == ""
    ) {
        echo 0; // faltan datos en el formulario
        //echo date('Y-m-d');
    }else{
    /*
    echo $_POST['rut']."<br>";
    echo $_POST['nombre']."<br>";
    echo $_POST['orientacion']."<br>";
    echo $_POST['cantidad']."<br>";
    echo $_POST['peso']."<br>";
    echo $_POST['talla']."<br>";
    echo $_POST['dureza']."<br>"; 
    echo $_POST['c_forro']."<br>";
    echo $_POST['ciudad']."<br>";
    */
        
        $resultado=$mysqli->query("SELECT MAX(id) AS max_id FROM (
            SELECT id FROM solicitud
            UNION ALL
            SELECT id FROM solicitud_ter
        ) AS combined_tables");
        $row = $resultado->fetch_array();
        $id = $row[0]+1;
        $rut = $_POST['rut'];
        if ($_POST['orientacion'] == 'izquierda') {
            if ($_FILES['file-iz']['name'] != "") {
                
                //$ext = explode('.', $_FILES['file-iz']['name']);
				$filename = $id . '-iz.gcode';
				$destination = '../../../archive/' . $filename;
				$location = $_FILES["file-iz"]["tmp_name"];
                if (move_uploaded_file($location, $destination)) {

                    $contenido = '../../archive/'. $filename;

                    $sql = "INSERT INTO solicitud (id, rut, orientacion, cantidad, gcode_iz, talla, dureza, forro, estado, peso, ciudad, convenio) 
                    VALUES ('$id', '$rut', '".$_POST['orientacion']."', '".$_POST['cantidad']."', 
                    '$contenido', '".$_POST['talla']."', '".$_POST['dureza']."', '".$_POST['c_forro']."', 'Ingresada', '".$_POST['peso']."', '".$_POST['ciudad']."', '".$_POST['convenio']."')";
                    
                    if ($mysqli->query($sql) === TRUE) {
                        $sql = "INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('$id', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '','Ingresada') ";
                        $mysqli->query($sql);
                        //echo "Error en la consulta: " . $mysql->error;
                        echo 1;
                    } else {
                        //echo "Error en la consulta: " . $mysql->error;
                        echo 2; // error al insertar or: izquierda
                    }
                }else{
                    echo 3;
                }
				
				
            }else{
                echo 0; // error no se encuentra archivo izquierdo
            }
        }
        if ($_POST['orientacion'] == 'par') {
            if ($_FILES['file-iz']['name'] != "" && $_FILES['file-par']['name'] != "" && $_FILES['file-der']['name'] != "") {

                $filename_iz = $id . '-iz.gcode';
				$destination_iz = '../../../archive/' . $filename_iz;
				$location_iz = $_FILES["file-iz"]["tmp_name"];

                $filename_par = $id . '-par.gcode';
				$destination_par = '../../../archive/' . $filename_par;
				$location_par = $_FILES["file-par"]["tmp_name"];

                $filename_der = $id . '-der.gcode';
				$destination_der = '../../../archive/' . $filename_der;
				$location_der = $_FILES["file-der"]["tmp_name"];

                if (move_uploaded_file($location_iz, $destination_iz) && move_uploaded_file($location_par, $destination_par) && move_uploaded_file($location_der, $destination_der)) {
                    $contenido_iz = '../../archive/'. $filename_iz;
                    $contenido_par = '../../archive/'. $filename_par;
                    $contenido_der = '../../archive/'. $filename_der;

                    $sql = "INSERT INTO solicitud (id, rut, orientacion, cantidad, gcode_iz, gcode_par, gcode_der, talla, dureza, forro, estado, peso, ciudad, convenio) 
                    VALUES ('$id',  '$rut', '".$_POST['orientacion']."', '".$_POST['cantidad']."', 
                    '$contenido_iz', '$contenido_par', '$contenido_der',
                    '".$_POST['talla']."', '".$_POST['dureza']."', '".$_POST['c_forro']."', 'Ingresada', '".$_POST['peso']."', '".$_POST['ciudad']."', '".$_POST['convenio']."')";
                    
                    if ($mysqli->query($sql) === TRUE) {
                        $sql = "INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('$id', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '','Ingresada') ";
                        $mysqli->query($sql);
                        //echo "Error en la consulta: " . $mysql->error;
                        echo 1;
                        
                    } else {
                        //echo "Error en la consulta: " . $mysql->error;
                        echo 2; // error al insertar or: izquierda
                    }
                }else{
                    echo 3;
                }

                
            }else{
                echo 0; // error faltan archivos por adjuntar
            }
        }
        if ($_POST['orientacion'] == 'derecha') {
            if ($_FILES['file-der']['name'] != "") {

                $filename = $id . '-der.gcode';
				$destination = '../../../archive/' . $filename;
				$location = $_FILES["file-der"]["tmp_name"];
                if (move_uploaded_file($location, $destination)) {

                    $contenido = '../../archive/'. $filename;
                    $sql = "INSERT INTO solicitud (id, rut, orientacion, cantidad, gcode_der, talla, dureza, forro, estado, peso, ciudad, convenio) 
                    VALUES ('$id',  '$rut', '".$_POST['orientacion']."', '".$_POST['cantidad']."', 
                    '$contenido', '".$_POST['talla']."', '".$_POST['dureza']."', '".$_POST['c_forro']."', 'Ingresada', '".$_POST['peso']."', '".$_POST['ciudad']."', '".$_POST['convenio']."')";
                    
                    if ($mysqli->query($sql) === TRUE) {
                        $sql = "INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('$id', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '','Ingresada') ";
                        $mysqli->query($sql);
                        echo 1;
                        //echo "Error en la consulta: " . $mysql->error;
                    } else {
                        //echo "Error en la consulta: " . $mysql->error;
                        echo 2; // error al insertar or: izquierda
                    }
                }else{
                    echo 3;
                }
            }else{
                echo 0; // error no se encuentra archivo derecho
            }
        }
    }
}

if ($_POST['accion'] == 3) {
    $resultado=$mysqli->query("SELECT
    a.rut,
    c.nombre AS ciudad,
    a.orientacion,
    a.id,
    COALESCE(d.veces_entregado, 0) AS veces_entregado,
    a.estado,
    c.id
FROM
    (
        SELECT 
            a.rut,
            a.orientacion,
            a.id,
            a.ciudad,
            a.estado
        FROM solicitud AS a
        WHERE a.rut = '".$_POST['rut']."' AND a.ciudad != '0'

        UNION

        SELECT 
            a.rut,
            a.orientacion,
            a.id,
            a.ciudad,
            a.estado
        FROM solicitud_ter AS a
        WHERE a.rut = '".$_POST['rut']."' AND a.ciudad != '0'
    ) AS a
JOIN ciudad AS c ON a.ciudad = c.id
LEFT JOIN (
    SELECT 
        h.id_solicitud,
        COUNT(*) AS veces_entregado
    FROM historial_soli h
    JOIN solicitud s ON h.id_solicitud = s.id
    WHERE h.estado = 'Entregado'
    GROUP BY h.id_solicitud

    UNION ALL

    SELECT 
        h.id_solicitud,
        COUNT(*) AS veces_entregado
    FROM historial_soli h
    JOIN solicitud_ter st ON h.id_solicitud = st.id
    WHERE h.estado = 'Entregado'
    GROUP BY h.id_solicitud
) AS d ON a.id = d.id_solicitud
ORDER BY a.id DESC;
    ");
    $row_cnt = $resultado->num_rows;
    $texto = "";
    if ($row_cnt != 0) {
        $texto = $texto . '<h5 class="text-center mb-3">Historial de solicitudes del paciente</h5>';
        $texto = $texto . '<table class="table table-sm table-bordered">';
        $texto = $texto . '<thead>';
            $texto = $texto . '<tr>';
            $texto = $texto . '<th class="text-center">Sucursal</th>';
            $texto = $texto . '<th class="text-center">Orientación</th>';
            $texto = $texto . '<th class="text-center">Detalle </th>';
            $texto = $texto . '<th class="text-center">Cobrar garantía </th>';
            $texto = $texto . '<tr>';
        $texto = $texto . '</thead>';
        $texto = $texto . '<tbody>';
        while($row = $resultado->fetch_array()){
            $texto = $texto . '<tr>';
            $texto = $texto . '<td class="text-center">En '.$row[1].'</td>';
            $texto = $texto . '<td class="text-center">'.$row[2].'</td>';
            $texto = $texto . '<td class="text-center"><button class="btn btn-success btn-sm" onclick="detalle_solcitud_na('.$row[3].')">Ver detalle</button> </td>';
            if ($row[4] != 0 && ($row[5] == 'Entregado' || $row[5] == 'Pagado'|| $row[5] == 'Finalizado') && $_SESSION['ciudad'] == $row[6] ) {
                $texto = $texto . '<td class="text-center"><button class="btn btn-danger btn-sm" onclick="detalle_solcitud_general('.$row[3].')">Cobrar garantía</button></td>';
            }else{
                $texto = $texto . '<td class="text-center"></td>';
            }
            $texto = $texto . '<tr>';
            $nombre = $row[0];       
        }
        $texto = $texto . '</tbody>';
        $texto = $texto . '</table>';
    }else{
        $texto = '<h5 class="text-center mb-3">No existen solicitudes previas asociadas al RUT</h5>'; 
        $nombre = "";  
    }
    //echo $texto;
    $array = array($texto, $nombre);
    echo json_encode($array);
}

if ($_POST['accion'] == 5) {
    if ($_SESSION['tipo'] == 0) {
        $resultado=$mysqli->query("SELECT s.id, p.nombre AS nombre_paciente, s.orientacion
        FROM solicitud s
        JOIN paciente p ON s.rut = p.rut
        WHERE s.estado = 'Ingresada'");
    }else{
        $resultado=$mysqli->query("SELECT s.id, p.nombre AS nombre_paciente, s.orientacion
        FROM solicitud s
        JOIN paciente p ON s.rut = p.rut
        WHERE s.estado = 'Ingresada' AND s.ciudad = '".$_SESSION['ciudad']."' ");
    }
    
    $row_cnt = $resultado->num_rows;
    if ($row_cnt != 0) {
        ?>
        <h5 class="text-center mb-3">Evaluaciones listas para enviar a proceso de impresión</h5>
        <table class="table table-sm table-bordered">

            <tbody>
                <?php
                while($row = $resultado->fetch_array()){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $row[0]?></td>
                        <td class="text-center"><?php echo $row[1]?></td>
                        <td class="text-center"><?php echo $row[2]?></td>
                        <td class="text-center"><button class="btn btn-success btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')">Ver detalle</button> </td>
                    <tr>
                    <?php
                }
                ?>    
            </tbody>
        </table>
        
        <?php
    }else{
        ?>
        <h5 class="text-center mb-3">No existen solicitudes pendientes de impresión</h5>
        <?php
    }
    
}
if ($_POST['accion'] == 6) {
    $resultado=$mysqli->query("SELECT id, nombre FROM convenio ");
    ?>

    <?php
    while($row = $resultado->fetch_array()){
            ?>
            <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
           <?php
        
    }
}

if ($_POST['accion'] == 7) {
    $sql = "SELECT p.rut, p.nombre, p.fono, p.correo, 
    COUNT(s.id) + COUNT(st.id) AS cantidad_solicitudes
    FROM paciente p
    LEFT JOIN solicitud s ON p.rut = s.rut
    LEFT JOIN solicitud_ter st ON p.rut = st.rut
    GROUP BY p.rut, p.nombre, p.fono, p.correo
    ORDER BY p.rut;";
    $resultado = $mysqli->query($sql);

    // Comprobar si hay resultados
    if ($resultado->num_rows > 0) {
        ?>
        <a class="btn btn-primary mb-3" onclick="cont_flujo('agregar')">
            Agregar paciente
        </a>
        <table class="table table-border" id="tabla-paciente">
                <thead>
                <tr>
                    <th>RUT</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
        <?php
                    // Iterar sobre los resultados y mostrar en la tabla
                    while ($fila = $resultado->fetch_array()) {
                        ?> <tr>
                                <td><button type="button" class="btn" onclick="selecionar('<?php echo $fila[0]; ?>')"><?php echo $fila[0]; ?></button></td>
                                <td><?php echo $fila[1]; ?></td>
                                <td><?php echo $fila[2]; ?></td>
                                <td><?php echo $fila[3]; ?></td>
                                <td><button class="btn btn-sm btn-info" onclick="pre_editar('<?php echo $fila[0]; ?>', '<?php echo $fila[1]; ?>', '<?php echo $fila[2]; ?>', '<?php echo $fila[3]; ?>'); cont_flujo('editar')">Editar</button></td>
                                <td>
                                    <?php
                                    if ($fila[4] == 0) {
                                        ?>
                                        <button class="btn btn-sm btn-danger" onclick="pre_eliminar('<?php echo $fila[0]; ?>', '<?php echo $fila[1]; ?>' ); cont_flujo('eliminar')">Eliminar</button>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                    }
            echo '</tbody>';
        echo '</table>';
    } else {
        echo "No se encontraron resultados.";
    }
}
if ($_POST['accion'] == 8) {
    //echo $_POST['rut'];
    $resultado=$mysqli->query("SELECT rut, nombre, fono, correo FROM paciente WHERE rut ='".$_POST['rut']."' ");
    $datosFormateados = array();
    $row = $resultado->fetch_array();
    //echo $row[0]." ".$row[1] ." ".$row[2] ." ".$row[3] ;
    //$datosFormateados = array($row[0],$row[1],$row[2],$row[3]);    
    //$nombre = utf8_encode($row[1]);
    $data = array(
        'rut' => $row[0],
        'nombre' => $row[1],
        'fono' =>$row[2],
        'correo' =>$row[3]
        // ... otros datos ...
    );
    echo json_encode($data);
}
if ($_POST['accion'] == 9) {
    //echo $_POST['rut'];
    $mysqli->set_charset("utf8mb4");
    $sql = "INSERT IGNORE INTO paciente(rut, nombre, fono, correo) VALUES ('".$_POST['rut']."', '".$_POST['nombre']."', '".$_POST['fono']."', '".$_POST['correo']."')";
    if ($mysqli->query($sql) === TRUE) {
        if ($mysqli->affected_rows > 0) {
            // Se insertó correctamente
            echo 1;
        } else {
            // La fila ya existe (rut duplicado)
            echo 2;
        }
    } else {
        // Error en la consulta SQL
        echo 0;
    }
}

if ($_POST['accion'] == 10) {
    $nombre = $_POST['nombre'];
    $mysqli->set_charset("utf8mb4");
    $sql = "UPDATE paciente SET nombre='$nombre',fono='".$_POST['fono']."',correo='".$_POST['correo']."' WHERE rut = '".$_POST['rut']."'";
    if ($mysqli->query($sql) === TRUE) {
        // Se insertó correctamente
        echo 1;
    } else {
        // Error en la consulta SQL
        echo 0;
    }
}
if ($_POST['accion'] == 11) {
  
    $sql = "DELETE FROM paciente WHERE rut = '".$_POST['rut']."'";
    if ($mysqli->query($sql) === TRUE) {
        // Se insertó correctamente
        echo 1;
    } else {
        // Error en la consulta SQL
        echo 0;
    }
}
?>