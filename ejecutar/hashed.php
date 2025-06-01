<?php
$mysqli = new mysqli("localhost", "plantifl_user", "Plantiflex.2023", "plantifl_plantiflex");
//$mysqli = new mysqli("localhost", "root", "", "plantiflex");
if ($mysqli->connect_errno) {
   die("error de conexión: " . $mysqli->connect_error);
}
// Contraseña que deseas hashear
// Obtener todas las contraseñas
$result = $mysqli->query("SELECT rut, pass FROM user");

while ($row = $result->fetch_assoc()) {
    echo $row['rut'];
    echo "<br>";
    $hashedPassword = password_hash($row['pass'], PASSWORD_DEFAULT);
    echo $hashedPassword;
    echo "<br>";
    $rut = $row['rut'];
    $mysqli->query("UPDATE user SET pass = '$hashedPassword' WHERE rut = '$rut' ");
}

echo "Todas las contraseñas han sido hasheadas correctamente.";

$mysqli->close();
?>