<?php 
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Si estamos pidiendo una convocatoria en concreto para pintar el detalle
    if(isset($_GET['id'])) {
        $convocatoriaId = $_GET['id'];
        $detalle = ConvocatoriaRepository::obtenerConvocatoriaDetalle($convocatoriaId);
        $detalle['lista'] = BaremacionRepository::listadoNotas($convocatoriaId);
  
        if(Session::estaLogueado()) {
            $user = Session::leerDatosSession();
            $detalle['entregados'] = ConvocatoriaRepository::obtenerConvocatoriaBaremablesAlumno($convocatoriaId, $user->get_id());
        }
        echo json_encode($detalle);
        return;
    }

    // devolvemos un objetco que tiene dos atributos, convocatorias y tusConvocatorias.
    $respuesta = array();
    $tusConvocatorias = array();

    if(Session::estaLogueado()) {
        //$tusConvocatorias  se rellenana aqui 
    }

    // Usuario anonimo. Recibe todas las Convocatorias abiertas
    $convocatorias = ConvocatoriaRepository::obtenerConvocatoriasAbiertas();
    //$convocatoriasAEnviar = array_filter($convocatorias, function ($convocatoriaActual){
    //    return !in_array($convocatoriaActual, $tusConvocatorias);
    //});

    $respuesta['convocatorias'] = JsonEncodeHelper::encodeArray($convocatorias);
    $respuesta['tusConvocatorias'] = JsonEncodeHelper::encodeArray($tusConvocatorias);
    echo json_encode($respuesta);
    return;
} 
?>