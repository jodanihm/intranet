<?php
require_once("connect.php");

$q = isset($_POST['query']) ? $mysqli->real_escape_string($_POST['query']) : '';
$ciudad = $_SESSION['ciudad'] ?? '';
$tipo_usuario = $_SESSION['tipo'] ?? 1; // 0 = admin, otro = normal

// Filtros condicionales
$filtro_ciudad = ($tipo_usuario == 0) ? '1=1' : "s.ciudad = '$ciudad'";
$filtro_ciudad_ter = ($tipo_usuario == 0) ? '1=1' : "st.ciudad = '$ciudad'";

$sql = "(SELECT s.id, s.rut, p.nombre AS nombre_paciente, s.orientacion, s.estado
        FROM solicitud AS s
        JOIN paciente AS p ON s.rut = p.rut
        WHERE (s.id LIKE '%$q%' OR s.rut LIKE '%$q%' OR p.nombre LIKE '%$q%')
          AND $filtro_ciudad
          AND LOWER(s.estado) IN ('ingresada','pendiente','entregado'))
        UNION ALL
        (SELECT st.id, st.rut, p.nombre AS nombre_paciente, st.orientacion, st.estado
        FROM solicitud_ter AS st
        JOIN paciente AS p ON st.rut = p.rut
        WHERE (st.id LIKE '%$q%' OR st.rut LIKE '%$q%' OR p.nombre LIKE '%$q%')
          AND $filtro_ciudad_ter
          AND LOWER(st.estado) IN ('ingresada','pendiente','entregado'))
        ORDER BY id";

$resultado = $mysqli->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
} else {
    if ($resultado->num_rows != 0) {
        echo '<div class="card shadow"><div class="card-body">';
        echo '<h5 class="text-center">Resultado de la búsqueda</h5>';
        echo '<table class="table"><thead><tr><th scope="col">ID</th><th scope="col">Rut Paciente</th><th scope="col">Nombre Paciente</th><th scope="col">Orientación</th><th scope="col">Estado</th></tr></thead><tbody>';

        while ($row = $resultado->fetch_array()) {
            echo '<tr>';
            echo '<td><button class="btn btn-dark btn-sm" onclick="detalle_solcitud_na(\'' . $row[0] . '\')">' . $row[0] . '</button></td>';
            echo '<td>' . $row[1] . '</td>';
            echo '<td>' . $row[2] . '</td>';
            echo '<td>' . $row[3] . '</td>';
            echo '<td>' . $row[4] . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></div></div>';
    } else {
        echo '<div class="alert alert-warning">No se encontraron resultados.</div>';
    }
}
?>