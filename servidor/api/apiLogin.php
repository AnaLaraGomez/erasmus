<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario=$_POST['usuario'];
    $password=$_POST['password'];

    if(Session::estaLogueado()) {
        header('Location: ' . Config::baseUrl() . '/interfaz/index.html');
        exit;
    }
   
    $erroresValidacion=array();
    $erroresValidacion=Validator::campoRequerido($usuario, 'usuario', "Es obligatorio identificarse con un usuario", $erroresValidacion);
    $erroresValidacion=Validator::campoRequerido($password, 'password',"Es obligatorio introducir una contraseña", $erroresValidacion);

    if(!empty($erroresValidacion)) {
        $erroresValidacion['status_code'] = 400; 
        echo json_encode($erroresValidacion);
        return;
    }

    $userObj = UsuarioRepository::obtenerUsuarioPorDni($usuario);
    $erroresCredenciales = Validator::credencialesCorrectas($usuario, $password, $userObj);
    if(!empty($erroresCredenciales)) {
        $erroresValidacion['status_code'] = 401; 
        echo json_encode($erroresCredenciales);
        return;
    }
    
    Session::login($userObj);
    header('Location: ' . Config::baseUrl() . '/interfaz/index.html');
    exit;

} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    Session::logout();
    header('Location: ' . Config::baseUrl() . '/interfaz/index.html');
    exit;
}


?>