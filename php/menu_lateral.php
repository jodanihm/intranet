<?php
include ("connect.php");
if ($_SESSION['tipo'] != 0) {
    if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 3) {
    ?>
        <li class="nav-item">
            <a class="nav-link active" href="ingreso.html"><i class="bi bi-journal-plus"></i> Ingreso de solicitud</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="buscar.html"><i class="bi bi-search"></i> Buscador solicitudes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pendientes.html"><i class="bi bi-hourglass-split"></i> Pendiente en Fábrica</a>
        </li>
    <?php
    }

    if ($_SESSION['tipo'] == 2) {
        ?>
        <li class="nav-item">
            <a class="nav-link" href="impresion.html"><i class="bi bi-printer"></i> Solicitudes de impresión</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="buscar_impresor.html"><i class="bi bi-search-heart"></i> Buscador de solicitudes</a>
        </li>
    <?php 
    }

    if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 3) {
        ?>
        <li class="nav-item">
            <a class="nav-link" href="recep_entre.html"><i class="bi bi-box-seam"></i> Recepción y entrega</a>
        </li>
    <?php 
    } 

    if ($_SESSION['tipo'] == 3) {
        ?>
        <li class="nav-item">
            <a class="nav-link" href="pago.html"><i class="bi bi-cash-coin"></i> Pago al administrador</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="reporte_pago.html"><i class="bi bi-receipt"></i> Reporte de pagos</a>
        </li>
    <?php 
    } 
    ?>
    <hr>
    <li class="nav-item">
        <a class="nav-link" href="https://plantillas.newfeetserver.es/login" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> https://plantillas.newfeetserver.es/login
        </a>
    </li>
    <?php 
}
if ($_SESSION['tipo'] == 0) {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="ingreso.html"><i class="bi bi-journal-plus"></i> Ingreso de solicitud</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="impresion.html"><i class="bi bi-printer"></i> Solicitudes de impresión</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="recep_entre.html"><i class="bi bi-box-seam"></i> Recepción y entrega</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="admin.html"><i class="bi bi-cash-stack"></i> Solicitar pago a centros</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="pendientes.html"><i class="bi bi-hourglass-split"></i> Pendiente en Fábrica</a>
    </li>
    <hr>
    <li class="nav-item">
        <a class="nav-link" href="pago.html"><i class="bi bi-cash-coin"></i> Pago al administrador</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="pago_imp.html"><i class="bi bi-person-badge-fill"></i> Pago impresor</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="finalizar.html"><i class="bi bi-check2-circle"></i> Finalizar proceso</a>
    </li>
    <hr>
    <li class="nav-item">
        
        <a class="nav-link" href="buscar.html"><i class="bi bi-search"></i> Buscador solicitudes (KINE)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="buscar_admin.html"><i class="bi bi-calendar-range"></i> Buscador entre fechas (ADMIN)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="buscar_impresor.html"><i class="bi bi-search-heart"></i> Buscador de solicitudes (impresor)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="reporte_pago.html"><i class="bi bi-receipt"></i> Reporte de pagos (KINE ADMIN)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="timeline.html"><i class="bi bi-bar-chart-line"></i> Gráfico horas fab. (ADMIN)</a>
    </li>
    <hr>
    <li class="nav-item">
        <a class="nav-link" href="resumen_mensual.html"><i class="bi bi-calendar-check"></i> Resumen mensual</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="buscar_sp.html"><i class="bi bi-diagram-3"></i> Ingresos por sucursal (agrupado por profesionales)</a>
    </li>
    <hr>
    <li class="nav-item">
        <a class="nav-link" href="total_mensual.html"><i class="bi bi-calculator"></i> Total mensual</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="estado_actual.html"><i class="bi bi-activity"></i> Estado Actual</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="admin_usuarios.html"><i class="bi bi-person-gear"></i> Cuentas de usuario</a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="precios.html"><i class="bi bi-tags"></i> Mantenedor Precios</a>
    </li>
    <hr>
    <li class="nav-item">
        <a class="nav-link" href="https://plantillas.newfeetserver.es/login" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> https://plantillas.newfeetserver.es/login
        </a>
    </li>
<?php } ?>

<button class="btn btn-secondary btn-sm mt-3" disabled>
  Usuario: <?php echo $_SESSION['nombre'] ?>
</button>
<button class="btn btn-danger btn-sm mt-2" onclick="salir()">Cerrar sesión</button>
