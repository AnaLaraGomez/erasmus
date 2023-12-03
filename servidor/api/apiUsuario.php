<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener toda la informacion del usuario actual (con la session abierta)
    // Util para pintar el header, saber si es etc.
    $user = obtenerUsuarioDeSessionORedireccionarLogin();
    echo $user->to_json();
}

elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Eliminar usuario del sistema. 
    // Se permite ya que el usuario debe de tener la posibilidad de eliminar sus datos del sistema
    // si no quiere seguir formando parte de la plataforma 
    $respuesta = array();
    $user = obtenerUsuarioDeSessionORedireccionarLogin();
    UsuarioRepository::eliminarUsuarioPorId($user->get_id());
    $respuesta ['status_code'] = 202; // Accepted
    $respuesta ['succeed'] = true;
    echo json_encode($respuesta);
}

function obtenerUsuarioDeSessionORedireccionarLogin() {
    if(!Session::estaLogueado()) {
        header('Location: ' . Config::baseUrl() . '/interfaz/acceso/login.html');
        exit;
    }

    return Session::leerDatosSession();
}

?>