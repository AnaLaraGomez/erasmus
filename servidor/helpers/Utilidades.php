<?php
class Utilidades {
    public static function ocultarDni($dni) {
        return $dniOculto = '***' . substr($dni, 3,3) . '***';
    }
}

?>