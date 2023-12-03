<?php
    class IdiomasRepository {

        public static function obtenerIdiomas() {
            $consultas = Conexion::basedatos()->query("Select * from idiomas");
            $idiomas = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $idiomas[] =  new Idioma(
                    $resultados->id,
                    $resultados->nivel
                );
            }
            return $idiomas;
        }

       
    }
?>