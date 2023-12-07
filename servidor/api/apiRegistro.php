<?php
require($_SERVER['DOCUMENT_ROOT'].'/erasmus/servidor/autoloader.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre=$_POST['nombre'];
    $apellidos=$_POST['apellidos'];
    $dni=$_POST['dni'];
    $fechaNac=$_POST['fechaNac'];
    $domicilio=$_POST['domicilio'];
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $curso=$_POST['curso'];
    $nombreTutor=$_POST['nombreTutor'];
    $apellidosTutor=$_POST['apellidosTutor'];
    $dniTutor=$_POST['dniTutor'];
    $domicilioTutor=$_POST['domicilioTutor'];
    $telefonoTutor=$_POST['telefonoTutor'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];


    // El formulario tiene los campos obligatorios/correctos?
    $erroresValidacion=array();
    $erroresValidacion = Validator::campoRequerido($password, 'password', "Es obligatorio introducir una contraseña", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($password2, 'password2', "Es obligatorio introducir repetir la contraseña", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($dni, 'dni', "Es obligatorio rellenar el dni del alumno", $erroresValidacion);
    $erroresValidacion = Validator::validarDni($dni, $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($nombre, 'nombre', "Es obligatorio rellenar el nombre del alumno", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($apellidos, 'apellidos', "Es obligatorio rellenar los apellidos del alumno", $erroresValidacion);
    $erroresValidacion = Validator::validarFechaNac($fechaNac, $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($domicilio, 'domicilio', "Es obligatorio rellenar el domicilio del alumno", $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($telefono, 'telefono', "Es obligatorio introducir el número de teléfono del alumno", $erroresValidacion);
    $erroresValidacion = Validator::validarTelefono($telefono, $erroresValidacion);
    $erroresValidacion = Validator::campoRequerido($correo, 'correo', "Es obligatorio introducir el correo electrónico del alumno", $erroresValidacion);
    $erroresValidacion = Validator::validarEmail($correo, $erroresValidacion);

    $erroresValidacion = Validator::campoRequerido($curso, 'curso', "Es obligatorio indicar el curso en el que el alumno está matriculado", $erroresValidacion);

    // Los datos relacionados con el tutor legal serán necesarios solamente si el alumno es menor de edad
    // Se debe comprobar primero la mayoría de edad y, en relación al resultado, la obligatoriedad de los datos del tutor
    // Creamos un objeto DateTime con la fecha de nacimiento
    $fechaNacimiento = new DateTime($fechaNac);

    // Creamos un objeto DateTime con la fecha actual
    $fechaActual = new DateTime();

    // Calculamos la diferencia en años entre la fecha de nacimiento y la fecha actual
    $edad = $fechaNacimiento->diff($fechaActual)->y;

    // Comprobamos si el usuario es mayor de edad
    if ($edad < 18) {
        $erroresValidacion = Validator::campoRequerido($nombreTutor, 'nombreTutor', "Es obligatorio indicar el nombre del tutor del alumno", $erroresValidacion);
        $erroresValidacion = Validator::campoRequerido($apellidosTutor, 'apellidosTutor', "Es obligatorio indicar los apellidos del tutor del alumno", $erroresValidacion);
        $erroresValidacion = Validator::campoRequerido($dniTutor, 'dniTutor', "Es obligatorio indicar el dnni del tutor del alumno", $erroresValidacion);
        $erroresValidacion = Validator::campoRequerido($domicilioTutor, 'domicilioTutor', "Es obligatorio indicar el domicilio del tutor del alumno", $erroresValidacion);
        $erroresValidacion = Validator::campoRequerido($telefonoTutor, 'telefonoTutor', "Es obligatorio introducir el número de teléfono del tutor del alumno", $erroresValidacion); 
    }

    // Las contraseñas coinciden?
    if($password != $password2) {
        $erroresValidacion['passwordDiferente'] = "Las contraseñas introducidas no coinciden";
    }

    if(!empty($erroresValidacion)) {
        $erroresValidacion['status_code'] = 400; 
        echo json_encode($erroresValidacion);
        return;
    }

    // El usuario existe?
    $userObj = UsuarioRepository::obtenerUsuarioPorDni($dni);
    if(!empty($userObj)) {
        $erroresValidacion['usuarioExiste'] = 'El usuario introducido ya está registrado en el sistema';
        $erroresValidacion['status_code'] = 409; // es el status conflict que significa que ha habido colision
        echo json_encode($erroresValidacion);
        return;
    }


    try {
        Conexion::beginTrasaction();
        // Introducir nuevo usuario en base de datos
        $id = UsuarioRepository::crearUsuario($dni, $password);
        CandidatoRepository::crearCandidato(new Candidato($id, $nombre, $apellidos, $fechaNac, $curso, $telefono, $correo, $domicilio, $tutorNombre, $tutorApellidos, $tutorDni, $tutorDomicilio, $tutorTelefono));
        ServicioEmail::enviarEmailBienvenida($correo, $nombre, $apellidos);
        Conexion::commit();
        header("Location: http://localhost/erasmus/interfaz/acceso/login.html");
        exit();
    } catch(Throwable | Error $e) {
        // Ups! algo ha ido mal, vamos a revertir los cambios de DB y mandar error al cliente
        Conexion::rollback();
        $erroresValidacion['status_code'] = 500; // Error de servidor
        $erroresValidacion['msg'] = $e->getMessage();
        echo json_encode($erroresValidacion);
        return;
    }
    
} 

?>