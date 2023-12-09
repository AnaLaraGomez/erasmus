<?php
    class BaremacionRepository {

        public static function crearActualizarEntregableSolicitudAlumno($convocatoriaId, $candidatoId, $itemId, $url) {
            // Evitar hacer comprobaciones de si la fila existe para ejecutar un update o un insert.
            // Es posible usar el OnDUplicate ke porque he configurado la PK de la tabla como 
            //  PRIMARY KEY(`convocatoria_id`, `candidato_id`, `item_id`)
            //Fuente: https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
            Conexion::basedatos()->exec("INSERT INTO `baremacion` (convocatoria_id, candidato_id, item_id, `url`) 
            VALUES ($convocatoriaId, $candidatoId, $itemId, '$url') ON DUPLICATE KEY UPDATE `url`= '$url'");
        }

        public static function evaluarBaremableSoloNota($convocatoriaId, $candidatoId, $itemId, $nota) {
            Conexion::basedatos()->exec("UPDATE `baremacion` SET nota = $nota 
            WHERE convocatoria_id = $convocatoriaId AND candidato_id = $candidatoId AND item_id = $itemId");
        }

        public static function evaluarBaremableNotaYFichero($convocatoriaId, $candidatoId, $itemId, $url, $nota) {
            Conexion::basedatos()->exec("INSERT INTO  `baremacion` (convocatoria_id, candidato_id, item_id, `url`, nota) 
             VALUES ($convocatoriaId, $candidatoId, $itemId, '$url', $nota) ON DUPLICATE KEY UPDATE nota = $nota, `url`= '$url'");
        }

        public static function listadoNotas($convocatoriaId) {
            $respuesta = array();
            $consultas =Conexion::basedatos()->query("SELECT 
            u.dni, 
            sum(b.nota) as puntuacion
            FROM baremacion b
            inner JOIN convocatoria c on c.id = b.convocatoria_id
            inner JOIN convocatoria_baremo cb on cb.convocatoria_id = b.convocatoria_id and cb.item_id = b.item_id
            inner JOIN usuario u on u.id = b.candidato_id
            WHERE b.convocatoria_id = $convocatoriaId
            AND c.fecha_lista_provisional <= now()
            GROUP by b.candidato_id
            HAVING sum(b.nota < cb.min_requisito) = 0 
            order by puntuacion desc");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $entrada = array();
                $entrada['puntuacion'] = $resultados->puntuacion;
                $entrada['dni'] = Utilidades::ocultarDni($resultados->dni);
                $respuesta[] = $entrada;
            }    
            return $respuesta;
        }
    }
?>