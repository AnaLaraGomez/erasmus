<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/repository/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/entities/proyecto.php');

    class ProyectoRepository {

        public static function obtenerProyectos() {
            $consultas = Conexion::basedatos()->query("Select * from proyecto");
            $proyectos = array();
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                $proyectos[] =  new Proyecto(
                    $resultados->id,
                    $resultados->codigo,
                    $resultados->nombre,
                    $resultados->fecha_inicio,
                    $resultados->fecha_fin
                );
            }
            return $proyectos;
        }

        public static function obtenerProyectoPorId($id) {
            $consultas = Conexion::basedatos()->query("Select * from proyecto where codigo = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Proyecto(
                    $resultados->id,
                    $resultados->codigo,
                    $resultados->nombre,
                    $resultados->fechaInicio,
                    $resultados->fechaFin
                );
            }
        }


    }
?>