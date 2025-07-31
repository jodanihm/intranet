<?php
include ("connect.php");
//header('Content-Type: text/html; charset=utf-8');

function tipo($id){

    if ($id == 0) {
        $nombre = "Administrador";
    }
    if ($id == 1) {
        $nombre = "Kinesiologo";
    }
    if ($id == 2) {
        $nombre = "Impresor";
    }
    if ($id == 3) {
        $nombre = "Jefe Kinesiologia";
    }

    return $nombre;
}

if ($_POST['accion'] == 1) {  
    $result=$mysqli->query("SELECT a.rut, a.name, a.user_name, a.mail, a.type, b.nombre FROM user as a, ciudad as b WHERE a.ciudad = b.id");
    if (!$result) {
        die("Error en la consulta: " . $mysqli->error);
    }else{
        ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="text-center">
                    Precios
                </h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">RUT</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">User name</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Ciudad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = $result->fetch_array()){
                        ?>
                        <tr>
                            <th><?php echo $row[0];?></th>
                            <td><?php echo $row[1];?></td>
                            <td><?php echo $row[2];?></td>
                            <td><?php echo $row[3];?></td>
                            <td><?php echo tipo($row[4]);?></td>
                            <td><?php echo $row[5];?></td>
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
    $resultado=$mysqli->query("SELECT id, nombre FROM ciudad ");
    
    ?>
    <option value="" selected disabled="true"></option>
    <?php
    while($row = $resultado->fetch_array()){
        ?>
        <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
        <?php
    }
    
}

if ($_POST['accion'] == 3) {
    // Recibe los datos del formulario
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $userName = $_POST['userName'];
    $correo1 = $_POST['correo1'];
    $correo2 = $_POST['correo2'];

    $correo = $correo1."@".$correo2;

    $miCadenaSinGuiones = str_replace('-', '', $rut);
    $clave= substr($miCadenaSinGuiones, -4);

    $tipo = $_POST['tipo'];
    $ciudad = $_POST['ciudad'];
    $query ="INSERT INTO `user`(`rut`, `name`, `user_name`, `pass`, `type`, `mail`, `ciudad`) VALUES ('$rut','$nombre','$userName','$clave','$tipo','$correo','$ciudad')";
    // Realiza la inserción en la base de datos o cualquier otra acción necesaria
    // Ejecuta la consulta
    if ($mysqli->query($query) === TRUE) {
        echo "Registro insertado correctamente.";
    } else {
        echo "Error al insertar el registro: " . $mysqli->error;
    }
    
    
}
?>