<?php
require_once("connect.php");
include("topbar.php");
function btnEditar($tipo, $id) {
  return "<button class='btn btn-warning btn-sm' onclick=\"editarRegistro('$tipo','$id')\"><i class='bi bi-pencil'></i></button>";
}

// Tabla precios
$precios = $mysqli->query("SELECT * FROM precios ORDER BY `Talla`");
if (!$precios) {
    die("Error en la consulta de precios: " . $mysqli->error);
}

echo "<h5>Precios por talla</h5>
<table class='table table-bordered table-sm'>
<thead class='table-dark'>
<tr>
  <th>Talla</th>
  <th>Imp. Par</th>
  <th>Imp. Unit</th>
  <th>Reimp. Par</th>
  <th>Reimp. Unit</th>
  <th>Pago Imp. Par</th>
  <th>Pago Imp. Unit</th>
  <th>Editar</th>
</tr>
</thead><tbody>";

while ($row = $precios->fetch_assoc()) {
  echo "<tr>
    <td>{$row['Talla']}</td>
    <td>{$row['imp_par']}</td>
    <td>{$row['imp_unit']}</td>
    <td>{$row['reimp_par']}</td>
    <td>{$row['reimp_unit']}</td>
    <td>{$row['pago_imp_par']}</td>
    <td>{$row['pago_imp_unit']}</td>
    <td>" . btnEditar("precio", $row['Talla']) . "</td>
  </tr>";
}
echo "</tbody></table><hr>";

// Tabla ciudad
$ciudades = $mysqli->query("SELECT * FROM ciudad ORDER BY nombre");
if (!$ciudades) {
    die("Error en la consulta de ciudades: " . $mysqli->error);
}

echo "<h5>Impuestos por ciudad</h5>
<table class='table table-bordered table-sm'>
<thead class='table-dark'>
<tr>
  <th>Ciudad</th>
  <th>Impuesto Adulto</th>
  <th>Impuesto Infantil</th>
  <th>Editar</th>
</tr>
</thead><tbody>";

while ($row = $ciudades->fetch_assoc()) {
  echo "<tr>
    <td>{$row['nombre']}</td>
    <td>{$row['impuesto_adulto']}</td>
    <td>{$row['impuesto_infantil']}</td>
    <td>" . btnEditar("ciudad", $row['id']) . "</td>
  </tr>";
}
echo "</tbody></table><hr>";

// Tabla convenio
$convenio = $mysqli->query("SELECT * FROM convenio ORDER BY nombre");
if (!$convenio) {
    die("Error en la consulta de convenio: " . $mysqli->error);
}

echo "<h4>Convenios 
        <button class='btn btn-sm btn-success ms-2' onclick='nuevoConvenio()'>+ Nuevo</button>
      </h4>
      <table class='table table-bordered table-sm'>
      <thead class='table-dark'>
      <tr>
        <th>Nombre</th>
        <th>Descuento</th>
        <th>Editar</th>
        <th>Eliminar</th>
      </tr>
      </thead><tbody>";

while ($row = $convenio->fetch_assoc()) {
  echo "<tr>
    <td>{$row['nombre']}</td>
    <td>{$row['descuento']}</td>
    <td>" . btnEditar("convenio", $row['id']) . "</td>
    <td><button class='btn btn-danger btn-sm' onclick='eliminarConvenio({$row['id']})'><i class='bi bi-trash'></i></button></td>
  </tr>";
}
echo "</tbody></table>";
?>
