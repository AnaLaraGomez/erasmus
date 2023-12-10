<?php
class Destinatario {
    // Properties
    private $id;
    private $codigoGrupo;
    private $nombre;

     // Constructor
     function __construct($id,$codigoGrupo, $nombre) {
        $this->id = $id;
        $this->codigoGrupo = $codigoGrupo;
        $this->nombre = $nombre;
     }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_codigoGrupo() {
        return $this->codigoGrupo;
    }
    function get_nombre() {
        return $this->nombre;
    }

    function to_json() {
        return '{' .
            '"id": ' . $this->id . ',' .
            '"nombre": "' . $this->codigoGrupo  . ' '. $this->nombre . '"' .
            '}';
    }
}

?>