<?php

use Dompdf\Dompdf;

class ServicioPdf {

    public static function generarPdfConvocatoria($convocatoria,$detalle) {
        $mipdf = new Dompdf();
        $mipdf->getOptions()->setChroot($_SERVER["DOCUMENT_ROOT"]);
        $mipdf ->setpaper("A4", "portrait");
        $mipdf ->loadhtml(self::plantillaConvocatoria($convocatoria,$detalle));
        $mipdf->render();
        return $mipdf;
    }

    public static function generarPdfSolicitud($candidato, $usuario, $convocatoria) {
        $mipdf = new Dompdf();
        $mipdf->getOptions()->setChroot($_SERVER["DOCUMENT_ROOT"]);
        $mipdf ->setpaper("A4", "portrait");
        $mipdf ->loadhtml(self::plantillaSolicitud($candidato, $usuario, $convocatoria));
        $mipdf->render();
        # Creamos un fichero
        $pdf = $mipdf->output();
        #Guardar el pdf
        $filename = '../../archivos/' . uniqid() . '.pdf';
        file_put_contents($filename, $pdf);
        return $filename;
    }

    public static function generarPdfListaConvocatoria($convocatoria,$listado) {
        $mipdf = new Dompdf();
        $mipdf->getOptions()->setChroot($_SERVER["DOCUMENT_ROOT"]);
        $mipdf ->setpaper("A4", "portrait");
        $mipdf ->loadhtml(self::plantillaListaConvocatoria($convocatoria,$listado));
        $mipdf->render();
        return $mipdf;
    }

    private static function plantillaListaConvocatoria($convocatoria,$listado) {
        $noExcluidos = array_filter($listado, function($actual){
            return $actual['excluido'] == 0;
        });

        $admitidos = array_slice($noExcluidos, 0, $convocatoria->get_movilidades());
        $reserva = array_slice($noExcluidos,$convocatoria->get_movilidades());
        
        $excluidos =  array_filter($listado, function($actual){
            return $actual['excluido'] == 1;
        });

        return '
        <html>
            <head>
                <style>
                .nota {
                    font-size: smaller;
                }
                </style>
            </head>
            <body>
                <h1>Resolución de solicitudes para la convocatoria ' . $convocatoria->get_nombre() . ' '. $convocatoria->get_proyectoNombre() . ' </h1>

                <p class="nota">
                    A continuación se muestra el listado del total de alumnos que han solicitado beca en esta convocatoria.
                </p>
                                
                <h2>Alumnos admitidos</h2>
                <p class="nota">
                   En esta sección se encuentran aquellos alumnos becados a razon del número de movilidaddes ofrecidas en esta convocatoria ordenado de manera descendente por su puntuación final.
                </p>
                '. self::pintarListado($admitidos).'
                
                <h2>Alumnos reserva</h2>
                <p class="nota">
                    En esta sección se encuentran aquellos alumnos que han quedado que no han podido optar a una de las movilidades ofertadas.
                </p>
                '. self::pintarListado($reserva).'

                <h2>Alumnos excluidos</h2>
                <p class="nota">
                    Se excluye automáticamente los candidatos que no superaron el corte mínimo en alguno de los baremables solicitados.
                </p>
                '. self::pintarListado($excluidos).'
            </body>
        </html>';
    }

