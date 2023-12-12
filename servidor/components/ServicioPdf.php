<?php

use Dompdf\Dompdf;

class ServicioPdf {

    public static function generarPdfConvocatoria($convocatoria,$detalle,$listado) {
        $mipdf = new Dompdf();
        $mipdf->getOptions()->setChroot($_SERVER["DOCUMENT_ROOT"]);
        $mipdf ->setpaper("A4", "portrait");
        $mipdf ->loadhtml(self::$plantillaConvocatoria);
        $mipdf->render();
        return $mipdf;
    }

    public static function generarPdfSolicitud() {
        $mipdf = new Dompdf();
        $mipdf->getOptions()->setChroot($_SERVER["DOCUMENT_ROOT"]);
        $mipdf ->setpaper("A4", "portrait");
        $mipdf ->loadhtml(self::$plantillaSolicitud);
        $mipdf->render();
        # Creamos un fichero
        $pdf = $mipdf->output();
        #Guardar el pdf
        $filename = '../../archivos/' . uniqid() . '.pdf';
        file_put_contents($filename, $pdf);
        return $filename;
    }

    private static $plantillaConvocatoria = '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Convocatoria </title>
            <style>
            header { position: fixed; top: 0px; left: 0px; right: 0px;  }
            footer { position: fixed; bottom: 0px; left: 0px; right: 0px;  }
            .contenido {
                margin-top:100px;
                margin-bottom: 100px;
                page-break-after: always;
            }
            .cabecera {
                display: flex;
                justify-content: center;
                gap: 1em;
            }
            
            .informacion-proyecto {
                max-width: 40%;
            }
            
            .icono-erasmus img {
                width: 16em;
            }
            
            .italic {
                font-style: italic;
            }
            
            h3 {
                font-weight: bold;
                border-bottom: 2px solid black;
            }
            </style>
        </head>
        <body>
            <header>
                <div class="cabecera">
                    <div class="icono-erasmus">
                        <img src="../../interfaz/images/erasmus_icon.jpg" />
                    </div>

                    <div class="informacion-proyecto">
                        <p class="descripcion">Curso Académico: 2023-2024</p>
                        <p class="titulo"><span id="codigoProyecto"></span> <br> "<span class="italic" id="proyectoNombre"></span>"</p>
                    </div>
                </div>
            </header>
            <footer><img src="../../interfaz/images/footerPdf.jpg" width="595" /></footer>
            
            <div class="contenido">
                <div class="apartado">
                    <h3>1. OBJETO DE LA CONVOCATORIA Y DESTINATARIOS DE LAS AYUDAS</h3>
                    <ul>
                        <li>Se ofertan movilidades de <span id="a1.1.tipoDur"></span> duración para la realización de prácticas en empresas de otros países de la Unión Europea dentro del programa Erasmus+ del IES Las Fuentezuelas.</li>
                        <li>Destinatarios: alumnado de <span id="a1.2.des"></span> matriculado en el IES Las Fuentezuelas en el curso 2021-2022.</li>
                    </ul>   
                </div>

                <div class="apartado">
                    <h3>2. CONDICIONES GENERALES</h3>
                    <ul>
                        <li>Movilidades: <span id="a2.movilidades"></span> movilidades de <span id="a2.duracion"></span> días.</li>
                        <li>Países de destino: Portugal y Grecia.</li>
                        <li>Requisitos para la selección:</li>
                        <ul>
                            <li>Estar matriculado en 2º de Ciclos Formativos de Grado Medio en el IES Las Fuentezuelas en el curso 2023-2024.</li>
                            <li>Antes de la movilidad, los participantes seleccionados deberán tener superados los módulos profesionales del ciclo formativo a excepción del módulo de Formación en Centros de Trabajo.</li>
                            <li>Será necesario un informe de idoneidad positivo realizado por el equipo docente del grupo en el que se encuentra matriculado el candidato.</li>
                        </ul>
                        <li>El programa incluye los siguientes conceptos: ayuda individual y ayuda de transporte.</li>
                    </ul>   
                </div>

