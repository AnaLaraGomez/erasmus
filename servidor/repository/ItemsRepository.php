<?php
    class ItemsRepository {

        public static function obtenerItems() {
            $consultas = Conexion::basedatos()->query("Select * from item_baremable");
            $items = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $items[] =  new Item(
                    $resultados->id,
                    $resultados->nombre,
                    $resultados->sube_alumno
                );
            }
            return $items;
        }

       
    }
?>