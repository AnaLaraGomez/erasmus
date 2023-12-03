<?php 

class Validator {
    public static function campoRequerido($campo, $claveError, $mensajeError, $errores) {
        if(empty($campo) ){
            $errores[$claveError]=$mensajeError;
        }
        return $errores;
    }
    
    public static function credencialesCorrectas($usuario, $password, $userObj) {
        $errores = array();
        if(empty($userObj)){
            $errores['usuario2']="El usuario introducido no existe";
        }
        if(!empty($userObj) && $password!=$userObj->get_password()){
            $errores['password2']="La contraseña no es correcta";
        }
        return $errores;
    }

    public static function validarDni($dni, $errores) {
        $letras="TRWAGMYFPDXBNJZSQVHLCKE";
        if(preg_match("/^[0-9]{8}[a-zA-z]{1}$/", $dni) != 1) {
            $errores['dniInvalido']="El campo $dni no es un Dni válido";
            return $errores;
        }

        $numero=substr($dni,0,8);
        $letra=substr($dni,8,1);
        if($letras[$numero%23] != strtoupper($letra)) {
            $errores['dniInvalido']="El campo $dni es un Dni con letra no válida";
        }

        return $errores;
    }

    public static function validarEmail($correo, $errores){
        if(!filter_var($correo, FILTER_VALIDATE_EMAIL))
        {
            $errores['correoInvalido']="El correo electrónico debe ser un email válido";
        }
        return $errores;
    }

    public static function validarTelefono($telefono, $errores){
        if (preg_match("/^[6-9]\d{8}$/", $telefono) != 1) {
            $errores['telefonoInvalido']="El número de teléfono no es válido. Debe de contener 9 dígitos y empezar por 6,7,8 o 9";
        }
        return $errores;
    }

    public static function validarFechaNac ($fechaNac, $errores){
        if(empty($fechaNac)) {
            $errores['fechaNac']="La fecha de nacimiento es un campo obligatorio";
            return $errores;
        }
        $fechaNacimientoComoEntero = (new DateTime($fechaNac))->getTimestamp();
        $fechaActual = new DateTime();
        $fechaActual->setTime(0,0,0,0);
        $fechaActualComoEntero = $fechaActual->getTimestamp();

        if($fechaActualComoEntero-$fechaNacimientoComoEntero <= 0){
            $errores['fechaFutura']="La fecha de nacimiento tiene que ser una fecha pasada";
        }
        return $errores;
    }

    public static function validarFechaInicioFin($inicio, $fin, $key, $errores, $msg = 'La fecha de inicio debe ser anterior a la fecha fin') {
        if(empty($inicio) || empty($fin)) {
            return $errores;
        }
        
        $inicioEntero = (new DateTime($inicio))->getTimestamp();
        $finEntero = (new DateTime($fin))->getTimestamp();
        if($finEntero <= $inicioEntero) {
            $errores[$key] = $msg;
        }
        return $errores;
    }
}



?>