<?php
class Idioma {
    // Properties
    private $id;
    private $nivel;

     // Constructor
     function __construct($id,$nivel) {
        $this->id = $id;
        $this->nivel = $nivel;
     }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_nivel() {
        return $this->nivel;
    }

}
?>