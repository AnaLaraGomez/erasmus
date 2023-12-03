<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user = obtenerUsuarioDeSessionORedireccionarLogin();
    
    if($user->get_admin() != 1) {
        $erroresValidacion['status_code'] = 403; //Forbidden
        $erroresValidacion['succeed'] = false;
        echo json_encode($erroresValidacion);
        return;
    }
    $proyectos = ProyectoRepository::obtenerProyectos();
    $jsonRespString = JsonEncodeHelper::encodeArray($proyectos);
    echo $jsonRespString;
    return;

} elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = obtenerUsuarioDeSessionORedireccionarLogin();
    
    if($user->get_admin() != 1) {
        $erroresValidacion['status_code'] = 403; //Forbidden
        $erroresValidacion['succeed'] = false;
        echo json_encode($erroresValidacion);
        return;
    }
    
}

function obtenerUsuarioDeSessionORedireccionarLogin() {
    if(!Session::estaLogueado()) {
        header('Location: ' . Config::baseUrl() . '/interfaz/acceso/login.html');
        exit;
    }

    return Session::leerDatosSession();
}

?>