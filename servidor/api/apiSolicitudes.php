<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $convocatoriaId = $_POST['convocatoriaId'];
    $user = obtenerUsuarioDeSessionORedireccionarLogin();

    $response = array();
    try {
        // $key es el id del item
        // $value es el fichero en sí    
        foreach($_FILES as $key => $value) {
            // Procesamos solo los ficheros adjuntos
            if(empty($value['name'])) {
                continue;
            }
            $tipoFichero = $value['type'];
            $extension = explode('/', $tipoFichero)[1];
            $nombreAleatorioDelFichero = $user->get_dni() . '-' .   uniqid() . ".$extension";
            move_uploaded_file($value['tmp_name'],"../../archivos/$nombreAleatorioDelFichero");
            $url = Config::baseUrl() . "/archivos/$nombreAleatorioDelFichero";
            BaremacionRepository::crearActualizarEntregableSolicitudAlumno($convocatoriaId, $user->get_id(), $key, $url);
        }
        $response['status_code'] = 201; // Created
        echo json_encode($response);
        return;
    } catch(Throwable | Error $e) { // Fuente: https://www.php.net/manual/en/class.throwable.php
        $response['status_code'] = 500; // Internal Server Error 
        $response['msg'] = $e->getMessage();
        echo json_encode($response);
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