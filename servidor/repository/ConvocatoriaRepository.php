<?php
    class ConvocatoriaRepository {

        public static function obtenerConvocatoriaPorId($id) {
            $consultas = Conexion::basedatos()->query("Select * from convocatoria where id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Convocatoria(
                    $resultados->id,
                    $resultados->movilidades,
                    $resultados->largaDuracion,
                    $resultados->fechaInicioSolicitudes,
                    $resultados->fechaFinSolicitudes,
                    $resultados->fechaInicioPruebas,
                    $resultados->fechaFinPruebas,
                    $resultados->fechaListaProvisional,
                    $resultados->fechaListaDefinitiva,
                    $resultados->proyectoId,
                    $resultados->descripcion,
                    $resultados->nombre,
                );
            }
        }

        public static function eliminarConvocatoriaPorId($id) {
            Conexion::basedatos()->exec("DELETE FROM `convocatoria` WHERE id = $id");
        }

        public static function crearConvocatoria($convocatoria) {
            $movilidades = $convocatoria->get_movilidades();
            $largaDuracion = $convocatoria->get_largaDuracion();
            $fechaInicioSolicitudes = str_replace('T', ' ', $convocatoria->get_fechaInicioSolicitudes());
            $fechaFinSolicitudes = str_replace('T', ' ', $convocatoria->get_fechaFinSolicitudes());
            $fechaInicioPruebas = str_replace('T', ' ', $convocatoria->get_fechaInicioPruebas());
            $fechaFinPruebas = str_replace('T', ' ', $convocatoria->get_fechaFinPruebas());
            $fechaListaProvisional = str_replace('T', ' ', $convocatoria->get_fechaListaProvisional());
            $fechaListaDefinitiva = str_replace('T', ' ', $convocatoria->get_fechaListaDefinitiva());
            $proyectoId = $convocatoria->get_proyectoId();
            $descripcion = $convocatoria->get_descripcion();
            $nombre = $convocatoria->get_nombre();

            Conexion::basedatos()->exec("INSERT INTO `convocatoria` 
            (movilidades, larga_duracion, fecha_inicio_solicitudes, 
            fecha_fin_solicitudes, fecha_inicio_pruebas, fecha_fin_pruebas, 
            fecha_lista_provisional, fecha_lista_definitiva, proyecto_id,
            descripcion, nombre)
            VALUES ('$movilidades',$largaDuracion,'$fechaInicioSolicitudes',
            '$fechaFinSolicitudes','$fechaInicioPruebas','$fechaFinPruebas',
            '$fechaListaProvisional','$fechaListaDefinitiva',$proyectoId,
            '$descripcion', '$nombre'
            )");

            return Conexion::basedatos()->lastInsertId();
        }


        public static function añadirDestinatarioAConvocatoria($convocatoriaId, $destinatarioId) {
            Conexion::basedatos()->exec("INSERT INTO `destinatario_convocatoria` (convocatoria_id, destinatario_id) VALUES ($convocatoriaId, $destinatarioId)");
        }

        public static function añadirBaremoIdiomaAConvocatoria($convocatoriaId, $idiomaId, $puntuacion) {
            Conexion::basedatos()->exec("INSERT INTO `convocatoria_baremo_idioma` (convocatoria_id, idioma_id, puntuacion) VALUES ($convocatoriaId, $idiomaId, $puntuacion)");
        }
        
        public static function añadirBaremoItemAConvocatoria($convocatoriaId, $itemId, $puntuacionMax, $requisito, $minRequisito) {
            echo("INSERT INTO `convocatoria_baremo` (convocatoria_id, item_id, puntuacion_max, requisito, min_requisito) 
            VALUES ($convocatoriaId, $itemId, $puntuacionMax, $requisito, $minRequisito)");
            Conexion::basedatos()->exec("INSERT INTO `convocatoria_baremo` (convocatoria_id, item_id, puntuacion_max, requisito, min_requisito) 
                VALUES ($convocatoriaId, $itemId, $puntuacionMax, $requisito, $minRequisito)");
        }

        public static function actualizarConvocatoria($convocatoria) {
            $id = $convocatoria->get_id();
            $movilidades = $convocatoria->get_movilidades();
            $largaDuracion = $convocatoria->get_largaDuracion();
            $fechaInicioSolicitudes = $convocatoria->get_fechaInicioSolicitudes();
            $fechaFinSolicitudes = $convocatoria->get_fechaFinSolicitudes();
            $fechaInicioPruebas = $convocatoria->get_fechaInicioPruebas();
            $fechaFinPruebas = $convocatoria->get_fechaFinPruebas();
            $fechaListaProvisional = $convocatoria->get_fechaListaProvisional();
            $fechaListaDefinitiva = $convocatoria->get_fechaListaDefinitiva();
            $proyectoId = $convocatoria->get_proyectoId();

            //falta query
        }

    }
?>