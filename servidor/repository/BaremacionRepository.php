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

    }
?>