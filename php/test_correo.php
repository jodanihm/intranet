<?php
require_once("enviar_correo.php");

if (enviarCorreo("dhernandez@gmail.com", "Test", "Esto es un correo de prueba")) {
    echo "Correo enviado";
} else {
    echo "Falló el envío";
}
