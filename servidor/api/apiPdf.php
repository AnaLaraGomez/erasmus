<?php 
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['convocatoriaId'])) {
    $convocatoriaId = $_GET['convocatoriaId'];

    if(empty($convocatoriaId)) {
        return;
    }

    $convocatoria = ConvocatoriaRepository::obtenerConvocatoriaPorId($convocatoriaId);
    $detalle = ConvocatoriaRepository::obtenerConvocatoriaDetalle($convocatoriaId);
    $pdf = ServicioPdf::generarPdfConvocatoria($convocatoria,$detalle);
    $pdf->stream($filename,['Attachment'=>false]);

} elseif($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['convocatoriaListadoId'])) {
    $convocatoriaId = $_GET['convocatoriaListadoId'];
    if(empty($convocatoriaId)) {
        return;
    }
    $convocatoria = ConvocatoriaRepository::obtenerConvocatoriaPorId($convocatoriaId);
    $listado = BaremacionRepository::listadoNotas($convocatoriaId);
    $pdf = ServicioPdf::generarPdfListaConvocatoria($convocatoria,$listado);
    $pdf->stream($filename,['Attachment'=>false]);
}

?>