<?php
    class UsuarioRepository {

        public static function obtenerUsuarioPorId($id) {
            $consultas = Conexion::basedatos()->query("Select * from usuario where id = $id");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Usuario(
                    $resultados->id,
                    $resultados->dni,
                    $resultados->password,
                    $resultados->admin,
                    $resultados->foto,
                );
            }
        }

        public static function obtenerUsuarioPorDni($dni) {
            $consultas = Conexion::basedatos()->query("Select * from usuario where dni = '$dni'");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return new Usuario(
                    $resultados->id,
                    $resultados->dni,
                    $resultados->password,
                    $resultados->admin,
                    $resultados->foto,
                );
            }
        }

        public static function guardarFoto($id, $foto) {
            Conexion::basedatos()->exec("UPDATE `usuario`  SET foto = '$foto' where id = $id"); 
        }

        public static function obtenerFoto($id) {
            $consultas = Conexion::basedatos()->query("Select foto from usuario where id = '$id'");
            while ($resultados = $consultas->fetch(PDO::FETCH_OBJ)) {
                 return $resultados->foto;
            }
        }

        public static function crearUsuario($dni, $password) {
            Conexion::basedatos()->exec("INSERT INTO `usuario` (dni, `password`) VALUES ('$dni', '$password')"); 
            return Conexion::basedatos()->lastInsertId();
        }

        public static function eliminarUsuarioPorId($id) {
            Conexion::basedatos()->exec("DELETE FROM `usuario` WHERE id = $id"); 
        }
    }
?>
