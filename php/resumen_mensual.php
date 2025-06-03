<?php
include ("connect.php");
include("topbar.php");
//header('Content-Type: text/html; charset=utf-8');

if ($_POST['accion'] == 1) {  
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    
if ($_POST['tipo'] == '1') {
    $result=$mysqli->query("SELECT 
    subquery.*,
    IFNULL(
        (SELECT pago FROM solicitud WHERE id = subquery.id_c), 
        (SELECT pago FROM solicitud_ter WHERE id = subquery.id_c)
    ) AS pago,
    IFNULL(
        (SELECT pago_imp FROM solicitud WHERE id = subquery.id_c), 
        (SELECT pago_imp FROM solicitud_ter WHERE id = subquery.id_c)
    ) AS pago_imp
    FROM (
        SELECT 
            id_solicitud as id_c,
            MIN(CASE WHEN estado = 'Ingresada' THEN estado END) AS estado_ingresada,
            MIN(CASE WHEN estado = 'Ingresada' THEN fecha END) AS fecha_ingresada,
            MIN(CASE WHEN estado = 'Solicita Pago' THEN estado END) AS estado_solicita_pago,
            MIN(CASE WHEN estado = 'Solicita Pago' THEN fecha END) AS fecha_solicita_pago,
            MIN(CASE WHEN estado = 'Pagado' THEN estado END) AS estado_pagado,
            MIN(CASE WHEN estado = 'Pagado' THEN fecha END) AS fecha_pagado,
            MIN(CASE WHEN estado = 'Pagado a Impresor' THEN estado END) AS estado_pagado_a_impresor,
            MIN(CASE WHEN estado = 'Pagado a Impresor' THEN fecha END) AS fecha_pagado_a_impresor,
            MIN(CASE WHEN estado = 'Finalizado' THEN estado END) AS estado_finalizado,
            MIN(CASE WHEN estado = 'Finalizado' THEN fecha END) AS fecha_finalizado
        FROM 
            historial_soli
        GROUP BY
            id_solicitud
    ) AS subquery
    WHERE 
        YEAR(fecha_ingresada) = '$anio'
        AND MONTH(fecha_ingresada) = '$mes'  
    ORDER BY `subquery`.`id_c` ASC
        ");
}elseif ($_POST['tipo'] == '2') {
    $result=$mysqli->query("SELECT 
    subquery.*,
    IFNULL(
        (SELECT pago FROM solicitud WHERE id = subquery.id_c), 
        (SELECT pago FROM solicitud_ter WHERE id = subquery.id_c)
    ) AS pago,
    IFNULL(
        (SELECT pago_imp FROM solicitud WHERE id = subquery.id_c), 
        (SELECT pago_imp FROM solicitud_ter WHERE id = subquery.id_c)
    ) AS pago_imp
    FROM (
        SELECT 
            id_solicitud as id_c,
            MIN(CASE WHEN estado = 'Ingresada' THEN estado END) AS estado_ingresada,
            MIN(CASE WHEN estado = 'Ingresada' THEN fecha END) AS fecha_ingresada,
            MIN(CASE WHEN estado = 'Solicita Pago' THEN estado END) AS estado_solicita_pago,
            MIN(CASE WHEN estado = 'Solicita Pago' THEN fecha END) AS fecha_solicita_pago,
            MIN(CASE WHEN estado = 'Pagado' THEN estado END) AS estado_pagado,
            MIN(CASE WHEN estado = 'Pagado' THEN fecha END) AS fecha_pagado,
            MIN(CASE WHEN estado = 'Pagado a Impresor' THEN estado END) AS estado_pagado_a_impresor,
            MIN(CASE WHEN estado = 'Pagado a Impresor' THEN fecha END) AS fecha_pagado_a_impresor,
            MIN(CASE WHEN estado = 'Finalizado' THEN estado END) AS estado_finalizado,
            MIN(CASE WHEN estado = 'Finalizado' THEN fecha END) AS fecha_finalizado
        FROM 
            historial_soli
        GROUP BY
            id_solicitud
    ) AS subquery
    WHERE 
        YEAR(fecha_solicita_pago) = '$anio'
        AND MONTH(fecha_solicita_pago) = '$mes'  
    ORDER BY `subquery`.`id_c` ASC
        ");
}

    
    
  
    
        
        ?>

            <div class="card shadow">
                <div class="card-body">
                    
                    <table class="table table-hover text-center table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">ID</th>
                                <th scope="col" >Estado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha</th>
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
                                    <td class="table-info"><?php echo $row[1]?></td>
                                    <td class="table-info"><?php echo $row[2]?></td>
                                    <td class="table-danger"><?php echo $row[3]?></td>
                                    <td class="table-danger"><?php echo $row[4]?></td>
                                    <td class="table-warning"><?php echo $row[5]?></td>
                                    <td class="table-warning"><?php echo $row[6]?></td>
                                    <td class="table-primary"><?php echo $row[7]?></td>
                                    <td class="table-primary"><?php echo $row[8]?></td>
                                    <td class="table-secondary"><?php echo $row[9]?></td>
                                    <td class="table-secondary"><?php echo $row[10]?></td>
                                    <td class="table-dark"><?php echo "$ ".number_format( $row[11] , 0, ',', '.');?></td>
                                    <td class="table-dark"><?php echo "$ ".number_format( $row[12] , 0, ',', '.');?></td>
                                </tr>
                                <?php
                                $total = $total + 1 ;
                                $precio_total_p = $precio_total_p + $row[11];
                                $precio_total_i = $precio_total_i + $row[12];
                            }
                            
                            ?>

                            <tr>
                                <td colspan=12></td>
                                <td><?php echo "$ ".number_format( $precio_total_p , 0, ',', '.');?></td>
                                <td><?php echo "$ ".number_format( $precio_total_i , 0, ',', '.');?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php
        
    
}


?>