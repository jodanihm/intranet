<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    if ($_POST['tipo'] == 1 ) {
        $result=$mysqli->query("SELECT 
        s.id,
        s.rut,
        p.nombre AS nombre_paciente, 
        s.orientacion, 
        s.cantidad, 
        s.gcode_der, 
        s.gcode_par, 
        s.gcode_iz, 
        s.talla, 
        s.dureza, 
        s.forro, 
        s.ciudad, 
        s.peso, 
        s.estado, 
        s.convenio, 
        s.pago, 
        s.pago_imp 
 FROM solicitud AS s
 JOIN paciente AS p ON s.rut = p.rut WHERE s.ciudad = '".$_SESSION['ciudad']."' ");
    }elseif ($_POST['tipo'] == 2) {
        $result=$mysqli->query("SELECT 
        s.id,
        s.rut,
        p.nombre AS nombre_paciente, 
        s.orientacion, 
        s.cantidad, 
        s.gcode_der, 
        s.gcode_par, 
        s.gcode_iz, 
        s.talla, 
        s.dureza, 
        s.forro, 
        s.ciudad, 
        s.peso, 
        s.estado, 
        s.convenio, 
        s.pago, 
        s.pago_imp 
 FROM solicitud_ter AS s
 JOIN paciente AS p ON s.rut = p.rut WHERE s.ciudad = '".$_SESSION['ciudad']."'");
    }
    
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
                                <th scope="col">Rut Paciente</th>
                                <th scope="col">Nombre Paciente</th>
                                <th scope="col">Orientación</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $result->fetch_array()){
                            ?>
                            <tr>
                                <th><?php echo $row[0];?></th>
                                <td><?php echo $row[1]?></td>
                                <td><?php echo $row[2]?></td>
                                <td><?php echo $row[3]?></td>
                                <td><button class="btn btn-danger" onclick="detalle_solcitud_na('<?php echo $row[0];?>')">Ver detalles</button></td>
                            </tr>
                            <?php
                            }
                            ?>
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

    $result=$mysqli->query("SELECT 
        s.id,
        s.rut,
        p.nombre AS nombre_paciente, 
        s.orientacion, 
        s.cantidad, 
        s.gcode_der, 
        s.gcode_par, 
        s.gcode_iz, 
        s.talla, 
        s.dureza, 
        s.forro, 
        s.ciudad, 
        s.peso, 
        s.estado, 
        s.convenio, 
        s.pago, 
        s.pago_imp 
    FROM solicitud AS s
    JOIN paciente AS p ON s.rut = p.rut WHERE s.id = '".$_POST['id']."' AND s.ciudad = '".$_SESSION['ciudad']."'
    UNION ALL
    SELECT 
            s.id,
            s.rut,
            p.nombre AS nombre_paciente, 
            s.orientacion, 
            s.cantidad, 
            s.gcode_der, 
            s.gcode_par, 
            s.gcode_iz, 
            s.talla, 
            s.dureza, 
            s.forro, 
            s.ciudad, 
            s.peso, 
            s.estado, 
            s.convenio, 
            s.pago, 
            s.pago_imp 
    FROM solicitud_ter AS s
    JOIN paciente AS p ON s.rut = p.rut WHERE s.id = '".$_POST['id']."' AND s.ciudad = '".$_SESSION['ciudad']."' ");
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
                                <th scope="col">Rut Paciente</th>
                                <th scope="col">Nombre Paciente</th>
                                <th scope="col">Orientación</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $result->fetch_array()){
                            ?>
                            <tr>
                                <th><?php echo $row[0];?></th>
                                <td><?php echo $row[1]?></td>
                                <td><?php echo $row[2]?></td>
                                <td><?php echo $row[3]?></td>
                                <td><button class="btn btn-danger" onclick="detalle_solcitud_na('<?php echo $row[0];?>')">Ver detalles</button></td>
                            </tr>
                            <?php
                            }
                            ?>
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

if ($_POST['accion'] == 3) {

    $result=$mysqli->query("SELECT 
    s.id,
    s.rut,
    p.nombre AS nombre_paciente, 
    s.orientacion, 
    s.cantidad, 
    s.gcode_der, 
    s.gcode_par, 
    s.gcode_iz, 
    s.talla, 
    s.dureza, 
    s.forro, 
    s.ciudad, 
    s.peso, 
    s.estado, 
    s.convenio, 
    s.pago, 
    s.pago_imp 
FROM solicitud AS s
JOIN paciente AS p ON s.rut = p.rut WHERE s.rut = '".$_POST['rut']."' AND s.ciudad = '".$_SESSION['ciudad']."'
                            UNION ALL
                            SELECT 
        s.id,
        s.rut,
        p.nombre AS nombre_paciente, 
        s.orientacion, 
        s.cantidad, 
        s.gcode_der, 
        s.gcode_par, 
        s.gcode_iz, 
        s.talla, 
        s.dureza, 
        s.forro, 
        s.ciudad, 
        s.peso, 
        s.estado, 
        s.convenio, 
        s.pago, 
        s.pago_imp 
 FROM solicitud_ter AS s
 JOIN paciente AS p ON s.rut = p.rut WHERE s.rut = '".$_POST['rut']."' AND s.ciudad = '".$_SESSION['ciudad']."' ");
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
                                <th scope="col">Rut Paciente</th>
                                <th scope="col">Nombre Paciente</th>
                                <th scope="col">Orientación</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $result->fetch_array()){
                            ?>
                            <tr>
                                <th><?php echo $row[0];?></th>
                                <td><?php echo $row[1]?></td>
                                <td><?php echo $row[2]?></td>
                                <td><?php echo $row[3]?></td>
                                <td><button class="btn btn-danger" onclick="detalle_solcitud_na('<?php echo $row[0];?>')">Ver detalles</button></td>
                            </tr>
                            <?php
                            }
                            ?>
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


?>