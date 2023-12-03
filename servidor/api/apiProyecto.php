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
    $proyectos = ProyectoRepository::obtenerProyectos(); // [obj1, obj2..]
    $jsonRespObjects=array();
    foreach ($proyectos as $proyecto) {
        $jsonRespObjects[] =  $proyecto->to_json();
    }
    // $jsonRespObjects => ['{...}', '{...}']
    // implode(',', $jsonRespObjects) => '{...},{...}'
    $jsonRespString = '[' . implode(',', $jsonRespObjects) . ']';
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