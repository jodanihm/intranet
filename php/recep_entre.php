<?php
include ("connect.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  


    if ($_SESSION['tipo'] == 0) {
        $result=$mysqli->query("SELECT 
        s.id,
        p.nombre AS nombre_paciente,
        s.talla,
        s.dureza,
        f.tipo AS nombre_forro,
        c.nombre as nombre_ciudad
        FROM 
            solicitud s
        JOIN 
            paciente p ON s.rut = p.rut
        JOIN 
            forro f ON s.forro = f.id
        JOIN
        	ciudad c ON s.ciudad = c.id
        WHERE 
            s.estado = 'Enviado' ");
    }else{
        $result=$mysqli->query("SELECT 
        s.id,
        p.nombre AS nombre_paciente,
        s.talla,
        s.dureza,
        f.tipo AS nombre_forro,
        c.nombre as nombre_ciudad
        FROM 
            solicitud s
        JOIN 
            paciente p ON s.rut = p.rut
        JOIN 
            forro f ON s.forro = f.id
        JOIN
        	ciudad c ON s.ciudad = c.id
        WHERE 
            s.estado = 'Enviado' 
        AND
            s.ciudad = '".$_SESSION['ciudad']."' ");
    }
    
    
    
    $row_cnt2 = $result->num_rows;
    if ($row_cnt2 != 0) {
        ?>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-warning opacity-75">
                    <strong><i class="bi bi-card-checklist"></i> ETAPA 1: Recepción de productos </strong>
                </div>
                <div class="card-body table-responsive">
                <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill"></i> Validar que la plantilla cumpla con la solicitud realizada en el sistema Newfeet.
                </div>
                    
                    <table class="table table-sm text-center table-bordered table-hover" >
                        <thead class="table-warning">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Id solicitud</th>
                                <th scope="col">Nombre paciente</th>
                                <th scope="col">Talla</th>
                                <th scope="col">Dureza</th>
                                <th scope="col">Forro</th>
                                <th scope="col">Ciudad</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cont = 1;
                            while($row = $result->fetch_array()){
                                ?>
                                <tr>
                                    <td><?php echo $cont?></td>
                                    <td scope="col">
                                        <button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')">
                                        <?php echo $row['0']?>
                                        </button>
                                    </td>
                                    <td><?php echo $row['1']?></td>
                                    <td><?php echo $row['2']?></td>
                                    <td><?php echo $row['3']?></td>
                                    <td><?php echo $row['4']?></td>
                                    <td><?php echo $row['5']?></td>

                                </tr>
                                
                                <?php
                                $cont = $cont + 1;
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    
    if ($_SESSION['tipo'] == 0) {
        $result=$mysqli->query("SELECT 
        s.id,
        p.nombre AS nombre_paciente,
        s.talla,
        s.dureza,
        f.tipo AS nombre_forro,
        c.nombre as nombre_ciudad
        FROM 
            solicitud s
        JOIN 
            paciente p ON s.rut = p.rut
        JOIN 
            forro f ON s.forro = f.id
        JOIN
        	ciudad c ON s.ciudad = c.id
        WHERE 
            s.estado = 'Recepcionado' ");
    }else{
        $result=$mysqli->query("SELECT 
        s.id,
        p.nombre AS nombre_paciente,
        s.talla,
        s.dureza,
        f.tipo AS nombre_forro,
        c.nombre as nombre_ciudad
        FROM 
            solicitud s
        JOIN 
            paciente p ON s.rut = p.rut
        JOIN 
            forro f ON s.forro = f.id
        JOIN
        	ciudad c ON s.ciudad = c.id
        WHERE 
            s.estado = 'Recepcionado' 
        AND
            s.ciudad = '".$_SESSION['ciudad']."' ");
    }
    
    
    $row_cnt2 = $result->num_rows;
    if ($row_cnt2 != 0) {
        ?>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-secondary text-light opacity-75">
                    <strong><i class="bi bi-cart-check"></i> ETAPA 2: Entrega a paciente</strong>
                </div>
                <div class="card-body table-responsive">

                    <table class="table table-sm text-center table-bordered table-hover" >
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Id solicitud</th>
                                <th scope="col">Nombre paciente</th>
                                <th scope="col">Talla</th>
                                <th scope="col">Dureza</th>
                                <th scope="col">Forro</th>
                                <th scope="col">Ciudad</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cont = 1;
                            while($row = $result->fetch_array()){
                                ?>
                                <tr>
                                    <td><?php echo $cont?></td>
                                    <td scope="col">
                                        <button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')">
                                        <?php echo $row['0']?>
                                        </button>
                                    </td>
                                    <td><?php echo $row['1']?></td>
                                    <td><?php echo $row['2']?></td>
                                    <td><?php echo $row['3']?></td>
                                    <td><?php echo $row['4']?></td>
                                    <td><?php echo $row['5']?></td>

                                </tr>
                                
                                <?php
                                $cont = $cont + 1;
                            }
                            ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }


    
    
    

}
if ($_POST['accion'] == 2) {
    /*
    echo "accion: ".$_POST['accion'];
    echo "<br>".$_POST['estado'];
    echo "<br>".$_POST['id'];
    echo "<br>".$_POST['update-motivo'];
    echo "<br>".$_POST['update-talla'];
    echo "<br>".$_POST['update-dureza'];
    echo "<br>".$_POST['update-forro'];
    echo "<br>".$_FILES['update-file-iz']['name'];
    echo "<br>".$_FILES['update-file-par']['name'];
    echo "<br>".$_FILES['update-file-der']['name'];
    */

    $result=$mysqli->query("SELECT * FROM solicitud WHERE id = '".$_POST['id']."' ");
    $row = $result->fetch_array();

    $result_f=$mysqli->query("SELECT * FROM forro ");
    while($row_f = $result_f->fetch_array()){
        $array_forro[$row_f[0]]=$row_f[1];
    }


    $var = 0;
    $coment = "";
    if ($_POST['update-talla'] != $row[8]) {
        $coment = $coment."Modificación de talla: Original ".$row[8]." Nueva: ".$_POST['update-talla']."<br>".$_POST['update-motivo'];
        $mysqli->query("UPDATE solicitud SET talla = '".$_POST['update-talla']."' WHERE id = '".$_POST['id']."' ");
        $var = $var + 1;
    }

    if ($_POST['update-dureza'] != $row[9]) {
        $coment = $coment."Modificación de dureza: Original ".$row[9]." Nueva: ".$_POST['update-dureza']."<br>".$_POST['update-motivo'];
        $mysqli->query("UPDATE solicitud SET dureza = '".$_POST['update-dureza']."' WHERE id = '".$_POST['id']."' ");
        $var = $var + 1;
    }

    if ($_POST['update-forro'] != $row[10]) {
        $coment = $coment."Modificación de Forro: Original ".$array_forro[$row[10]]." Nueva: ".$array_forro[$_POST['update-forro']]."<br>".$_POST['update-motivo'];
        $mysqli->query("UPDATE solicitud SET forro = '".$_POST['update-forro']."' WHERE id = '".$_POST['id']."' ");
        $var = $var + 1;
    }

    if (isset($_FILES['update-file-iz']['name']) && !empty($_FILES['update-file-iz']['name'])) {
        $coment = $coment."Modificación archivo .gcode orientación izquierda<br>".$_POST['update-motivo'];
        $filename = $_POST['id'] . '-iz.gcode';
        $destination = '../archive/' . $filename;
        $location = $_FILES["update-file-iz"]["tmp_name"];
        if (move_uploaded_file($location, $destination)) {
            $contenido = 'archive/'. $filename;
        }
        $var = $var + 1;
    }
    if (isset($_FILES['update-file-par']['name']) && !empty($_FILES['update-file-par']['name'])) {
        $coment = $coment."Modificación archivo .gcode orientación par<br>".$_POST['update-motivo'];
        $filename = $_POST['id'] . '-par.gcode';
        $destination = '../archive/' . $filename;
        $location = $_FILES["update-file-par"]["tmp_name"];
        if (move_uploaded_file($location, $destination)) {
            $contenido = 'archive/'. $filename;
        }
        
        $var = $var + 1;
    }
    if (isset($_FILES['update-file-der']['name']) && !empty($_FILES['update-file-der']['name'])) {
        $coment = $coment."Modificación archivo .gcode orientación derecha<br>".$_POST['update-motivo'];
        $filename = $_POST['id'] . '-der.gcode';
        $destination = '../archive/' . $filename;
        $location = $_FILES["update-file-der"]["tmp_name"];
        if (move_uploaded_file($location, $destination)) {
            $contenido = 'archive/'. $filename;
        }
        $var = $var + 1;
    }

    if ($var == 0) {
        $mysqli->query("UPDATE solicitud SET estado = 'Reparacion' WHERE id = '".$_POST['id']."' ");
        $coment = $_POST['update-motivo'];
        $mysqli->query("INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '$coment','Reparacion') ");
    }elseif ($var >= 0) {
        $mysqli->query("UPDATE solicitud SET estado = 'Reimpresion' WHERE id = '".$_POST['id']."' ");
        $mysqli->query("INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '$coment','Reimpresion') ");
    }
    echo $coment;
}
?>