                <div class="apartado">
                    <h3>3. CRITERIOS DE SELECCIÓN O BAREMO DE LAS CANDIDATURAS</h3>
                    <ul>
                        <li>Los criterios de baremación de las candidaturas estarán supeditados al cumplimiento de las condiciones generales. Para la selección del alumnado se utilizarán los siguientes criterios:</li>
                        <ul>
                            <li>entrada por item dentro de d.items</li>
                        </ul>
                    </ul>   
                </div>
                
                <div class="apartado">
                    <h3>4. SELECCIÓN DE LOS CANDIDATOS</h3>
                    <ul>
                        <li>La puntuación final obtenida por cada candidato estará compuesta por la suma de las puntuaciones de los apartados nota media, nivel de idiomas, informe del equipo docente y entrevista personal. Se seleccionarán los candidatos que hayan obtenido una mayor puntuación.</li>
                        <li>En el caso de que dos o más solicitantes obtengan la misma puntuación total, tendrá prioridad la mejor nota media del expediente académico. Si, aún así, se mantiene el empate, tendrá prioridad quien tenga una mayor puntuación en el apartado nivel de idiomas. Por último, se desempatará teniendo en cuenta la mayor puntuación obtenida en la suma de los apartados informe de idoneidad y entrevista personal.</li>
                        <li>Al finalizar el proceso de selección se publicará el listado provisional de candidatos con las puntuaciones otorgadas en cada apartado</li>
                        <li>Los candidatos dispondrán de un plazo de 2 días lectivos, a partir de la publicación del listado provisional de candidatos, para presentar alegaciones a dicho listado.</li>
                        <li>Una vez resueltas las alegaciones presentadas, se publicará el listado definitivo con los participantes seleccionados y reservas ordenados por puntuación.</li>
                    </ul>   
                </div>

                <div class="apartado">
                    <h3>5. CALENDARIO DE ACTUACIONES</h3>
                    <ul>
                        <li>El 15 de noviembre de 2023 se realizará una reunión informativa sobre el programa Erasmus+ con el alumnado matriculado en 2o de ciclos formativos de grado medio. Hora de la reunión: 11:00 h. Lugar: Aula SUM.</li>
                        <li>Del 15 de noviembre al 24 de noviembre de 2023 el alumnado presentará la solicitud de inscripción en la Secretaría del IES Las Fuentezuelas. Se adjuntará a la misma el certificado de idiomas, en caso de poseerlo, la fotocopia del DNI del solicitante y de los padres si es menor de edad y un documento de autorización de representación si es
                            menor de edad.</li>
                        <li>Las pruebas de selección se celebrarán del <span id="a5.inicioPruebas"></span> al <span  id="a5.finPruebas"></span>. Se notificará a los participantes el día, hora y lugar de celebración de
                            las pruebas en el tablón de anuncios del centro educativo.</li>
                        <li>Los candidatos dispondrán de un plazo de 2 días lectivos, a partir de la publicación del listado provisional de candidatos, para presentar alegaciones a dicho listado.</li>
                        <li>Una vez resueltas las alegaciones presentadas, se publicará el listado definitivo con los participantes seleccionados y reservas ordenados por puntuación.</li>
                    </ul>   
                </div>
                <div id="listadoContenedor" class="apartado">
                    <h3>LISTADO <span id="tipoListado"></span></h3>
                    <p class="descripcion">
                        A continuación se muestra el listado del total de alumnos que han solicitado beca en esta convocatoria. 
                        Se excluye automáticamente los candidatos que no superaron el corte mínimo en alguno de los baremables solicitados.
                    </p>
                    <p class="descripcion">
                        Sobre la línea divisoria se encuentran aquellos alumnos becados a razon del número de movilidaddes ofrecidas en esta convocatoria ordenado de manera descendente por su puntuación final.
                    </p>
                    <p class="descripcion"> DNI - puntuación</p>
                    <ul class="becados" id="listadoBecados"></ul>
                    <ul id="listadoNoBecados"></ul>
                </div>
            </div>

        </body>
        </html>
    ';

    private static $plantillaSolicitud = '<html><body><h1>Holi</h1></body></html>';
}
?>