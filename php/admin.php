<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {    

    

    $resultado=$mysqli->query("SELECT s.id, s.rut, p.nombre AS nombre_paciente, s.orientacion, s.cantidad, s.gcode_der, 
    s.gcode_par, s.gcode_iz, s.talla, s.dureza, s.forro, s.ciudad, s.peso, s.estado, s.convenio, s.pago, s.pago_imp, f.impuesto
    FROM solicitud s
    JOIN paciente p ON s.rut = p.rut
    JOIN forro f ON s.forro = f.id
    WHERE s.ciudad = '".$_POST['ciudad']."' 
    AND s.estado = 'Entregado' 
    AND s.pago = 0
    ");

    //$row = $resultado->fetch_array();

    $precios = array();
    $result2=$mysqli->query("SELECT * FROM precios");
    while($row2 = $result2->fetch_array()){
        $precios[$row2[0]] = array($row2[1],$row2[2],$row2[3],$row2[4]);
    }

    $result3=$mysqli->query("SELECT nombre, impuesto_adulto, impuesto_infantil FROM ciudad WHERE id = '".$_POST['ciudad']."' ");
    $row3 = $result3->fetch_array();

    $impuesto_adulto = $row3[1];
    $impuesto_infantil = $row3[2];

    //print_r($precios);
        
    if (!$resultado) {
        die("Error en la consulta: " . $mysqli->error);
    }
    $row_cnt2 = $resultado->num_rows;
    if ($row_cnt2 != 0) {
        
        ?>
        <div class="card shadow">
            <div class="card-body">
                <table class="table text-center table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Orientación imp</th>
                            <th scope="col">Talla</th>


                            <th scope="col">Cantidad a imprimir</th>

                            <th scope="col">Convenio</th>
                            <th scope="col">Precio final</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">
                                
                                <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" onchange="s_todo(),actualizarBoton()">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Seleccionar todo
                                        </label>
                                </div>
                                <button class="btn btn-success btn-sm w-100" id="calcular" disabled onclick="solicitar_pago()">Solicitar pago</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while($row = $resultado->fetch_array()){

                            $r_reimp=$mysqli->query("SELECT count(*) FROM historial_soli WHERE id_solicitud = '$row[0]' AND estado = 'Reimpresion' ");
                            $row_r = $r_reimp->fetch_array();
                            $c_reimp = $row_r[0];
                        
                            $r_desc=$mysqli->query("SELECT * FROM convenio WHERE id = '$row[14]' ");
                            $row_d = $r_desc->fetch_array();
                            $nombre_convenio = $row_d[1];
                            $desc_conv = $row_d[2];

                            // original
                            
                            if ($row_d[0] == '0') {
                                $desc_final = $nombre_convenio." (- ".$desc_conv."%)";
                            }else{
                                $desc_final = '<div class="alert alert-warning m-0 p-2" role="alert">
                                                <i class="bi bi-exclamation-triangle-fill"></i> '.$nombre_convenio." (- ".$desc_conv."%)".'</div>';
                            }
                            

                            if ($row['8'] <= 35) {
                                $talla = $row['8']." Niño";
                                if ($row['3'] == 'par') {
                                    $p_base = $precios['Infantil'][0];
                                    $imp_reimp = $precios['Infantil'][2];
                                }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                                    $p_base = $precios['Infantil'][1];
                                    $imp_reimp = $precios['Infantil'][3];
                                }
                                $impuesto_ciudad = $impuesto_infantil;
                                
                            }
            
                            if ($row['8'] > 35) {
                                $talla = $row['8']." Adulto";
                                if ($row['3'] == 'par') {
                                    $p_base = $precios['Adulto'][0];
                                    $imp_reimp = $precios['Adulto'][2];
                                }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                                    $p_base = $precios['Adulto'][1];
                                    $imp_reimp = $precios['Adulto'][3];
                                }
                                $impuesto_ciudad = $impuesto_adulto;
                            }
                            
                            ?>
                            <tr>

                                <td><?php echo $row[0]?></cite></td>
                                <td><?php echo $row[2]?></td>
                                <td><?php echo $row[3]?></td>
                                <td><?php echo $talla;?></td>
                                
                                <td><?php echo $row[4]?></td>

                                <td>
                                    <?php echo $desc_final; ?>
                                </td>
                                
                                <td><?php 
                                    
                                    $pre_precio = (($p_base * $impuesto_ciudad) / 100 + $p_base) * $row[4];
                                    $pre_precio = (($c_reimp * $row[4]) * $imp_reimp) + $pre_precio;

                                    $precio_final = $pre_precio - (($desc_conv*$pre_precio) / 100);

                                    $precio_final = $precio_final + $row[17]; // suma de impuesto de forro
                                    $total = $total + $precio_final;
                                    echo "$ ".number_format( $precio_final , 0, ',', '.');
                                    
                                    ?>
                                </td>
                                
                               
                                <td><button class="btn btn-danger btn-sm" onclick="ver_detalles('<?php echo $row[0]?>')">Detalle solicitud</button></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" onchange="actualizarBoton()" type="checkbox" value="<?php echo $row[0]?>" name="s_pago" data-segundo-valor="<?php echo $precio_final?>">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Solicitar Pago
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            
                            
                            <?php
                        }
                        ?>   
                        <tr>
                            
                            <td colspan="6"></td>
                            <td>Total: <?php echo "$ ".number_format( $total , 0, ',', '.'); ?></td>
                            <td></td>
                            <td></td>
                        </tr> 
                    </tbody>
                    
                </table>
            </div>
        </div>      
        
        <?php
    }
    


}
if ($_POST['accion'] == 2) {
    //$resultado=$mysqli->query("SELECT * FROM solicitud WHERE id='".$_POST['id']."' ");
    $resultado=$mysqli->query("SELECT s.id, s.rut, p.nombre AS nombre_paciente, 
    s.orientacion, s.cantidad, s.gcode_der, s.gcode_par, s.gcode_iz, s.talla, s.dureza, f.tipo AS nombre_forro, 
    s.ciudad, s.peso, s.estado, s.convenio, s.pago, s.pago_imp, f.impuesto
    FROM solicitud s
    JOIN paciente p ON s.rut = p.rut
    JOIN forro f ON s.forro = f.id
    WHERE s.id = '".$_POST['id']."' 
    ");
    $row = $resultado->fetch_array();

    

    $precios = array();
    $result2=$mysqli->query("SELECT * FROM precios");
    while($row2 = $result2->fetch_array()){
        $precios[$row2[0]] = array($row2[1],$row2[2],$row2[3],$row2[4]);
    }

    $result3=$mysqli->query("SELECT nombre, impuesto_adulto, impuesto_infantil FROM ciudad WHERE id = '$row[11]' ");
    $row3 = $result3->fetch_array();

    $impuesto_adulto = $row3[1];
    $impuesto_infantil = $row3[2];

    $r_reimp=$mysqli->query("SELECT count(*) FROM historial_soli WHERE id_solicitud = '".$_POST['id']."' AND estado = 'Reimpresion' ");
    $row_r = $r_reimp->fetch_array();
    $c_reimp = $row_r[0];

    $r_desc=$mysqli->query("SELECT * FROM convenio WHERE id = '$row[14]' ");
    $row_d = $r_desc->fetch_array();
    $nombre_convenio = $row_d[1];
    $desc_conv = $row_d[2];
    //$c_reimp = $row_r[0];

    ?>
    <div class="conaier-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card shadow">
                    <div class="card-body">
                    <h5 class="text-center">Detalle solicitud</h5>
                    <table class="table table-bordered">

                            <tbody>
                                <tr>
                                <th >Id solicitud:</th>
                                <td><?php echo $row['0']?></td>

                                </tr>
                                <tr>
                                    <th>Rut:</th>
                                    <td><?php echo $row['1']?></td>

                                </tr>
                                <tr>
                                    <th>Nombre paciente</th>
                                    <td><?php echo $row['2']?></td>
                                </tr>
                                <tr>
                                    <th>Orientación</th>
                                    <td><?php echo $row['3']?></td>
                                </tr>
                                <tr>
                                    <th>Ciudad</th>
                                    <td><?php echo $row3[0]?></td>
                                </tr>
                            
                                <tr>
                                    <th>Talla</th>
                                    <td><?php echo $row['8']?></td>
                                </tr>
                                <tr>
                                    <th>Dureza</th>
                                    <td><?php echo $row['9']?></td>
                                </tr>
                                <tr>
                                    <th>Tipo</th>
                                    <td><?php echo $row['10']?></td>
                                </tr>
                                <tr>
                                    <th>Archivo(s) .gcode</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 text-center p-0">
                                                <?php
                                                if ($row['3'] == 'izquierda' || $row['3'] == 'par') {
                                                ?>
                                                    <a href="<?php echo $row['7']?>" download="izquierda.gcode">
                                                        <img src="img/download.png" alt="" style="width:50px">
                                                        <p>Izquierda</p>
                                                    </a>
                                                <?php
                                                }   
                                                ?>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <?php
                                                if ($row['3'] == 'par') {
                                                ?>
                                                    <a href="<?php echo $row['6']?>" download="par.gcode">
                                                        <img src="img/download.png" alt="" style="width:50px">
                                                        <p>Par</p>
                                                    </a>
                                                <?php
                                                }   
                                                ?>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <?php
                                                if ($row['3'] == 'derecha' || $row['3'] == 'par') {
                                                ?>
                                                    <a href="<?php echo $row['5']?>" download="derecha.gcode">
                                                        <img src="img/download.png" alt="" style="width:50px">
                                                        <p>Derecha</p>
                                                    </a>
                                                <?php
                                                }   
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                        
                        
                    </div>
                    
                    
                </div>
            </div>
            <div class="col-sm-6">
            <?php
                if ($row['8'] <= 35) {
                    $talla = $row['8']." Niño";
                    if ($row['3'] == 'par') {
                        $p_base = $precios['Infantil'][0];
                        $imp_reimp = $precios['Infantil'][2];
                    }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                        $p_base = $precios['Infantil'][1];
                        $imp_reimp = $precios['Infantil'][3];
                    }
                    $impuesto_ciudad = $impuesto_infantil;
                    
                }

                if ($row['8'] > 35) {
                    $talla = $row['8']." Adulto";
                    if ($row['3'] == 'par') {
                        $p_base = $precios['Adulto'][0];
                        $imp_reimp = $precios['Adulto'][2];
                    }elseif($row['3'] == 'izquierda' || $row['3'] == 'derecha'){
                        $p_base = $precios['Adulto'][1];
                        $imp_reimp = $precios['Adulto'][3];
                    }
                    $impuesto_ciudad = $impuesto_adulto;
                }
            ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="text-center">Calculo precio</h5>

                        

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Precio Base</td>
                                    <td></td>
                                    <td><?php echo "$ ".number_format($p_base, 0, ',', '.')?></td>
                                </tr>
                                <tr>
                                    <td>Impuesto por ciudad (+<?php echo $impuesto_ciudad." %)";?></td>
                                    <td><?php echo "$ ".number_format(($p_base * $impuesto_ciudad) / 100, 0, ',', '.');?></td>
                                    <td><?php echo "$ ".number_format(($p_base * $impuesto_ciudad) / 100 + $p_base, 0, ',', '.');?></td>
                                </tr>
                                <tr>
                                    <td>Cantidad a imprimir ( * <?php echo $row[4]?>)</td>
                                    <td><?php echo "$ ".number_format((($p_base * $impuesto_ciudad) / 100) + $p_base, 0, ',', '.') ;?></td>
                                    <td><?php echo "$ ".number_format($pre_precio = ((($p_base * $impuesto_ciudad) / 100) + $p_base) * $row[4], 0, ',', '.') ;?></td>
                                </tr>
                                <?php
                                if ($c_reimp != 0) {
                                    ?>
                                    <tr>
                                        <td>Re impresiones
                                            <br>
                                            (Cantidad imp * cantidad reimp)
                                            <br>
                                            (<?php echo $c_reimp." * ". $row[4]?>) * <?php echo $imp_reimp?>
                                        </td>
                                        <td><?php echo "$ ".number_format(($c_reimp * $row[4]) * $imp_reimp, 0, ',', '.')?></td>
                                        <td><?php 
                                            $pre_precio = (($c_reimp * $row[4]) * $imp_reimp) + $pre_precio;
                                            echo "$ ".number_format($pre_precio , 0, ',', '.');
                                            ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                
                                <tr>
                                    <td>Descuento convenio
                                        <br>
                                        <?php echo $nombre_convenio." (- ".$desc_conv."%)"?>
                                    </td>
                                    <td><?php echo "$ ".number_format(($desc_conv * $pre_precio) / 100, 0, ',', '.');?></td>
                                    <td>
                                        <?php 
                                        $precio_final = $pre_precio - (($desc_conv*$pre_precio) / 100);
                                        echo "$ ".number_format( $precio_final , 0, ',', '.');
                                        ?>
                                    </td>
                                </tr>

                                <?php
                                if ($row[17] != 0) {
                                    ?>
                                    <tr>
                                        <td>Impuesto tipo forro
                                            <br>
                                            (Precio + impuesto)
                                            <br>
                                            <?php echo $precio_final." + ". $row[17]?>
                                        </td>
                                        <td><?php echo "$ ".number_format($row[17], 0, ',', '.')?></td>
                                        <td><?php 
                                            $precio_final = $precio_final + $row[17];
                                            echo "$ ".number_format($precio_final , 0, ',', '.');
                                            ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                                <tr>
                                    <td>Precio Total</td>
                                    <td></td>
                                    <td><strong><?php echo "$ ".number_format($precio_final, 0, ',', '.'); ?></strong></td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>  
                </div>
                <div class="card shadow mt-2">
                    <div class="card-body">
                    <h5 class="text-center">Pago personalizado</h5>
                        <div class="row mt-2">
                            <div class="col-6">
                                
                                Ingrese monto
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control d-none" id="id_pago_per" value="<?php echo $row['0']?>">
                                <input type="number" class="form-control" id="monto_pago_per" value="<?php echo $precio_final ?>">
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                Ingrese comentario (opcional)
                            </div>
                            <div class="col-6">
                                <textarea class="form-control" id="coment_pago_per"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button class="btn btn-primary mb-3 w-100" onclick="pago_per()">Solicitar pago</button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow"> 
                    <div class="card-body">
                        <h5 class="text-center">Historial de la solicitud</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <?php
                                $result_detalle=$mysqli->query("SELECT b.name, a.fecha, a.comentario, a.estado 
                                FROM historial_soli as a, user as b
                                WHERE a.id_solicitud='".$_POST['id']."' AND a.user = b.rut ORDER BY a.fecha DESC");

                                while($row_detalle = $result_detalle->fetch_array()){
                                ?>
                                
                                    <tr>
                                        <td colspan=2 style="font-size: 14px;">
                                            
                                            Con fecha <strong><?php echo $row_detalle[1]?> </strong>
                                            el profesional <strong><?php echo $row_detalle[0]?></strong>,  
                                            dejo en estado: <strong><?php echo $row_detalle[3]?></strong>
                                            [Comentario: <strong><?php echo $row_detalle[2]?></strong>]</td>
                                            
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
        
    <?php
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

if ($_POST['accion'] == 5) {
    $valores = $_POST['array_datos'];
    
    foreach ($valores as $valor) {
        $primerValor = $valor['primerValor'];
        $segundoValor = $valor['segundoValor'];
        $sql="UPDATE solicitud 
        SET estado='Solicita pago', pago = '$segundoValor' WHERE id = '$primerValor' ";

        if ($mysqli->query($sql) === TRUE) {
            $sql="INSERT INTO historial_soli
            VALUES ('$primerValor', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '','Solicita pago') ";

            if ($mysqli->query($sql) === TRUE) {
                ?>
                <div class="card shadow">
                    <div class="card-body">
                        Historial actualizado y solicitud enviada correctamente id: <?php echo $primerValor?> con el valor: <?php echo $segundoValor?> <br> 
                    </div>
                </div>
                <?php
                
            }else{
                echo "Error insertar historial";
            }
            
        } else {
            echo "Error update tabla";; // error al insertar or: izquierda
        }
    }
}
if ($_POST['accion'] == 6) {

    


        $sql="UPDATE solicitud 
        SET estado='Solicita pago', pago = '".$_POST['monto']."' WHERE id = '".$_POST['id']."' ";

        if ($mysqli->query($sql) === TRUE) {
            $sql="INSERT INTO historial_soli
            VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '".$_POST['comentario']."','Solicita pago') ";

            if ($mysqli->query($sql) === TRUE) {
                echo "Insertado correctamente";
                
                
            }else{
                echo "Error insertar historial";
            }
            
        } else {
            echo "Error update tabla";; // error al insertar or: izquierda
        }
    
}
?>