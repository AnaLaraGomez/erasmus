<?php 
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $convocatoriaId = $_GET['convocatoriaId'];

    if(empty($convocatoriaId)) {
        return;
    }

    $convocatoria = ConvocatoriaRepository::obtenerConvocatoriaPorId($convocatoriaId);
    $detalle = ConvocatoriaRepository::obtenerConvocatoriaDetalle($convocatoriaId);
    $listado = BaremacionRepository::listadoNotas($convocatoriaId);
    $pdf = ServicioPdf::generarPdfConvocatoria($convocatoria,$detalle,$listado);
    $pdf->stream($filename,['Attachment'=>false]);
}

?>