<?php
class Proyecto {
    // Properties
    private $id;
    private $codigo;
    private $nombre;
    private $fechaInicio;
    private $fechFin;

     // Constructor
     function __construct($id,$codigo, $nombre, $fechaInicio, $fechaFin) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
     }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_codigo() {
        return $this->codigo;
    }
    function get_nombre() {
        return $this->nombre;
    }
    function get_fechaInicio() {
        return $this->fechaInicio;
    }
    function get_fechaFin() {
        return $this->fechaFin;
    }

    function set_id($id){
        $this->id = $id;
    }
    function set_codigo($codigo){
        $this->codigo = $codigo;
    }
    function set_nombre($nombre){
        $this->nombre = $nombre;
    }
    function set_fechaInicio($fechaInicio){
        $this->fechaInicio = $fechaInicio;
    }
    function set_fechaFin($fechaFin){
        $this->fechaFin = $fechaFin;
    }

    function to_json() {
        return '{' .
            '"id": ' . $this->id . ', ' .
            '"codigo": "' . $this->codigo . '", ' .
            '"nombre": "' . $this->nombre . '", ' .
            '"fechaInicio": "' . $this->fechaInicio . '", ' .
            '"fechaFin": "' . $this->fechaFin . '"' .
            '}';
    }
}
?>