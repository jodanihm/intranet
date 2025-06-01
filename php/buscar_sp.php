<?php
include ("connect.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    $ciudad = $_POST['ciudad'];
    $tipo = $_POST['tipo'];


    $result=$mysqli->query("SELECT a.id_solicitud, a.fecha, a.estado, c.name, d.nombre , b.pago, b.pago_imp
    FROM `historial_soli` as a , `solicitud` as b, `user` as c, `ciudad` as d 
    WHERE a.id_solicitud = b.id 
    AND a.user = c.rut 
    AND b.ciudad = d.id 
    AND YEAR(a.fecha) = '$anio' 
    AND MONTH(a.fecha) = '$mes' 
    AND a.estado = '$tipo' 
    AND d.id = ' $ciudad'
    UNION ALL
    SELECT a.id_solicitud, a.fecha, a.estado, c.name, d.nombre , b.pago, b.pago_imp
    FROM `historial_soli` as a , `solicitud_ter` as b, `user` as c, `ciudad` as d 
    WHERE a.id_solicitud = b.id 
    AND a.user = c.rut 
    AND b.ciudad = d.id 
    AND YEAR(a.fecha) = '$anio' 
    AND MONTH(a.fecha) = '$mes' 
    AND a.estado = '$tipo' 
    AND d.id = ' $ciudad'
    ;");


    if ($result->num_rows > 0) {

        ?>

            <div class="card shadow">
                <div class="card-body">
                    
                    <table class="table table-hover text-center table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">ID</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Profesional</th>
                                <th scope="col">Sucursal</th>
                                <th scope="col">Pago</th>
                                <th scope="col">Pago impresor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 1;
                            $precio_total_p = 0;
                            $precio_total_i = 0;
                            while($row = $result->fetch_array()){
                                ?>
                                <tr class="">
                                    <td><?php echo $total?></td>
                                    <td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')"><?php echo $row[0]?></button></td>
                                    <td ><?php echo $row[1]?></td>
                                    <td ><?php echo $row[2]?></td>
                                    <td ><?php echo $row[3]?></td>
                                    <td ><?php echo $row[4]?></td>
                              
                                    <td ><?php echo "$ ".number_format( $row[5] , 0, ',', '.');?></td>
                                    <td ><?php echo "$ ".number_format( $row[6] , 0, ',', '.');?></td>
                                </tr>
                                <?php
                                $total = $total + 1 ;
                                $precio_total_p = $precio_total_p + $row[5];
                                $precio_total_i = $precio_total_i + $row[6];
                            }
                            
                            ?>

                            <tr>
                                <td colspan=6></td>
                                <td><?php echo "$ ".number_format( $precio_total_p , 0, ',', '.');?></td>
                                <td><?php echo "$ ".number_format( $precio_total_i , 0, ',', '.');?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php
        
    
    }
}
if ($_POST['accion'] == 4) {
    $resultado=$mysqli->query("SELECT id, nombre FROM ciudad ");
    
    while($row = $resultado->fetch_array()){
        if ($_SESSION['ciudad'] == $row[0]) {
            ?>
            <option value="<?php echo $row[0]?>" selected><?php echo $row[1]?></option>
            <?php
        }else{
            ?>
            <option value="<?php echo $row[0]?>"><?php echo $row[1]?></option>
            <?php
        }
        
    }
}
?>