    private static function plantillaConvocatoria($convocatoria,$detalle){
    return '
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Convocatoria </title>
            <style>
            @page{
                margin-top: 220px; /* create space for header */
                margin-bottom: 150px; /* create space for footer */
            }
            header { position: fixed; top: -150px; left: 80px; right: 0px;  }
            footer { position: fixed; bottom: -50px; left: 0px; right: 0px;  }
            header .page-number:after { content: counter(page); }
            table, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            .cabecera-grande {
                text-align: center;
            }
            .cabecera-grande p {
                font-weight: bold;
                color: rgb(8, 32, 92);
            }
            .sin-borde {
                border: unset;
            }
            
            .titulo {
                font-weight: bold;
            }
            .cabecera {
                margin-top: 16px;
                margin-left: 100px;
            }
            .apartado {
                page-break-inside: avoid;
            }
            .informacion-proyecto {
                max-width: 240px;
            }
            .informacion-proyecto p {
                margin: 0;
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
                <table>
                    <tr>
                        <td rowspan="4">
                            <img src="../../interfaz/images/logo.jpg" height="" width=""/>
                        </td>
                        <td>IES LAS FUENTEZUELAS</td>
                        <td>Fecha: 14-12-23</td>
                        <td>
                            <img src="../../interfaz/images/IESCA.jpg" height="40" width="100"/>
                        </td>
                    </tr>

                    <tr>
                        <td rowspan="3" colspan="2">INFORMACIÓN SOBRE EL PROGRAMA ERASMUS+</td>
                        <td>DC850601</td>
                    </tr>

                    <tr>
                        <td>Versión 0</td>
                    </tr>

                    <tr>
                        <td>Página <span class="page-number"></span></td>
                    </tr>
                </table>
            </header>
            <footer><img src="../../interfaz/images/footerPdf.jpg" width="595" /></footer>
           
            <table class="cabecera-grande">
                <tr>
                    <td>
                        <img src="../../interfaz/images/erasmus_icono_grande.jpg" width="250" />
                        <p>BASES DE LA CONVOCATORIA PARA LA SELECCIÓN DE ALUMNADO DE CICLOS FORMATIVOS DE GRADO MEDIO EN EL PROGRAMA DE MOVILIDAD DE PRÁCTICAS ERASMUS+ DEL IES LAS FUENTEZUELAS</p>
                    </td>
                </tr>
            </table>

            <table class="cabecera sin-borde">
                <tr>
                    <td class="icono-erasmus sin-borde">
                        <img src="../../interfaz/images/erasmus_icon.jpg" />
                    </td>

                    <td class="informacion-proyecto sin-borde">
                        <p class="descripcion">Curso Académico: 2023-2024</p>
                        <p class="titulo">'. $convocatoria->get_proyectoNombre() . '<br> "<span class="italic" id="proyectoNombre">'.$convocatoria->get_nombre() . '</span>"</p>
                    </td>
                </tr>
            </table>

            <div class="contenido">
                <div class="apartado">
                    <h3>1. OBJETO DE LA CONVOCATORIA Y DESTINATARIOS DE LAS AYUDAS</h3>
                    <ul>
                        <li>Se ofertan movilidades de <span id="a1.1.tipoDur">'. $convocatoria->get_movilidades() . '</span> duración para la realización de prácticas en empresas de otros países de la Unión Europea dentro del programa Erasmus+ del IES Las Fuentezuelas.</li>
                        <li>Destinatarios: alumnado de <span id="a1.2.des">'. self::destinatariosComoString($detalle) . '</span> matriculado en el IES Las Fuentezuelas en el curso 2021-2022.</li>
                    </ul>   
                </div>

                <div class="apartado">
                    <h3>2. CONDICIONES GENERALES</h3>
                    <ul>
                        <li>Movilidades: <span id="a2.movilidades">'. $convocatoria->get_movilidades() . '</span> movilidades de <span id="a2.duracion">'. ($convocatoria->get_largaDuracion() == 1 ? '90' : '60') . '</span> días.</li>
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
                        ' . self::entradaPorItems($detalle) . '
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
                        <li>Del '. $convocatoria->get_fechaInicioSolicitudes() . ' al '. $convocatoria->get_fechaFinSolicitudes() . ' el alumnado presentará la solicitud de inscripción en la Secretaría del IES Las Fuentezuelas. Se adjuntará a la misma el certificado de idiomas, en caso de poseerlo, la fotocopia del DNI del solicitante y de los padres si es menor de edad y un documento de autorización de representación si es
                            menor de edad.</li>
                        <li>Las pruebas de selección se celebrarán del <span id="a5.inicioPruebas">'. $convocatoria->get_fechaInicioPruebas() . '</span> al <span  id="a5.finPruebas"> '. $convocatoria->get_fechaFinPruebas() . '</span>. Se notificará a los participantes el día, hora y lugar de celebración de
                            las pruebas en el tablón de anuncios del centro educativo.</li>
                        <li>Los candidatos dispondrán de un plazo de 2 días lectivos, a partir de la publicación del listado provisional de candidatos, para presentar alegaciones a dicho listado.</li>
                        <li>Una vez resueltas las alegaciones presentadas, se publicará el listado definitivo con los participantes seleccionados y reservas ordenados por puntuación.</li>
                    </ul>   
                </div>
            </div>

        </body>
        </html>
        ';
    }

    private static function plantillaSolicitud($candidato, $usuario, $convocatoria) {
        return 
        '<html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Convocatoria </title>
            <style>
            @page{
                margin-top: 220px; /* create space for header */
                margin-bottom: 150px; /* create space for footer */
            }
            header { position: fixed; top: -150px; left: 80px; right: 0px;  }
            footer { position: fixed; bottom: -50px; left: 0px; right: 0px;  }
            header .page-number:after { content: counter(page); }
            table, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            .titulo-principal {
                text-align:center;
                font-weight:bold;
            }
            .titulo {
                margin-top:20px;
                text-align:center;
            }
            .parrafo {
                text-align: justify;
                text-justify: inter-word;
                text-indent: 40px;
            }
            .nota {
                margin-top:80px;
                font-size: smaller;
            }
            </style>
        </head>
        <body>
            <header>
            <table>
                <tr>
                    <td rowspan="4">
                        <img src="../../interfaz/images/logo.jpg" height="" width=""/>
                    </td>
                    <td>IES LAS FUENTEZUELAS</td>
                    <td>Fecha: 14-12-23</td>
                    <td>
                        <img src="../../interfaz/images/IESCA.jpg" height="40" width="100"/>
                    </td>
                </tr>

                <tr>
                    <td rowspan="3" colspan="2">INSTANCIA A LA DIRECCIÓN</td>
                    <td>MD740112RG</td>
                </tr>

                <tr>
                    <td>Versión 0</td>
                </tr>

                <tr>
                    <td>Página <span class="page-number"></span></td>
                </tr>
            </table>
            </header>
            <footer></footer>

            <p class="titulo-principal">SOLICITUD DE INSCRIPCIÓN EN EL PROYECTO ERASMUS+</p>
            <table>
                <tr>
                    <td style="padding:12px">
                        El/La solicitante, D./Da ' . $candidato->get_nombre() . ' ' .$candidato->get_apellidos() .  ', con DNI ' .$usuario->get_dni() .  ', domiciliado en ' .$candidato->get_domicilio() .  ' , <br>
                        teléfono de contacto ' .$candidato->get_telefono() .  ' <br>
                        ,correo electrónico ' .$candidato->get_correo() .  ' 
                        ' . (self::esMayorDeEdad($candidato)? '' : 'En caso de ser menor de edad, <br>
                        D.' . $candidato->get_tutorNombre() . ' '. $candidato->get_tutorApellidos() .', representante legal, con DNI ' . $candidato->get_tutorDni() . ', domiciliado en ' . $candidato->get_tutorDomicilio() . ' <br>
                        y teléfono de contacto ' .$candidato->get_tutorTelefono() .  '.') . '
                    </td>
                </tr>
            </table>

            <p class="titulo">EXPONE/N:</p>
            <p class="parrafo">
                Que está matriculado/a en el IES Las Fuentezuelas en el ciclo formativo de
                Grado Medio de' .$candidato->get_curso() .  ', que son ciertos los datos que figuran en esta instancia, que cumple los requisitos para obtener la condición de beneficiario establecidos en las bases de la convocatoria del programa de movilidad de prácticas Erasmus+ del IES Las Fuentezuelas del curso académico 2021/22 y que la documentación presentada es copia fiel del original.
            </p>
            <p class="parrafo">Por lo expuesto,</p>

            <p class="titulo">SOLICITA/N:</p>
            <p class="parrafo">Participar en la selección de alumnado de ciclos formativos de Grado Medio en el
            programa de movilidad de prácticas Erasmus+ del IES Las Fuentezuelas,
            con en el proyecto: ' . $convocatoria->get_proyectoNombre() . ' , y que le sea concedida una ayuda para la realización de prácticas en empresas de otros países de la Unión Europea.
            </p>
            
            <p class="titulo">En Jaén, a ' . (new DateTime())->format('l') . ' de ' . (new DateTime())->format('F') . ' de' . (new DateTime())->format('Y') . '</p>

            <p class="nota">Documentación que se adjunta:<br>
            - Fotocopia del DNI del solicitante y de los padres si es menor de edad.<br>
            - El certificado de idiomas, en caso de poseerlo.<br>
            - El documento de autorización de representación si es menor.
            </p>

        </body>
        </html>
        ';
    }

    private static function destinatariosComoString($detalle) {
        $cadena = array();
        foreach($detalle['destinatarios'] as $destinatario) {
            $cadena[] = $destinatario->codigoGrupo .' ' . $destinatario->destinatarioNombre;
        }
        return implode(', ', $cadena );
    }

    private static function tablaDeBaremoIdiomas($detalle) {
        $tabla = '<table>';
        $tabla .='<tr>';
        foreach($detalle['idiomas']as $idioma) {
            $tabla .='<th>' . $idioma->idioma . '</th>' ;
        }
        $tabla .='</tr>';
        $tabla .='<tr>';
        foreach($detalle['idiomas']as $idioma) {
            $tabla .='<td>' . $idioma->puntuacion . '</td>' ;
        }
        $tabla .='</tr>';

        $tabla .='</table>';
        return $tabla;
    }

    private static function entradaPorItems($detalle) {
        $apartado = '<ol type="A">';
        foreach($detalle['items']as $item) {
            $apartado .='<li style="font-weight: bold;">'. $item->itemNombre . '. Máximo ' .$item->notaMax .'</li>';
            if($item->itemNombre == 'Certificado Idiomas' ) {
                $apartado .= self::tablaDeBaremoIdiomas($detalle);
            }
        }
        $apartado .= '</ol>';

        return $apartado;
    }

    private static function esMayorDeEdad($candidato) {
        $fechaNacimientoComoEntero = (new DateTime($candidato->get_fechaNac()))->getTimestamp();
        $fechaHace18anos = new DateTime();
        $fechaHace18anos = $fechaHace18anos->modify('-18 year');
        $fechaHace18anos = $fechaHace18anos->getTimestamp();
        return  $fechaHace18anos >= $fechaNacimientoComoEntero;
    }

    private static function pintarListado($listado) {
        $lista = '<ul>';
        foreach($listado as $alumno) {
            $lista .= '<li> '. Utilidades::ocultarDni($alumno['dni']) . ' - ' .$alumno['puntuacion'] . ' </li>';
        }
        $lista .= '</ul>';
        return $lista ;
    }

}


?>