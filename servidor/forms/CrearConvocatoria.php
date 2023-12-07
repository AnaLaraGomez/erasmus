<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

$user = Session::leerDatosSession();
if($user->get_admin() != 1) {
    exit;
}

$proyectos = ProyectoRepository::obtenerProyectos();
$destinatarios = DestinatarioRepository::obtenerDestinatarios();
$idiomas = IdiomasRepository::obtenerIdiomas();
$items = ItemsRepository::obtenerItems();

$errores = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $proyectoId = $_POST['proyectoId'];
    $descripcion = $_POST['descripcion'];
    $nombre = $_POST['nombre'];
    $movilidades = $_POST['movilidades'];
    $largaDuracion = $_POST['largaDuracion'];
    $fechaInicioSolicitudes = $_POST['fechaInicioSolicitudes'];
    $fechaFinSolicitudes = $_POST['fechaFinSolicitudes'];
    $fechaInicioPruebas = $_POST['fechaInicioPruebas'];
    $fechaFinPruebas = $_POST['fechaFinPruebas'];
    $fechaListaProvisional = $_POST['fechaListaProvisional'];
    $fechaListaDefinitiva = $_POST['fechaListaDefinitiva'];

    $itemsPosteados = array();
    $idiomasPosteados = array();
    $destinatariosPosteados = array();

    foreach($_POST as $key => $value) { // key: itemMin-1, value: 3
        if(str_contains($key, 'item')) {
            $errores = Validator::campoRequerido($value, $key, 'Campo requerido' ,$errores);
            $partes  = explode('-', $key); // partes[0] = itemMin; partes[1] = 1;
            $nombreAtributo = $partes[0];
            $itemId = $partes[1];
            
            if(!isset($itemsPosteados[$itemId])) {
                $itemsPosteados[$itemId] = array(); // items :[ 1: [] ]
            }
            $itemsPosteados[$itemId][$nombreAtributo] =  $value; // items :[ 1: ['itemMin' : 3]]
        } elseif(str_contains($key, 'idiomaPuntuacion')) {
            $errores = Validator::campoRequerido($value, $key, 'Campo requerido' ,$errores);
            $partes  = explode('-', $key); // partes[0] = idiomaPuntuacion; partes[1] = 1;
            $idiomasId = $partes[1];
            $idiomasPosteados[$idiomasId] =  $value; 
        } elseif(str_contains($key, 'destinatario')) {
            $partes  = explode('-', $key); 
            $destinatarioId = $partes[1];
            $destinatariosPosteados[] = $destinatarioId;
        }
    }

    $errores = Validator::campoRequerido($nombre, 'nombre', "El campo 'nombre' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($movilidades, 'movilidades', "El campo 'movilidades' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($largaDuracion, 'largaDuracion', "El campo 'larga Duracion' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaInicioSolicitudes, 'fechaInicioSolicitudes', "El campo 'fecha Inicio Solicitudes' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaFinSolicitudes, 'fechaFinSolicitudes', "El campo 'fecha Fin Solicitudes' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaInicioPruebas, 'fechaInicioPruebas', "El campo 'fecha Inicio Pruebas' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaFinPruebas, 'fechaFinPruebas', "El campo 'fecha Fin Pruebas' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaListaProvisional, 'fechaListaProvisional', "El campo 'fecha Lista Provisional' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($fechaListaDefinitiva, 'fechaListaDefinitiva', "El campo 'fecha Lista Definitiva' no puede estar vacío", $errores);
    $errores = Validator::campoRequerido($destinatariosPosteados, 'destinatario', "Debe elegir al menos un destinatario", $errores);
    $errores = Validator::validarFechaInicioFin($fechaInicioSolicitudes, $fechaFinSolicitudes, 'fechaSolicitudes', $errores);
    $errores = Validator::validarFechaInicioFin($fechaFinSolicitudes, $fechaInicioPruebas, 'fechaSolicitudesTrambolica', $errores, 'Las pruebas no pueden empezar antes de que acabe el proceso de solicitud');
    $errores = Validator::validarFechaInicioFin($fechaInicioPruebas, $fechaFinPruebas, 'fechaPruebas', $errores);
    $errores = Validator::validarFechaInicioFin($fechaFinPruebas, $fechaListaProvisional, 'fechaListasTrambolica', $errores, 'Las listas no pueden publicarse antes de que acabe el proceso de pruebas');
    $errores = Validator::validarFechaInicioFin($fechaListaProvisional, $fechaListaDefinitiva, 'fechaListas', $errores);

    if(empty($errores)) {
        // Esta todo validado, creamos transaccion y empezamos a guardar cosas en la base de datos
        try {
            Conexion::beginTrasaction();

            // Introducir cosicas en base de datos
            // Crear convocatoria
            $convocatoriaObj = new Convocatoria(null, $movilidades, $largaDuracion, 
                $fechaInicioSolicitudes, $fechaFinSolicitudes, 
                $fechaInicioPruebas, $fechaFinPruebas, $fechaListaProvisional, 
                $fechaListaDefinitiva, $proyectoId, $descripcion, $nombre);
            $convocatoriaId = ConvocatoriaRepository::crearConvocatoria($convocatoriaObj);
            

            // Añadir destinatarios
            foreach($destinatariosPosteados as $destinatarioActual) {
                ConvocatoriaRepository::añadirDestinatarioAConvocatoria($convocatoriaId, $destinatarioActual);
            }

            // Añadir idiomas
            foreach($idiomasPosteados as $key => $value) {
                ConvocatoriaRepository::añadirBaremoIdiomaAConvocatoria($convocatoriaId, $key, $value);
            }

            // Añadir items
            foreach($itemsPosteados as $key => $value) {
                $puntuacionMax = $value['itemMax'];
                $requisito = empty($value['itemRequisito']) ? 0 : 1;
                $minRequisito = $value['itemMin'];
                ConvocatoriaRepository::añadirBaremoItemAConvocatoria($convocatoriaId,$key,$puntuacionMax, $requisito, $minRequisito);
            }

            Conexion::commit();
            header("Location: http://localhost/erasmus/servidor/forms/CrearConvocatoria.php");
            exit();
        } catch(Throwable | Error $e) { // Fuente: https://www.php.net/manual/en/class.throwable.php
            // Ups! algo ha ido mal, vamos a revertir los cambios de DB
            Conexion::rollback();
        }        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/erasmus/interfaz/comun.css">
    <link rel="stylesheet" href="http://localhost/erasmus/interfaz/gestion/gestion.css">
    <title>Crear Convocatoria</title>
</head>
<body>
    <div class="pagina-anidada">
        <p class="titulo">Crear Convocatoria</p>
        <form id='convocatoriaFormulario' action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" >
            <div class="fila">
                <div class="conjunto">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value='<?php echo $nombre?>' />
                    <?php echo((!empty($errores['nombre']) ? '<p class="error">'. $errores['nombre'] .'</p>' : '') ) ?>
                </div>
                <div class="conjunto">
                    <label>Proyecto</label>
                    <select name="proyectoId" >
                        <?php
                        foreach ($proyectos as $proyecto) {
                        ?>    
                        <option
                            <?php echo ($proyectoId == $proyecto->get_id()? 'selected' : '') ?>
                            value='<?php echo $proyecto->get_id()?>'
                            > <?php echo $proyecto->get_nombre()?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="conjunto">
                    <label>Movilidades</label>
                    <input type="number" min='1' name="movilidades" value='<?php echo $movilidades?>' />
                    <?php echo((!empty($errores['movilidades']) ? '<p class="error">'. $errores['movilidades'] .'</p>' : '') ) ?>
                </div>
                <div class="conjunto">
                    <label>Larga duración (>=90 días)</label>
                    <select name="largaDuracion" >
                        <option value="true" <?php echo ($largaDuracion == 'true' ? 'selected' : '') ?>>Si </option>
                        <option value="false" <?php echo ($largaDuracion == 'false' ? 'selected' : '') ?>>No </option>
                    </select>    
                </div>
            </div>

            <div class="fila">
                <div class="tabla-items">
                    <p class="label">Items Baremables</p>

                    <table>
                        <tr>
                            <th>Nombre</th>
                            <th>Nota Mínima</th>
                            <th>Nota Máxima</th>
                            <th>Requisito</th>
                            <th>Adjuntado por</th>
                        </tr>
                        <?php
                        foreach ($items as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item->get_nombre() ?></td>
                                <td><input 
                                    type="number"
                                    min='0' 
                                    max='10'
                                    name='itemMin-<?php echo $item->get_id() ?>' 
                                    value='<?php echo $_POST['itemMin-'.$item->get_id()] ?>'
                                    <?php echo((empty($errores['itemMin-'.$item->get_id()]) ? '' : 'class="error-input"'))?>/></td>
                                <td><input 
                                    type="number"
                                    min='0' 
                                    max='10'
                                    name='itemMax-<?php echo $item->get_id() ?>' 
                                    value='<?php echo $_POST['itemMax-'.$item->get_id()] ?>'
                                    <?php echo((empty($errores['itemMax-'.$item->get_id()]) ? '' : 'class="error-input"'))?>/></td>
                                <td><input 
                                    type="checkbox"
                                    <?php echo (empty($_POST['itemRequisito-'.$item->get_id()]) ? '' : 'checked' )?>
                                    name='itemRequisito-<?php echo $item->get_id() ?>'/></td>
                                <td><p class="descripcion"> <?php echo($item->get_subeAlumno() == 0 ? 'profesor' : 'alumno') ?></p></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>

                <div class="tabla-items">
                    <p class="label">Baremo Idioma</p>
                    <table>
                        <tr>
                            <?php
                            foreach ($idiomas as $idioma) {
                                ?>
                                    <th><?php echo($idioma->get_nivel()) ?></th>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr>
                            <?php
                            foreach ($idiomas as $idioma) {
                            ?>
                                <td><input 
                                    type="number" 
                                    min='0' 
                                    max='10' 
                                    name='idiomaPuntuacion-<?php echo $idioma->get_id() ?>' 
                                    value='<?php echo $_POST['idiomaPuntuacion-'.$idioma->get_id()] ?>'
                                    <?php echo((empty($errores['idiomaPuntuacion-'.$idioma->get_id()]) ? '' : 'class="error-input"'))?>/></td>
                            <?php
                            }
                            ?>
                        </tr>
                    </table>
                    <div class="conjunto">
                        <label>Destinatarios</label>
                        <?php echo((!empty($errores['destinatario']) ? '<p class="error">'. $errores['destinatario'] .'</p>' : '') ) ?>

                        <div class="conjunto partible">
                            <?php
                                foreach ($destinatarios as $destinatario) {
                                ?>
                                <div>
                                    <input 
                                        type="checkbox" 
                                        class="checkbox"
                                        <?php echo (empty($_POST['destinatario-'.$destinatario->get_id()]) ? '' : 'checked' )?>
                                        name='destinatario-<?php echo $destinatario->get_id() ?>' 
                                    /><span>
                                        <?php echo $destinatario->get_codigoGrupo() . ' ' . $destinatario->get_nombre() ?>
                                    </span>
                                </div>
                                <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fila">
                <div class="conjunto">
                    <label>Fechas Solicitudes</label>
                    <?php echo((!empty($errores['fechaSolicitudes']) ? '<p class="error">'. $errores['fechaSolicitudes'] .'</p>' : '') ) ?>
                    <div class="fila">
                        <label>Inicio</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaInicioSolicitudes?>'
                            name="fechaInicioSolicitudes" />
                        <?php echo((!empty($errores['fechaInicioSolicitudes']) ? '<p class="error">'. $errores['fechaInicioSolicitudes'] .'</p>' : '') ) ?>
                    </div>  
                    <div class="fila">
                        <label>Fin</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaFinSolicitudes?>'
                            name="fechaFinSolicitudes" />
                        <?php echo((!empty($errores['fechaFinSolicitudes']) ? '<p class="error">'. $errores['fechaFinSolicitudes'] .'</p>' : '') ) ?>
                    </div>                
                </div>

                <div class="conjunto">
                    <label>Fechas Pruebas</label>
                    <?php echo((!empty($errores['fechaPruebas']) ? '<p class="error">'. $errores['fechaPruebas'] .'</p>' : '') ) ?>
                    <?php echo((!empty($errores['fechaSolicitudesTrambolica']) ? '<p class="error">'. $errores['fechaSolicitudesTrambolica'] .'</p>' : '') ) ?>

                    <div class="fila">
                        <label>Inicio</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaInicioPruebas?>'
                            name="fechaInicioPruebas" />
                        <?php echo((!empty($errores['fechaInicioPruebas']) ? '<p class="error">'. $errores['fechaInicioPruebas'] .'</p>' : '') ) ?>
                    </div>  
                    <div class="fila">
                        <label>Fin</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaFinPruebas?>'
                            name="fechaFinPruebas" />
                        <?php echo((!empty($errores['fechaFinPruebas']) ? '<p class="error">'. $errores['fechaFinPruebas'] .'</p>' : '') ) ?>
                    </div>                
                </div>

                <div class="conjunto">
                    <label>Fechas Listados</label>
                    <?php echo((!empty($errores['fechaListas']) ? '<p class="error">'. $errores['fechaListas'] .'</p>' : '') ) ?>
                    <?php echo((!empty($errores['fechaListasTrambolica']) ? '<p class="error">'. $errores['fechaListasTrambolica'] .'</p>' : '') ) ?>
                    <div class="fila">
                        <label>Provisional</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaListaProvisional?>'
                            name="fechaListaProvisional" />
                        <?php echo((!empty($errores['fechaListaProvisional']) ? '<p class="error">'. $errores['fechaListaProvisional'] .'</p>' : '') ) ?>
                    </div>  
                    <div class="fila">
                        <label>Definitivo</label>
                        <input 
                            type="datetime-local" 
                            value='<?php echo $fechaListaDefinitiva?>'
                            name="fechaListaDefinitiva" />
                        <?php echo((!empty($errores['fechaListaDefinitiva']) ? '<p class="error">'. $errores['fechaListaDefinitiva'] .'</p>' : '') ) ?>
                    </div>                
                </div>
            </div>

            <div class="fila">
                <button class="boton-primario">Crear</button>
            </div>

        </form>
    </div>
    

</body>
</html>