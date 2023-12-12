<?php

use PHPMailer\PHPMailer\PHPMailer;
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/vendor/autoload.php');

class ServicioEmail {

    private static $plantillaBienvenida = '<!DOCTYPE html>
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
            <p>Atentamente, la coordinación del proyecto Erasmus del IES Las Fuentezuelas</p><br>
        
        </body>
    </html>';

    private static $plantillaSolicitud = '<!DOCTYPE html>
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
            <p>Le informamos que se ha solicitado satisfactoriamente su beca a través de la plataforma becas Erasmus IES Las Fuentezuelas.</p><br>
            <p>Por favor, quede atento de las modificaciones en el estado de su beca y de las fechas relacionadas con esta. </p>
            <p>En este correo podrá encontrar como archivo adjunto la información dada en su solicitud. </p>
            <p>Atentamente, la coordinación del proyecto Erasmus del IES Las Fuentezuelas</p>
        
    </body>
    </html>';
    
    public static function enviarEmailBienvenida($correo, $nombre, $apellidos) {
        $mail = self::generarEmailBase();
        // asunto
        $mail->Subject    = "Registro Erasmus";
        // cuerpo

        $sustitutos = array($nombre.' ',$apellidos);
        $sustituir = array ("{{nombre}}", "{{apellidos}}");
        $mail->AddEmbeddedImage('logo.png','logopng','imagen a descargar');

        $plantilla = str_replace($sustituir, $sustitutos, self::$plantillaBienvenida);

        $mail->MsgHTML($plantilla);
        // adjuntos
        $mail->addAttachment("");
        // destinatario
        $mail->AddAddress($correo, $nombre);
        // enviar
        var_dump($mail->Send());
        $resul = $mail->Send();
        return $resul;
    }

    public static function enviarEmailSolicitudConvocatoria($correo, $nombre, $apellidos) {
        $mail = self::generarEmailBase();
        // asunto
        $mail->Subject    = "Solicitud Erasmus";
        // cuerpo

        $sustitutos = array($nombre.' ',$apellidos);
        $sustituir = array ("{{nombre}}", "{{apellidos}}");
        $mail->AddEmbeddedImage('logo.png','logopng','imagen a descargar');

        $plantilla = str_replace($sustituir, $sustitutos, self::$plantillaSolicitud);

        $mail->MsgHTML($plantilla);
        // adjuntos
        // Fabricar PDF
        $rutaDelPdf = ServicioPdf::generarPdfSolicitud();
        $mail->addAttachment($rutaDelPdf);
        // destinatario
        $mail->AddAddress($correo, $nombre);
        // enviar
        $resul = $mail->Send();
        return $resul;
    }

    private static function generarEmailBase () {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // cambiar a 0 para no ver mensajes de error
        $mail->SMTPDebug  = 0;                          
        $mail->SMTPAuth   = true;
        $mail->Host       = "smtp.gmail.com";    
       // $mail->Host = gethostbyname("smtp.gmail.com");
        $mail->Port       = 587;
        $mail->SMTPSecure = "tls";                 

       // $mail->Port = 465;                    //SMTP port
        //$mail->SMTPSecure = "ssl";              
        // introducir usuario de google
        $mail->Username   = "alargom549@g.educaand.es"; 
        // introducir clave
        $mail->Password   = "njqs mity foid kjyj";       
        $mail->SetFrom('alargom549@g.educaand.es', 'IES LAS FUENTEZUELAS');
        return $mail;
    }

}
?>