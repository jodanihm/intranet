<?php 
$mysqli = new mysqli("localhost", "plantifl_user", "Plantiflex.2023", "plantifl_plantiflex");
//$mysqli = new mysqli("localhost", "root", "", "plantifl_plantiflex");
if ($mysqli->connect_errno) {
   die("error de conexión: " . $mysqli->connect_error);
}

$accion = $_POST['accion'];
/*
if ($accion == "validar_credenciales") {

    if (isset($_POST['user']) && isset($_POST['pass']) ){  

        $var1=$_POST['user'];
        $var2=$_POST['pass'];
        $resultado=$mysqli->query("SELECT * FROM user WHERE user_name = '$var1' AND pass = '$var2'");
        $row = $resultado->fetch_array();
        
        //echo $row[2];
       

        if (($_POST['user']==$row[2])&&($_POST['pass']==$row[3])&&($_POST['pass']!=""))
        {
            session_start();
            $_SESSION['rut']=$row[0];
            $_SESSION['nombre']=$row[1];
            $_SESSION['user_name']=$row[2];
            $_SESSION['tipo']=$row[4];
            $_SESSION['mail']=$row[5];
            $_SESSION['ciudad']=$row[6];

            if ($_SESSION['tipo'] == 0) {
                $url = "estado_actual.html";
            }
            if ($_SESSION['tipo'] == 1) {
                $url = "ingreso.html";
            }
            if ($_SESSION['tipo'] == 2) {
                $url = "impresion.html";
            }
            if ($_SESSION['tipo'] == 3) {
                $url = "pago.html";
            }

            $var = "datos_correctos";
            //$var = "datos_correctos";
        }
        else{
            $var = "datos_incorrectos";
        }

    } else {

    $var = "faltan_datos";

    }; 
    $array = array($var, $url);
    echo json_encode($array);
};
*/
session_start();
if ($accion == "validar_credenciales") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rut = $_POST['user'];
        $password = $_POST['pass'];

        // Preparar la consulta para obtener el hash de la contraseña almacenada
        $stmt = $mysqli->prepare("SELECT rut, name, user_name, pass, type, mail, ciudad FROM user WHERE user_name = ?");
        if ($stmt) {
            $stmt->bind_param("s", $rut);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($rut, $nombre,$user_name, $hashed_password, $tipo, $mail, $sucursal);
                $stmt->fetch();

                // Agregar mensajes de depuración
                //echo "Password ingresada: " . $password . "<br>";
                //echo "Password hasheada almacenada: " . $hashed_password . "<br>";

                // Verificar la contraseña
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['rut'] = $rut;
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['user_name']=$user_name;
                    $_SESSION['tipo'] = $tipo;
                    $_SESSION['mail']=$mail;
                    $_SESSION['ciudad'] = $sucursal;

                    if ($_SESSION['tipo'] == 0) {
                        $url = "impresion.html";
                    }
                    if ($_SESSION['tipo'] == 1) {
                        $url = "ingreso.html";
                    }
                    if ($_SESSION['tipo'] == 2) {
                        $url = "impresion.html";
                    }
                    if ($_SESSION['tipo'] == 3) {
                        $url = "pago.html";
                    }
                    
                    // Generar un token de sesión
                    $session_token = bin2hex(random_bytes(32));
                    setcookie("session_token", $session_token, time() + (86400 * 30), "/"); // 30 días

                    // Guardar el token en la base de datos
                    $update_stmt = $mysqli->prepare("UPDATE user SET session_token = ? WHERE rut = ?");
                    if ($update_stmt) {
                        $update_stmt->bind_param("ss", $session_token, $rut);
                        $update_stmt->execute();

                        // Verificar si la actualización fue exitosa
                        if ($update_stmt->affected_rows > 0) {
                            echo json_encode(['status' => 'success', 'message' => 'Login exitoso.', 'init' => $url]);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el token de sesión.']);
                        }

                        $update_stmt->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => "Error al preparar la consulta de actualización: " . $mysqli->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error al preparar la consulta: " . $mysqli->error]);
        }

        $mysqli->close();
    }
}


  ?>