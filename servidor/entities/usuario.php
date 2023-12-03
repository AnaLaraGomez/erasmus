<?php
class Usuario {
    private $id;
    private $dni;
    private $password;
    private $admin;

    // Constructor
    function __construct($id, $dni, $password, $admin){
        $this->id = $id;
        $this->dni = $dni;
        $this->password = $password;
        $this->admin = $admin;
    }

    // Methods
    function get_id() {
        return $this->id;
    }
    function get_dni(){
        return $this->dni;
    }
    function get_password(){
        return $this->password;
    }
    function get_admin() {
        return $this->admin;
    }

    function set_id($id){
        $this->id = $id;
    }
    function set_dni($dni){
        $this->dni = $dni;
    }function set_password($password){
        $this->password = $password;
    }function set_admin($admin){
        $this->admin = $admin;
    }

    function to_json() {
        return '{'.
            '"id":' . $this->id . ',' .
            '"dni": "' . $this->dni . '",' .
            '"admin":' . ($this->admin == 1 ? 'true' : 'false') .
            '}';
    }
}
?>