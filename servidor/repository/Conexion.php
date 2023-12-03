<?php
    class Conexion {
        private static $conn;

        public static function basedatos() {
            if(!isset(self::$conn)) {
                // Fuente: https://www.php.net/manual/en/pdo.setattribute.php#122170
                // Configurar la conexion para que lance excepciones en vez de errores
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];
                self::$conn = new PDO('mysql:host=localhost;dbname=erasmus', 'ana', 'root',$options);
            }
            return self::$conn;
        }

        public static function beginTrasaction() {
            Conexion::basedatos()->beginTransaction();
        }

        public static function commit() {
            Conexion::basedatos()->commit();
        }

        public static function rollback() {
            Conexion::basedatos()->rollBack();
        }
    }

?>