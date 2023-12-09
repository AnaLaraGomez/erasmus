<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'
         && !empty($_GET['convocatoriaId']) 
         && !empty($_GET['candidatoId'])) {
    // Get para Evaluar Candidato
    $resultado = array();

    if(!Session::estaLogueado()) {
        $resultado['status_code'] = 401; //Unauthorized
        echo json_encode($resultado);
        return;
    } else {
        $user = Session::leerDatosSession();
        if($user->get_admin()!= 1) {
            $resultado['status_code'] = 403; //Forbidden
            echo json_encode($resultado);
            return;
        }
    }

    $resultado['usuario'] = UsuarioRepository::obtenerUsuarioPorId($_GET['candidatoId'])->to_json();
    $resultado['candidato'] = CandidatoRepository::obtenerCandidatoPorId($_GET['candidatoId'])->to_json();
    $resultado['baremables'] = ConvocatoriaRepository::obtenerConvocatoriaBaremablesTotales($_GET['convocatoriaId'], $_GET['candidatoId']);
    $resultado['status_code'] = 200;
    echo json_encode($resultado);
    return;

}else if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get para Pintar todas las convocatorias y candidatos en tablas.
    $resultado = array();

    if(!Session::estaLogueado()) {
        $resultado['status_code'] = 401; //Unauthorized
        echo json_encode($resultado);
        return;
    } else {
        $user = Session::leerDatosSession();
        if($user->get_admin()!= 1) {
            $resultado['status_code'] = 403; //Forbidden
            echo json_encode($resultado);
            return;
        }
    }

    $resultado['convocatorias'] = ConvocatoriaRepository::obtenerConvocatoriasPuntuables();
    $resultado['status_code'] = 200;
    echo json_encode($resultado);
    return;
}else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Añadir/Modificar nota y/o entrgable para la solicitud de un candidato
    $convocatoriaId = $_POST['convocatoriaId'];
    $candidatoId = $_POST['candidatoId'];
    $itemId = $_POST['itemId'];
    $nota = $_POST['nota'];

    if(!Session::estaLogueado()) {
        $resultado['status_code'] = 401; //Unauthorized
        echo json_encode($resultado);
        return;
    } else {
        $user = Session::leerDatosSession();
        if($user->get_admin()!= 1) {
            $resultado['status_code'] = 403; //Forbidden
            echo json_encode($resultado);
            return;
        }
    }
    $erroresValidacion = array();
    $erroresValidacion = Validator::campoRequerido($convocatoriaId, 'convocatoriaId', "Es obligatorio indicar la convocatoria", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($candidatoId, 'candidatoId', "Es obligatorio indicar el candidato", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($itemId, 'itemId', "Es obligatorio indicar el item", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($nota, 'nota', "Es obligatorio indicar un valor en la nota", $erroresValidacion);
   
    if(!empty($erroresValidacion)) {
        $resultado['status_code'] = 400; //Bad request
        $resultado['errores'] = $erroresValidacion;
        echo json_encode($resultado);
        return;
    }

    if($_FILES['fichero'] && !empty($_FILES['fichero']['name'])) {
        $tipoFichero = $_FILES['fichero']['type'];
        $extension = explode('/', $tipoFichero)[1];
        $nombreAleatorioDelFichero = uniqid() . ".$extension";
        move_uploaded_file($_FILES['fichero']['tmp_name'],"../../archivos/$nombreAleatorioDelFichero");
        $url = Config::baseUrl() . "/archivos/$nombreAleatorioDelFichero";
        BaremacionRepository::evaluarBaremableNotaYFichero($convocatoriaId, $candidatoId, $itemId, $url , $nota);
    } else {
        BaremacionRepository::evaluarBaremableSoloNota($convocatoriaId, $candidatoId, $itemId, $nota);
    }

    $resultado['status_code'] = 200; // OK
    echo json_encode($resultado);
    return;
}

?>