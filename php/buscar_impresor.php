<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    
    $result=$mysqli->query("SELECT a.id_solicitud, a.estado, a.fecha, b.pago_imp, c.nombre, d.nombre
    FROM historial_soli as a, solicitud as b, ciudad as c, paciente as d
    WHERE a.estado = 'Pagado a impresor' 
    AND YEAR(a.fecha) = '$anio' AND MONTH(a.fecha) = '$mes'
    AND a.id_solicitud = b.id
    AND b.ciudad = c.id
    AND b.rut = d.rut
    ORDER BY a.id_solicitud
     ");
    
    $result2=$mysqli->query("SELECT a.id_solicitud, a.estado, a.fecha, b.pago_imp, c.nombre, d.nombre
    FROM historial_soli as a, solicitud_ter as b, ciudad as c, paciente as d
    WHERE a.estado = 'Pagado a impresor' 
    AND YEAR(a.fecha) = '$anio' AND MONTH(a.fecha) = '$mes'
    AND a.id_solicitud = b.id
    AND b.ciudad = c.id
    AND b.rut = d.rut
    ORDER BY a.id_solicitud
     ");
    
    
        
        ?>

            <div class="card shadow">
                <div class="card-body">
                    
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>

                                <th scope="col">ID</th>
                                <th scope="col">Nombre paciente</th>
                                <th scope="col">Ciudad origen</th>
                                <th scope="col">Fecha pago</th>
                                <th scope="col">Monto</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            while($row = $result->fetch_array()){
                                $total = $total + $row[3];
                                ?>
                                <tr class="table-danger">
                                    <td><?php echo $row[0]?></td>
                                    <td><?php echo $row[5]?></td>
                                    <td><?php echo $row[4]?></td>
                                    <td><?php echo $row[2]?></td>
                                    <td><?php echo "$ ".number_format( $row[3] , 0, ',', '.');?></td>
                                    <td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row[0]?>')">Ver detalles</button></td>
                                
                                </tr>
                                <?php
                            }
                            while($row2 = $result2->fetch_array()){
                                $total = $total + $row2[3];
                                ?>
                                <tr class="table-primary">
                                    <td><?php echo $row2[0]?></td>
                                    <td><?php echo $row2[5]?></td>
                                    <td><?php echo $row2[4]?></td>
                                    <td><?php echo $row2[2]?></td>
                                    <td><?php echo "$ ".number_format( $row2[3] , 0, ',', '.');?></td>
                                    <td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_general('<?php echo $row2[0]?>')">Ver detalles</button></td>
                                   
                                </tr>
                            <?php
                            }
                            ?>
                            <tr class="table-dark">
                                    
                                    <td colspan=4 class="text-right">Total</td>
                                    <td><?php echo "$ ".number_format( $total , 0, ',', '.');?></td>
                                    <td></td>
                                    
                                   
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php
        
    
}
if ($_POST['accion'] == 2) {

    $resultado=$mysqli->query("SELECT 
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
JOIN paciente AS p ON s.rut = p.rut WHERE (s.estado = 'Entregado' OR s.estado = 'Solicita Pago' OR s.estado = 'Pagado') AND s.pago_imp = '0'");
    //$row = $resultado->fetch_array();

   /*

    $result3=$mysqli->query("SELECT nombre, impuesto_adulto, impuesto_infantil FROM ciudad  ");
    $row3 = $result3->fetch_array();
    while($row3 = $result3->fetch_array()){
        $ciudad[$row3[0]] = $row3[1];
    }
*/

   
    $precios = array();
    $result2=$mysqli->query("SELECT * FROM precios");
    while($row2 = $result2->fetch_array()){
        $precios[$row2[0]] = array($row2[1],$row2[2],$row2[3],$row2[4],$row2[5],$row2[6], );
    }
    //print_r($precios);
    
    
    if (!$resultado) {
        die("Error en la consulta: " . $mysqli->error);
    }
    $row_cnt2 = $resultado->num_rows;
    if ($row_cnt2 != 0) {
        
        ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="text-center">Solicitudes pendientes de pago a impresor</h5>
                <table class="table text-center table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            
                            <th scope="col">Talla / orientación</th>
                            <th scope="col">Precio base</th>
                            <!--
                            <th scope="col">
                                Cantidad a imprimir
                                <br>
                                (Cantidad * (precio base * %costo) / 100)
                            </th>
                            -->
                            <th scope="col">Re impresiones</th>
                            <th scope="col">Precio estimado</th>
                            <th scope="col">Detalle</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 1;
                        $total_precio = 0;
                        while($row = $resultado->fetch_array()){

                            $r_reimp=$mysqli->query("SELECT count(*) FROM historial_soli WHERE id_solicitud = '$row[0]' AND estado = 'Reimpresion' ");
                            $row_r = $r_reimp->fetch_array();
                            $c_reimp = $row_r[0];
                        
                            $r_desc=$mysqli->query("SELECT * FROM convenio WHERE id = '$row[14]' ");
                            $row_d = $r_desc->fetch_array();
                            $nombre_convenio = $row_d[1];
                            $desc_conv = $row_d[2];

                            
                                if ($row['8'] <= 35) {
                                    $talla = $row['8']." Niño";
                                    if ($row['3'] == 'par') {
                                        $p_base = $precios['Infantil'][0];
                                        $imp_reimp = $precios['Infantil'][4];
                                    }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                                        $p_base = $precios['Infantil'][1];
                                        $imp_reimp = $precios['Infantil'][5];
                                    }
                                    
                                    
                                }

                                if ($row['8'] > 35) {
                                    $talla = $row['8']." Adulto";
                                    if ($row['3'] == 'par') {
                                        $p_base = $precios['Adulto'][0];
                                        $imp_reimp = $precios['Adulto'][4];
                                    }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                                        $p_base = $precios['Adulto'][1];
                                        $imp_reimp = $precios['Adulto'][5];
                                    }
                                   
                                }
            
                            ?>
                            <tr>
                                <td><?php echo $total?></td>
                                <td><?php echo $row[0]?></cite></td>
                                <td><?php echo $row[2]?></td>
                                
                                <td><?php echo $talla."<br><strong>".$row[3]."</strong>";?></td>
                                <td>
                                    <?php echo $p_base;?>
                                    
                                </td>
                                <!--
                                <td>
                                    <?php echo $row[4]." * ((".$p_base." * ".$imp_reimp.") / 100 ) = ";  ?> 
                                    <strong><?php echo "$ ".number_format($row[4] * (($p_base * $imp_reimp) / 100) , 0, ',', '.'); ?></strong>
                                    
                                </td>
                                -->
                                <?php $precio_1 = $row[4] * (($p_base * $imp_reimp) / 100);?>
                                <td>
                                    <?php 
                                    if ($c_reimp != 0) {
                                        echo $c_reimp." * ((".$p_base." * ".$imp_reimp.") / 100 ) = ";  
                                        echo "<strong>$ ".number_format($c_reimp * (($p_base * $imp_reimp) / 100) , 0, ',', '.') . "<strong>";
                                        $precio_2 = $c_reimp * (($p_base * $imp_reimp) / 100);
                                    }else{
                                        echo "Sin re impresiones";
                                        $precio_2 = 0;
                                    }
                                    
                                    ?> 
                                </td>
                                
                                <td>
                                    <?php echo "<strong>$ ".number_format($precio_final = $precio_1 + $precio_2, 0, ',', '.'). "<strong>"; ?>
                                </td>
                                
                               
                                <td><button class="btn btn-danger btn-sm" onclick="detalle_solcitud_na('<?php echo $row[0]?>')">Detalle solicitud</button></td>
                                
                            </tr>
                            
                            
                            <?php
                            $total_precio = $total_precio + $precio_final;
                            $total = $total + 1;
                        }
                        ?>    
                            <tr>
                                <td colspan=6 class="text-right">Total: </td>
                                <td><?php echo "<strong>$ ".number_format($total_precio, 0, ',', '.'). "<strong>"; ?></td>
                                <td></td>
                            </tr>
                    </tbody>
                    
                </table>
            </div>
        </div>      
        
        <?php
    }

    /*

    $result=$mysqli->query("SELECT 
    s.id,
    p.nombre AS nombre_paciente,
    c.nombre as nombre_ciudad
    FROM 
        solicitud s
    JOIN 
        paciente p ON s.rut = p.rut
    JOIN 
        historial_soli h ON s.id = h.id_solicitud
    JOIN
        ciudad c ON s.ciudad = c.id
    WHERE 
        s.id NOT IN (SELECT id_solicitud FROM historial_soli WHERE estado = 'Pagado a impresor')
    GROUP BY 
        s.id
     ");

    $row_cnt = $result->num_rows;
    if ($row_cnt != 0) {
    ?>

            <div class="card shadow">
                <div class="card-body">
                    
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">Correlativo</th>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre paciente</th>
                                <th scope="col">Ciudad origen</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 1;
                            while($row = $result->fetch_array()){

                            ?>
                            <tr>
                                <th><?php echo $total?></th>
                                <th><?php echo $row[0]?></th>
                                <td><?php echo $row[1]?></td>
                                <td><?php echo $row[2]?></td>
                                <td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_na('<?php echo $row[0]?>')">Ver detalles</button></td>
                               
                            </tr>
                            <?php
                            $total = $total + 1;
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
    */

  }


?>