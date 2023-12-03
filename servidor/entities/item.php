<?php
class Item {
    // Properties
    private $id;
    private $nombre;
    private $subeAlumno;

     // Constructor
     function __construct($id,$nombre, $subeAlumno) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->subeAlumno = $subeAlumno;
     }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_nombre() {
        return $this->nombre;
    }
    function get_subeAlumno() {
        return $this->subeAlumno;
    }
}
?>