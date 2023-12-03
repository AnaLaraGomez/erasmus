<?php
    class DestinatarioRepository {

        public static function obtenerDestinatarios() {
            $consultas = Conexion::basedatos()->query("Select * from destinatario");
            $destinatarios = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $destinatarios[] =  new Destinatario(
                    $resultados->id,
                    $resultados->codigo_grupo,
                    $resultados->nombre
                );
            }
            return $destinatarios;
        }

       
    }
?>