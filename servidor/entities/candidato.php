<?php
class Candidato {
    // Properties
    private $id;
    private $nombre;
    private $apellidos;
    private $fechaNac;
    private $curso;
    private $telefono;
    private $correo;
    private $domicilio;
    private $tutorNombre;
    private $tutorApellidos;
    private $tutorDni;
    private $tutorDomicilio;
    private $tutorTelefono;

    // Constructor
    function __construct($id, $nombre, $apellidos, $fechaNac, $curso, 
                            $telefono, $correo, $domicilio, 
                            $tutorNombre, $tutorApellidos, $tutorDni, 
                            $tutorDomicilio, $tutorTelefono) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fechaNac = $fechaNac;
        $this->curso = $curso;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->domicilio = $domicilio;
        $this->tutorNombre = $tutorNombre;
        $this->tutorApellidos = $tutorApellidos;
        $this->tutorDni = $tutorDni;
        $this->tutorDomicilio = $tutorDomicilio;
        $this->tutorTelefono = $tutorTelefono;
      }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_nombre() {
        return $this->nombre;
    }
    function get_apellidos() {
        return $this->apellidos;
    }
    function get_fechaNac() {
        return $this->fechaNac;
    }
    function get_curso() {
        return $this->curso;
    }
    function get_telefono() {
        return $this->telefono;
    }
    function get_correo() {
        return $this->correo;
    }
    function get_domicilio() {
        return $this->domicilio;
    }
    function get_tutorNombre() {
        return $this->tutorNombre;
    }
    function get_tutorApellidos() {
        return $this->tutorApellidos;
    }
    function get_tutorDni() {
        return $this->tutorDni;
    }
    function get_tutorDomicilio() {
        return $this->tutorDomicilio;
    }
    function get_tutorTelefono() {
        return $this->tutorTelefono;
    }
    
    function set_id($id){
        $this->id = $id;
    }
    function set_nombre($nombre){
        $this->nombre = $nombre;
    }
    function set_apellidos($apellidos){
        $this->apellidos = $apellidos;
    }
    function set_fechaNac($fechaNac){
        $this->fechaNac = $fechaNac;
    }
    function set_curso($curso){
        $this->curso = $curso;
    }
    function set_telefono($telefono){
        $this->telefono = $telefono;
    }
    function set_correo($correo){
        $this->correo = $correo;
    }
    function set_domicilio($domicilio){
        $this->domicilio = $domicilio;
    }
    function set_tutorNombre($tutorNombre){
        $this->tutorNombre = $tutorNombre;
    }
    function set_tutorApellidos($tutorApellidos){
        $this->tutorApellidos = $tutorApellidos;
    }
    function set_tutorDni($tutorDni){
        $this->tutorDni = $tutorDni;
    }
    function set_tutorDomicilio($tutorDomicilio){
        $this->tutorDomicilio = $tutorDomicilio;
    }
    function set_tutorTelefono($tutorTelefono){
        $this->tutorTelefono = $tutorTelefono;
    }

    function to_json() {
        return '{'.
            '"id":' . $this->id . ',' .
            '"nombre": "' . $this->nombre . '",' .
            '"apellidos":"' . $this->apellidos . '",' .
            '"fechaNac":"' . $this->fechaNac . '",' .
            '"curso":"' . $this->curso . '",' .
            '"telefono":"' . $this->telefono . '",' .
            '"correo":"' . $this->correo . '",' .
            '"domicilio":"' . $this->domicilio . '",' .
            '"tutorNombre":"' . $this->tutorNombre . '",' .
            '"tutorApellidos":"' . $this->tutorApellidos . '",' .
            '"tutorDni":"' . $this->tutorDni . '",' .
            '"tutorDomicilio":"' . $this->tutorDomicilio . '",' .
            '"tutorTelefono":"' . $this->tutorTelefono . '"' .            
            '}';
    }

}    
?>