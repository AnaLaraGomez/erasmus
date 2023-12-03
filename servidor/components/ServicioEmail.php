<?php

use PHPMailer\PHPMailer\PHPMailer;
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/vendor/autoload.php');

class ServicioEmail {

    private static function plantillaBienvenida() { 
    return '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            img {
                width: 300px;
                height: auto;
                border: 2px solid #ddd;
                border-radius: 8px;
                display: block; 
                margin: 20px auto;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <img src="cid:logopng">
        <p>Estimado/a {{nombre}}{{apellidos}} : </p><br>
        <p>Le informamos que se ha registrado satisfactoriamente en nuestra plataforma de gestión de becas Erasmus.</p><br>
        <p>Attentamente, la coordinación del proyecto Erasmus del IES Las Fuentezuelas</p>
    
    </body>
    </html>';
}
    
    public static function enviarEmailBienvenida($correo, $nombre, $apellidos) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // cambiar a 0 para no ver mensajes de error
        $mail->SMTPDebug  = 0;                          
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";                 
        $mail->Host       = "smtp.gmail.com";    
        $mail->Port       = 587;                 
        // introducir usuario de google
        $mail->Username   = "alargom549@g.educaand.es"; 
        // introducir clave
        $mail->Password   = "njqs mity foid kjyj";       
        $mail->SetFrom('alargom549@g.educaand.es', 'IES LAS FUENTEZUELAS');
        // asunto
        $mail->Subject    = "Registro Erasmus";
        // cuerpo
    
        $sustitutos = array($nombre.' ',$apellidos);
        $sustituir = array ("{{nombre}}", "{{apellidos}}");
        $mail->AddEmbeddedImage('logo.png','logopng','imagen a descargar');

        $plantilla = str_replace($sustituir, $sustitutos, ServicioEmail::plantillaBienvenida());
        $mail->MsgHTML($plantilla);
        // adjuntos
        $mail->addAttachment("");
        // destinatario
        $mail->AddAddress($correo, $nombre);
        // enviar
        var_dump($mail->Send());
        $resul = $mail->Send();
        if(!$resul) {
          echo "Error" . $mail->ErrorInfo;
        } else {
          echo "Enviado";
        }
    }

    public static function enviarEmailRecuperacionPassword() {

    }
}
?>