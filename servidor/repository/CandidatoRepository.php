<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/repository/conexion.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/entities/candidato.php');

    class CandidatoRepository {

        public static function obtenerCandidatoPorId($id) {
            $consultas = Conexion::basedatos()->query("Select * from candidato where id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Candidato(
                    $resultados->id,
                    $resultados->nombre,
                    $resultados->apellidos,
                    $resultados->fechaNac,
                    $resultados->curso,
                    $resultados->telefono,
                    $resultados->correo,
                    $resultados->domicilio,
                    $resultados->tutorNombre,
                    $resultados->tutorApellidos,
                    $resultados->tutorDni,
                    $resultados->tutorDomicilio,
                    $resultados->tutorTelefono
                );
            }
        }

        public static function eliminarCandidatoPorId($id) {
            Conexion::basedatos()->exec("DELETE FROM `candidato` WHERE id = $id");
        }

        public static function crearCandidato($candidato) {
            $id = $candidato->get_id();
            $nombre = $candidato->get_nombre();
            $apellidos = $candidato->get_apellidos();
            $fechaNac = $candidato->get_fechaNac();
            $curso = $candidato->get_curso();
            $telefono = $candidato->get_telefono();
            $correo = $candidato->get_correo();
            $domicilio = $candidato->get_domicilio();
            $tutorNombre = $candidato->get_tutorNombre();
            $tutorApellidos = $candidato->get_tutorApellidos();
            $tutorDni = $candidato->get_tutorDni();
            $tutorDomicilio = $candidato->get_tutorDomicilio();
            $tutorTelefono = $candidato->get_tutorTelefono();

            Conexion::basedatos()->exec("
            INSERT INTO `candidato` (id, nombre, apellidos, fechaNac, curso, telefono, correo, domicilio, tutorNombre, tutorApellidos, tutorDni, tutorDomicilio, tutorTelefono) 
            VALUES ($id, '$nombre', '$apellidos', '$fechaNac', '$curso', '$telefono', '$correo', '$domicilio', '$tutorNombre', '$tutorApellidos', '$tutorDni', '$tutorDomicilio', '$tutorTelefono)"); 
        }

        public static function actualizarCandidato($candidato) {
            $id = $candidato->get_id();
            $nombre = $candidato->get_nombre();
            $apellidos = $candidato->get_apellidos();
            $fechaNac = $candidato->get_fechaNac();
            $curso = $candidato->get_curso();
            $telefono = $candidato->get_telefono();
            $correo = $candidato->get_correo();
            $domicilio = $candidato->get_domicilio();
            $tutorNombre = $candidato->get_tutorNombre();
            $tutorApellidos = $candidato->get_tutorApellidos();
            $tutorDni = $candidato->get_tutorDni();
            $tutorDomicilio = $candidato->get_tutorDomicilio();
            $tutorTelefono = $candidato->get_tutorTelefono();

            //falta query
        }

    }
?>