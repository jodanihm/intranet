<?php
include ("connect.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {    

    
    //$row = $resultado->fetch_array();
    if ($_SESSION['tipo'] == 0) {
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
 JOIN paciente AS p ON s.rut = p.rut
 WHERE s.estado = 'Solicita pago';  ");
    }else{
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
 JOIN paciente AS p ON s.rut = p.rut
 WHERE s.estado = 'Solicita pago'
 AND s.ciudad = '".$_SESSION['ciudad']."'  ");
    }
   

    $result3=$mysqli->query("SELECT nombre, impuesto_adulto, impuesto_infantil FROM ciudad WHERE id = '".$_SESSION['ciudad']."' ");
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
                <?php
                    if ($_SESSION['tipo'] == 0) {
                        ?>
                         <h5 class="text-center">Solicitudes de todas las sucursales</h5>
                        <?php
                    }else{
                        ?>
                        <h5 class="text-center">Solicitudes sucursal <?php echo $row3[0] ?></h5>
                        <?php
                    }
                ?>
                
                <table class="table text-center table-bordered">
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
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
                                
                                
                            }
            
                            if ($row['8'] > 35) {
                                $talla = $row['8']." Adulto";
                                
                            }
                            
                            ?>
                            <tr>
                                <td><?php echo $row[0]?></cite></td>
                                <td><?php echo $row[2]?></td>
                                <td><?php echo $row[3]?></td>
                                <td><?php echo $talla;?></td>
                                
                                <td><?php echo $row[4]?></td>
                            
                                <td><?php echo $nombre_convenio." (- ".$desc_conv."%)"?></td>
                                
                                <td>
                                    <?php 
                                    echo "$ ".number_format( $row[15] , 0, ',', '.');
                                    ?>
                                </td>
                                
                               
                                <td><button class="btn btn-danger btn-sm" onclick="ver_detalles('<?php echo $row[0]?>')">Detalle solicitud</button></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="actualizarTotal()" onchange="actualizarBoton()" type="checkbox" value="<?php echo $row[0]?>" name="s_pago" data-segundo-valor="<?php echo $row[15];?>">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Pagar
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            
                            
                            <?php
                        }
                        ?>    
                        <tr>
                            <td colspan=8></td>
                            
                            <td>
                                <div id="total-pagar">Total: $0</div>
                                <input type="number" class="d-none" id="input-pre-pago">
                                <button class="btn btn-success btn-sm w-100" id="calcular" disabled onclick="modal_pagar()">Pagar</button>
                            </td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
        </div>      
        
        <?php
    }
    


}
if ($_POST['accion'] == 2) {
    $resultado=$mysqli->query("SELECT * FROM solicitud WHERE id='".$_POST['id']."' ");
    $row = $resultado->fetch_array();


    $result3=$mysqli->query("SELECT nombre, impuesto_adulto, impuesto_infantil FROM ciudad WHERE id = '$row[11]' ");
    $row3 = $result3->fetch_array();


    $r_reimp=$mysqli->query("SELECT count(*) FROM historial_soli WHERE id_solicitud = '".$_POST['id']."' AND estado = 'Reimpresion' ");
    $row_r = $r_reimp->fetch_array();
    $c_reimp = $row_r[0];

    

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
                                                    <img src="img/download.png" alt="" style="width:30px">
                                                    <p>Izq</p>
                                                <?php
                                                }   
                                                ?>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <?php
                                                if ($row['3'] == 'par') {
                                                ?>
                                                    <img src="img/download.png" alt="" style="width:30px">
                                                    <p>Par</p>
                                                <?php
                                                }   
                                                ?>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <?php
                                                if ($row['3'] == 'derecha' || $row['3'] == 'par') {
                                                ?>
                                                    <img src="img/download.png" alt="" style="width:30px">
                                                    <p>Der</p>
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
                <div class="card shadow mt-2">
                    <div class="card-body">
                    <h5 class="text-center">Total a Pagar <?php echo "$ ".number_format( $row[15] , 0, ',', '.');?></h5>
                        <div class="row mt-2">
                            <div class="col-6">
                                Banco
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control d-none" id="id_pago_per" value="<?php echo $row['0']?>">
                                <input type="text" class="form-control" id="banco_pago_per" >
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                Ingrese codigo transferencia
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" id="codigo_pago_per">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button class="btn btn-primary mb-3 w-100" onclick="pago_per()">Pagar</button>
                                <div class="mt-3" id="estado-pago-per"></div>
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
                                WHERE a.id_solicitud='".$_POST['id']."' AND a.user = b.rut");

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




if ($_POST['accion'] == 5) {
    $valores = $_POST['array_datos'];
    
    foreach ($valores as $valor) {
        $primerValor = $valor['primerValor'];
        $segundoValor = $valor['segundoValor'];

        $coment = "Banco: ".$_POST['banco']." Codigo transferencia: ".$_POST['codigo'];

        $sql="UPDATE solicitud 
        SET estado='Pagado', pago = '$segundoValor' WHERE id = '$primerValor' ";

        if ($mysqli->query($sql) === TRUE) {
            $sql="INSERT INTO historial_soli
            VALUES ('$primerValor', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '$coment','Pagado') ";

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
        SET estado='Pagado' WHERE id = '".$_POST['id']."' ";
        $coment = "Banco: ".$_POST['banco']." Codigo transferencia: ".$_POST['codigo'];
        if ($mysqli->query($sql) === TRUE) {
            $sql="INSERT INTO historial_soli
            VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '$coment','Pagado') ";

            if ($mysqli->query($sql) === TRUE) {
                echo "Insertado correctamente";
                
                
            }else{
                echo "Error insertar historial";
            }
            
        } else {
            echo "Error update tabla";; // error al insertar or: izquierda
        }
    
}
if ($_POST['accion'] == 7) {
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
            <div class="card shadow mt-2">
                    <div class="card-body">
                    <h5 class="text-center">Total a Pagar <?php echo "$ ".number_format($_POST['monto'], 0, ',', '.')?></h5>
                        <div class="row mt-2">
                            <div class="col-6">
                                Banco
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" id="banco_pago_per" >
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                Ingrese codigo transferencia
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" id="codigo_pago_per">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button class="btn btn-primary mb-3 w-100" onclick="pagar()">Pagar</button>
                                <div class="mt-3" id="estado-pago"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
    <?php
}
?>