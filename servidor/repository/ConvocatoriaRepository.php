<?php
    class ConvocatoriaRepository {

        public static function obtenerConvocatoriasAbiertas() {
            $consultas = Conexion::basedatos()->query("SELECT c.*, p.nombre as proyecto_nombre from convocatoria c
            inner join proyecto p on p.id = c.proyecto_id
            where fecha_inicio_solicitudes <= NOW();");
            $convocatorias = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $convocatorias[] = new Convocatoria(
                    $resultados->id,
                    $resultados->movilidades,
                    $resultados->larga_duracion,
                    $resultados->fecha_inicio_solicitudes,
                    $resultados->fecha_fin_solicitudes,
                    $resultados->fecha_inicio_pruebas,
                    $resultados->fecha_fin_pruebas,
                    $resultados->fecha_lista_provisional,
                    $resultados->fecha_lista_definitiva,
                    $resultados->proyecto_id,
                    $resultados->descripcion,
                    $resultados->nombre,
                    $resultados->proyecto_nombre
                );
            }
            return $convocatorias;
        }

        public static function  obtenerConvocatoriasInactivas() {
            $consultas = Conexion::basedatos()->query("SELECT c.*, p.nombre as proyecto_nombre from convocatoria c
            inner join proyecto p on p.id = c.proyecto_id
            where fecha_inicio_solicitudes > NOW();");
            $convocatorias = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $convocatorias[] = new Convocatoria(
                    $resultados->id,
                    $resultados->movilidades,
                    $resultados->larga_duracion,
                    $resultados->fecha_inicio_solicitudes,
                    $resultados->fecha_fin_solicitudes,
                    $resultados->fecha_inicio_pruebas,
                    $resultados->fecha_fin_pruebas,
                    $resultados->fecha_lista_provisional,
                    $resultados->fecha_lista_definitiva,
                    $resultados->proyecto_id,
                    $resultados->descripcion,
                    $resultados->nombre,
                    $resultados->proyecto_nombre
                );
            }
            return $convocatorias;
        }

        public static function  obtenerConvocatoriasSolicitadas($candidatoId) {
            $consultas = Conexion::basedatos()->query("SELECT c.*, p.nombre as proyecto_nombre from convocatoria c
            inner join proyecto p on p.id = c.proyecto_id
            inner join baremacion b on b.convocatoria_id = c.id
            where fecha_inicio_solicitudes <=  NOW()
            and b.candidato_id = $candidatoId
            GROUP by b.convocatoria_id;");
            $convocatorias = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $convocatorias[] = new Convocatoria(
                    $resultados->id,
                    $resultados->movilidades,
                    $resultados->larga_duracion,
                    $resultados->fecha_inicio_solicitudes,
                    $resultados->fecha_fin_solicitudes,
                    $resultados->fecha_inicio_pruebas,
                    $resultados->fecha_fin_pruebas,
                    $resultados->fecha_lista_provisional,
                    $resultados->fecha_lista_definitiva,
                    $resultados->proyecto_id,
                    $resultados->descripcion,
                    $resultados->nombre,
                    $resultados->proyecto_nombre
                );
            }
            return $convocatorias;
        }

        public static function obtenerConvocatoriaDetalle($id) {
            $detalle = array();
      
            $detalle['destinatarios'] = array();
            $consultas = Conexion::basedatos()->query("SELECT 
                d.id as destinatarioId,
                d.codigo_grupo codigoGrupo, 
                d.nombre as destinatarioNombre
                FROM convocatoria c
                INNER JOIN destinatario_convocatoria dc on dc.convocatoria_id = c.id
                INNER JOIN destinatario d on d.id = dc.destinatario_id
                where c.id  = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $detalle['destinatarios'][] =  $resultados;
            }

            $detalle['idiomas'] = array();
            $consultas = Conexion::basedatos()->query("SELECT 
                i.id as id,
                i.nivel as idioma,
                cbi.puntuacion as puntuacion
                FROM convocatoria c
                INNER JOIN convocatoria_baremo_idioma cbi on cbi.convocatoria_id = c.id
                INNER JOIN idiomas i on i.id = cbi.idioma_id
                where c.id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $detalle['idiomas'][] =  $resultados;
            }

            $detalle['items'] = array();
            $consultas = Conexion::basedatos()->query("SELECT 
                ib.id as itemId,
                ib.nombre as itemNombre,
                ib.sube_alumno as subeAlumno,
                cb.puntuacion_max as notaMax,
                cb.requisito,
                cb.min_requisito as notaMin
                FROM convocatoria c
                INNER JOIN convocatoria_baremo cb on cb.convocatoria_id = c.id
                INNER JOIN item_baremable ib on ib.id = cb.item_id
                where c.id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $detalle['items'][] =  $resultados;
            }
            return $detalle;
        }

        public static function obtenerConvocatoriaBaremablesAlumno($convocatoriaId, $userId) {
            $consultas = Conexion::basedatos()->query("SELECT 
                b.item_id as itemId,
                ib.nombre as itemNombre,
                b.url as itemUrl,
                b.nota as itemNota,
                ib.sube_alumno as subeAlumno,
                cb.puntuacion_max as notaMax,
                cb.min_requisito as notaMin,
                cb.requisito as requisito
                from baremacion b
                inner JOIN item_baremable ib on ib.id = b.item_id
                inner JOIN convocatoria_baremo cb on cb.convocatoria_id = b.convocatoria_id  and cb.item_id = b.item_id 
                where b.convocatoria_id = $convocatoriaId 
                and b.candidato_id = $userId");
            $baremables = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $baremables[] =  $resultados;
            }
            return $baremables;
        }

        public static function obtenerConvocatoriaBaremablesTotales($convocatoriaId, $userId) {
            $consultas = Conexion::basedatos()->query("SELECT 
                cb.item_id as itemId,
                ib.nombre as itemNombre,
                b.url as itemUrl,
                b.nota as itemNota,
                ib.sube_alumno as subeAlumno,
                cb.puntuacion_max as notaMax,
                cb.min_requisito as notaMin,
                cb.requisito as requisito
                from convocatoria_baremo cb
                left outer JOIN baremacion b on cb.convocatoria_id = b.convocatoria_id and cb.item_id = b.item_id and b.candidato_id = $userId
                inner JOIN item_baremable ib on ib.id = cb.item_id
                where cb.convocatoria_id = $convocatoriaId");

                $baremables = array();
                while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                    $baremables[] =  $resultados;
                }
                return $baremables;    
        }

        public static function obtenerConvocatoriaPorId($id) {
            $consultas = Conexion::basedatos()->query("Select * from convocatoria where id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Convocatoria(
                    $resultados->id,
                    $resultados->movilidades,
                    $resultados->larga_duracion,
                    $resultados->fecha_inicio_solicitudes,
                    $resultados->fecha_fin_solicitudes,
                    $resultados->fecha_inicio_pruebas,
                    $resultados->fecha_fin_pruebas,
                    $resultados->fecha_lista_provisional,
                    $resultados->fecha_lista_definitiva,
                    $resultados->proyecto_id,
                    $resultados->descripcion,
                    $resultados->nombre,
                );
            }
        }

        public static function obtenerConvocatoriasPuntuables() {
            // En pruebas o en provisional
            $respuesta = array();
            $consultas = Conexion::basedatos()->query("SELECT 
                c.*, p.nombre as proyecto_nombre 
                from convocatoria c
                inner join proyecto p on p.id = c.proyecto_id
                where fecha_inicio_pruebas <= NOW() 
                AND fecha_lista_definitiva >= NOW() ");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $convocatoriaArray = array();
                $convocatoriaArray['id'] = $resultados->id;
                $convocatoriaArray['nombre'] = $resultados->nombre;
                $convocatoriaArray['proyectoNombre'] = $resultados->proyecto_nombre;
                $convocatoriaArray['fechaInicioPruebas'] = $resultados->fecha_inicio_pruebas;
                $convocatoriaArray['fechaFinPruebas'] = $resultados->fecha_fin_pruebas;
                $convocatoriaArray['fechaListaProvisional'] = $resultados->fecha_lista_provisional;
                $convocatoriaArray['candidatos'] = array();

                $consultaCandidatos = Conexion::basedatos()->query("SELECT 
                    u.id as id,
                    u.dni as dni,
                    c.nombre as nombre,
                    c.apellidos as apellidos,
                    count(b.nota)as evaluadas,
                    count(cb.item_id) as evaluables,
                    count(b.nota) = count(cb.item_id) as evaluado
                    from baremacion b
                    inner JOIN usuario u on u.id = b.candidato_id
                    inner JOIN candidato c on c.id = b.candidato_id
                    inner JOIN convocatoria_baremo cb on cb.convocatoria_id = b.convocatoria_id and cb.item_id = b.item_id
                    where b.convocatoria_id = $resultados->id                    
                    group by u.dni
                    order by evaluado asc;");

                while ($resultadosCandidatos = $consultaCandidatos->fetch(PDO::FETCH_OBJ)) {
                    $convocatoriaArray['candidatos'][] =  $resultadosCandidatos;
                }
                $respuesta[] = $convocatoriaArray;
            }
            return $respuesta;

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

        public static function limpiarDestinatariosDeConvocatoria($convocatoriaId) {
            Conexion::basedatos()->exec("DELETE FROM `destinatario_convocatoria` WHERE convocatoria_id = $convocatoriaId");
        }

        public static function añadirBaremoIdiomaAConvocatoria($convocatoriaId, $idiomaId, $puntuacion) {
            Conexion::basedatos()->exec("INSERT INTO `convocatoria_baremo_idioma` (convocatoria_id, idioma_id, puntuacion) 
                                        VALUES ($convocatoriaId, $idiomaId, $puntuacion)
                                        ON DUPLICATE KEY UPDATE `puntuacion`= '$puntuacion' ");
        }
        
        public static function añadirBaremoItemAConvocatoria($convocatoriaId, $itemId, $puntuacionMax, $requisito, $minRequisito) {
            Conexion::basedatos()->exec("INSERT INTO `convocatoria_baremo` (convocatoria_id, item_id, puntuacion_max, requisito, min_requisito) 
                VALUES ($convocatoriaId, $itemId, $puntuacionMax, $requisito, $minRequisito)");
        }

        public static function limpiarItemsDeConvocatoria($convocatoriaId) {
            Conexion::basedatos()->exec("DELETE FROM `convocatoria_baremo` WHERE convocatoria_id = $convocatoriaId");
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
            $nombre = $convocatoria->get_nombre();

            Conexion::basedatos()->exec(" UPDATE `convocatoria`  SET 
                movilidades = $movilidades, 
                larga_duracion = $largaDuracion, 
                fecha_inicio_solicitudes = '$fechaInicioSolicitudes', 
                fecha_fin_solicitudes = '$fechaFinSolicitudes', 
                fecha_inicio_pruebas = '$fechaInicioPruebas', 
                fecha_fin_pruebas = '$fechaFinPruebas', 
                fecha_lista_provisional = '$fechaListaProvisional', 
                fecha_lista_definitiva = '$fechaListaDefinitiva', 
                proyecto_id = $proyectoId,
                nombre = '$nombre'
                WHERE id = $id");
        }

    }
?>