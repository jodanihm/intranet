<?php
require_once("connect.php");

$tipo = $_POST['tipo'] ?? '';
$id = $_POST['id'] ?? '';

if ($tipo === 'precio') {
  $query = $mysqli->prepare("SELECT * FROM precios WHERE Talla = ?");
  $query->bind_param("s", $id);
  $query->execute();
  $resultado = $query->get_result();
  $dato = $resultado->fetch_assoc();
  ?>

  <form id="form-edicion">
    <input type="hidden" name="tipo" value="precio">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

    <div class="row">
      <div class="col-md-4">
        <label>Talla</label>
        <input type="text" class="form-control" name="Talla" value="<?php echo $dato['Talla']; ?>" readonly>
      </div>
      <div class="col-md-4">
        <label>Imp. Par</label>
        <input type="number" class="form-control" name="imp_par" value="<?php echo $dato['imp_par']; ?>">
      </div>
      <div class="col-md-4">
        <label>Imp. Unit</label>
        <input type="number" class="form-control" name="imp_unit" value="<?php echo $dato['imp_unit']; ?>">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-4">
        <label>Reimp. Par</label>
        <input type="number" class="form-control" name="reimp_par" value="<?php echo $dato['reimp_par']; ?>">
      </div>
      <div class="col-md-4">
        <label>Reimp. Unit</label>
        <input type="number" class="form-control" name="reimp_unit" value="<?php echo $dato['reimp_unit']; ?>">
      </div>
      <div class="col-md-4">
        <label>Pago Imp. Par</label>
        <input type="number" step="0.01" class="form-control" name="pago_imp_par" value="<?php echo $dato['pago_imp_par']; ?>">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-6">
        <label>Pago Imp. Unit</label>
        <input type="number" step="0.01" class="form-control" name="pago_imp_unit" value="<?php echo $dato['pago_imp_unit']; ?>">
      </div>
    </div>
  </form>

<?php
} elseif ($tipo === 'ciudad') {
  $query = $mysqli->prepare("SELECT * FROM ciudad WHERE id = ?");
  $query->bind_param("i", $id);
  $query->execute();
  $dato = $query->get_result()->fetch_assoc();
  ?>

  <form id="form-edicion">
    <input type="hidden" name="tipo" value="ciudad">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" class="form-control" value="<?php echo $dato['nombre']; ?>" readonly>
    </div>
    <div class="mb-3">
      <label>Impuesto Adulto</label>
      <input type="number" step="0.01" class="form-control" name="impuesto_adulto" value="<?php echo $dato['impuesto_adulto']; ?>">
    </div>
    <div class="mb-3">
      <label>Impuesto Infantil</label>
      <input type="number" step="0.01" class="form-control" name="impuesto_infantil" value="<?php echo $dato['impuesto_infantil']; ?>">
    </div>
  </form>

<?php
} elseif ($tipo === 'convenio') {
  $query = $mysqli->prepare("SELECT * FROM convenio WHERE id = ?");
  $query->bind_param("i", $id);
  $query->execute();
  $dato = $query->get_result()->fetch_assoc();
  ?>

  <form id="form-edicion">
    <input type="hidden" name="tipo" value="convenio">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" class="form-control" value="<?php echo $dato['nombre']; ?>" readonly>
    </div>
    <div class="mb-3">
      <label>Descuento</label>
      <input type="number" step="0.01" class="form-control" name="descuento" value="<?php echo $dato['descuento']; ?>">
    </div>
  </form>

<?php
} else {
  echo "<p>Error: Tipo no válido.</p>";
}
?>
