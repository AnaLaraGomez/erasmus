<?php
    class CandidatoRepository {

        public static function obtenerCandidatoPorId($id) {
            $consultas = Conexion::basedatos()->query("Select c.*, d.codigo_grupo, d.nombre as nombre_grupo from candidato c inner join destinatario d on d.id = c.curso where c.id = $id ");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Candidato(
                    $resultados->id,
                    $resultados->nombre,
                    $resultados->apellidos,
                    $resultados->fecha_nacimiento,
                    $resultados->codigo_grupo . ' ' . $resultados->nombre_grupo,
                    $resultados->telefono,
                    $resultados->correo,
                    $resultados->domicilio,
                    $resultados->tutor_nombre,
                    $resultados->tutor_apellidos,
                    $resultados->tutor_dni,
                    $resultados->tutor_domicilio,
                    $resultados->tutor_telefono
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
            INSERT INTO `candidato` (id, nombre, apellidos, fecha_nacimiento, curso, telefono, correo, domicilio, tutor_nombre, tutor_apellidos, tutor_dni, tutor_domicilio, tutor_telefono) 
            VALUES ($id, '$nombre', '$apellidos', '$fechaNac', '$curso', '$telefono', '$correo', '$domicilio', '$tutorNombre', '$tutorApellidos', '$tutorDni', '$tutorDomicilio', '$tutorTelefono')"); 
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