<?php
include("connect.php");


if ($_POST['accion'] == '1') {
    $id = $mysqli->real_escape_string($_POST['id']);
    $sql = "SELECT orientacion, gcode_der, gcode_par, gcode_iz FROM solicitud WHERE id='$id' UNION SELECT orientacion, gcode_der, gcode_par, gcode_iz FROM solicitud_ter WHERE id='$id'";
    $resultado = $mysqli->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Archivos solicitud <?php echo htmlspecialchars($id); ?></h5>
                <ul class="list-group">
                <?php if ($row['orientacion'] == 'izquierda' || $row['orientacion'] == 'par') { ?>
                    <li class="list-group-item">Izquierda: <a href="<?php echo $row['gcode_iz']; ?>" download>Descargar</a> | <a href="https://gcode.ws/" target="_blank">Abrir visor</a></li>
                <?php } ?>
                <?php if ($row['orientacion'] == 'derecha' || $row['orientacion'] == 'par') { ?>
                    <li class="list-group-item">Derecha: <a href="<?php echo $row['gcode_der']; ?>" download>Descargar</a> | <a href="https://gcode.ws/" target="_blank">Abrir visor</a></li>
                <?php } ?>
                <?php if ($row['orientacion'] == 'par') { ?>
                    <li class="list-group-item">Par: <a href="<?php echo $row['gcode_par']; ?>" download>Descargar</a> | <a href="https://gcode.ws/" target="_blank">Abrir visor</a></li>
                <?php } ?>
                </ul>
                <div class="mt-3">
                    <iframe src="https://gcode.ws/" style="width:100%; height:600px; border:1px solid #ccc;"></iframe>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-warning">Solicitud no encontrada</div>';
    }
}
?>