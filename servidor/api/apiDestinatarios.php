<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $destinatarios =  DestinatarioRepository::obtenerDestinatarios();
    echo JsonEncodeHelper::encodeArray($destinatarios);
    return;
}

?>