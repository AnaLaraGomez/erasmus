<?php
session_start();

class Session {

    public static function estaLogueado(){

        $sessionId = session_id();
        if(empty($sessionId)){
            return false;
        }
        if(empty($_SESSION [$sessionId])) {
            return false;
        }
    
        return true;
    }
    
    public static function login($userObject) {
        $sessionId = session_id();
        $_SESSION [$sessionId] = serialize($userObject);
    }
    
    public static function leerDatosSession() {
        $sessionId = session_id();
        return unserialize($_SESSION[$sessionId]);
    }
    
    public static function logout() {
        $sessionId = session_id();
        unset($_SESSION [$sessionId]);
    }
}



?>