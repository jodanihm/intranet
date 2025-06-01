<?php
require "phpmailer/class.phpmailer.php";
require "phpmailer/class.smtp.php";
require "connect.php";

$destinatario = $_POST['destinatario'];
$asunto = $_POST['asunto'];
$mensaje = $_POST['cuerpo'];

date_default_timezone_set("America/Santiago");

$estado = 'Fallido'; // Por defecto se considera fallido
$fecha_hora = date("Y-m-d H:i:s");

try {
    $mail = new PHPMailer();
    $mail->PluginDir = "phpmailer/";
    $mail->Mailer = "smtp";
    $mail->Host = "mail.plantiflex.cl";
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->Username = "ventas@plantiflex.cl"; 
    $mail->Password = "plantiflex2023";
    $mail->From = "ventas@plantiflex.cl";
    $mail->FromName = "Plantiflex SpA";  
    $mail->Timeout = 15;

    $mail->AddAddress($destinatario);
    $mail->CharSet = 'UTF-8';	
    $mail->IsHTML(true);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Subject = $asunto;
    $mail->Body = $mensaje;

    $exito = $mail->Send();

    if ($exito) {
        $estado = 'Enviado';
    }

} catch (Exception $e) {
    error_log("Error PHPMailer: " . $e->getMessage());
}

// Guardar intento en base de datos (dentro o fuera del try-catch, según preferencia)
$stmt = $mysqli->prepare("INSERT INTO valida_mail (destinatario, asunto, cuerpo, estado, fecha_hora) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error en prepare: " . $mysqli->error);
}

$stmt->bind_param("sssss", $destinatario, $asunto, $mensaje, $estado, $fecha_hora);
$stmt->execute();
$stmt->close();

// Respuesta
echo ($estado === 'Enviado') ? 1 : 0;
?>