<?php
class Convocatoria {
    // Properties
    private $id;
    private $proyectoId;
    private $descripcion;
    private $nombre;

    private $movilidades;
    private $largaDuracion;
    private $fechaInicioSolicitudes;
    private $fechaFinSolicitudes;
    private $fechaInicioPruebas;
    private $fechaFinPruebas;
    private $fechaListaProvisional;
    private $fechaListaDefinitiva;

    // Constructor
    function __construct($id, $movilidades, $largaDuracion, 
                            $fechaInicioSolicitudes, $fechaFinSolicitudes, 
                            $fechaInicioPruebas, $fechaFinPruebas, $fechaListaProvisional, 
                            $fechaListaDefinitiva, $proyectoId, $descripcion, $nombre) {
        $this->id = $id;
        $this->movilidades = $movilidades;
        $this->largaDuracion = $largaDuracion;
        $this->fechaInicioSolicitudes = $fechaInicioSolicitudes;
        $this->fechaFinSolicitudes = $fechaFinSolicitudes;
        $this->fechaInicioPruebas = $fechaInicioPruebas;
        $this->fechaFinPruebas = $fechaFinPruebas;
        $this->fechaListaProvisional = $fechaListaProvisional;
        $this->fechaListaDefinitiva = $fechaListaDefinitiva;
        $this->proyectoId = $proyectoId;
        $this->descripcion = $descripcion;
        $this->nombre = $nombre;
    }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_movilidades(){
        return $this->movilidades;
    }
    function get_largaDuracion(){
        return $this->largaDuracion;
    }
    function get_fechaInicioSolicitudes(){
        return $this->fechaInicioSolicitudes;
    }
    function get_fechaFinSolicitudes(){
        return $this->fechaFinSolicitudes;
    }
    function get_fechaInicioPruebas(){
        return $this->fechaInicioPruebas;
    }
    function get_fechaFinPruebas(){
        return $this->fechaFinPruebas;
    }
    function get_fechaListaProvisional(){
        return $this->fechaListaProvisional;
    }
    function get_fechaListaDefinitiva(){
        return $this->fechaListaDefinitiva;
    }
    function get_proyectoId(){
        return $this->proyectoId;
    }
    function get_descripcion(){
        return $this->descripcion;
    }
    function get_nombre(){
        return $this->nombre;
    }

    function set_id($id){
        $this->id = $id;
    }
    function set_movilidades($movilidades){
        $this->movilidades = $movilidades;
    }
    function set_largaDuracion($largaDuracion){
        $this->largaDuracion = $largaDuracion;
    }
    function set_fechaInicioSolicitudes($fechaInicioSolicitudes){
        $this->fechaInicioSolicitudes = $fechaInicioSolicitudes;
    }
    function set_fechaFinSolicitudes($fechaFinSolicitudes){
        $this->fechaFinSolicitudes = $fechaFinSolicitudes;
    }
    function set_fechaInicioPruebas($fechaInicioPruebas){
        $this->fechaInicioPruebas = $fechaInicioPruebas;
    }
    function set_fechaFinPruebas($fechaFinPruebas){
        $this->fechaFinPruebas = $fechaFinPruebas;
    }
    function set_fechaListaProvisional($fechaListaProvisional){
        $this->fechaListaProvisional = $fechaListaProvisional;
    }
    function set_fechaListaDefinitiva($fechaListaDefinitiva){
        $this->fechaListaDefinitiva = $fechaListaDefinitiva;
    }
    function set_proyectoId($proyectoId){
        $this->proyectoId = $proyectoId;
    }
    function set_descripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    function set_nombre($nombre){
        $this->nombre = $nombre;
    }
}
?>