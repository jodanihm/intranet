<?php
include ("connect.php");

if ($_POST['accion'] == "seguridad") {
    //session_start();
    if (isset($_SESSION['rut']) ) {
		
        if($_SESSION['tipo'] == 0 ){
            $acceso="aceptado";
        }
        elseif($_SESSION['tipo'] == 1 && ($_POST['pag'] == "ingreso.html" || $_POST['pag'] == "buscar.html" || $_POST['pag'] == "recep_entre.html"|| $_POST['pag'] == "pendientes.html")){
            $acceso="aceptado";
        }
        elseif($_SESSION['tipo'] == 2 && ($_POST['pag'] == "impresion.html" || $_POST['pag'] == "buscar_impresor.html")){
            $acceso="aceptado";
        }
        elseif($_SESSION['tipo'] == 3 &&  ($_POST['pag'] == "ingreso.html" || $_POST['pag'] == "buscar.html" || $_POST['pag'] == "recep_entre.html" || $_POST['pag'] == "pago.html" || $_POST['pag'] == "reporte_pago.html" || $_POST['pag'] == "pendientes.html")){
            $acceso="aceptado";
        }else{
            $acceso="denegado";
        }
	}else{
	    $acceso="denegado";
	}
    echo $acceso;

}

if ($_POST['accion'] == "salir") {
    $user_id = $_SESSION['rut'];

    // Preparar la consulta para borrar el token de sesión de la base de datos
    $stmt = $mysqli->prepare("UPDATE user SET session_token = NULL WHERE rut = ?");
    if ($stmt) {
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();
    }
    $_SESSION = array();
    //session_start();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_unset();
    session_destroy();
    if (isset($_COOKIE['session_token'])) {
        setcookie('session_token', '', time() - 42000, '/');
    }
    echo "salir";
}
if ($_POST['accion'] == "detalle-solicitud") {
    ?>
    <div class="modal fade" id="myModal-detalle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Detalles de la solicitud</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cuerpo-modal">
                <?php
                $resultado=$mysqli->query("SELECT a.id, a.rut, p.nombre AS nombre_paciente, a.orientacion, b.nombre AS nombre_ciudad, a.gcode_der, a.gcode_par, a.gcode_iz, a.talla, a.dureza, f.tipo AS tipo_forro, a.peso, a.estado
                FROM solicitud a
                JOIN paciente p ON a.rut = p.rut
                JOIN ciudad b ON a.ciudad = b.id
                JOIN forro f ON a.forro = f.id
                WHERE a.id='".$_POST['id']."'
                UNION
                SELECT a.id, a.rut, p.nombre AS nombre_paciente, a.orientacion, b.nombre AS nombre_ciudad, a.gcode_der, a.gcode_par, a.gcode_iz, a.talla, a.dureza, f.tipo AS tipo_forro, a.peso, a.estado
                FROM solicitud_ter a
                JOIN paciente p ON a.rut = p.rut
                JOIN ciudad b ON a.ciudad = b.id
                JOIN forro f ON a.forro = f.id
                WHERE a.id='".$_POST['id']."'
                
                ");

                $row = $resultado->fetch_array();
                ?>
                 <table class="table table-bordered">
                    <thead >
                        <tr class="table-dark">
                            <th colspan=2 class="text-center">Detalle de la solicitud</th>
                        </tr>
                    </thead>
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
                            <td><?php echo $row['4']?></td>
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
                            <th>Peso paciente</th>
                            <td><?php echo $row['11']?> KG</td>
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
                        
                     <?php if ($row['12'] == 'Pendiente' || $row['12'] == 'En proceso') { ?>
                        <tr>
                            <th>Imprimir etiqueta</th>
                            <td class="text-center">
                                <a class="btn btn-primary"
                                href="php/etiqueta.php?id=<?php echo $row['0'] ?>&paciente=<?php echo urlencode($row['2']) ?>&dureza=<?php echo urlencode($row['9']) ?>&forro=<?php echo urlencode($row['10']) ?>"
                                target="_blank">
                                Imprimir etiqueta (PDF)
                                </a>
                            </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                    <?php 
                    if ($_POST['tipo'] != 'reporte_impresor' && $_POST['tipo'] != 'estado-actual') {
                        ?>
                        <thead >
                            <tr class="table-dark">
                                <th colspan=2 class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>   
                            <?php 
                            
                                if ($_POST['tipo'] == "ingreso" && $row['12'] == 'Ingresada' ) {
                                    $titulo = 'Solicitar impresión';
                                    $titulo_cancel = 'Eliminar evaluación';
                                    $confirm = 'Pendiente';
                                    $cancel = 'cancelada';
                                    
                                }
                                // cobro de garantia
                                if ($_POST['tipo'] == "ingreso" && $row['12'] != 'Ingresada' ) {
                                    $titulo = 'Cobrar garantía';
                                    $titulo_cancel = 'Cobrar garantía';
                                    $confirm = 'Garantia';
                                    $cancel = 'Garantia';
                                    
                                }
                                if ($_POST['tipo'] == "impresion" && ($row['12'] == 'Reimpresion' ||  $row['12'] == 'Garantia')) {
                                    $titulo = 'Comenzar re impresión';
                                    $confirm = 'En proceso';
                                    $cancel = 'Ingresada';
                                }
                                if ($_POST['tipo'] == "impresion" && $row['12'] == 'Reparacion') {
                                    $titulo = 'Comenzar reparación';
                                    $titulo_cancel = 'Cambiar estado a "Reimpresión"';
                                    $confirm = 'En proceso';
                                    $cancel = 'Reimpresion';
                                }
                                
                                if ($_POST['tipo'] == "impresion" && $row['12'] == 'Pendiente' ) {
                                    $titulo = 'Comenzar impresión';
                                    $titulo_cancel = 'Cancelar solicitud';
                                    $confirm = 'En proceso';
                                    $cancel = 'Ingresada';
                                }
                                if ($_POST['tipo'] == "impresion" && $row['12'] == 'En proceso') {
                                    $titulo = 'Cambiar a estado "En prensa"';
                                    $titulo_cancel = 'Cancelar "volver a pendientes"';
                                    $confirm = 'En prensa';
                                    $cancel = 'Pendiente';
                                }
                                if ($_POST['tipo'] == "impresion" && $row['12'] == 'En prensa') {
                                    $titulo = 'Impresión finalizada';
                                    $titulo_cancel = 'Cancelar, volver a estado "En prensa"';
                                    $confirm = 'Producto impreso';
                                    $cancel = 'En proceso';
                                }
                                if ($_POST['tipo'] == "impresion" && $row['12'] == 'Producto impreso') {
                                    $titulo = 'Enviar producto (adjunte N° seguimiento)';
                                    $titulo_cancel = 'Cancelar, volver a estado "En prensa"';
                                    $confirm = 'Enviado';
                                    $cancel = 'En prensa';
                                }
                                if ($_POST['tipo'] == "recep_entre" && $row['12'] == 'Enviado') {
                                    $titulo = 'Recibí conforme';
                                    $confirm = 'Recepcionado';
                                    //$cancel = 'Pendiente';
                                }
                                if ($_POST['tipo'] == "recep_entre" && $row['12'] == 'Recepcionado') {
                                    $titulo = 'Entrega final a cliente';
                                    $titulo_cancel = 'Solicitar re impresión';
                                    $confirm = 'Entregado';
                                    $cancel = 'Reimpresion';
                                }
                                ?>  
                                <td class="text-center">
                                    <?php
                                    if ($titulo != 'Cobrar garantía') {
                                        # code...
                                    
                                    ?>
                                    <button class="btn btn-success"  type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" ><?php echo $titulo?></button>
                                    <div class="collapse mt-3" id="collapseExample">
                                            Agregar comentario: 

                                            <textarea class="form-control" maxlength="2000" id="texto-confirmado"></textarea>
                                            <div class="row m-2">
                                                <button class="btn btn-primary btn-sm col-sm-6" onclick="cambia_estado('<?php echo $confirm?>' ,'<?php echo $row['0']?>')">Confirmar</button>
                                                <button class="btn btn-danger btn-sm col-sm-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Cancelar</button>
                                            </div>
                                    </div>
                                    <?php
                                    } 
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($row['12'] != 'Enviado' AND $row['12'] != 'Reimpresion' AND $row['12'] != 'Recepcionado'AND $row['12'] != 'Garantia') {
                                        ?>
                                        <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" ><?php echo $titulo_cancel?></button>
                                        <div class="collapse mt-3" id="collapseExample2">
                                                Motivo (*): 
                                                <textarea class="form-control" maxlength="2000" id="texto-cancelado"></textarea>
                                                <div class="row m-2">
                                                    <button class="btn btn-primary btn-sm col-sm-6" onclick="cancelar('<?php echo $cancel?>','<?php echo $row['0']?>')">Confirmar(<?php echo $titulo_cancel?>)</button>
                                                    <button class="btn btn-danger btn-sm col-sm-6" type="button" data-bs-toggle="collapse2" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">Cancelar</button>
                                                </div>
                                        </div>
                                        <?php
                                    }
                                    if ($row['12'] == 'Recepcionado') {
                                        ?>
                                        <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" ><?php echo $titulo_cancel?></button>
                                        <div class="collapse mt-3" id="collapseExample2">
                                            <form class="row g-3" enctype="multipart/form-data" id="update_form" method="post">
                                                Motivo (* Campo obligatorio): 
                                                <textarea class="form-control" maxlength="2000" name="update-motivo"></textarea>
                                                <br>
                                                Modificación de parametros:
                                                <br>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">Talla</div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="update-talla" value="<?php echo $row['8']?>">
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">Dureza</div>
                                                    <div class="col-sm-8">
                                                        <select id="dureza" name="update-dureza" class="form-select">
                                                            <?php
                                                            $var = 70;
                                                            while($var <= 100){
                                                                if ($var == $row['9']) {
                                                                    echo '<option = "'.$var.'" selected>'.$var.'</option>'; 
                                                                }else{
                                                                    echo '<option = "'.$var.'">'.$var.'</option>'; 
                                                                }
                                                                
                                                                $var = $var+5;
                                                            }

                                                            ?>
                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">Tipo</div>
                                                    <div class="col-sm-8">
                                                        <select id="c_forro" name="update-forro" class="form-select">
                                                        <?php
                                                            $resultado_forro=$mysqli->query("SELECT id, tipo FROM forro ");
                                                            while($row_f = $resultado_forro->fetch_array()){
                                                                if ($row_f[1] == $row['10']) {
                                                                    ?>
                                                                    <option value="<?php echo $row_f[0]?>" selected><?php echo $row_f[1]?></option>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <option value="<?php echo $row_f[0]?>"><?php echo $row_f[1]?></option>
                                                                    <?php
                                                                }
                                                                
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                if ($row['3'] == 'izquierda' || $row['3'] == 'par') {
                                                ?>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">.gcode izquierda</div>
                                                    <div class="col-sm-8">
                                                            <input class="form-control " type="file" id="file-iz" name="update-file-iz" onchange="fileValidation('file-iz');" accept=".gcode" size="15000">
                                                    </div>
                                                </div>
                                                <?php
                                                }   
                                                if ($row['3'] == 'par') {
                                                ?>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">.gcode par</div>
                                                    <div class="col-sm-8 text-center">
                                                        <input class="form-control " type="file" id="file-par" name="update-file-par" onchange="fileValidation('file-par');" accept=".gcode" size="15000">
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                if ($row['3'] == 'derecha' || $row['3'] == 'par') {
                                                ?>
                                                <div class="row mt-2">
                                                    <div class="col-sm-4">.gcode derecha</div>
                                                    <div class="col-sm-8 text-center">
                                                        <input class="form-control " type="file" id="file-der" name="update-file-der" onchange="fileValidation('file-der');" accept=".gcode" size="15000">
                                                    </div>
                                                    
                                                </div>
                                                <?php
                                                }   
                                                ?>
                                            </form>
                                                <div class="row m-2">
                                                    <button class="btn btn-primary btn-sm col-sm-6" onclick="update_order('<?php echo $cancel?>','<?php echo $row['0']?>')">Confirmar(<?php echo $titulo_cancel?>)</button>
                                                    <button class="btn btn-danger btn-sm col-sm-6" type="button" data-bs-toggle="collapse2" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">Cancelar</button>
                                                </div>
                                            
                                            <div class="mt-2" id="result_update"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    
                                </td>
                            
                            </tr>
                        </tbody>
                        <?php
                    }
                    ?>
                    <thead >
                        <tr class="table-dark">
                            <th colspan=2 class="text-center">Historial de la solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $resultado=$mysqli->query("SELECT b.name, a.fecha, a.comentario, a.estado 
                    FROM historial_soli as a, user as b
                    WHERE a.id_solicitud='".$_POST['id']."' AND a.user = b.rut ORDER BY a.fecha DESC");

                    while($row = $resultado->fetch_array()){
                    ?>
                    
                        <tr>
                            <td colspan=2>
                                Con fecha <strong><?php echo $row[1]?> </strong>
                                el profesional <strong><?php echo $row[0]?></strong>,  
                                dejo en estado: <strong><?php echo $row[3]?></strong>
                                [Comentario: <strong><?php echo $row[2]?></strong>]</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal" id="btn-cerrar-modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    <?php
    
}

if ($_POST['accion'] == "3") {
    
    if ($_POST['estado'] == 'cancelada') {
        $mysqli->query("DELETE FROM solicitud WHERE id = '".$_POST['id']."' ");
        $mysqli->query("DELETE FROM historial_soli WHERE id_solicitud = '".$_POST['id']."' ");
        echo $_POST['estado'];
    }elseif ($_POST['estado'] == 'Garantia') {
        $result=$mysqli->query("SELECT 1 FROM solicitud_ter WHERE id = '".$_POST['id']."'");
        
        if ($result->num_rows > 0) {
            // Mover datos de solicitud_ter a solicitud
            $mysqli->query("INSERT INTO solicitud SELECT * FROM solicitud_ter WHERE id = '".$_POST['id']."'");
            

            // Actualizar estado a "garantia" en solicitud
            $mysqli->query("UPDATE solicitud SET estado = 'Garantia' WHERE id = '".$_POST['id']."'");
            

            // Eliminar registro de solicitud_ter
            $mysqli->query("DELETE FROM solicitud_ter WHERE id = '".$_POST['id']."'");
            
        } else {
            // Si no existe en solicitud_ter, actualizar estado a "garantia" en solicitud
            $mysqli->query( "UPDATE solicitud SET estado = 'Garantia' WHERE id = '".$_POST['id']."'");
           
        }
        $sql2="INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '".$_POST['texto']."', '".$_POST['estado']."') ";
        if ($mysqli->query($sql2) === TRUE) {
            echo $_POST['estado'];
        }else{
            echo 2;
        }
    }else{
        $sql="UPDATE solicitud SET estado='".$_POST['estado']."' WHERE id = '".$_POST['id']."' ";
    
        if ($mysqli->query($sql) === TRUE) {
            
            $sql2="INSERT INTO historial_soli(id_solicitud, user, fecha, comentario, estado) VALUES ('".$_POST['id']."', '".$_SESSION['rut']."', '".date('Y-m-d H:i:s')."', '".$_POST['texto']."', '".$_POST['estado']."') ";
            if ($mysqli->query($sql2) === TRUE) {
                echo $_POST['estado'];
            }else{
                echo 2;
            }
        } else {
            echo 2; // error al insertar or: izquierda
        }
    }
    
}

if ($_POST['accion'] == "detalle-solicitud-na") {
    ?>
        <div class="modal fade" id="myModal-detalle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Detalles de la solicitud</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cuerpo-modal">
    <?php
    
    $resultado=$mysqli->query("SELECT a.id, a.rut, p.nombre AS nombre_paciente, a.orientacion, b.nombre AS nombre_ciudad, a.gcode_der, a.gcode_par, a.gcode_iz, a.talla, a.dureza, f.tipo AS tipo_forro, a.peso, a.estado
    FROM solicitud a
    JOIN paciente p ON a.rut = p.rut
    JOIN ciudad b ON a.ciudad = b.id
    JOIN forro f ON a.forro = f.id
    WHERE a.id='".$_POST['id']."'
    UNION ALL
    SELECT st.id, st.rut, p.nombre AS nombre_paciente, st.orientacion, b.nombre AS nombre_ciudad, st.gcode_der, st.gcode_par, st.gcode_iz, st.talla, st.dureza, f.tipo AS tipo_forro, st.peso, st.estado
    FROM solicitud_ter st
    JOIN paciente p ON st.rut = p.rut
    JOIN ciudad b ON st.ciudad = b.id
    JOIN forro f ON st.forro = f.id
    WHERE st.id='".$_POST['id']."';
    
    ");

    $row = $resultado->fetch_array();
    ?>
     <table class="table table-bordered">
        <thead >
            <tr class="table-dark">
                <th colspan=2 class="text-center">Detalle de la solicitud</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th >Id solicitud:</th>
            <td><?php echo $row[0]?></td>

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
                <td><?php echo $row['4']?></td>
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
                <th>Peso paciente</th>
                <td><?php echo $row['11']?> KG</td>
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
        <thead >
            <tr class="table-dark">
                <th colspan=2 class="text-center">Historial de la solicitud</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $resultado=$mysqli->query("SELECT b.name, a.fecha, a.comentario, a.estado 
        FROM historial_soli as a, user as b
        WHERE a.id_solicitud='".$_POST['id']."' AND a.user = b.rut
        AND a.estado != 'Pagado a impresor' 
        ORDER BY a.fecha DESC
        ");

        while($row = $resultado->fetch_array()){
        ?>
        
            <tr>
                <td colspan=2>
                    Con fecha <strong><?php echo $row[1]?> </strong>
                    el profesional <strong><?php echo $row[0]?></strong>,  
                    dejo en estado: <strong><?php echo $row[3]?></strong>
                    <?php
                    if ($row[2] != "" || $row[2] != " ") {
                        ?>
                        <br>
                        Comentario: <strong><?php echo $row[2]?></strong></td>
                        <?php
                    }
                    ?>
                    
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    </div>
            <div class="modal-footer">
              <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal" id="btn-cerrar-modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    <?php  
}
if ($_POST['accion'] == "detalle-solicitud2") {
    ?>
    <div class="modal fade" id="myModal-detalle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Detalles de la solicitud</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cuerpo-modal">
            <?php
            $resultado=$mysqli->query("SELECT * FROM solicitud_ter WHERE id='".$_POST['id']."' ");
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
                    <?php 
                    if ($_SESSION['tipo'] == 0) {
                        ?>
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
                                                <tr>
                                                    <td>Precio Total</td>
                                                    <td></td>
                                                    <td><strong><?php echo "$ ".number_format($precio_final, 0, ',', '.'); ?></strong></td>
                                                </tr>
                                            </tbody>
                                            
                                        </table>
                                    </div>  
                                </div>
                                
                            </div>
                        <?php
                    }
                    ?>
                    
                </div>
                <table class="table table-bordered">
                    <thead >
                        <tr class="table-dark">
                            <th colspan=2 class="text-center">Historial de la solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $resultado=$mysqli->query("SELECT b.name, a.fecha, a.comentario, a.estado 
                    FROM historial_soli as a, user as b
                    WHERE a.id_solicitud='".$_POST['id']."' AND a.user = b.rut
                    ORDER BY a.fecha DESC");

                    while($row = $resultado->fetch_array()){
                    ?>
                    
                        <tr>
                            <td colspan=2>
                                Con fecha <strong><?php echo $row[1]?> </strong>
                                el profesional <strong><?php echo $row[0]?></strong>,  
                                dejo en estado: <strong><?php echo $row[3]?></strong>
                                [Comentario: <strong><?php echo $row[2]?></strong>]</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal" id="btn-cerrar-modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    <?php
    
}

if ($_POST['accion'] == "extraer_paciente"){
    $resultado=$mysqli->query("SELECT
    COALESCE(s.rut, st.rut) AS rut_paciente,
    COALESCE(p.nombre, pt.nombre) AS nombre_paciente,
    COALESCE(p.fono, pt.fono) AS fono_paciente,
    COALESCE(p.correo, pt.correo) AS correo_paciente
    FROM solicitud s
    LEFT JOIN paciente p ON s.rut = p.rut
    LEFT JOIN solicitud_ter st ON s.id = st.id
    LEFT JOIN paciente pt ON st.rut = pt.rut
    WHERE s.id = '".$_POST['id_solicitud']."' OR st.id = '".$_POST['id_solicitud']."'
    ");

    $row = $resultado->fetch_array();
    $array = array($row[0],$row[1],$row[2],$row[3]);

    echo json_encode($array);
} 
?>